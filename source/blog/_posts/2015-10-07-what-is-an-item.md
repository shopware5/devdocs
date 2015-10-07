---
title: What is an item?
tags:
    - product
    - item
    - article
    - Shopware
    - variant
    - property
    - attribute
    - order
    - data structure
    - er diagram

categories:
- dev
indexed: false
github_link: blog/_posts/2015-10-07-what-is-an-item.md

authors: [dn]
---
In a shopping cart like Shopware the items are one of the main entities.
This blog post will have a look at Shopware items in several situations like listing, cart or order.
Furthermore it will discuss the general difference between e.g. *variant* and *property*.

# The correct name
<img alt="shopping carts" style="margin:10px;float: right;width:20%;" src="/blog/img/cart.jpg">
What an item actually is, highly depends on the actual shopping cart solution - many manufactures
have different approaches to items. This does not only apply for the item from a technical
perspective; it even applies for the name: *product*, *article* or *item* - somehow these terms
might be used to refer to the same entity - but there are subtle differences in meaning, depending
on whom you talk to.

So usually a *product* is a broader term, which might apply e.g. for an insurance or a commercial
software. For the company shopware, for example, the software Shopware could be considered a product -
but not necessarily any single plugin in the shopware community store is a product in this sense.
*Articles*, however, are often associated with blog posts and other reading material, even though the
term perfectly applies for "items" from a general perspective. *Items* finally is a term
that will perfectly match "the thing you have in your cart" - but it might be considered very generic.

In this blog post I will stick to the name *item* for any entity that can be purchased in a Shopware shop.

# What does an item look like…
## …in the item tables?
<a href="/blog/img/db_article.png">
    <img alt="s_articles db schema" style="margin:10px;float: left;width:20%;" src="/blog/img/db_article.png">
</a>
<a href="/blog/img/db_detail.png">
    <img alt="s_articles_details db schema" style="margin:10px;float: right;width:20%;" src="/blog/img/db_detail.png">
</a>
In Shopware there are basically four database tables that will describe a concrete item:
The most relevant are `s_articles` and `s_articles_details`. These define the basic information
and relations of the item.
The `s_articles` table will contain all the general information of the item, e.g. the
description, the long description, SEO keywords, tax rate and supplier.
The `s_articles_details` contains all the information that might differ per variant. Even
if the item is not a variant item, it will still have one entry in `s_articles` and one entry
in`s_articles_details`. The `s_articles_details` will for example have the order number,
stock values, weight, width, height, length or an EAN.
A special field is the `kind` field: If it is `1`, this variant is the **only** or the **main**
variant of the product. The variant with `kind=1` will usually be shown first on the article
detail page or in listings.

The prices can be found in `s_articles_prices`. Every variant from the `s_articles_details` does
have one or more entries here. The price table might have multiple entries per variant,
e.g. for bulk prices or for price groups.
Also important regarding extensibility is the `s_articles_attributes` table. Every entry
in the `s_articles_details` will have exactly one entry in the `s_articles_attributes` table,
so it is an OneToOne relation.
These four tables are usually considered the minimum requirement of an item. So items without
a **price** or **attribute** will not be shown in the frontend.

## …in the store front?
Starting with Shopware 5, there are three kind of representations of an item:

* `\Shopware\Bundle\StoreFrontBundle\Struct\BaseProduct`
* `\Shopware\Bundle\StoreFrontBundle\Struct\ListProduct`
* `\Shopware\Bundle\StoreFrontBundle\Struct\Product`

The `BaseProduct` only contains the most relevant information to identify a product, the
`s_articles::id`, `s_articles_details::detailID` and `s_articles_details::number`.

The `ListProduct` extends the `BaseProduct` and adds information relevant for the listing.
The full product representation can be found in form of the `Product` on the items detail page.

The full product will not only contain all the information from the `ListProduct` - it will
also have e.g. configurator / variant information and nested similar and related items that
will then again be listed as `ListProducts`.

The UML diagram of the frontend items does look like this:

![UML diagram of frontend items](/blog/img/uml_products.png)

All protected properties (indicated by `#`) are accessible by getters. The public
methods (indicated by `+` in the last section of every class) do provide additional logic.

## …in the cart
<a href="/blog/img/db_basket.png">
    <img alt="s_order_basket db schema" style="margin:10px;float: left;width:20%;" src="/blog/img/db_basket.png">
</a>
Items, as discussed above, are used everywhere in the back office as well as the frontend or even the API.
In many cases it boils down to entries in `s_articles`, `s_articles_details`, `s_articles_prices` and
`s_articles_attributes`.

Things change, when a customer adds an item to the cart. Then the `s_order_basket` comes into play:
In the Shopware cart, every cart item will be represented as a row in the `s_order_basket`. Items
belonging to the same cart of the same user are grouped by the corresponding `sessionID` column.
The `s_order_basket` is pretty much a stand alone representation of the cart: There is a `s_order_basket_attributes`
table for plugin extensions and Shopware tends to re-calculate the cart items by looking up
 the `ordernumber` of the cart items. But generally all relevant price and tax information can
 be found in the `s_order_basket`.

## …in an order
<a href="/blog/img/db_order.png">
    <img alt="s_order db schema" style="margin:10px;float: left;width:20%;" src="/blog/img/db_order.png">
