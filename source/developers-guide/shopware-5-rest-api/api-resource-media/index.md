---
layout: default
title: Shopware 5 Rest API - Media End-Point
github_link: developers-guide/shopware-5-rest-api/api-resource-media/index.md
indexed: true
---

## Introduction

In this part of the documentation you can learn more about the API's media resource. With this resource, it is possible to 
receive, create and delete any media of your shop. Also we will have a look at the provided data.

## General information
You may find the related resource under
**engine\Shopware\Controllers\Api\Media.php**.

This resources handles everything around the media that is stored in your shop. This includes article images, blog images or downloadable files.

This resource supports the following operations:

|  Access URL                 | GET                | GET (List)      | PUT             | PUT (Stack)      | POST             | DELETE          | DELETE (Stack)  |
|-----------------------------|--------------------|-----------------|-----------------|------------------|------------------|-----------------|-----------------|
| /api/media                  | ![Yes](./img/yes.png)    | ![Yes](./img/yes.png) | ![No](./img/yes.png)  | ![No](./img/no.png)    | ![Yes](./img/no.png)   | ![Yes](./img/yes.png) | ![No](./img/no.png)   |

## GET

To get information about a specific medium, you can simply call the API as shown in this example:

* **http://my-shop-url/api/media/id**

And don't forget to replace 'id' with the specific identity.

### Return Value

| Model					             | Table			     |
|------------------------------------|-----------------------|
| Shopware\Models\Media\Media        | s_media               |

| Field                 | Type                  | Original Object                                                               |
|-----------------------|-----------------------|-------------------------------------------------------------------------------|
| id				    | integer (primary key) | 							                                                    |
| albumId			    | integer (foreign key) | 									                                            |
| name   			    | string                |                         														|
| description			| string                |  										   										|
| path					| string				| 										      								    |
| type					| string				| 																		        |
| extension 			| string				| 																				|
| userId				| integer (foreign key) |																				|
| created				| date/time				|																			    |
| fileSize  			| integer				|																				|

## GET (List)

To get list list of medias, simply call

* **http://my-shop-url/api/media/**

without providing any id.

### Return Value

| Model					             | Table			     |
|------------------------------------|-----------------------|
| Shopware\Models\Media\Media        | s_media               |

| Field                 | Type                  | Original Object                                                               |
|-----------------------|-----------------------|-------------------------------------------------------------------------------|
| id				    | integer (primary key) | 							                                                    |
| albumId			    | integer (foreign key) | 									                                            |
| name   			    | string                |                         														|
| description			| string                |  										   										|
| path					| string				| 										      								    |
| type					| string				| 																		        |
| extension 			| string				| 																				|
| userId				| integer (foreign key) |																				|
| created				| date/time				|																			    |
| fileSize  			| integer				|																				|

*Since this returns a list, the following fields will be appended to the array:*

| Field               | Type                  | Comment			                                |
|---------------------|-----------------------|-------------------------------------------------|
| total				  | integer				  | The total amount of category resources          |
| success		      | boolean				  | Indicates if the call was stressful or not.		|

## POST 
If you wish to add new data to the shop's media collection simply create an array and send it via POST request to the API.
The following keys can be provided in the array:

| Model					             | Table			     |
|------------------------------------|-----------------------|
| Shopware\Models\Media\Media        | s_media               |

| Field                 | Type                  | Original Object                                                               |
|-----------------------|-----------------------|-------------------------------------------------------------------------------|
| albumId (required)    | integer (foreign key) | 	                                								            |
| name   			    | string                | Auto generated if not provided    											|
| file (required)		| string				| Path to the file that should be uploaded										|
| description (required)| string                |  										   										|
| path					| string				| Auto generated if not provided		      								    |
| type					| string				| Auto generated if not provided										        |
| extension 			| string				| Auto generated if not provided												|
| userId				| integer (foreign key) |																				|
| created				| date/time				| Auto generated if not provided											    |
| fileSize  			| integer				| Auto generated if not provided												|

**The most of those values are being generated automaticly (such as fileSize and created). It is not recommended to set them manually!**
## DELETE

In order to delete a specifc medium simply call the following URL using the 'DELETE' operation:

* **(DELETE) http://my-shop-url/api/media/id**

Simply replace 'id' with the specific identifier.

## Examples

TODO