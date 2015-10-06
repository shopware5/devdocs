---
layout: default
title: Shopware Backend Components Listing
github_link: developers-guide/backend-components/listing.md
tags:
  - backend
  - extjs
  - standard components
indexed: true
---

Im letzten Tutorial [Shopware Backend Komponenten - Basics](/developers-guide/backend-components/) wurde mittels der Shopware Backend Komponenten ein simples Produktlisting im Backend implementiert. In diesem Tutorial werden die Grundlagen der Listingansicht erklärt und im Beispiel angewendet. Hierzu werden die Komponenten `Shopware.grid.Panel` und `Shopware.window.Listing` genauer erklärt.

Als Grundlage für dieses Tutorial dient das Plugin Ergebnis aus dem Tutorial Shopware Backend Komponenten. Dieses Plugin können Sie hier nochmal herunterladen: [Plugin herunterladen](http://community.shopware.com/files/downloads/swagproduct-14024152.zip)

Das `Shopware.grid.Panel` wurde hier für die Produktliste unter `SwagProduct/Views/backend/swag_product/view/list/product.js` implementiert.

Das `Shopware.window.Listing` wurde in als initiale Ansicht der Applikation unter `SwagProduct/Views/backend/swag_product/view/list/window.js` implementiert.

## Shopware.window.Listing Basics
Die `Shopware.window.Listing` Komponente, nachfolgend auch Listing Window genannt, besitzt nur wenige Konfigurationsmöglichkeiten und ist daher auch schnell erklärt. Das Listing Window wird in der Regel als Startfenster einer Applikation definiert und im Main Controller des letzten Beispiel Plugins beim Start der Applikation instanziert und angezeigt. Als Requirements besitzt das Listing Window die Konfigurationsparameter `listingGrid` und `listingStore`. In diesen beiden Parametern werden die Klassennamen des `Shopware.grid.Panel` und des `Shopware.store.Listing` deklariert:

```javascript
Ext.define('Shopware.apps.SwagProduct.view.list.Window', {
    extend: 'Shopware.window.Listing',
    alias: 'widget.product-list-window',
    height: 340,
    width: 600,
    title : '{s name=window_title}Product listing{/s}',

    configure: function() {
        return {
            listingGrid: 'Shopware.apps.SwagProduct.view.list.Product',
            listingStore: 'Shopware.apps.SwagProduct.store.Product'
        };
    }
});
```

Der hier definierte Listing Store wird dann bei der Instanzierung des Listing Windows in der `createListingStore()` Funktion erzeugt. Sollte kein Store definiert worden sein, wird folgende Fehlermeldung angezeigt:

<div class="alert alert-danger">Uncaught Shopware configuration error: Shopware.apps.SwagProduct.view.list.Window: Component requires the configured `listingStore` property in the configure() function.</div>

Das definierte `Shopware.grid.Panel` wird in der Funktion `createGridPanel()` erzeugt und und als Element in das items Property des Listing Windows hinzugefügt. Zusätzlich steht die Instanz des `Shopware.grid.Panel` in dem Listing Window Property `gridPanel` zur Verfügung. Dies vereinfacht den späteren Zugriff auf die Komponente.
Sollte kein `listingGrid` definiert worden sein, so wird die folgende Fehlermeldung angzeigt:

<div class="alert alert-danger">Uncaught Shopware configuration error: Shopware.apps.SwagProduct.view.list.Window: Component requires the configured `listingGrid` property in the configure() function. </div>

Weitere Konfigurationen des `Shopware.window.Listing` werden in weiter führenden Tutorials angesprochen.

## Showpare.grid.Panel Basics
In diesem Abschnitt des Tutorials wird auf die Basics des Shopware.grid.Panels eingegangen und wie das `Shopware.grid.Panel` im Hintergrund aggiert. So wird zum einen die Generierung der Spalten angesprochen aber auch die Konfigurationsmöglichkeiten der Gridspalten. Des Weiteren werden exemplarisch die möglichen Featurekonfigurationen und das Event Controling des Grid Panels angesprochen.

### Generierung der Spalten
Das `Shopware.grid.Panel` erwartet bei der Instanzierung einen übergebenen `Ext.data.Store`, welches ein `Ext.data.Model` hinterlegt hat. Dieses Model dient als Grundlage für die Generierung der Spalten. Standardmäßig wird für das Erzielen schneller Resultate in der Backend Entwicklung für jedes Feld des Models, abgesehen vom `id` Feld, eine Spalte erzeugt: 

```javascript
Ext.define('Shopware.apps.SwagProduct.model.Product', {
   fields: [
      { name : 'id', type: 'int', useNull: true },
      { name : 'name', type: 'string' },
      { name : 'active', type: 'boolean' },
      { name : 'createDate', type: 'date' },
      { name : 'description', type: 'string' },
      { name : 'descriptionLong', type: 'string' },
      { name : 'lastStock', type: 'boolean' }
   ]
});
```

be7db243c1109931f672ba31bff62701_5.jpg

Anhand des Datentypen des Model Feldes werden die verschiedenen Shopware Defaults für die Spalte erzeugt. Da ein Model jedoch sehr viel mehr Felder beinhalten kann, sollen nicht immer alle Spalten angezeigt werden. 
Um dies zu limitieren kann der `columns` Konfigurationsparameter in der <code>configure()</code> Funktion gesetzt werden. Sobald im `columns` Parameter Spalten definiert wurden, werden nur noch diese Spalten im Grid erzeugt und angezeigt. Der `columns` Parameter und die sich darin befindenden Spaltendefinitionen werden als Objekt deklariert. Innerhalb des `columns` Objekt werden die Spaltennamen als Properties gesetzt:

```javascript
Ext.define('Shopware.apps.SwagProduct.view.list.Product', {
    extend: 'Shopware.grid.Panel',
    configure: function() {
        return {
            columns: {
                name: {  }
            }
        };
    }
});
```
56004a3d0e01cf82b96f35af350bdf7a.jpg

Der `columns` Parameter kann nicht nur zur Limitierung der erzeugten Grid Spalten dienen sondern bietet auch noch weitere Funktionalitäten die bei der Finalisierung einer Applikation sehr hilfreich seinen können.

Die **erste Funktionalität** ist die Umsortierung der angezeigten Grid Spalten. Das `Shopware.grid.Panel` erzeugt die Spalten genau in der Reihenfolge, wie die Felder im Model definiert wurden. Dies ist jedoch nicht immer die Reihenfolge die im Grid oder später in der Detailansicht dargestellt werden soll. Sobald der `columns` Parameter gesetzt ist, werden die Spalten in der Reihenfolge erzeugt in der Sie im `columns` Parameter definiert wurden. 

Die **zweite Funktionalität** ist die Konfigurationsmöglichkeit der Spalten. Das hinterlegte Objekt bei jeder Spalte dient als Konfigurationsmöglichkeit um die Spalte genauer zu spezifizieren. So können bei jeder Spalte weitere Konfigurationen wie Übersetzungen oder Columnrenderer Funktion hinterlegt werden:

```javascript
Ext.define('Shopware.apps.SwagProduct.view.list.Product', {
    extend: 'Shopware.grid.Panel',
    configure: function() {
        return {
            columns: {
                name: { header: 'Produkt name' },
                description: { flex: 3 },
                active: { width: 60, flex: 0 }
            }
        };
    }
});
```
ec434027ef6bf38363bdc261b818d7ae.jpg

Doch dies ist nicht die einzige Vorgehensweise eine Spalte zu konfigurieren. Es ist ebenfalls möglich, eine Funktion zu hinterlegen, die aufgerufen werden soll um die Spalte zu erstellen:

```javascript
Ext.define('Shopware.apps.SwagProduct.view.list.Product', {
    extend: 'Shopware.grid.Panel',
    configure: function() {
        return {
            columns: {
                name: this.createNameColumn
            }
        };
    },

    createNameColumn: function(model, column) {
        column.header = 'Produkt name';
        return column;
    }
});
```
0546f86226cbb919e9e9886ce4a4b87e.jpg

In der obigen Definition wurde das `header` Property der Spalte `name` modifiziert. Damit nicht für jede Übersetzung ein Objekt mit dem header Property hinterlegt werden muss unterstützt das `Shopware.grid.Panel` auch ein Shorthand für die Übersetzung einer Spalte.

```javascript
Ext.define('Shopware.apps.SwagProduct.view.list.Product', {
    extend: 'Shopware.grid.Panel',
    configure: function() {
        return {
            columns: {
                name: 'Produkt name'
            }
        };
    }
});
```
0546f86226cbb919e9e9886ce4a4b87e.jpg

### Feature Konfiguration
Das `Shopware.grid.Panel` besitzt diverse Features wie die Toolbar und dessen Unterelement. Diese Features können aktiviert oder deaktiviert werden. Jedes Feature des `Shopware.grid.Panel` besitzt einen Aktivierungsparameter:

```javascript
Ext.define('Shopware.apps.SwagProduct.view.list.Product', {
    extend: 'Shopware.grid.Panel',
    configure: function() {
        return {
            columns: { name: 'Produkt name' },
            toolbar: false
        };
    }
});
```
c0e4075ee981b1be0c4108d0aead09a7.jpg

```javascript
Ext.define('Shopware.apps.SwagProduct.view.list.Product', {
    extend: 'Shopware.grid.Panel',
    configure: function() {
        return {
            columns: { name: 'Produkt name' },
            addButton: false,
            searchField: false
        };
    }
});
```
a38c33f14546895fa7579dc71a569d5f.jpg

```javascript
Ext.define('Shopware.apps.SwagProduct.view.list.Product', {
    extend: 'Shopware.grid.Panel',
    configure: function() {
        return {
            columns: { name: 'Produkt name' },
            actionColumn: false
        };
    }
});
```
f742f029a870d820a8e987a27a10298e.jpg

```javascript
Ext.define('Shopware.apps.SwagProduct.view.list.Product', {
    extend: 'Shopware.grid.Panel',
    configure: function() {
        return {
            columns: { name: 'Produkt name' },
            deleteColumn: false
        };
    }
});
```
c92cbbdc3f74da52c4a8f3b080db2c9e.jpg

<div class="alert alert-info">Eine genaue Dokumentation der Featureparameter befindet sich ebenfalls im Source Code der Komponenten. Die hier zur Verfügung stehenden Parameter, möglichen Werte eines Parameters und dessen Verwendung sind genaustens in den Sourcen dokumentiert.</div>

### Controlling der Events
Das Controlling der `Shopware.grid.Panel` Events wird von dem `Shopware.grid.Controller` geregelt. Dieser Controller wird automatisch vom `Shopware.grid.Panel` erzeugt und an das Grid Panel zugewiesen. Die `Shopware.grid.Panel` Events werden zur Sicherheit, damit Events nicht doppelt definiert sein können, mit einem Eventalias geprefixt. Dieser Eventalias wird automatisch vom `Shopware.grid.Panel` anhand des Store Modelnamens ermittelt.

**Beispiel**:  
Der übergebene Store besitzt das konfigurierte Model `Shopware.apps.SwagProduct.model.Product`. Als Eventalias verwendet das Grid nun den letzten Bestandteil des Modelnames: `eventAlias = 'product'`

Alle Events werden nun mit dem Prefix `product` versehen:

* product-add-item
* product-delete-items
* product-search
* ...

Der Shopware.grid.Controller fängt diese Events ab und führt die Standard Aktionen für das entsprechende Event aus. 
In dem Tutorial Eigenes Komponenten Controlling wird genauer erklärt wie es möglich ist die Shopware Standard Controller zu deaktivieren oder zu erweitern.

## How to extend
In diesem Abschnitt des Tutorials wird erklärt wie das Shopware.grid.Panel einfach um Applikations spezifische Funktionalitäten erweitert werden kann.

Das Shopware.grid.Panel ist auf zwei Wegen erweiterbar:

* Mittels Override der entsprechenden Funktion
* Mittels des Ext JS Events Systems.

In den nachfolgenden Beispielen werden beide Wege erläutert. Die verschiedenen Lösungswege sind über die Tabfunktionalität einsehbar.
Für die Erweiterung der Komponenten über das Ext JS Event System benötigen wir einen Ext JS Controller. In den nachfolgenden Beispielen wird dafür der Main Controller verwendet (`swag_product/controller/main.js`)

### Eigene Action Column hinzufügen
Die Action Column des `Shopware.grid.Panel` wird in der `createActionColumn()` Funktion erstellt. Die eigentlichen Elemente der Action Column werden in der `createActionColumnItems()` Funktion erstellt:

```javascript
createActionColumnItems: function () {
    var me = this, items = [];

    me.fireEvent(me.eventAlias + '-before-create-action-column-items', me, items);

    if (me.getConfig('deleteColumn')) {
        items.push(me.createDeleteColumn());
    }
    if (me.getConfig('editColumn')) {
        items.push(me.createEditColumn());
    }

    me.fireEvent(me.eventAlias + '-after-create-action-column-items', me, items);

    return items;
},
```

Um nun eine neue Spalte hinzuzufügen, kann die Funktion direkt in der `Shopware.apps.SwagProduct.view.list.Product` Komponente überschrieben werden oder über das Event `product-after-create-action-column-items` im Main Controller abgefangen werden:

**Mittels Override**
```javascript
Ext.define('Shopware.apps.SwagProduct.view.list.Product', {
    extend: 'Shopware.grid.Panel',
    alias:  'widget.product-listing-grid',
    region: 'center',

    createActionColumnItems: function () {
        var me = this,
            items = me.callParent(arguments);

        items.push({
            action: 'notice',
            iconCls: 'sprite-balloon',
            handler: function (view, rowIndex, colIndex, item, opts, record) {
                Shopware.Notification.createGrowlMessage(undefined, 'do some stuff in grid panel');
            }
        });
        return items;
    }
});
```

**Mittels Event System**

```php
Ext.define('Shopware.apps.SwagProduct.controller.Main', {
    extend: 'Enlight.app.Controller',
    init: function() {
        var me = this;
        me.control({
            'product-listing-grid': {
                'product-after-create-action-column-items': me.addActionColumn
            }
        });
        me.mainWindow = me.getView('list.Window').create({ }).show();
    },

    addActionColumn: function(gridPanel, items) {
        items.push({
            action: 'notice',
            iconCls: 'sprite-balloon',
            handler: function (view, rowIndex, colIndex, item, opts, record) {
                Shopware.Notification.createGrowlMessage('', 'do some stuff in main controller');
            }
        });
        return items;
    }
});
```

### Eigenen Toolbar Button implementieren
Die Toolbar des `Shopware.grid.Panel` wird in der `createToolbar()` Funktion erstellt. Die Elemente der Toolbar wiederum, werden in der `createToolbarItems()` Funktion erzeugt.

```php
createToolbarItems: function () {
    var me = this, items = [];

    me.fireEvent(me.eventAlias + '-before-create-toolbar-items', me, items);

    if (me.getConfig('addButton')) {
        items.push(me.createAddButton());
    }
    if (me.getConfig('deleteButton')) {
        items.push(me.createDeleteButton())
    }

    me.fireEvent(me.eventAlias + '-before-create-right-toolbar-items', me, items);

    if (me.getConfig('searchField')) {
        items.push('->');
        items.push(me.createSearchField());
    }

    me.fireEvent(me.eventAlias + '-after-create-toolbar-items', me, items);

    return items;
},
```

Damit das Beispiel nicht zu einfach gestrickt ist, soll der Button hinter dem `deleteButton` eingefügt werden. Dafür muss im Override Beispiel die Funktion `Ext.Array.insert()` verwendet werden, welche Elemente an eine bestimmte Position innerhalb eines Arrays einfügen.

Im Event System Beispiel wird das Event `product-before-create-right-toolbar-items` verwendet:


**Mittels Override**
```php
Ext.define('Shopware.apps.SwagProduct.view.list.Product', {
    extend: 'Shopware.grid.Panel',
    alias:  'widget.product-listing-grid',
    region: 'center',

    createToolbarItems: function() {
        var me = this,
            items = me.callParent(arguments);

         items = Ext.Array.insert(
             items,
             2,
             [ me.createToolbarButton() ]
         );

        return items;
    },

    createToolbarButton: function() {
        return Ext.create('Ext.button.Button', {
            text: 'Custom button'
        });
    }
});
```

**Mittels Event System**
```php
Ext.define('Shopware.apps.SwagProduct.controller.Main', {
    extend: 'Enlight.app.Controller',
    init: function() {
        var me = this;
        me.control({
            'product-listing-grid': {
                'product-before-create-right-toolbar-items': me.addToolbarButton
            }
        });
        me.mainWindow = me.getView('list.Window').create({ }).show();
    },

    addToolbarButton: function(grid, items) {
        items.push(this.createToolbarButton());
        return items;
    },

    createToolbarButton: function() {
        return Ext.create('Ext.button.Button', {
            text: 'Custom button'
        });
    }
});
```

### Zusatzspalte implementieren
Zuletzt soll noch eine zusätzliche Spalte in das Grid implementiert werden. Die Spalte ist nicht im Model definiert, daher erstellt das `Shopware.grid.Panel` keine automatisch generierte Spalte hierfür. Die Spalte soll prüfen ob das Produkt im Monat Juli im Shop angelegt wurde. Dargestellt werden soll das ganze in einer Checkbox innerhalb des Grids. 

Die Spalten des `Shopwar.grid.Panel` werden in der `createColumns()` Funktion erstellt:

```javascript
createColumns: function () {
    var me = this, model = null,
        column = null,
        configColumns = me.getConfig('columns'),
        columns = [];

    model = me.store.model.$className;

    if (model.length > 0) {
        model = Ext.create(model);
    }

    me.fireEvent(me.eventAlias + '-before-create-columns', me, columns);
    
    if (me.getConfig('rowNumbers')) {
        columns.push(me.createRowNumberColumn());
    }

    var keys = model.fields.keys;
    if (Object.keys(configColumns).length > 0) keys = Object.keys(configColumns);

    Ext.each(keys, function(key) {
        var modelField = me.getFieldByName(model.fields.items, key);
        column = me.createColumn(model, modelField);

        //column created? then push it into the columns array
        if (column !== null) columns.push(column);
    });

    me.fireEvent(me.eventAlias + '-before-create-action-columns', me, columns);

    if (me.getConfig('actionColumn')) {
        column = me.createActionColumn();
        if (column !== null) {
            columns.push(column);
        }
    }

    me.fireEvent(me.eventAlias + '-after-create-columns', me, columns);

    return columns;
}
```

Um die neue Spalte hinzuzufügen muss im Override Beispiel die `createColumns()` Funktion überschrieben werden. Im Event System Beispiel kann das Event `product-before-create-action-columns` verwendet werden:

**Mittels Override**
```javascript
Ext.define('Shopware.apps.SwagProduct.view.list.Product', {
    extend: 'Shopware.grid.Panel',
    alias:  'widget.product-listing-grid',
    region: 'center',
    
    createColumns: function() {
        var me = this,
            columns = me.callParent(arguments);

        var column = {
            xtype: 'gridcolumn',
            header: 'Created in july',
            renderer: me.columnRenderer,
            sortable: false,
            dataIndex: 'inJuly'
        };

        columns = Ext.Array.insert(
            columns,
            columns.length - 1,
            [ column ]
        );

        return columns;
    },

    columnRenderer: function(value, metaData, record) {
        var date = record.get('createDate');
        return this.booleanColumnRenderer((date.getMonth() === 6));
    }
});
```

**Mittels Event System**
```javascript
Ext.define('Shopware.apps.SwagProduct.controller.Main', {
    extend: 'Enlight.app.Controller',
    init: function() {
        var me = this;
        me.control({
            'product-listing-grid': {
                'product-before-create-action-columns': me.addColumn

            }
        });
        me.mainWindow = me.getView('list.Window').create({ }).show();
    },

    addColumn: function(grid, columns) {
        var me = this;

        columns.push({
            xtype: 'gridcolumn',
            header: 'Created in july',
            renderer: me.columnRenderer,
            sortable: false,
            dataIndex: 'inJuly'
        });

        return columns;
    },

    columnRenderer: function(value, metaData, record) {
        var date = record.get('createDate');
        return this.booleanColumnRenderer((date.getMonth() === 6));
    }
});
```

In diesem Beispiel ist eine Besonderheit enthalten. Und zwar ist die Funktion `booleanColumnRenderer`. Diese Funktion wird in beiden Beispielen verwendet ohne dass diese im Shopware.grid.Panel noch im Enlight.app.Controller definiert wurde. Hierbei handelt es sich um eine Helfer Funktion von Shopware, welche sich in der `Shopware.model.Helper` <b>Klasse</b> befindet. Diese <b>Klasse</b> wird in allen Komponenten per Ext JS mixin eingebunden. Dadurch stehen sämtliche Funktionen dieser Klasse im `this` Scope zur Verfügung. Die `booleanColumnRenderer` Funktion im Main Controller Beispiel steht zur Verfügung da die Renderer Funktion als Scope das Grid Panel übergeben bekommt.


### Plugin Download - [SwagProduct.zip](http://community.shopware.com/files/downloads/swagproduct-14067634.zip)

Herzlichen Glückwunsch zu Ihrer ersten individualisierten Listing Komponenten mit den Shopware Backend Komponenten. Sie haben nun gelernt die Listingansicht vollständig für Ihr Plugin zu individualisieren und zu erweitern.


### Weitere Tutorials

In dem nächsten Tutorial wird die Implementierung und Individualisierung der Detailansicht der Shopware Backend Komponenten erklärt.
Weiter mit <a href="http://community.shopware.com/Shopware-Backend-Komponenten-Detailansicht_detail_1408_871.html">Shopware Backend Komponenten - Detailansicht</a>.