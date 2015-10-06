---
layout: default
title: Shopware Backend Components Batch Processes
github_link: developers-guide/backend-components/batch-processes/index.md
tags:
  - backend
  - extjs
  - standard components
indexed: true
---

Im letzten Tutorial [Shopware Backend Komponenten - Listing Extensions](/developers-guide/backend-components/listing-extensions/) wurden die verfügbaren Listings Extensions erklärt.

In diesem Tutorial werden die Möglichkeiten erklärt mit den Shopware Backend Komponenten schnell und einfach eine Verarbeitung von großen Datenmengen zu implementieren.

Als Grundlage für dieses Tutorial dient das folgende Plugin: [Plugin herunterladen](http://community.shopware.com/files/downloads/swagproduct-14212679.zip)

Dieses Plugin ist das Ergebnis aus dem [Shopware Backend Komponenten - Basics](/developers-guide/backend-components/basics/) Tutorial:

38e2a8cd3d97274bb47b1ba2f4f8d859.jpg

## Verarbeitung von großen Datenmengen

Für die Verarbeitung von großen Datenmengen bieten die Shopware Backend Komponenten eine mitgelieferte Komponenten. Hierbei handelt es sich um die `Shopware.window.Progress` Komponente. 
Mit dieser Komponente ist es möglich mehrere Prozesse hintereinander ausführen und iterieren zu lassen.
Das `Shopware.grid.Panel` verwendet diese Komponente um alle selektierten Datensätze im Grid mit einem Klick löschen zu können:

59a1947753a8e2bf22cdfb36e92bf4d3.jpg

### Vorbereitung PHP Controller
Diese Komponente soll nun in das bestehende Produkt-Listing implementiert werden. Doch bevor die Shopware.window.Progress Komponente eingebunden werden kann, schaffen wir für die Implementierung die entsprechende Grundlage.

Hierfür werden zunächst die folgenden Funktionen im PHP Controller implementiert, die in den nachfolgenden Beispielen verwendet werden:

```php
class Shopware_Controllers_Backend_SwagProduct extends Shopware_Controllers_Backend_Application
{
    protected $model = 'Shopware\CustomModels\Product\Product';
    protected $alias = 'product';

    ...

  public function deactivateProductsAction()
    {
        try {
            $productId = $this->Request()->getParam('productId');

            /**@var $product \Shopware\CustomModels\Product\Product */
            $product = $this->getManager()->find(
                $this->model,
                $productId
            );

            $product->setActive(0);

            $this->getManager()->flush($product);

            $this->View()->assign(array('success' => true));
        } catch (Exception $e) {
            $this->View()->assign(array(
                'success' => false,
                'error' => $e->getMessage()
            ));
        }
    }

    public function changeCreateDateAction()
    {
        try {
            $productId = $this->Request()->getParam('productId');

            /**@var $product \Shopware\CustomModels\Product\Product */
            $product = $this->getManager()->find(
                $this->model,
                $productId
            );

            $product->setCreateDate('now');

            $this->getManager()->flush($product);

            $this->View()->assign(array('success' => true));
        } catch (Exception $e) {
            $this->View()->assign(array(
                'success' => false,
                'error' => $e->getMessage()
            ));
        }
    }
}
```

Die Funktion `deactivateProductsAction()` soll das Produkt mit der übergebenen `productId` deaktivieren.
Die Funktion `changeCreateDateAction()` setzt das Erstelldatum des Produktes auf den aktuellen Tag.
Beide Funktionen sind recht einfach gestrickt und sollen hier nur als Beispiel für die Implementierung der `Shopware.window.Progress` Komponente sein.

### Vorbereitung Ext JS Listing
Im Ext JS Teil wird für die Implementierung ein neuer Toolbar Button im Listing benötigt, über welchen diese Funktionen angesprochen werden können. Hierfür wird die Datei `SwagProduct/Views/backend/swag_product/view/list/product.js` wie folgt angepasst:

```php
Ext.define('Shopware.apps.SwagProduct.view.list.Product', {
    extend: 'Shopware.grid.Panel',
    alias:  'widget.product-listing-grid',
    region: 'center',

    configure: function() {
        return {
            detailWindow: 'Shopware.apps.SwagProduct.view.detail.Window'
        };
    },

    createToolbarItems: function() {
        var me = this, items = me.callParent(arguments);

        items = Ext.Array.insert(items, 2,
            [ me.createToolbarButton() ]
        );

        return items;
    },

    createToolbarButton: function() {
        var me = this;
        return Ext.create('Ext.button.Button', {
            text: 'Produkte ändern',
            handler: function() {
                me.fireEvent('change-products', me);
            }
        });
    }
});
```
8ff74e75cfd1e515e3f81a94f6753ee6_5.jpg

Sobald nun der Benutzer auf den Button klickt, wird das Event <code>change-products</code> auf dem Grid Panel gefeuert. Dieses Event kann dann gleich im Main Controller der Applikation verwendet werden um das Shopware.window.Progress zu öffnen.

## Implementierung Shopware.window.Progress
Bevor das `Shopware.window.Progress`, nachfolgend auch Progress-Window genannt, verwendet werden kann muss verständlich sein wie dieses funktioniert.

Das Progress-Window ist im Grund nur eine Helfer Komponente die von der eigentlichen Applikations-Logik nichts weiß und auch nichts wissen will.

Daher muss die Komponente auch nicht als eigene Applikation View definiert werden. Dies ist zwar möglich, jedoch nicht erforderlich um die Komponente zu implementieren.

Da die Komponente nicht wissen kann was bei den verschieden Tasks mit den Daten gemacht werden soll, muss sich die Applikation selbst darum kümmern. Dafür wird bei jedem Task ein Event definiert, welches auf der Komponente gefeuert werden soll.

Um nun das Progress-Window im Produkt-Listing zu implementieren wird zunächst der Main Controller der Applikation wie folgt angepasst:

```php
Ext.define('Shopware.apps.SwagProduct.controller.Main', {
    extend: 'Enlight.app.Controller',

    init: function() {
        var me = this;

        me.control({
            'product-listing-grid': {
                'change-products': me.displayProcessWindow
            }
        });
        me.mainWindow = me.getView('list.Window').create({ }).show();
    },

    displayProcessWindow: function(grid) {
        var selection = grid.getSelectionModel().getSelection();

        if (selection.length <= 0) return;

        Ext.create('Shopware.window.Progress', {
            title: 
            configure: function() {
                return {
                    tasks: [{
                        event: 'deactivate-products-process',
                        data: selection,
                        text: 'Produkt [0] von [1]'
                    }],

                    infoText: '<h2>Produkte deaktivieren</h2>' +
                        'Um den Prozess abzubrechen, können Sie den <b><i>`Cancel process`</i></b> Button verwenden. ' +
                        'Abhänging von der Datenmenge kann dieser Prozess einige Minuten in Anspruch nehmen.'
                }
            }
        }).show();
    }
});
```

Hier wird das vorhin implementierte Event <code>change-products</code> abgefangen und von der Funktion `displayProcessWindow` gehandelt.

In der Funktion werden zunächst die selektierten Datensätze mittels
`var selection = grid.getSelectionModel().getSelection();` ermittelt.

Sobald sicher gestellt ist dass Datensätze selektiert sind, kann die Shopware.window.Progress Komponente über ein `Ext.create()` erzeugt werden.

Um die Tasks zu konfigurieren, wird bei der Instanzierung der Komponente die `configure()` Funktion übergeben.
In dieser Funktion können dann die Tasks definiert werden. Da es mehrere Tasks geben kann, wird dem `tasks` Parameter ein Array zugewiesen `tasks: [ ... ]`.

Als Beispiel wurde hier ein Task implementiert, welcher gleich alle selektierten Produkte deaktivieren soll.
In dem Task wird zunächst definiert, welches Event gefeuert werden soll um die Daten zu verarbeiten:

```php
tasks: [{
    event: 'deactivate-products-process',
    ...
}]
```
Anschließend werden dem `data` Property alle Datensätze übergeben, die bearbeitet werden sollen `data: selection`. Das Progress-Window wird dadurch für jeden der übergebenen Datensätze das konfigurierte Event feuern.
Der hier konfigurierte Text wird in der Toolbar angezeigt. Die Werte <code>[0]</code> und <code>[1]</code> stehen hier für:

* 0 => Aktueller Index
* 1 => Anzahl Datensätze.

624e3698130ef9a6eea2e9647be141c1.jpg

Nun muss noch die Verarbeitung der Daten implementiert werden. Hierfür wird der Main Controller wie folgt angepasst:

```php
Ext.define('Shopware.apps.SwagProduct.controller.Main', {
    extend: 'Enlight.app.Controller',

    init: function() {
        var me = this;

        me.control({
            'product-listing-grid': {
                'change-products': me.displayProcessWindow
            },

            'shopware-progress-window': {
                'deactivate-products-process': me.onDeactivateProducts
            }
        });
        me.mainWindow = me.getView('list.Window').create({ }).show();
    },

    onDeactivateProducts: function (task, record, callback) {
        Ext.Ajax.request({
            url: '{url controller=SwagProduct action=deactivateProducts}',
            method: 'POST',
            params: {
                productId: record.get('id')
            },
            success: function(response, operation) {
                callback(response, operation);
            }
        });
    },
    
    ...
});
```

Hier wurde ein weitere Event Listener angelegt, welcher das Event <code>deactivate-products-process</code> abfangen und bearbeiten soll. Dieses Event wurde im vorherigen Schritt bei dem Task des `Shopware.window.Progress` hinterlegt.

Dem Event Listener werden die folgenden Parameter übergeben:

* `task` - Der aktuelle Task der ausgeführt wurde
* `record` - Der aktuelle Datensatz der bearbeitet werden soll
* `callback` - Callback Methode, welche für die Iteration aufgerufen werden muss.

Die `Shopware.window.Progress` Komponente erwartet nun, dass sich die Applikation um die Verarbeitung des Datensatzes kümmert. 

Da in den meisten Fällen in dieser Situation ein Ajax Request abgesendet wird, muss für die Fortsetzung des Prozesses die übergebene Callback Funktion aufgerufen werden. Andernfalls werden die weiteren Datensätze nicht bearbeitet.

In dem obigen Beispiel wird ein Ajax Request auf die Plugin Controller Funktion `deactivateProducts` gesendet, welche das übergebe Produkt deaktivieren soll.

Damit die anderen Datensätze auch bearbeitet werden, wird in der success Callback Methode des Ajax Request die übergebene `callback` Funktion aufgerufen.

65e062cad00c87b264ec3670694ac522.jpg

Anders als bei den bisherigen Komponenten, übernimmt die Shopware.window.Progress Komponente nicht die Datensteuerung für den Entwickler sondern die Generierung der View und iteration der Datensätze die sonst für jeden neuen Task neu implementiert werden müsste.

Besonders hevor sticht dieser Vorteil wenn ein weiterer Task definiert wird, der im gleichem Schritt mit ausgeführt werden soll.

Als Beispiel soll nun ein zusätzlicher Request gesendet werden, welcher das Erstelldatum des Produktes auf den aktuellen Tag setzt.

Hierfür wird der main Controller wie folgt angepasst:

```php
Ext.define('Shopware.apps.SwagProduct.controller.Main', {
    extend: 'Enlight.app.Controller',

    init: function() {
        var me = this;

        me.control({
            'product-listing-grid': {
                'change-products': me.displayProcessWindow
            },

            'shopware-progress-window': {
                'deactivate-products-process': me.onDeactivateProducts,
                'change-create-date-process': me.onChangeCreateDate
            }
        });
        me.mainWindow = me.getView('list.Window').create({ }).show();
    },

    onChangeCreateDate: function (task, record, callback) {
        Ext.Ajax.request({
            url: '{url controller=SwagProduct action=changeCreateDate}',
            method: 'POST',
            params: {
                productId: record.get('id')
            },
            success: function(response, operation) {
                callback(response, operation);
            }
        });
    },

    onDeactivateProducts: function (task, record, callback) {
        ...
    },

    displayProcessWindow: function(grid) {
        var selection = grid.getSelectionModel().getSelection();

        if (selection.length <= 0) return;

        Ext.create('Shopware.window.Progress', {
            title: 'Stapelverarbeitung',
            configure: function() {
                return {
                    tasks: [{
                        event: 'deactivate-products-process',
                        data: Ext.clone(selection),
                        text: 'Produkt [0] von [1]'
                    }, {
                        event: 'change-create-date-process',
                        data: Ext.clone(selection),
                        text: 'Geändertes Erstelldatum [0] von [1]'
                    }],

                    infoText: '<h2>Produkte deaktivieren</h2>' +
                        'Um den Prozess abzubrechen, können Sie den <b><i>`Cancel process`</i></b> Button verwenden. ' +
                        'Abhänging von der Datenmenge kann dieser Prozess einige Minuten in Anspruch nehmen.'
                }
            }
        }).show();
    }
});
```

Wichtig bei dieser Änderung ist die Zuweisung der Datensätze bei der Definition der Tasks. Da hier das selbe Array an Daten übergeben wird, muss dieses per `Ext.clone(selection)` zugewiesen werden. Dies muss nur gemacht werden wenn das selbe Daten-Array für zwei verschiedene tasks verwendet werden soll, da das Window mit einer Referenz der Daten-Arrays arbeitet.

b7981ea9a63f955d4743ba4e6c434a4b.jpg