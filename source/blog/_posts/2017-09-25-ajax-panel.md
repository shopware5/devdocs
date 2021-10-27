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
In the B2B-Suite we had to develop a backend user interface inside the Shopware frontend. 

On a single page in a typical backend view you may find:
* list of entities
* form to enter new data
* list of related entities
* some statistics

Serving all this data from a single controller action may already be hard, now imagine the sheer number of parameters you may 
have to exchange with this single action when you want to enable pagination, validation and searching on this single page, 
through this single action.

This is pretty much the reason why stateless services and stateful frontends are an important topic in today's web development. 
And it is exactly the reason why we created the ajax panel. It provides us with the means to load local states from the 
server and create a rich ui.

We evaluated different frameworks to achieve this target. AngularJS and Vue.JS were possible frameworks which allows
two way data binding and stateful frontend access.

<img src="/blog/img/ajax-panel-abstract.svg" alt="image">

The small controller actions don't respond with the full page dom tree anymore. Each controller is only responsible for a
specific panel content.

One ouf our targets was also to use most of the already existing dependencies instead of adding new frameworks just for
the B2B-Suite. We use jQuery because it is a base dependency of Shopware 5.

We decided to develop our own lightweight frontend framework on top of jQuery which allows asynchronous HTTP requests for our frontend.
The main target of this framework is to execute asynchronous calls and render the response in a given and zoned DOM element by using an event.
The behaviour is very similar to angular's [zone.js](https://github.com/angular/zone.js).

## Code Example
The base structure of our ajax panel index action looks like this:
```html
<div class="ajax-panel" data-url="http://domain.tld/ajax-panel-controller" data-id="example"></div>
```

On page load our jQuery ajax panel plugin will search for the class `ajax-panel` and use the data attribute `data-url`. 
If this attribute contains a valid url the jQuery plugin will perform an asynchronous http request on the attribute url. 
If the response is signed with a HTTP Status Code of 200 the response will be rendered back in the original zoned div with the data-id `example`.

## Response Example

The ajax panel controller action responds with the content of the ajax panel element and moves the dom structure inside the given element.
The response could be like:

```html
<span>Example Ajax Content</span>
```

After the response is rendered in the parent Ajax Panel `div` the full dom structure will look like this:

```html
<div class="ajax-panel" data-url="http://domain.tld/ajax-panel-controller" data-id="example">

    <span>Example Ajax Content</span>

</div>
```

## Ajax Panel Plugins
After we build our first views we run in several problems. The biggest problem was, that we want to use jQuery in the 
response's rendered content. We decided to develop an own plugin loader for our ajax panel which loads automatically
JavaScript plugins after the panel load. We throw many events for third party plugins that developers can use for their 
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
Responsive theme, so we don't need to require any additional component. Awesome, isn't it?

## Conclusion
Our Ajax Panel is a complete flexible and lightweight framework with many possibilities and jQuery as a single dependency. 
The panel can be handled with simple data attributes and additional plugins allows to use JavaScript plugins.
We use this technology in our B2B-Suite in each module very successfully.
