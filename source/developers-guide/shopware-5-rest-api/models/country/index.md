---
layout: default
title: Shopware 5 Rest API - Country Model
github_link: developers-guide/shopware-5-rest-api/models/country/index.md
indexed: true
---

## Introduction

This is the data of the country model.

* **Model:** Shopware\Models\Country\Country
* **Table:** s_core_countries

## Structure

| Field               		  | Type                  | Original object                                 |
|-----------------------------|-----------------------|-------------------------------------------------|
| id 	         	  		  | integer (primary key) |                                                 |
| name		      	  		  | string				  | 		                                        |
| iso				  		  | string				  | 												|
| isoName	      	  		  | string				  | 		                                        |
| position			  		  | integer				  | 												|
| description		  		  | string				  | 												|
| shippingFree		  		  | boolean				  | 												|
| taxFree			  		  | boolean				  | 												|
| taxFreeUstId		  		  | boolean				  | 												|
| taxFreeUstIdChecked 		  | boolean				  | 												|
| active			  		  | boolean				  | 												|
| iso3				  		  | string				  | 												|
| displayStateInRegistration  | boolean				  | 												|
| forceStateInRegistration	  | boolean				  | 												|
| areaId					  | integer				  | 												|


**[Back to overview](../)**