---
layout: default
title: Payment plugin
github_link: developers-guide/payment-plugin/index.md
indexed: true
group: Developer Guides
subgroup: Tutorials
menu_title: Create custom payment plugin
menu_order: 35
---

<div class="toc-list"></div>

## Introduction
In this tutorial we will point out some important information for adding a new payment method to shopware. This is done by creating a payment plugin. Payment handling is essential for every shop and therefore a payment plugin needs some extra functionalities to guarantee secure and comfortable payments. 
### Qualification
Before getting started with this tutorial, it is recommended that you first become familiar with creating Shopware plugins, since this guide will only point out the extras for creating a __payment plugin__. For further information on developing plugins see [our plugin guides](plugin-guide/).

## Order process
<img src="img/shopware_payment_process.jpg" alt="Payment process" class="image-border" />

Once proceeding to the checkout process, the first check is whether the customer is logged in. If not, he is offered the option of either logging in or registering.

If the customer is known by the shop system, the confirm action is passed. After selecting the payment method and shipping the shopping cart is entered and a temporary order is created with the status "-1" (cancel). An order number is not generated at this point.

In the next step the customer is directed to the payment interface controller. The way in which the customer enters the payment interface can be controlled here. Generally, this is done by implementing an iFrame or by a direct link to the payment provider. At this point the return address to the shop is generally communicated, too. Once the customer has gone through the payment process of the payment interface, they must be redirected back to the shop. Depending on whether the payment was successful or not, the customer is either directed to the return action or a cancel action. Here, the interface author can decide whether they wish to complete the order. When the order is completed, the order status is set to a value of "0" and the payment status is set to "17" (open). This means that the order will be visible in the backend and the order confirmation email is deleted.
## Plugin structure
The structure of our example plugin is as follows:

<img src="img/structure.png" alt="Directory structure" class="image-border" />

The parts of the demo provider, here in red boxes are not necessary for a payment plugin. This is just for testing purposes and to get our example plugin to work.

## Plugin base class
In our plugin base class we need some additional logic for the payment plugin.

