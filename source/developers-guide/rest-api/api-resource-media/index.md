---
layout: default
title: REST API - Media resource
github_link: developers-guide/rest-api/api-resource-media/index.md
menu_title: Media resource
menu_order: 150
indexed: true
menu_style: bullet
group: Developer Guides
subgroup: REST API
---

## Introduction

In this part of the documentation you can learn more about the API's media resource.
With this resource, it is possible to retrieve, create and delete any media of your shop.
We will also have a look at the associated data structures.

## General information

This resource handles everything related to the media that is stored in your shop.
This includes article images, blog images and downloadable files.

This resource supports the following operations:

| Access URL | GET                    | GET (List)             | PUT                    | PUT (Batch)          | POST                   | DELETE                 | DELETE (Batch)       |
|------------|------------------------|------------------------|------------------------|----------------------|------------------------|------------------------|----------------------|
| /api/media | ![Yes](../img/yes.png) | ![Yes](../img/yes.png) | ![Yes](../img/yes.png) | ![No](../img/no.png) | ![Yes](../img/yes.png) | ![Yes](../img/yes.png) | ![No](../img/no.png) |

## GET

To get information about a specific media, you can simply call the API as shown in this example:

```
GET http://my-shop-url/api/media/{id}
```

### Return Value

| Model                       | Table   |
|-----------------------------|---------|
| Shopware\Models\Media\Media | s_media |

| Field       | Type                  | Original Object |
|-------------|-----------------------|-----------------|
| id          | integer (primary key) |                 |
| albumId     | integer (foreign key) |                 |
| name        | string                |                 |
| description | string                |                 |
| path        | string                |                 |
| type        | string                |                 |
| extension   | string                |                 |
| userId      | integer (foreign key) |                 |
| created     | date                  |                 |
| fileSize    | integer               |                 |

## GET (List)

To get a list of media, simply call the url without providing any id:

```
GET http://my-shop-url/api/media
```

### Return Value

| Model                       | Table   |
|-----------------------------|---------|
| Shopware\Models\Media\Media | s_media |

This API call returns an array of elements, one for each media.
Each of these elements has the following structure:


| Field       | Type                  | Original Object |
|-------------|-----------------------|-----------------|
| id          | integer (primary key) |                 |
| albumId     | integer (foreign key) |                 |
| name        | string                |                 |
| description | string                |                 |
| path        | string                |                 |
| type        | string                |                 |
| extension   | string                |                 |
| userId      | integer (foreign key) |                 |
| created     | date                  |                 |
| fileSize    | integer               |                 |

Appended to the above-mentioned list, you will also find the following data:

| Field   | Type    | Comment                                      |
|---------|---------|----------------------------------------------|
| total   | integer | The total number of media resources          |
| success | boolean | Indicates if the call was successful or not. |

## POST (create)

If you wish to add new data to the shop's media collection, simply create an array and send it via `POST` request to the API.

```
POST http://my-shop-url/api/media
```

| Model                       | Table   |
|-----------------------------|---------|
| Shopware\Models\Media\Media | s_media |

| Field                  | Type                  | Original Object                          |
|------------------------|-----------------------|------------------------------------------|
| album (required)       | integer (foreign key) |                                          |
| name                   | string                | Auto generated if not provided           |
| file (required)        | string                | Path to the file that should be uploaded |
| description (required) | string                |                                          |
| path                   | string                | Auto generated if not provided           |
| type                   | string                | Auto generated if not provided           |
| extension              | string                | Auto generated if not provided           |
| userId                 | integer (foreign key) |                                          |
| created                | date                  | Auto generated if not provided           |
| fileSize               | integer               | Auto generated if not provided           |

**The most of these values are generated automatically (such as `fileSize` and `created`).
It is not recommended setting them manually**

## PUT (update)

As of Shopware 5.3, you can replace a media file by providing a dataURI or link to fetch a resource from.

```
PUT http://my-shop-url/api/media/{id}
```

Replace the `id` with the specific media id.

| Field           | Type   | Description          |
|-----------------|--------|----------------------|
| file (required) | string | Path / URL / dataURI |

## DELETE

In order to delete a specific media, simply call the following URL using the `DELETE` operation:

```
DELETE http://my-shop-url/api/media/{id}
```

Replace the `id` with the specific media id.
