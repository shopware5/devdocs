---
layout: default
title: Shopware Backend Components Listing Extensions
github_link: developers-guide/backend-components/listing-extensions/index.md
tags:
  - backend
  - extjs
  - standard components
indexed: true
---

Im letzten Tutorial [Shopware Backend Komponenten - Assoziationen](/developers-guide/backend-components/associations/) wurden die Möglichen Implementierungen von assoziierten Daten erklärt.
In diesem Tutorial werden die von Shopware bereitgestellten Listing Extensions erklärt und in das Produkt-Listing der letzten Tutorials implementiert.

Als Grundlage für dieses Tutorial dient das folgende Plugin: [Plugin herunterladen](http://community.shopware.com/files/downloads/swagproduct-14172882.zip)

Dieses Plugin ist das Ergebnis aus dem [Shopware Backend Komponenten - Assoziation](/developers-guide/backend-components/associations/) Tutorial.

1b10abf062b18da2bfe594b1e9cbbfb6.jpg

Shopware stellt als Extensions für die Listingansicht zwei Komponenten zur Verfügung:

* `Shopware.listing.InfoPanel` - Dient zur detaillierten Anzeige von Daten im Listing
* `Shopware.listing.FilterPanel` - Dient zur Erweiterung Filterung der Listingdaten

In den nachfolgenden Beispiel werden verschiedene neue Komponenten erstellt, welche jedes mal in der app.js registriert werden müssen. 

Damit das Tutorial übersichtlich bleibt, wird die app.js hier einmal dargestellt mit allen nachfolgend zu implementierenden Komponenten. Diese sind einfach nach jedem Abschnitt einzukommentieren:

```php
Ext.define('Shopware.apps.SwagProduct', {
    extend: 'Enlight.app.SubApplication',

    name:'Shopware.apps.SwagProduct',

    loadPath: '{url action=load}',
    bulkLoad: true,

    controllers: [ 'Main' ],

    views: [
        'list.Window',
        'list.Product',
        //'list.extensions.Info',
        //'list.extensions.Filter',

        'detail.Product',
        'detail.Window',

        'detail.Category',
        'detail.Attribute',
        'detail.Variant',
    ],

    models: [
        'Product',
        'Category',
        'Attribute',
        'Variant'
    ],
    stores: [
        'Product',
        'Variant'
    ],

    launch: function() {
        return this.getController('Main').mainWindow;
    }
});
```

## Shopware.listing.InfoPanel

Das Shopware.listing.InfoPanel, nachfolgend auch Info Panel genannt, kann verwendet werden um, bereits im Listing, detaillierte Daten des selektierten Datensatzes anzuzeigen.
Im nachfolgenden Beispiel wird dieses Info Panel in das bestehende Produkt-Listing des vorherigen Tutorials implementiert.

Dafür sind folgende Anpassungen an den Applikation Sourcen notwendig:

* Definition des Info Panels in einer View Komponente
* Konfiguration des Info Panels im Listing Window
* Registrierung des Info Panels in der app.js


Zunächst wird die neue View Komponente implementiert. Hierfür wird der folgende Source Code in die neue Datei `SwagProduct/Views/backend/swag_product/view/list/extensions/info.js` eingefügt:

```php
Ext.define('Shopware.apps.SwagProduct.view.list.extensions.Info', {
    extend: 'Shopware.listing.InfoPanel',
    alias:  'widget.product-listing-info-panel',
    width: 270,

    configure: function() {
        return {
            model: 'Shopware.apps.SwagProduct.model.Product'
        };
    }
});
```

Die einzige Vorraussetzung zur Verwendung der `Shopware.listing.InfoPanel` Komponente ist die Konfiguration des `model` Parameters in der `configure()` Funktion.

Durch dies Konfiguration kann das Info Panel für jedes Model Feld ein entsprechendes Template erzeugen, welches dann in der Ext.view.View dargestellt wird.

Anschließend wird die Info Panel Extension im Listing Window hinzugefügt. Dafür wird der extensions Parameter in der configure() Funktion implementiert und mit dem xtype der Komponente gesetzt:

```php
Ext.define('Shopware.apps.SwagProduct.view.list.Window', {
    extend: 'Shopware.window.Listing',
    alias: 'widget.product-list-window',
    height: 340,
    width: 600,
    title : '{s name=window_title}Product listing{/s}',

    configure: function() {
        return {
            ...
            extensions: [
                { xtype: 'product-listing-info-panel' }
            ]
        };
    }
});
```
bad95e23e62b4508bc319c9047cfaf49_5.jpg

<div class="alert alert-info">
Damit die Änderungen sichtbar werden, müssen die neuen Elemente in der app.js einkommentiert werden.
</div>

Die `Shopware.listing.InfoPanel` Extension erstellt für jedes des Feld, des konfigurierten Models, ein Anzeige Element was zunächst die Raw-Daten des Models anzeigt.

### Konfigurations Möglichkeiten

Um zu steuern welche Felder in welcher Reihenfolge angezeigt werden sollen, ist das fields Property der `configure()` Funktion zuständig. Dies bietet die selben Funktionalitäten wie die Spalten des Shopware.grid.Panel und die Formular Felder des Shopware.model.Container:

```php
Ext.define('Shopware.apps.SwagProduct.view.list.extensions.Info', {
    extend: 'Shopware.listing.InfoPanel',
    ...

    configure: function() {
        return {
            model: 'Shopware.apps.SwagProduct.model.Product',
            fields: {
                name: null,
                description: null
            }
        };
    }
});
```
6ca567adca90944ec5a35e7cf8cf1453_5.jpg

Jedes konfigurierte Feld kann auch ein Template hinterlegt haben in dem die Daten angezeigt werden sollen.
Standardmäßig wird für jedes Feld das folgende Template erzeugt:

```php
<p style="padding: 2px"><b>Name:</b> {literal}{name}{/literal}</p>
```

Als Platzhalter für den Wert des entsprechendes Feldes wird einfach der Name des Feldes in geschweiften Klammer angegeben. 

Wichtig hierbei ist, dass dieser Platzhalter mit einem {literal} umschlossen ist, da dieser sonst durch Smarty geparst wird:

```php
Ext.define('...view.list.extensions.Info', {
    extend: 'Shopware.listing.InfoPanel',
    ...
    configure: function() {
        return {
            model: 'Shopware.apps.SwagProduct.model.Product',
            fields: {
                name: '<p style="padding: 2px">' +
                         'Der Produktname lautet: ' +
                         '{literal}{name}{/literal}' +
                      '</p>',
            }
        };
    }
});
```
40f45af2a82e20f453e0744ac5509165_5.jpg

Aternativ kann auch eine Funktion hinterlegt werden, die aufgerufen werden soll, um das Template für ein Feld zu erzeugen:

```php
Ext.define('...view.list.extensions.Info', {
    extend: 'Shopware.listing.InfoPanel',
    ...
    configure: function() {
        var me = this;

        return {
            model: 'Shopware.apps.SwagProduct.model.Product',
            fields: {
                name: '...',
                description: me.createDescriptionField
            }
        };
    },

    createDescriptionField: function(infoPanel, field) {
        return '<p style="padding:10px 2px">' +
            'Aufruf einer Custom-Funktion für das Feld description' +
        '</p>';
    }
});
```
4437fc068d602f0b846e55cab7d69371_5.jpg

Diese beiden Funktionen sollten jedoch nur eingesetzt werden, wenn die Anzeige einzelner Felder spezifiziert werden soll. 

Wenn das gesamte Template überschrieben werden soll, kann stattdessen einfach die `createTemplate()` Funktion überschrieben werden:

```php
Ext.define('...view.list.extensions.Info', {
    extend: 'Shopware.listing.InfoPanel',
    ...

    configure: function() {
        return {
            model: 'Shopware.apps.SwagProduct.model.Product'
        };
    },

    createTemplate: function() {
        return new Ext.XTemplate(
            '<tpl for=".">',
            '<div class="item" style="">',
                '<p style="padding: 2px">',
                    'Der <b>Produktname</b> lautet: ',
                    '{literal}{name}{/literal}',
                '</p>',
                '<p style="padding: 10px 2px">',
                    '<b>Produktbeschreibung</b>: ',
                    '{literal}{description}{/literal}',
                '</p>',
            '</div>',
            '</tpl>'
        );
    }
});
```
4de3214807537c1c2d122f4a9be17d0c_5.jpg

Eine genaue Auflistung aller zur Verfügung stehenden Funktionen des Ext.XTemplates finden Sie in der offiziellen Ext JS Dokumentation unter: [Ext JS API - Ext.XTemplate](http://docs.sencha.com/extjs/4.1.3/#!/api/Ext.XTemplate).


## Shopware.listing.FilterPanel

Das `Shopware.listing.FilterPanel`, nachfolgend auch Filter Panel genannt, bietet die Möglichkeit zusätzlich zur Freitextsuche eine erweiterte Filterung im Listing bereit zu stellen.

Die Implementierung des Filter Panels entspricht der des Info Panels. Hierfür werden folgende Source Anpassungen vorgenommen:

* Definition des Filter Panels in einer View Komponente
* Konfiguration des Filter Panels im Listing Window
* Registrierung des Filter Panels in der `app.js`


Zunächst wird die neue View Komponenten implementiert. Hierfür wird der folgende Source Code in die neue Datei `SwagProduct/Views/backend/swag_product/view/list/extensions/filter.js` eingefügt:

```php
Ext.define('Shopware.apps.SwagProduct.view.list.extensions.Filter', {
    extend: 'Shopware.listing.FilterPanel',
    alias:  'widget.product-listing-filter-panel',
    width: 270,

    configure: function() {
        return {
            controller: 'SwagProduct',
            model: 'Shopware.apps.SwagProduct.model.Product'
        };
    }
});
```

Das `Shopware.listing.FilterPanel` benötigt zwei konfigurierte Parameter. 
Zum einen muss das `controller` Property in der `configure()` Funktion gesetzt sein damit der Such Request an den Plugin Controller gesendet werden kann.

Für die Generierung der Filter Felder muss der `model` Parameter konfiguriert sein. Hier wird das selbe Model erwartet, das im Listing-Grid-Store verwendet hier. 

Nachdem die Komponenten in der app.js registriert wurde (Hierfür kommentieren Sie die Zeile `//'list.extensions.Filter'` ein) kann diese als Extension im Listing Window hinterlegt werden:

```php
Ext.define('Shopware.apps.SwagProduct.view.list.Window', {
    extend: 'Shopware.window.Listing',
    alias: 'widget.product-list-window',
    height: 340,
    width: 800,
    title : '{s name=window_title}Product listing{/s}',

    configure: function() {
        return {
            listingGrid: 'Shopware.apps.SwagProduct.view.list.Product',
            listingStore: 'Shopware.apps.SwagProduct.store.Product',

            extensions: [
                { xtype: 'product-listing-filter-panel' }
            ]
        };
    }
});
```
8059551374e9423c774811754c001e6e_5.jpg

Die hier generierten Felder ähnleln der generierten Feldern in der Detailansicht. Die generierten Felder werden in einen Zusätzlichen Container erstellt und mit einer Checkbox versehen. Mittels der Checkbox kann gesteuert werden nach welchen Feldern gefiltert werden soll. Sollte mehr als ein Felder aktiviert sein, werden die Felder mit einer `AND` Verknüpfung als Filterbedingung übergeben:


fc4738ae5451f5fe847bea1673ed2fb2_5.jpg

ee9fba549934f86b778dc55a94819b90_5.jpg

### Konfigurations Möglichkeiten

In manchen Fällen kann es vorkommen dass nicht alle Felder gefiltert werden sollen. Daher bietet das `Shopware.listing.FilterPanel` eine Möglichkeit die Felder zu limitieren und zu konfigurieren:

```php
Ext.define('Shopware.apps.SwagProduct.view.list.extensions.Filter', {
    extend: 'Shopware.listing.FilterPanel',
    alias:  'widget.product-listing-filter-panel',
    width: 270,

    configure: function() {
        return {
            controller: 'SwagProduct',
            model: 'Shopware.apps.SwagProduct.model.Product',
            fields: {
                name: {},
                taxId: 'Steuersatz',
                active: this.createActiveField
            }
        };
    },

    createActiveField: function(model, formField) {
        formField.fieldLabel = 'Aktive Produkte';
        return formField;
    }
});
```
bcdfda68ab08b18aee8998374e9df00d_5.jpg

Die Konfiguration der Filter Felder funktioniert genauso wie die Spalten des `Shopware.grid.Panel` und der Felder des `Shopware.model.Container`.

## Plugin Download - [SwagProduct.zip](http://community.shopware.com/files/downloads/swagproduct-14183888.zip)

Das waren die beiden Listing-Extensions die Shopware für die Listingansicht bereit stellt.

805a5a3388084e7627d43cde4f770308.jpg

### Weitere Tutorials

In dem nächsten Tutorial werden die Möglichkeiten zur Batch Verarbeitung von Daten mit den Shopware Backend Komponenten erklärt.

[Shopware Backend Komponenten - Batch Prozesse](/developers-guide/backend-components/batch-processes/)