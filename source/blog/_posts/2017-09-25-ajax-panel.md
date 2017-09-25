---
title: Ajax Panel
tags:
- frontend
- ajax
- javascript
- jquery
- framework

categories:
- dev

authors: [lh]
github_link: blog/_posts/2017-09-25-ajax-panel.md

---

In this blog post I want to present the concept of our ajax based panel system which we use in our B2B-Suite.
In the B2B-Suite we had to develop a backend user interface inside the shopware frontend. 
We evaluated different frameworks to achieve this target. AngularJS and Vue.JS were possible frameworks which allows
two way data binding and statefull frontend access.

Our target was also to use most of the already existing dependencies instead of adding new frameworks just for
the B2B-Suite. As you maybe already know jQuery is a base dependency of Shopware 5.

We decided to develop our own lightweight frontend framework which allows asynchronous HTTP requests for our frontend.
The main target of this framework is to execute the asynchronous call and render the response in a given DOM element.

This approach allows us to build front pages with one dom element. An additional feature is that we can still work
under the http cache and be able to ignore the shopware cache ids.

## Code Example
The base structure of our ajax panel look like this:
```html
<div class="ajax-panel" data-url="http://domain.tld/ajax-panel-controller" data-id="example"></div>
```

On page load our jQuery ajax panel plugin will search for the attribute `data-url`. If this attribute contains a valid
url the jQuery plugin will perform an asynchronous http request on the attribute url. If the response is signed with 
a HTTP Status Code of 200 the response will be rendered back in the original div with the data-id `example`.

## Ajax Panel Plugins
After we build our first views we run in several problems. The biggest problem was, that we want to use jQuery in the 
response rendered content. We decided to develop an own plugin loader for our ajax panel which loads automatically
JavaScript plugins after the panel load. We throw many events for third party plugins that deveopers can use for their 
own plugin. Also the shopware default plugins can be registered in the ajax panel.

To achieve this approach we added an optional `data-plugins` attribute which can contain multiple JavaScript plugins:

```html
<div class="ajax-panel" data-url="[..]" data-id="[..]" data-plugins="examplePlugin"></div>
```

## Basic Ajax Panel Plugin
Our JavaScript example plugin code looks like that:

```javascript
/**
 * Remove all elements with triggerSelector Class
 */
$.plugin('b2bAjaxPanelExamplePlugin', {
    defaults: {
        triggerSelector: '.should--removed'
    },

    init: function () {
        var me = this;
        me._on(document, 'b2b--ajax-panel_loaded', $.proxy(me.addClasses, me));
    },

    addClasses: function (event, eventData) {
        var $panel = $(eventData.panel);

        $panel
            .find(me.defaults.triggerSelector)
            .remove();
    },

    destroy: function() {
        var me = this;
        me._destroy();
    }
});
```

## Dependencies
With the ajax panel we build a lightweight frontend framework which only depends on jQuery. Shopware delivers jQuery in the 
Responsive theme, so we don't need to require any addional component. Awesome, isn't it?

## Conclusion
Our Ajax Panel is a complete lightweight framework with many possibilities and jQuery as a single dependency. 
The panel can be handled with simple data attributes and additional plugins allows to use JavaScript plugins.