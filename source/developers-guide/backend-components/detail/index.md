---
layout: default
title: Shopware Backend Components Detail
github_link: developers-guide/backend-components/detail.md
tags:
  - backend
  - extjs
  - standard components
indexed: true
---

Im letzten Tutorial <a href="http://community.shopware.com/Shopware-Backend-Komponenten-Listingansicht_detail_1406_871.html">Shopware Backend Komponenten - Listingansicht</a> wurden die Möglichkeiten des Shopware.grid.Panels erläutert. In diesem Tutorial werden die Grundlagen der Detailansicht erklärt und in Beispielen angewendet. Hierzu werden die Komponenten Shopware.model.Container und Shopware.window.Detail genauer erläutert.

Als Grundlage für dieses Tutorial dient das Plugin Ergebnis aus dem Tutorial <a href="http://community.shopware.com/Shopware-Backend-Komponenten-Listingansicht_detail_1406_871.html">Shopware Backend Komponenten - Listingansicht</a>. Dieses Plugin können Sie hier nochmal herunterladen:
<a class="btn primary download" href="http://community.shopware.com/files/downloads/swagproduct-14067634.zip" target="_blank">Plugin herunterladen</a>

Das `Shopware.window.Detail` wurde in dem Plugin für die Produktdetailseite unter `SwagProduct/Views/backend/swag_product/view/detail/window.js` implementiert.
Der `Shopware.model.Container` wurde im Plugin für die detailierte Ansicht des Produkt Models unter `SwagProduct/Views/backend/swag_product/view/detail/product.js` implementiert.

<h2>Shopware.window.Detail Basics</h2>
Die `Shopware.window.Detail` Komponente, nachfolgend auch Detail Window genannt, dient als Einstiegspunkt für die Detailansicht und wird im `Shopware.grid.Panel` als `detailWindow` Konfiguration hinterlegt.
Das `Shopware.grid.Panel` übergibt dem Detail Window einen einzelnden Record, dies ist das einzige Requirement für das Detail Window. Das Detail Window erzeugt für den übergebenen Record die im `Shopware.data.Model` hinterlegte Detailansicht, welche im `detail` Property in der `configure()` Funktion des `Shopware.data.Model` hinterlegt wurde:
```javascript
Ext.define('Shopware.apps.SwagProduct.model.Product', {
    extend: 'Shopware.data.Model',
    configure: function() {
        return {
            controller: 'SwagProduct',
            detail: 'Shopware.apps.SwagProduct.view.detail.Product'
        };
    },
    fields: [
        { name : 'id', type: 'int', useNull: true },
        ...
    ]
});
```

<h3>Event Controlling</h3>
Das Controlling der `Shopware.window.Detail` und `Shopware.model.Container` Events wird von dem `Shopware.detail.Controller` geregelt. Dieser Controller wird automatisch vom `Shopware.window.Detail` erzeugt und an das Detail Window gebunden. Die `Shopware.window.Detail` und `Shopware.model.Container` Events werden zur Sicherheit, wie beim `Shopware.grid.Panel`, mit einem Eventalias geprefix. Dieser Eventalias wird automatisch vom `Shopware.detail.Window` anhand des übergebenen Record Klassennamen ermittelt.
<br><b>Beispiel</b>:
<br>Der Name des übergebenen Models lautet: `Shopware.apps.SwagProduct.model.Product`.
<br>Als Eventalias verwendet die Detailansicht nun den letzten Bestandteil des Modelnames: <code>eventAlias = 'product'</code>
<br><br>Alle Events werden nun mit dem Prefix `product` versehen:

* product-save
* product-tab-changed
* ...

Der `Shopware.detail.Controller` fängt diese Events ab und führt die Standard Aktionen für das entsprechende Event aus. 
In dem Tutorial Eigenes Komponenten Controlling wird genauer erklärt wie es möglich ist die Shopware Standard Controller zu deaktivieren oder zu erweitern.

<h2>Shopware.model.Container Basics</h2>
In diesem Abschnitt des Tutorials wird auf die Basics des `Shopware.model.Container`, nachfolgend auch Model Container genannt, eingegangen und wie der Model Container im Hintergrund agiert. Dazu wird erklärt wie der Model Container die Fieldsets und Formular Felder erzeugt und wie diese konfiguriert werden können. Als Requirement besitzt der `Shopware.model.Container` lediglich ein übergebenes `Shopware.data.Model` welches als `record` Property übergeben werden muss und den `controller` Parameter, welcher den Namen des PHP Controllers beinhalten muss. 

