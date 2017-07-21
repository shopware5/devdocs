---
layout: labs
title: Internationalization
github_link: labs/internationalization/index.md
shopware_version: X
indexed: true
group: Labs
menu_title: Internationalization
menu_order: 300
---

Internationalisation is a topic that we are very passionate about here at shopware. Empowered by our company vision of
internationally shaping the world of eCommerce, we decided to put a strong focus on making it easier for shop owners to
create immersive and emotional shopping experiences for customers from all over the world. Below you can find some of the
areas of research this initiative for improved internationalisation includes.

## Flexible Taxation Rules
One of our research topics is the flexible integration of very detailed, international taxation rules. This includes individual tax rates
for states within a country, as e.g. the states in the USA each have different tax rules and regulations, as well as more fine-grained control
on a per-product level. Products that have plugs or can be considered 'dangerous goods' might be taxed differently in some countries but not others,
so taxes would need to be configurable based on product attributes and the like. 

## Internationalized Prices
Price handling can be very complicated depending on the country your shop is based in and the countries you are offering
your products in. You might be maintaining your prices in Euros, but as soon as you sell products to the UK, you need to 
take daily exchange rates into account, which will have an impact on the way prices are displayed to your customers. We are
currently evaluating ways to prevent ugly, auto-converted prices by e.g. allowing to maintain prices in multiple currencies 
or by allowing to round prices to the nearest X cents.

## Improved Form Handling and Configuration
While in Germany, almost every address consists of a street, street number, zip code and city, other countries can be vastly
different when it comes to address formatting. In the United Kingdom, houses might have their own zip code or a their own name
instead of a street number. Therefore we are researching, how internationalised form configuration could be implemented
to e.g. allow a different order of address fields during registration / checkout, depending on the user's country.