### Add payment to database
Some payment providers offer more than just one method of payment. Shopware offers the possibility of combining multiple payments within a plugin. To do that we need to add the payment method to the database in our plugin base class. In this example we add two payment methods. One for invoices and one for credit cards. In the 5.2 plugin system we can use the [`Shopware\Components\Payment\Installer` service since shopware 5.2.13](https://developers.shopware.com/developers-guide/plugin-system/#add-a-new-payment-method) to add the rows. In older versions of shopware we have to create a payment model on our own and save it to the database. In the legacy plugin system we can use the `$this->createPayment()` helper method in the plugin bootstrap. 

`SwagPaymentExample/SwagPaymentExample.php`:
```
public function install(InstallContext $context)
{
    /** @var \Shopware\Components\Plugin\PaymentInstaller $installer */
    $installer = $this->container->get('shopware.plugin_payment_installer');

    $options = [
        'name' => 'example_payment_invoice',
        'description' => 'Example payment method invoice',
        'action' => 'PaymentExample',
        'active' => 0,
        'position' => 0,
        'additionalDescription' =>
            '<!-- Logo start -->'
            .'  <img src="http://your-image-url"/>'
            .'<!-- Logo end -->'
            .'<div id="payment_desc">'
            .'  Pay save and secured by invoice with our example payment provider.'
            .'</div>'
    ];
    $installer->update($context->getPlugin(), $options);

    $options = [
        'name' => 'example_payment_cc',
        'description' => 'Example payment method credit card',
        'action' => 'PaymentExample',
        'active' => 0,
        'position' => 0,
        'additionalDescription' =>
            '<!-- Logo start -->'
            .'  <img src="http://your-image-url"/>'
            .'<!-- Logo end -->'
            .'<div id="payment_desc">'
            .'  Pay save and secured by credit card with our example payment provider.'
            .'</div>'
    ];

    $installer->update($context->getPlugin(), $options);
}
```
* name : This is the name of the payment method. This is required in order to clearly identify the payment method. It will not be displayed in the templates. The description will be used for this.
* description : The description should be kept as short as possible, because it will be displayed in the template. Ideally, it should also provide a clear explanation of the payment method for the customer.
* action : This field is used to determine which controller is responsible for this payment method.
* active : With this flag we can determine whether the payment method is activated or deactivated upon completion of the installation.
* position : This determines where the payment method appears in the list of methods.
* pluginID : This is the ID of the plugin which is returned with $this->getId().
* additionalDescription : Here we can add more information about the payment method, for example add an image that will be shown in the checkout process.

If several payment methods are used, their names should be unique and clearly distinguishable. In the checkout process our example could look like this:

<img src="img/payments.png" alt="paymentmeans" class="image-border" />

### uninstall, activate, deactivate the plugin
You should be careful when removing the payment methods from the database. If they have been used in previous orders it can cause unforeseen problems. We recommend to just deactivate them on plugin uninstall or deactivation.

`SwagPaymentExample/SwagPaymentExample.php`:
```
/**
 * @param UninstallContext $context
 */
public function uninstall(UninstallContext $context)
{
    $this->setActive($context, false);
}

/**
 * @param DeactivateContext $context
 */
public function deactivate(DeactivateContext $context)
{
    $this->setActive($context, false);
}

/**
 * @param ActivateContext $context
 */
public function activate(ActivateContext $context)
{
    $this->setActive($context, true);
}

/**
 * @param $context ActivateContext|DeactivateContext|UninstallContext
 * @param $active bool
 */
public function setActive($context, $active)
{
    $payments = $context->getPlugin()->getPayments();
    $em = $this->container->get('models');

    foreach ($payments as $payment) {
        $payment->setActive($active);
        $em->persist($payment);
    }
    $em->flush();
}
```

## Payment service
For a better overview and a clearer separation between our controller and the business logic we create a small payment service which handles the responses of the provider and takes care of token generation and validation.

`SwagPaymentExample/Components/ExamplePayment/ExamplePaymentService.php`:
```
<?php

namespace SwagPaymentExample\Components\ExamplePayment;

class ExamplePaymentService
{
    /**
     * @param $request \Enlight_Controller_Request_Request
     * @return PaymentResponse
     */
    public function createPaymentResponse(\Enlight_Controller_Request_Request $request)
    {
        $response = new PaymentResponse();
        $response->transactionId = $request->getParam('transactionId', null);
        $response->status = $request->getParam('status', null);
        $response->token = $request->getParam('token', null);
        return $response;
    }

    /**
     * @param PaymentResponse $response
     * @param string $token
     * @return bool
     */
    public function isValidToken(PaymentResponse $response, $token)
    {
        if($token === $response->token) {
            return true;
        }

        return false;
    }

    /**
     * @param float $amount
     * @param int $customerId
     * @return string
     */
    public function createPaymentToken($amount, $customerId)
    {
        return md5(implode('|', [$amount, $customerId]));
    }
}
```

### Payment response
We also create an object for the response. Even though we have only three variables in our response, a real payment provider could hand over a lot more. This way we have a well structured object we can work with.

`SwagPaymentExample/Components/ExamplePayment/PaymentResponse.php`: 
```
<?php

namespace SwagPaymentExample\Components\ExamplePayment;

class PaymentResponse
{
    /**
     * @var int
     */
    public $transactionId;

    /**
     * @var string
     */
    public $token;

    /**
     * @var string
     */
    public $status;
}
```

### Register the service
To register our service we just create the `services.xml`.

`SwagPaymentExample/Resources/services.xml`:
```
<?xml version="1.0" ?>
<container xmlns="http://symfony.com/schema/dic/services"
           xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
           xsi:schemaLocation="http://symfony.com/schema/dic/services http://symfony.com/schema/dic/services/services-1.0.xsd">
    <services>
        <service id="swag_payment_example.example_payment_service" class="SwagPaymentExample\Components\ExamplePayment\ExamplePaymentService">
        </service>
    </services>
</container>
```

## Backend configuration
For our example plugin we just need a configuration field for the url of the payment provider. For testing purposes we set the default url to our demo payment provider to handle the requests.

`SwagPaymentExample/Resources/config.xml`:
```
<?xml version="1.0" encoding="utf-8"?>
<config xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" 
        xsi:noNamespaceSchemaLocation="https://raw.githubusercontent.com/shopware/shopware/5.2/engine/Shopware/Components/Plugin/schema/config.xsd">
    <elements>
        <element required="true" type="text">
            <name>providerUrl</name>
            <label lang="de">Payment Provider url</label>
            <label lang="en">Payment Provider url</label>
            <value>http://localhost/DemoPaymentProvider/pay?</value>
        </element>
    </elements>
</config>
```

## Frontend controller
To implement the frontend logic we need a frontend controller. In our example plugin we use the [controller auto-registration](https://developers.shopware.com/developers-guide/plugin-system/#controller-auto-registration) which is available since shopware 5.2.7. It is important that the controller has the same name as specified in the action field of the payment method and it has to __extend from the shopware payment controller__ to provide the necessary payment methods.  
The frontend controller is activated once the customer clicks on the "Confirm order" button. The system then forwards the request to the controller which has been defined in the action field of the payment method.

### register templates
Since we use the [controller auto-registration](https://developers.shopware.com/developers-guide/plugin-system/#controller-auto-registration) we need to register our templates in the `preDispatch()` method of our controller:
`SwagPaymentExample/Controllers/Frontend/PaymentExample.php`:
```
public function preDispatch()
{
    /** @var \Shopware\Components\Plugin $plugin */
    $plugin = $this->get('kernel')->getPlugins()['SwagPaymentExample'];

    $this->get('template')->addTemplateDir($plugin->getPath() . '/Resources/views/');
}
```
### indexAction
The index() action is always called in this controller. To redirect to the correct action, we proceed as follows:
`SwagPaymentExample/Controllers/Frontend/PaymentExample.php`:
```
public function indexAction()
{
    /**
     * Check if one of our payment methods is selected. Else return to default controller.
     */
    switch ($this->getPaymentShortName()) {
        case 'example_payment_invoice':
            return $this->redirect(['action' => 'gateway', 'forceSecure' => true]);
        case 'example_paxment_cc':
            return $this->redirect(['action' => 'direct', 'forceSecure' => true]);
        default:
            return $this->redirect(['controller' => 'checkout']);
    }
}
```
Via the configuration parameter 'forceSecure', we can force the system to send a __secure query__.  
There are two ways to display the payment methods to the customers, via __iFrame__ or __direct forwarding__. Other methods are strongly advised against, as there are maybe unforeseen problems with the system.

### iFrame gateway
This method has the advantage that the customer does not leave the shop storefront, and so their shopping experience is not interrupted. To display the payment interface surface in an iFrame, we need to create a template with a corresponding iFrame. Here we can orient on the `frontend/checkout/payment.tpl` template.

`SwagPaymentExample/Controllers/Frontend/PaymentExample.php`:
```
public function gatewayAction()
{
    $providerUrl = Shopware()->Config()->getByNamespace('SwagPaymentExample', 'providerUrl');
    $this->View()->assign('gatewayUrl', $providerUrl . $this->getUrlParameters());
}
```
The gateway template could appear as follows:
`SwagPaymentExample/Resources/views/frontend/payment_example/gateway.tpl`:
```
{extends file="frontend/index/index.tpl"}

{block name="frontend_index_content"}
    <div id="payment">
        <iframe src="{$gatewayUrl}"
                scrolling="yes"
                style="x-overflow: none;"
                frameborder="0">
        </iframe>
    </div>
{/block}
```

### Direct forwarding
Several payment provider do not prefer the iFrame method. If this is the case, we can also have customers forwarded directly.
`SwagPaymentExample/Controllers/Frontend/PaymentExample.php`:
```
    public function directAction()
    {
        $providerUrl = Shopware()->Config()->getByNamespace('SwagPaymentExample', 'providerUrl');
        $this->redirect($providerUrl . $this->getUrlParameters());
    }
```
### Generating url parameters
The customer should be sent back to the shop after the payment process is completed. To protect the query from being manipulated, it will be built with a token. Most interfaces offer the option of passing parameters, which will be returned unchanged.
`SwagPaymentExample/Controllers/Frontend/PaymentExample.php`:
```
    private function getUrlParameters()
    {
        /** @var ExamplePaymentService $service */
        $service = $this->container->get('swag_payment_example.example_payment_service');
        $router = $this->Front()->Router();
        $user = $this->getUser();
        $billing = $user['billingaddress'];

        $parameter = [
            'amount' => $this->getAmount(),
            'currency' => $this->getCurrencyShortName(),
            'firstName' => $billing['firstname'],
            'lastName' => $billing['lastname'],
            'returnUrl' => $router->assemble(['action' => 'return', 'forceSecure' => true]),
            'cancelUrl' => $router->assemble(['action' => 'cancel', 'forceSecure' => true]),
            'token' => $service->createPaymentToken($this->getAmount(), $billing['customernumber'])
        ];
        return http_build_query($parameter);
    }
```
For security reasons we generate an unique token that we hand over to the payment provider. When the customer then returns we make sure the token is still the same in the `returnAction`.

### Completing Orders
When the customer is redirected from the interface to the shop, the developer of the payment method must decide whether they wish to complete the order. Generally, the customer is forwarded to a return address. In our example, `returnAction()` is called.

Here, the response of the interface is evaluated, and if the payment has been performed successfully, the order can be completed with the `saveOrder()` command. This method accepts four parameters. The first two mandatory parameters are transactionID and an unique payment id. If these parameters are not filled, the method returns a value of false. The __transactionID__ generally comes from the interface and is used for assigning orders in the system of the payment method provider. If the provider does not return a transactionID, any arbitrary value can be assigned. Note that in later processes, the __combination of the transactionID and the unique payment id__ are used to access the order.
`SwagPaymentExample/Controllers/Frontend/PaymentExample.php`:
```
public function returnAction()
{
    /** @var ExamplePaymentService $service */
    $service = $this->container->get('swag_payment_example.example_payment_service');
    $user = $this->getUser();
    $billing = $user['billingaddress'];
    /** @var PaymentResponse $response */
    $response = $service->createPaymentResponse($this->Request());
    $token = $service->createPaymentToken($this->getAmount(), $billing['customernumber']);

    if(!$service->isValidToken($response, $token)){
        $this->forward('cancel');
    }

    switch ($response->status) {
        case 'accepted':
            $this->saveOrder(
                $response->transactionId,
                $response->token,
                self::PAYMENTSTATUSPAID
            );
            $this->redirect(['controller' => 'checkout', 'action' => 'finish']);
            break;
        default:
            $this->forward('cancel');
            break;
    }
}
```
In the `returnAction` we check if the returned token is still valid. If this fails we could throw an exception or redirect to an error page. For the example plugin it should be sufficient to redirect to our `cancelAction`. 
If the token is valid we check for the response status of the payment provider and save the order if everything went fine.

### notifyAction
If the customer decides to pay later we have to provide an action which the payment provider can use to set the right payment status. To do so, the current session ID of the user must be passed to this action. To attach a sessionID to the return address, we can use the following call:
`SwagPaymentExample/Controllers/Frontend/PaymentExample.php`:
```
private function getUrlParameters()
{
    ...
    
    $parameter = [
        ...
        'notifyUrl' => $router->assemble(['action' => 'notify', 'forceSecure' => true, 'appendSession' => true]),
        ...
    ];
    return http_build_query($parameter);
}
```
This action needs to be whitelisted from CSRF protection to prevent CSRF errors.
`use` it in the frontend controller and implement the `getWhitelistedCSRFActions()` method:
`SwagPaymentExample/Controllers/Frontend/PaymentExample.php`:
```
<?php

use Shopware\Components\CSRFWhitelistAware;

class Shopware_Controllers_Frontend_PaymentExample extends Shopware_Controllers_Frontend_Payment implements CSRFWhitelistAware 
{
    ...
    
    /**
     * Whitelist notifyAction
     */
    public function getWhitelistedCSRFActions()
    {
        return ['notify'];
    }
    
    ...
}
```

### Adjusting payment status
To change the payment status of an order, use the `savePaymentStatus()` command. This method accepts three parameters:
* transactionID
* uniquepaymentID
* payment status

Optionally, a fourth parameter can be used, which informs the customer about status changes via email. if the saveOrder() command has already been called, an additional confirmation is not sent.

## New signature in shopware 5.3 and later
<img src="img/Payment.png" alt="Payment controller" class="image-border" />

In shopware 5.3 and higher there are some improvements on query manipulation and security. Before redirecting to the payment provider we generate an unique signature for our basket to verify that it has not changed when the customer has finished the payment process. To do this we edit our frontend controller as shown below.

### Generate signature
We generate an unique signature for our basket and add it to the array we hand over to the controller of our payment provider. You need to ask your provider for a parameter field they return unchanged to our controller. This could look like this:  
`SwagPaymentExample/Controllers/Frontend/PaymentExample.php`:
```
private function getUrlParameters()
{
    ...
    
    $parameter = [
        ...
        'signature' => $this->persistBasket(),
        ...
    ];
    return http_build_query($parameter);
}

```
`$this-persistBasket` returns a signature based on the basket and customer id and saves it together with a copy of the basket to the database.
### Checking for signature on return
When the customer has finished the external payment process he will be redirected to our controller and we load the signature and verify the basket.
`SwagPaymentExample/Controllers/Frontend/PaymentExample.php`:
```
public function returnAction()
{
    ...
    $response = $service->createPaymentResponse($this->Request());
    $signature = $response->signature;
    ...
    try {
        $basket = $this->loadBasketFromSignature($signature);
        $this->verifyBasketSignature($signature, $basket);
        $success = true;
    } catch (Exception $e) {
        $success = false;
    }
    
    if(!$success) {
        die('Signature does not match');
    }
    ...
}
```
`basket = $this->loadBasketFromSignature($signature);` loads the basket identified by its signature from the database and deletes the record, so it can only be loaded once. Then we verify that the saved basket is still the same and has not been changed by plugins for example.

To save the signature in our response object we could extend it as follows:
`SwagPaymentExample/Components/ExamplePayment/PaymentResponse.php`: 
```
<?php

namespace SwagPaymentExample\Components\ExamplePayment;

class PaymentResponse
{
    ...
        
    /**
     * @var string
     */
    public $signature;
}

```
`SwagPaymentExample/Components/ExamplePayment/ExamplePaymentService.php`:
```
<?php

namespace SwagPaymentExample\Components\ExamplePayment;

class ExamplePaymentService
{
    /**
     * @param $request \Enlight_Controller_Request_Request
     * @return PaymentResponse
     */
    public function createPaymentResponse(\Enlight_Controller_Request_Request $request)
    {
        $response = new PaymentResponse();
        ...
        $response->signature = $request->getParam('signature', null);
        return $response;
    }
}
```
## Security
In connection with the payment interface, it is particularly important that attention is paid to clean and secure programming. The Shopware system has a wide range of methods for preventing SQL injections.

If the database must be accessed directly, it must be assured that no malicious SQL commands can be inserted.

Therefore a numeric value is always used to convert a payment value:
```
$countryId = (int)$this->Request()->getParam('countryId');
$sql = 'SELECT id FROM s_core_countries WHERE countryiso=?';
$countryId = Shopware()->Db()->fetchOne($sql, array($countryId));
```
In this example, we see the main elements that should be considered when the database in accessed. The countryID parameter is read via the getParam() method. Request parameters must NEVER be read directly from the global PHP variables (e.g., $_POST, $_GET). Shopware is equipped with special filters to capture SQL injections that would not be captured in the case of direct access.

Another aspect is the use of the replacement method in the SQL query. Variables are not found directly in the SQL string, but separately passed to the query method. This has the advantage that the database system can escape cleanly with database specifics still taken into account.

## Amount mismatch
When creating orders (e.g. using the saveOrder method), we need to make sure, that the amount authorized by the payment provider matches the current amount of the cart. This can be done by adding the cart amount to the hash explained above - or by comparing the authorized amount and the current cart amount ($this->getAmount) explicitly.
<div class="alert alert-info">
In shopware 5.3 and later this is done via the siganture. 
</div>

When amounts do mismatch, the order could be rejected entirely or marked with payment status 21 indicating "review necessary".

## Anti pattern
* __Adjusting the order process__: The order process is one of the central pieces of any shop system. Every external adjustment of this process can have unwanted side effects and can prevent the system for being upgraded.
* __Overwriting payment methods__: Overwriting or deleting payment methods is absolutely prohibited.
* __Copying Shopware code__: Please do not copy Shopware code. The plugin will lose is update capabilities.
* __Creating order numbers__: Do not create order numbers yourself. Always use the pre-installed saveOrder() method. This is to be sure that order numbers can be created as the customers wish.
* __Saving credit cards and account data__: You should never save credit card data in the shop system and also avoid storing account data in the system as well.
* __Creating status options__: Avoid creating status options. This can be very difficult to translate, for example, and in the worst case can cause information to be ambiguous.
* __Trusting data__: Never trust data that you receive from the system. Always assume that the data contain malicious code and program the interface accordingly.