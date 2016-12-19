{block name='payment_example'}
    <h2>Hello {$firstName} {$lastName}</h2>
    <p>Do you want to pay {$amount} {$currency} with this example payment provider?</p>
    <a href="{$returnUrl}" title="pay {$amount} {$currency}">pay {$amount} {$currency}</a>
    <br/>
    <a href="{$cancelUrl}" title="cancel payment">cancel payment</a>
{/block}
