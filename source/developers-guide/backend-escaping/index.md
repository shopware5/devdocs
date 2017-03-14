---
layout: default
title: Backend escaping
github_link: developers-guide/backend-escaping/index.md
shopware_version: 5.2.21
tags:
  - backend
  - extjs
  - escaping
  - html
indexed: true
group: Developer Guides
subgroup: Backend and ExtJS
menu_title: Backend escaping
menu_order: 100
---

Since shopware 5.2.21 the escaping of HTML tags in `Ext.form.field`s and `Ext.grid`s can be configured using a parameter. By default all HTML tags are stripped to prevent unwanted behaviour in backend views. To explicitly allow HTML tags in these components you can set `allowHtml` to `true` as shown in the following example:
```
Ext.define('Shopware.apps.ExampleGrid', {
    extend: 'Ext.grid.Panel',
    
    initComponent: function () {
        var me = this;
    
        me.columns = [{
            header: 'Name',
            dataIndex: 'name',
            allowHtml: true,
            flex: 1
        },
        ...
        ]
    },
    ...
}
```
In this example we create a new `Ext.grid.Panel` element with a `name` column in which HTML tags are allowed. If name is now set to `<strong>Mueller</strong>` it will be shown __bold__ in the grid.