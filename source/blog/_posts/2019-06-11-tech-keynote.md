---
title: Shopware Tech Keynote
tags:
- Tech Keynote
- Shopware Community Day
- Shopware 6

categories: 
- dev

robots:
    hide: true

authors: [dn]
github_link: /blog/_posts/2019-06-11-tech-keynote.md

---

# Shopware Tech Keynote

Shopware Community Day 2019 was the first time, we had two keynotes at the same time: The main keynote by our CEO and co-founder Sebastian Hamann - and the tech keynote by myself. For me it was a great honor and pleasure to give you a technical overview of Shopware 6; in this blog post, I want to summarize my thoughts about Shopware 6

# Why we went Shopware 6

Shopware 6 is a complete rewrite of Shopware. Of course we didn't reinvent the wheel for things, that we were happy with in Shopware 5; at the same time we wanted to improve many topics we have not been able to address in Shopware 5.

Furthermore we thought a lot, how E-Commerce might look like in a few years, which requirements we might need to address - and which customer segments we want to reach. We quickly found, that it was not about hype technologies - but about creating a broad foundation, that small and smallest merchants as well as enterprise merchants could be successful with. In addition to many technological considerations this especially means, that merchants needs to know what customers want - perhaps without the customers knowing, what they want.

In order to reach existing and new customers, I pointed out three dimensions in my keynote:

- Internationalisation
- New channels
- Customer loyalty

In Shopware 6 we placed great emphasis on these dimensions. For internationalisation we have new concepts for translations (and inheritance of translations). Also handling of gross and net prices as well as prices per currency has improved. The new SalesChannel concept allows for a new perspective on additional channels such as eBay, Amazon, Instagram etc. The new RuleBuilder allows you to address every SalesChannel specifically - and is also a powerful regarding customer loyalty, as you can shape very individual customer segments and address any customer exactly as you want to.

In order to set the potential of those dimensions free, complexity must be reduced. Merchants need to be able to try out new things and want to quickly identify failures and successes in order to learn from their experiences. At the same time enabling creativity and speed also means, that developers want to know, which direction to go. "Everything is possible somehow" is not a sentence, that will help you to create awesome software. That's why we reconsidered how e.g. our data model should look like, how extensions should take place and how developers want to work with the shopping cart. 

Enabling people to use the new technologies also implies, that we need to reduce legal barriers. By switching to the more permissive MIT license, shopware clearly commits to the open source community.

For me, this is the philosophy behind Shopware 6: We enjoy new technologies and are excited to try them out. But the developers and merchants are the ones, who are truly innovative in their everyday projects. And they do neither need a tight e-commerce corset nor a spaceship toolbox. They need a reliable foundation on which to build. And that's Shopware 6: Simple, flexible, state of the art and open source.

# Tech

Now let's have a look at the technology. The most obvious change is the switch to Symfony, for sure. Symfony is probably the most popular PHP framework and has shaped a whole generation of developers. By using Symfony in Shopware, we make sure, that it becomes easier to onboard developers on Shopware 6.

For the same reason, we are switching from ExtJS to VueJS as a javascript framework for our new admin. VueJS describes itself as a "progressive and incrementally adoptable" framework. For us this means, that it can be tailored to our needs and requirements. And again: We are convinced, that it makes it easier to onboard new developers to Shopware 6.

## DAL

Another major change is the new data abstraction layer (DAL). This is a layer between your database storage and the actual application and takes care of all your data operations - be it reads, writes, searches or aggregations.  

![DAL schema](/blog/img/dal.png)

In Shopware 5 we introduced a similar layer for read operations only for products in the storefront. We found, that it made Shopware much more understandable and predictable for the development community. But in difference to the product services in Shopware 5, the DAL takes care of all entities and all kind of data access. For that reason, no more custom SQL queries should be required anymore. Furthermore, the DAL can also take care of syncing various storages: In modern e-commerce infrastructures you will easily find ElasticSearch and Redis in addition to MySQL. The DAL can be used to sync those storages, so that MySQL is used as a primary storage but ElasticSearch is always kept in sync. And there are even more functionalities such as versioning or translations, that you can make use of easily:

```php
class ProductManufacturerDefinition extends EntityDefinition
{
    public function getEntityName(): string
    {
        return 'product_manufacturer';
    }

    protected function defineFields(): FieldCollection
    {
        return new FieldCollection([
            (new IdField('id', 'id'))->addFlags(new PrimaryKey(), new Required()),
            new VersionField(),

            new FkField('media_id', 'mediaId', MediaDefinition::class),
            new StringField('link', 'link'),
            new TranslatedField('name'),
            new TranslatedField('description'),
            new TranslatedField('customFields'),

            new ManyToOneAssociationField('media', 'media_id', MediaDefinition::class, 'id', true),
            (new OneToManyAssociationField('products', ProductDefinition::class, 'product_manufacturer_id', 'id'))->addFlags(new RestrictDelete(), new ReverseInherited('manufacturer')),
            (new TranslationsAssociationField(ProductManufacturerTranslationDefinition::class, 'product_manufacturer_id'))->addFlags(new Required()),
        ]);
    }
}

```