<h3>Formular Generierung</h3>
Als Grundlage für die Formular Generierung dient das übergebene `Shopware.data.Model`, welches als record Property übergeben wurde. Standardmäßig wird für das erzielen schneller Resultate in der Backend Entwicklung für jedes Feld des Models, abgesehen vom `id` Feld, ein einges Formular Feld erzeugt. Zudem wird für die gesamten Model Felder nur ein Field Set erstellt.

```javascript
Ext.define('Shopware.apps.SwagProduct.model.Product', {
    extend: 'Shopware.data.Model',
    configure: { ... },
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
2008741f275c1e48c0e046c5af53d7e6.jpg


Anhand des Datentypen des Model Feldes werden die verschiedenen Shopware Defaults für die Formular Felder erzeugt. Da ein Model jedoch sehr viel mehr Felder beinhalten kann, sollen nicht immer alle Felder angezeigt werden oder sollen in mehreren Fieldsets dargestellt werden. Um dies zu konfigurieren kann der `fieldSets` Konfigurationsparameter verwendet werden. Der `fieldSets` Parameter definiert wie viele `fieldSets` in dem Container erstellt werden sollen und welche Felder in den Fieldsets dargestellt werden sollen:

```javascript
Ext.define('Shopware.apps.SwagProduct.view.detail.Product', {
    extend: 'Shopware.model.Container',
    alias: 'widget.product-detail-container',
    configure: function() {
        return {
            controller: 'SwagProduct',
            fieldSets: [{
                title: 'Product data',
                fields: {
                    name: {},
                    active: {}
                }
            }, {
                title: 'Additional data',
                fields: {
                    description: {},
                    descriptionLong: {}
                }
            }]
        };
    }
});
```
9c4e21055311f470a0b043447863fd95.jpg

Die im `fields` Property definierten Spalten werden in der angegebenen Reihenfolge angelegt. Zusätzlich werden die Spalten eines Fieldsets in zwei Container, mit einem Column Layout, aufgeteilt um den Platz in der Detailansicht optimal auszunutzen.

Das `fields` Property dient nicht nur zur Limitierung und Sortierung der Felder, sondern bietet auch noch weitere Möglichkeiten die, zur Finalisierung der Applikation, sehr hilfreich sein können. 
Wie bereits im <a href="http://community.shopware.com/Shopware-Backend-Komponenten-Listingansicht_detail_1406_871.html#Generierung_der_Spalten">Shopware.grid.Panel columns</a> Parameter gibt es  verschiedene Möglichkeiten die Formular Felder und auch das Fieldset mit weiteren Konfigurationen zu versehen:

```javascript
Ext.define('Shopware.apps.SwagProduct.view.detail.Product', {
    extend: 'Shopware.model.Container',
    alias: 'widget.product-detail-container',
    padding: 20,

    configure: function() {
        return {
            controller: 'SwagProduct',
            fieldSets: [{
                title: 'Product data',
                fields: {
                    name: 'Product name',
                    active: { disabled: true }
                }
            }, {
                title: 'Additional data',
                layout: 'fit',
                fields: {
                    description: 'Short description',
                    descriptionLong: {
                        fieldLabel: null
                    }
                }
            }]
        };
    }
});
```
e33bffbf1502c7ea26ea7ab2e935fe5e.jpg

In dem obigen Beispiel werden für die Felder `description` und `name` die Shorthand Funktionalität der `fields` verwendet. Diese ermöglicht, die Definition eines Objektes für jedes Feld zu umgehen, wenn es sich lediglich um eine Übersetzung des `fieldLabel` handeln sollte.
Zudem wird für das zweite Fieldset das `column` Layout deaktiviert. Dadurch werden die Felder direkt untereinander dargestellt.

Sollte es mal nicht aussreichen, ein reines Objekt hinterlegen zu können, so kann für jedes Feld auch eine eigene Funktion hinterlegt werden, die aufgerufen werden soll um das Feld zu erstellen. Der hinterlegten Funktion wird dabei ein bereits generiertes Feld übergeben. Dieses kann entweder erweitert oder komplett überschrieben werden.

```javascript
Ext.define('...view.detail.Product', {

   extend: 'Shopware.model.Container',
   alias: 'widget.product-detail-container',
   configure: function() {
      return {
         controller: 'SwagProduct',
         fieldSets: [{
            title: 'Product data',
            fields: {
               name: 'Product name',
               active: { disabled: true }
            }
         }, {
            title: 'Additional data',
            layout: 'fit',
            fields: {
               description: this.createDescription,
               descriptionLong: { 
                  fieldLabel: null, 
                  xtype: 'tinymce' 
               }
            }
         }]
      };
   },

   createDescription: function(model, formField) {
      formField.xtype = 'textarea';
      formField.height = 90;
      formField.grow = true;
      return formField;
   }
});
```
65b5493dc58d046ff567ab1acb356e62.jpg

Da es auch informative Elemente in einer Detailansicht geben kann, welche nicht die Daten aus einem Model Feld darstellen sollen, kann auch für das gesamte "FieldSet" eine Funktion hinterlegt werden, welche aufgerufen werden soll um das "FieldSet" (Die Funktion muss kein Fieldset zurückgeben) zu erstellen.
So kann zum Beispiel ganz einfach eine Shopware Block Message eingebunden werden:

```javascript
Ext.define('...view.detail.Product', {

   extend: 'Shopware.model.Container',
   alias: 'widget.product-detail-container',
   configure: function() {
      return {
         controller: 'SwagProduct',
         fieldSets: [{
            title: 'Product data',
            fields: {
               name: 'Product name',
               active: { disabled: true }
            }
         }, 
         this.createCustomContainer,
         {
            title: 'Additional data',
            layout: 'fit',
            fields: {
               description: this.createDescription,
               descriptionLong: { 
                  fieldLabel: null, 
                  xtype: 'tinymce' 
               }
            }
         }]
      };
   },

   createCustomContainer: function() {
      return Shopware.Notification.createBlockMessage(
         'Hier könnte auch ein Shopware.grid.Panel eingebunden werden',
         'notice'
      );
   }
});
```
34149a3991bc993eebb545d668e09b4e.jpg

<h2>How to extend</h2>
In diesem Abschnitt des Tutorials wird erklärt wie das Shopware.detail.Window und der Shopware.model.Container einfach um  Applikation spezifische Funktionalitäten erweitert werden kann.
Wie beim Shopware.grid.Panel gibt es zwei Wege für die Erweiterbarkeit:

* Mittels Override der entsprechenden Funktion
* Mittels des Ext JS Events Systems.

In den nachfolgenden Beispielen werden beide Wege erläutert. Die verschiedenen Lösungswege sind über die Tabfunktionalität einsehbar.
Für die Erweiterung der Komponenten über das Ext JS Event System benötigen wir einen Ext JS Controller. In den nachfolgenden Beispielen wird dafür der Main Controller verwendet (`swag_product/controller/main.js`)

<h3>Neuen Toolbar Button</h3>
Um einen neuen Button in die Toolbar hinzuzufügen, müssen die Toolbarelemente des `Shopware.window.Detail` erweitert werden. Die Toolbar des `Shopware.detail.Window` wird in der Funktion `createToolbar()` erzeugt. Die eigentlichen Elemente der Toolbar werden jedoch in der `createToolbarItems()` Funktion des Detail Windows erzeugt:
```javascript
createToolbarItems: function() {
    var me = this, items = [];

    me.fireEvent(this.getEventName('before-create-toolbar-items'), me, items);

    items.push({ xtype: 'tbfill' });

    items.push(me.createCancelButton());

    items.push(me.createSaveButton());

    me.fireEvent(this.getEventName('after-create-toolbar-items'), me, items);

    return items;
},
```

Um nun einen neuen Button hinzuzufügen kann die Funktion direkt in der `Shopware.apps.SwagProduct.view.detail.Window` Komponente überschrieben werden oder über das Event <code>product-after-create-toolbar-items</code> im Main Controller abgefangen werden.

**Mittels Override**
```javascript
Ext.define('Shopware.apps.SwagProduct.view.detail.Window', {
    extend: 'Shopware.window.Detail',
    alias: 'widget.product-detail-window',
    title : '{s name=title}Product details{/s}',
    height: 270,
    width: 680,

    createToolbarItems: function() {
        var me = this,
            items = me.callParent(arguments);

        items.push(me.createToolbarButton());

        return items;
    },

    createToolbarButton: function() {
        return Ext.create('Ext.button.Button', {
            text: 'Eigener Toolbar Button'
        });
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
            'product-detail-window': {
                'product-after-create-toolbar-items': me.addDetailWindowButton
            }
        });
        me.mainWindow = me.getView('list.Window').create({ }).show();
    },

    addDetailWindowButton: function(window, items) {
        items.push(this.createToolbarButton());
        return items;
    },

    createToolbarButton: function() {
        return Ext.create('Ext.button.Button', {
            text: 'Eigener Toolbar Button'
        });
    }
});
```

<h3>Sidebar implementieren</h3>
Um eine Sidebar zu implementieren, muss der `Shopware.model.Container` des Products erweitert werden (`swag_product/view/detail/product.js`). Die Elemente des `Shopware.model.Containers` werden in der `createItems()` Funktion erzeugt:

```javascript
createItems: function() {
    var me = this, items = [], item, config,
        associations, fields, field, keys;

    if (!me.fireEvent(me.eventAlias + '-before-create-items', me, items)) {
        return false;
    }

    //iterate all defined field sets. If no field set configured, the component is used for none model fields.
    Ext.each(me.getConfig('fieldSets'), function(fieldSet) {
        ...
        item = me.createModelFieldSet(me.record.$className, fields, fieldSet);
        items.push(item);
    });

    ...

    me.fireEvent(me.eventAlias + '-after-create-items', me, items);

    return items;
},
```

Um nun eine Sidebar zu implementieren haben wir per Override die Möglichkeit die Funktion createItems in `SwagProduct/Views/backend/swag_product/view/detail/product.js`zu überschreiben und per Event System das Event <code>product-after-create-items</code> zu verwenden.
Wichtig bei den folgenden Anpassung ist es das Layout des Produkt Containers auf hbox zu ändern:

```javascript
Ext.define('...view.detail.Product', {
    extend: 'Shopware.model.Container',
    alias: 'widget.product-detail-container',
    layout: {
        type: 'hbox',
        align: 'stretch'
    },
    ...
});
```

Die `configure()` Funktion des Produkt Containers muss hierzu nicht angepasst werden, weswegen Sie im nächsten Beispiel entfernt wurde.

**Mittels Override**

```javascript
Ext.define('Shopware.apps.SwagProduct.view.detail.Product', {
    extend: 'Shopware.model.Container',
    alias: 'widget.product-detail-container',
    ...

    createItems: function() {
        var me = this,
            items = me.callParent(arguments);

        var leftContainer = Ext.create('Ext.container.Container', {
            flex: 1,
            margin: 20,
            items: items
        });

        return [leftContainer, me.createSidebar()];
    },

    createSidebar: function() {
        return Ext.create('Ext.panel.Panel', {
            width: 200,
            layout: {
                type: 'accordion',
                titleCollapse: false,
                animate: true,
                activeOnTop: true
            },
            items: [{
                title: 'Panel 1',
                html: 'Panel content!'
            },{
                title: 'Panel 2',
                html: 'Panel content!'
            }]
        });
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
            'product-detail-container': {
                'product-after-create-items': me.afterCreateItems
            }
        });

        me.mainWindow = me.getView('list.Window').create({ }).show();
    },

    afterCreateItems: function(container, items) {
        var me = this;

        //create left container to wrap the already generated items
        var leftContainer = Ext.create('Ext.container.Container', {
            flex: 1,
            margin: 20,
            items: Ext.clone(items)
        });

        //reset reference array
        items.length = 0;

        //create new items array structure
        items.push(leftContainer, me.createSidebar());
    },

    createSidebar: function() {
        return Ext.create('Ext.panel.Panel', {
            width: 200,
            layout: {
                type: 'accordion',
                titleCollapse: false,
                animate: true,
                activeOnTop: true
            },
            items: [{
                title: 'Panel 1',
                html: 'Panel content!'
            },{
                title: 'Panel 2',
                html: 'Panel content!'
            }]
        });
    }
});
```

Bei der Event System Lösung musste ein wenig getrickst werden. Da das Referenz Array nicht neu instanziert werden darf, wird diese über <code>items.lenght = 0</code> resetet. Zudem werden die bereits erstellten Elemente über ein `Ext.clone()` dem Wrapper Container zugewiesen. 

<h3>Tab Panel implementieren</h3>
Um ein Tab Panel in der Detailansicht zu implementieren ist nicht viel Source nötig. Denn die Detailansicht der Backend Komponenten supportet dieses bereits.
Hierfür ist das `Shopware.detail.Window` zuständig.
Damit immer alle Daten der Detailansicht gesendet werden, wird hier nicht wie in anderen Komponenten die Funktion createItems() aufgerufen um die Elemente zu erstellen, sondern die Elemente werden wie folgt erzeugt:
<code>detailWindow.items = [ me.createFormPanel() ];</code>
Dadurch ist sicher gestellt, dass auf oberster Ebene immer das Form Panel zum Speichern der Daten erzeugt wird.
In der Funktion `createFormPanel()` wird dann zum einen das Form Panel erzeugt und zum anderen werden die Elemente über `createTabItems()` generiert. Sollte die Funktion `createTabItems()` ein Array mit mehr als einem Element zurück geben, so werden die erzeugten Elemente in einem Tabpanel dargestellt:

```javascript
createFormPanel: function () {
    var me = this, items;

    items = me.createTabItems();

    if (items.length > 1) {
        me.tabPanel = Ext.create('Ext.tab.Panel', {
            items: items,
            ...
        });
        items = [ me.tabPanel ];
    }

    me.formPanel = Ext.create('Ext.form.Panel', {
        items: items,
        ...
    });
    return me.formPanel;
},