</a>
When a customer finishes the checkout, an order is created. The main order table is `s_order`, it will
summarize the order regarding shipping method, total costs, payment state and associated user.
For data integrity reasons, however, the purchased item is also duplicated into the `s_order_details`
table. Here you will find e.g. the order number of the original item (`articleordernumber`), the `price`,
`quantity` or the `name` of the item. There are also some additional fields like `status` and `shipped`
which will provide additional information like "was this item shipped already?". The column `modus`
has the same meaning as in `s_order_basket`: It well tell apart "physical items"
(items existing in `s_articles` / `s_articles_details`) from virtual items (e.g. surcharges) and vouchers.


# Does Shopware have *master* or *parent* items?
Often I am asked, if Shopware uses some sort of *master* or *parent* structure for the items.
I think this is not the case. De facto an item always requires an entry from the `s_articles`
as well as from `s_articles_details`. The combination of both forms a purchasable item.
But there is no fallback-mechanism or *template* item, as master/parent systems usually have
it. So if you want to have individual descriptions per variant, you will usually add a custom
attribute for this.
Same applies for the order numbers: In Shopware a purchasable item has a unique order number.
As the `s_articles` entry is not purchasable on its own, there is no order number for it. The
main reason for this is the fact, as the variants are grouped together to an item by the
`articleID` in the `s_articles_details`. So actually there is no need for us, to group items
by an abstract order number. If you should need such a "common" order number for all variants,
anyway, I also recommend using attributes for this.
differentation
# Telling apart variants, properties and attributes
A common issue for new Shopware users is the differentiation of
*variant*, *property*, *filter* and *attribute*.
The following list tries to tell apart the concept behind the terms.
## Variant
A variant (sometimes also called "item detail") is a concrete forming of an item. Thinking
of a t-shirt, there might be variants like "Color: red, size: XL" or "Color: green, size: L".
A variant usually consists of "groups" (color, size) and "options" (XL, L, M or green, red).

Variants are usually used for *stock relevant, distinguishable aspects* of an item. So the fact
that a t-shirt is available in red and green is relevant and distinguishable, as you might
have it physically in stock. If you haven't, you cannot sell it.

Of course, you can also use variants to present other aspects of an item - that might be
useful, as in Shopware the prices are per variant. So for example an extended warranty
might be a variant, even though the warranty has nothing to do with your stock of that item.
So long story short: A variant is something the user can purchase.

Relevant tables:

* `s_article`: Here the common main item of all variants can be found. The main item
has a reference to a configurator set (`configurator_set_id`)
* `s_article_details`: The variant
* `s_articles_prices`: The variant prices
* `s_article_configurator_sets`: Basically any product will have an own configurator set. Any
set will have *groups* and *options* assigned
* `s_article_configurator_set_group_relations`: Which *groups* are active for a given set
* `s_article_configurator_set_option_relations`: Which *options* are active for agiven set
* `s_article_configurator_groups`: All existing *groups*
* `s_article_configurator_options`: All existing *options*
* `s_article_configurator_option_relations`: Mapping of a concrete *variant* to one ore more *options*

## Property
In opposite to *stock relevant, distinguishable* aspects (variants), there might also be informative,
accidental aspects of an item. These will usually be represented as *properties* (sometimes called *filter*).
Our typical examples for this kind of information is "suggested drinking temperature",
"technical certificates" or "brand". This kind of information might be relevant for the
customer and perhaps he will even filter and search for it - but its not relevant for
your stock and therefore an "accidental" fact of the individual items.

As properties are usually very easy filterable in the frontend, its a common usecase, to
also duplicate some aspects of the variants to the properties. With the new Shopware 5
components, it became easier to add filters even without having properties for those
fields, so that the separation of properties and variants might be easier to understand.
See my [example implementation of a variant filter](https://github.com/dnoegel/DsnVariantFilter)
for more details.

Relevant tables:

* `s_filter`: All filter groups as e.g. "Wine"
* `s_filter_options`: E.g. "drinking temperature" or "taste"
* `s_filter_values`: E.g. "taste: heavy" or "drinking temperature: <18°C"
* `s_filter_articles`: Mapping of items to the corresponding filter values
* `s_filter_relations`: Mapping of filter groups (wine) to the corresponding options (drinking temperature)

## Attribute
Attributes are additional fields a user or developer can add on a per-variant / per-product base.
For that reason attributes are used for e.g. variant-specific descriptions, per-product
shipping costs or perhaps stock information (shelf, position). In those cases, the user will
be able to maintain those fields easily from the Shopware backend (using the item module) and
perhaps show them in the Shopware frontend with an easy template modification.

Attributes can also be additional fields for developers, that (perhaps) will not be shown
in the back office / frontend at all. In those cases developers will store technical information
like "abo commerce ID" or "bundle ID", that are not directly relevant for the shop owner - but
relevant for a plugin.

So generally speaking, attributes can be used to extend the item model of Shopware, so that
it fits the need of the customer. In some cases, the customer will directly maintain those fields,
in other cases, the fields are hidden and will only be used by developers.

Relevant tables:

* `s_articles_attributes`: Any variant has exactly one attribute row. Additional attributes
(created by the shop owner or a plugin) will basically add new table columns.

There are many other attribute tables for e.g. customers, vouchers, shops - so all these entities
are extensible just the same way as items are.



# ER diagram
An ER diagram (entity-relationship model) is an overview of entities and their relationships.
For Shopware there is an ER diagram for the items and the most relevant relationships to other
entities (e.g. properties, shopping worlds etc).
You can find it [the PDF here](http://community.shopware.com/files/downloads/Shopware_ER_Diagramm.pdf).