The above example show, how to define a product manufacturer entity with various translatable fields and relations to e.g. media and products. For more details please have a look at [the description in our documentation](<https://docs.shopware.com/en/shopware-platform-dev-en/internals/core/data-abstraction-layer/definition>).

## Extensions

If you are already familiar with the Shopware 5 plugin system, I've good news for your: Many of the patterns will look familiar to you. 

```
<project root>
└── custom
    └── plugins
        └── PluginQuickStart
            ├── src
            │   ├── Controller
            │   │   └── MyController.php
            │   ├── Resources
            │   │   ├── config
            │   │   │   ├── config.xml
            │   │   │   ├── routes.xml
            │   │   │   └── services.xml
            │   ├── Service
            │   │   └──  MyService.php
            │   ├── Subscriber
            │   │   └── MySubscriber.php
            │   └── PluginQuickStart.php
            └── composer.json
```

So you still have a base plugin file, where you can implement various methods to define the behaviour of your plugin during installation or uninstallation. Creating new services or extending existing ones is possible using the `service.xml` definition file. And if you want to use events, you can write the same kind of subscribers, you've been using in Shopware 5, too. For more details, have a look at our [plugin quickstart guide](<https://docs.shopware.com/en/shopware-platform-dev-en/internals/plugins/plugin-quick-start?category=shopware-platform-dev-en/internals/plugins>).

## Summary

All in all a lot of things have changed in Shopware 6. But we are convinced, that this will make things easier for you in the long run. If you are looking for additional resources, have a look at our [documentation](<https://docs.shopware.com/en/shopware-platform-dev-en>) or our [training program](<https://www.shopware.com/en/academy/online-trainings/>). There are a lot of free remote trainings, that will help to get started with Shopware 6.

# Business Models

Talking about "business models" in a tech keynote might seem a little strange. But if we really want to understand, what kind of requirements are relevant for our development community, we had to understand, what kind of requirements you get in projects - and where things became tedious in the past.

## RuleBuilder

The RuleBuilder is a whole new concept regarding customer targeting. In Shopware 5 (and many other e-commerce systems) you will just find some kind of "groups" that can be used for pricing, promotion, content, category restrictions, countries etc. The more usecases one had, the more complicated it became to maintain all the groups.

The RuleBuilder goes another approach: It allows you to create complex and nested rules that describe a customer you want to address for a certain usecase. This not only make easier to create "dynamic groups" (e.g. a group of all customers older than 18 or a group with all customers from a certain area) but also makes it easier to  give those customers special pricing, special content or restrict orders / payments / shipments for them.

The new RuleBuilder allows Shopware 6 to meet requirements that would have required a custom extension in the past. 

## New Cart

One of the earliest parts of Shopware 6 is the new cart. Actually the first concepts where created in 2016. We analyzed many usecases where people had to modify the cart in order to meet the merchant's requirements. 

As the new RuleBuilder can also be used to create price definitions, price handling becomes much more flexible with the new cart. Not only is the cart much more powerful when it comes to the question which customer gets which price: it also is more flexible regarding threshold prices per currency and handling of gross / net prices. 

Another big topic was database performance. On the one hand, you want to make sure, that during the cart process all pricing and stock information is consistent and correct and no order is possibly lost. On the other hand, you want to reduce the impact of the cart onto the database as much as possible, as this critical part of most e-commerce-systems is notoriously known for its heavy write operations. This is why the new cart is much more lightweight and reduces read / write operations as much as possible. The price, to which this comes, are conventions regarding the information you should / could access with your extensions. 

## Shopping experiences

A new tool for merchants are the so called "shopping experiences". With this tool, it becomes easier to create unique and appealing content for the online shop. Of course - as you might now it from shopping worlds in Shopware 5 - you can create content. But the shopping experiences set another focus: 

There a dozens of prefabricated blocks, which show you all kind of combinations between images, texts, sliders, videos and commerce components. You can just pick the block, that comes closest to your imagination and drop it to the designer. This designer will always give you a realistic overview of how the shopping experience could look like in the frontend. At the same time it allows you to configure the elements to your needs: You can edit texts, swap elements with others or rearrange blocks. Every block is responsive by default. So you don't need to think about viewports and columns any more.

![data mapping with the CMS](/blog/img/cms-data-mapping.png)

What makes the shopping experience so powerful is the fact, that every page you created this way, can be used as a layout for category pages, product pages or landing pages. You can even dynamically reference properties of those pages. For example: If you create a layout for categories, you can dynamically reference the category's name. For a product layout, you could dynamically reference an image of the product or its description.

And for the future, we are planning more functionalities, which will come in handy in many projects: E.g. editorial workflows for content pages or versioning.

# Conclusion

Of course this can just be a glimps of the new technologies, functionalities and concepts in Shopware 6. But what makes this awesome from my perspective is the fact, that based on our experiences with small, medium and large e-commerce projects we asked ourselves: What do customers want? What do merchants need? What do you need as a developer? This common theme makes Shopware 6 more fun to use - and more powerful in projects. 
At the SCD 19 we released the developer preview of Shopware 6. With this version we want to collect your feedback as a developer. Feel free to check out our [Shopware 6 repository on Github](https://github.com/shopware/platform) and give us feedback. 