createTabItems: function () {
    var me = this, item, items = [];

    if (!me.fireEvent(me.getEventName('before-create-tab-items'), me, items)) {
        return [];
    }
    ...
    me.fireEvent(me.getEventName('after-create-tab-items'), me, items);

    return items;
}
```

Damit nun ein Tab Panel in der Detailansicht dargestellt wird, kann für das Override Beispiel die Funktion `createTabItems()` überschrieben werden und hier einfach das neue Elemente hinzu gefügt werden.
Für das Event System Beispiel kann das Event <code>product-after-create-tab-items</code> verwendet werden:

**Mittels Override**

```javascript
Ext.define('Shopware.apps.SwagProduct.view.detail.Window', {
    extend: 'Shopware.window.Detail',
    alias: 'widget.product-detail-window',
    title : '{s name=title}Product details{/s}',
    height: 270,
    width: 680,

    createTabItems: function() {
        var me = this,
            items = me.callParent(arguments);

        items.push(me.createOwnTabItem());

        return items;
    },

    createOwnTabItem: function() {
        return Ext.create('Ext.container.Container', {
            items: [],
            title: 'My tab item'
        });
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
            'product-detail-window': {
                'product-after-create-tab-items': me.afterCreateTabItems
            }
        });

        me.mainWindow = me.getView('list.Window').create({ }).show();
    },

    afterCreateTabItems: function(window, items) {
        var me = this;

        items.push(me.createOwnTabItem());

        return items;
    },

    createOwnTabItem: function() {
        return Ext.create('Ext.container.Container', {
            items: [],
            title: 'My tab item'
        });
    }
});
```

### Plugin Download - [SwagProduct.zip](http://community.shopware.com/files/downloads/swagproduct-14087253.zip)

Herzlichen Glückwunsch zu Ihrer ersten individualisierten Detailansicht mit den Shopware Backend Komponenten. Sie haben nun gelernt die Detailansicht vollständig für Ihr Plugin zu individualisieren und zu erweitern.

a913caf87f72e1d419edde15dc96a7e2.jpg


### Weitere Tutorials

Das waren die drei Tutorials zu den Basics der Shopware Backend Komponenten. In den nächsten Tutorials werden die hier erlernten Basics vertieft und es werden Assozierte Daten implementiert.
<a href="http://community.shopware.com/Shopware-Backend-Komponenten-Assoziationen_detail_1417_871.html">Shopware Backend Komponenten - Assoziationen</a>.