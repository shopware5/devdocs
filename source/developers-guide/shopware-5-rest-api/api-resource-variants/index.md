---
layout: default
title: Shopware 5 Rest API - Variants End-Point
github_link: developers-guide/shopware-5-rest-api/api-resource-variants/index.md
indexed: true
---

## Introduction

In this part of the documentation you can learn more about the API's variants resource. With this resource, it is possible to 
receive, delete and update any variant in your shops. Also we will have a look at the provided data.

## General Information
You may find the related resource under
**engine\Shopware\Controllers\Api\Variants.php**.

This resource supports the following operations:

|  Access URL                 | GET                | GET (List)      | PUT             | PUT (Stack)      | POST             | DELETE          | DELETE (Stack)  |
|-----------------------------|--------------------|-----------------|-----------------|------------------|------------------|-----------------|-----------------|
| /api/variants		          | ![No](./img/no.png)      | ![Yes](./img/yes.png) | ![Yes](./img/yes.png) | ![Yes](./img/yes.png)  | ![Yes](./img/yes.png)  | ![Yes](./img/yes.png) | ![Yes](./img/yes.png) |

If you want to access this end-point, simply append your shop-URL with

* **http://my-shop-url/api/translations**

## GET

You can receive data of variants by providing the specific id

* **http://my-shop-url/api/variants/id**

Simply replace the 'id' with the specific identifier

### Required Parameters

| Identifier		| Parameter			| Database Column					  | Example call															|
|-------------------|-------------------|-------------------------------------|-------------------------------------------------------------------------|
| Detail id			| id				| `s_articles_details.id`			  | /api/variants/2															|
| Detail number		| number			| `s_articlies_details.ordernumber`	  | /api/variants/SW10003?useNumberAsId=true								|

Option parameters can be provided: considerTaxInput: By default, all returned prices are net values. If the boolean "considerTaxInput" is set to true, gross values will be returned instead. Returns the following:

### Return Value

| Model								| Table						|
|-----------------------------------|---------------------------|
| Shopware\Models\Article\Detail	| `s_articles_details`		|

| Field               | Type                  | Original object                                 		|
|---------------------|-----------------------|---------------------------------------------------------|
| id 	         	  | integer (primary key) |                                                 		|
| articleId	      	  | integer (foreign key) | **[Article](./api-resource-article)**           		|
| unitId			  | integer (foreign key) | 														|
| number	      	  | string				  | 							                    		|
| supplierNumber	  | string				  | 														|
| kind				  | integer				  |															|
| additionalText	  | string				  |															|
| active			  | boolean				  |															|
| inStock			  | integer				  |												    		|
| stockMin			  | integer				  |															|
| weight			  |	string				  |												    		|
| len				  | string				  |															|
| height			  | string				  |															|	
| ean				  | string				  | 														|
| position			  | integer				  |															|
| minPurchase		  | integer				  |															|
| purchaseSteps		  |	integer				  |															|
| maxPurchase		  | integer				  |															|
| purchaseUnit		  | string				  |															|
| shippingFree		  | boolean				  |															|
| releaseDate		  | date/time			  |															|
| shippingTime		  | string				  |															|
| prices			  | array				  | **[Price](./models/price)**								|
| attribute			  | object				  | **[Attribute](./models/article-attribute)**				|
| configuratorOptions | array				  | **[ConfiguratorOptions](./models/configurator-option)** |

## POST
To post a variant, you need to provide the data as shown below:


### Data

You can use this data to add a new variant to the shop
| Model								| Table						|
|-----------------------------------|---------------------------|
| Shopware\Models\Article\Detail	| `s_articles_details`		|

| Field               | Type                  | Original object                                 		|
|---------------------|-----------------------|---------------------------------------------------------|
| id 	         	  | integer (primary key) |                                                 		|
| articleId	      	  | integer (foreign key) | **[Article](./api-resource-article)**           		|
| unitId			  | integer (foreign key) | 														|
| number	      	  | string				  | 							                    		|
| supplierNumber	  | string				  | 														|
| kind				  | integer				  |															|
| additionalText	  | string				  |															|
| active			  | boolean				  |															|
| inStock			  | integer				  |												    		|
| stockMin			  | integer				  |															|
| weight			  |	string				  |												    		|
| len				  | string				  |															|
| height			  | string				  |															|	
| ean				  | string				  | 														|
| position			  | integer				  |															|
| minPurchase		  | integer				  |															|
| purchaseSteps		  |	integer				  |															|
| maxPurchase		  | integer				  |															|
| purchaseUnit		  | string				  |															|
| shippingFree		  | boolean				  |															|
| releaseDate		  | date/time			  |															|
| shippingTime		  | string				  |															|
| prices			  | array				  | **[Price](./models/price)**								|
| attribute			  | object				  | **[Attribute](./models/article-attribute)**				|
| configuratorOptions | array				  | **[ConfiguratorOptions](./models/configurator-option)** |

You can post or put data by sending the following data to this URL:

* **(POST or PUT) http://my-shop-url/api/translations/id**

## PUT

To put data to a variant, simply provide one of the following parameters to identify it:

| Identifier		| Parameter		| Database Column					| Example Call										|
|-------------------|---------------|-----------------------------------|---------------------------------------------------|
| Detail Id			| id			| `s_articles_details.id`			| /api/variants/2									|
| Detail number		| number		| `s_articles_details.ordernumber`	| /api/variants/SW10003?useNumberAsId=true			|

**The data is the same as shown in the POST operation.**

You can use this data to update variant.
| Model								| Table						|
|-----------------------------------|---------------------------|
| Shopware\Models\Article\Detail	| `s_articles_details`		|

| Field               | Type                  | Original object                                 		|
|---------------------|-----------------------|---------------------------------------------------------|
| id 	         	  | integer (primary key) |                                                 		|
| articleId	      	  | integer (foreign key) | **[Article](./api-resource-article)**           		|
| unitId			  | integer (foreign key) | 														|
| number	      	  | string				  | 							                    		|
| supplierNumber	  | string				  | 														|
| kind				  | integer				  |															|
| additionalText	  | string				  |															|
| active			  | boolean				  |															|
| inStock			  | integer				  |												    		|
| stockMin			  | integer				  |															|
| weight			  |	string				  |												    		|
| len				  | string				  |															|
| height			  | string				  |															|	
| ean				  | string				  | 														|
| position			  | integer				  |															|
| minPurchase		  | integer				  |															|
| purchaseSteps		  |	integer				  |															|
| maxPurchase		  | integer				  |															|
| purchaseUnit		  | string				  |															|
| shippingFree		  | boolean				  |															|
| releaseDate		  | date/time			  |															|
| shippingTime		  | string				  |															|
| prices			  | array				  | **[Price](./models/price)**								|
| attribute			  | object				  | **[Attribute](./models/article-attribute)**				|
| configuratorOptions | array				  | **[ConfiguratorOptions](./models/configurator-option)** |

## DELETE
To delete a variant, simply provide one of the following parameters to identify it:

| Identifier		| Parameter		| Database Column					| Example Call										|
|-------------------|---------------|-----------------------------------|---------------------------------------------------|
| Detail Id			| id			| `s_articles_details.id`			| /api/variants/2									|
| Detail number		| number		| `s_articles_details.ordernumber`	| /api/variants/SW10003?useNumberAsId=true			|

## Examples

TODO