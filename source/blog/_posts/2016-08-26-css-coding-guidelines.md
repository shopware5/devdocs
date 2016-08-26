---
title: CSS coding guidelines
tags:
- cache
- http

categories:
- dev

authors: [stp]
indexed: true

github_link: blog/_posts/2016-08-26-css-coding-guidelines.md
---

Coding guidelines are important to improve collaboration and code quality. A lot of questions regarding our CSS coding
guidelines are coming up on our various platforms. In this blog post we're taking a closer look into the guidelines.

## Background
There are quite a few guides and principles how you should write your CSS code. Two of the most popular ones are [BEM](http://getbem.com/introduction/)
& [SMACSS](https://smacss.com/). Our guidelines are based on these two. We took the best parts of them and modified them to
our needs.

### Element sizing
The size of elements should be declared in percentage or REM values to provide relative values with the biggest flexibility.
The EM unit is relative to the `font-size` of the parent, which causes the compounding issue. The
REM unit on the other hand is relative to the root element (`html`). That means that we can define a single `font-size`
on the `html` element and define all REM units to be a percentage of that.

Let's take a quick look on the browser support:

<iframe src="https://caniuse.bitsofco.de/embed/index.html?feat=rem&periods=future_1,current,past_1" style="width: 100%; height: 375px;">
    We're sorry but your browser doesn't support the `iframe` element
</iframe>

As you can see, the browser support is pretty good & all of our target platforms are supported. Our best friend the Internet
Explorer 10 just has a partial support of the feature but that's not an issue for us.

Due to the fact that we're using LESS in the storefront, we created helpers, so called *mixins*, which are making it easy
to work with REM unit values. They're converting your pixel based values to REM values.

```
.my--class {

    .unitize(font-size, 16);             // Single properties

    .unitize-width(200);                 // Helpers for width and height

    .unitize-padding(20, 20);            // Helper for padding accepting the four value syntax

    .unitize-margin(10, 0, 0, 0);        // Helper for margin accepting the four value syntax
}
```

You can learn more on how to use the helpers in this [comprehensive guide](https://developers.shopware.com/designers-guide/less/).

## Think in objects

To write modular CSS you have to push yourself to think in objects. What are objects in this case? Objects are small
independent parts of functionality. UI elements like headers, footers, buttons or form elements are objects.
 
Object are **always** declared using a class selector. ID and tag based selectors are not that easy to customize. For example
to override an ID based selector you have to declare a selector chain with 255 classes to override one ID.

```
.button {
    background: linear-gradient(#eee, #ccc);
    border: 1px solid #999;
    color: #333;
    cursor: pointer;
    padding: 1em 1.5em;
     
    &:hover {
        background: linear-gradient(#fff, #ddd);
        color: #111;
    }
}
```

The `&` selector in LESS makes it easy to define different "states" of an object. In the above example we define the
"hover" state for the button.

## Parent-child relationship
Using a consistent naming scheme to define the parent-child relationship between objects helps to write much cleaner 
CSS code. Here's an example:

```
/** LESS code */
.post {
    margin: 2em;
     
    .title {
        font-size: 2em;
        font-weight: normal;
    }
}
  
/** Processed result */
.post {
    margin: 2em
}
.post .title {
    font-size: 2em;
    font-weight: normal;
}
```

As you can see in the above example we came up a hard relationship parent element `.post` and the child element `.title`.
The styling of the title will only be applied when the parent element has the class `.post`. That means we don't have the
chance to use the post title in a modified version for another object. So let's try to get rid of the hard relationship
between these two selectors:

```
/** LESS code and processed result */
.post {
    margin: 2em;
}
.post--title {
    font-size: 2em;
    font-weight: normal;
}
```

The main benefit of this approach is that the defined styles can be used throughout the application & we don't have
to worry about conflicts with similar named objects.

## Subclassing objects
The most object oriented concepts are using the concept of extending classes from other objects. This technique is called
*"subclassing"*. This is useful when you want to extend the properties of the object & define additional properties.

Let's go back to the button example and extend it to a drop down button:

```
.button {
    background: linear-gradient(#eee, #ccc);
    border: 1px solid #999;
    color: #333;
    cursor: pointer;
    padding: 1em 1.5em;
     
    &:hover {
        background: linear-gradient(#fff, #ddd);
        color: #111;
    }
}
  
.dropdown--button {
    &::after {
        content: '&#9662;'
    }
}
```

In this example we extended the button and transformed it to a drop down button. To use those button styles we have to
apply both classes to the "button" element:
  
```
<button class="button dropdown-button">
    Dropdown
</button>
```

## Using modifiers
The next topic is *"modifiers"*. A modifier can be used to define a certain state for an element or apply modifications
to the behavior and / or styling.

For states we're using the prefix `is--` as the [SMACSS](https://smacss.com/)'s naming scheme defines it. One of the most
popular use cases are active states:

```
.tab {
    background: #f7f7f7;
    color: #999;
    padding: 1em 1.5em;
     
    &.is--active {
        background: #fff;
        color: #545454;
        font-weight: bold
    }
}
```

Another example would be to modifying the behavior of the element. In the following example we can modify the size of a
form element using modifiers:

```

.textbox {
    font: 13px sans-serif;
    padding: 2px 4px;
  
    &.is--large {
        font-size: 18px;
    }
    &.is--small {
        font-size: 11px;
        padding: 1px 2px;
    }
}
```

In the above example we defined the modifiers only for this specific object. That's why we're using `&` parent selector.

## Global modifiers
As a counterpart to the element's specific modifiers we're having global modifiers which can be used in the layouting
process. Here are few examples:

```
.is--rounded { .border-radius(); }

.is--block { display: block !important; }
.is--inline { display: inline !important; }
.is--inline-block { display: inline-block !important; }
.is--hidden { display: none !important; }
.is--invisible { visibility: hidden !important; }

.is--align-left { text-align: left !important; }
.is--align-right { text-align: right !important; }
.is--align-center { text-align: center !important; }
.is--underline { text-decoration: underline !important; }
.is--line-through { text-decoration: line-through !important; }
.is--uppercase { text-transform: uppercase !important; }
.is--strong { font-weight: 600 !important; }
.is--bold { font-weight: bold !important; }
.is--italic { font-style: italic; }
.is--nowrap { white-space: nowrap !important; }

.is--dark { color: @text-color-dark !important; }
.is--light { color: darken(@gray-dark, 20%) !important; }
.is--soft { color: @gray-dark !important; }

.is--fluid { width: 100% !important; }
```

Global modifiers are mostly just setting one property. We're using the `!important` flag to override the property from
the original object styles.

## Classes which are set by a JavaScript plugin
The Shopware storefront uses a lot of JavaScript to enhance the experience for the user. In a lot of cases you don't know
if the class was on the element on page load or if it was added later in the process using JavaScript. To simplify this
we came up with the prefix `js--` to easily identify these selectors:

```
.js--slider {
    display: block;
    overflow: hidden;
  
    .js--slider-item {
        float: left;
        width: 25%;
    }
}
```

## Selector depth
LESS provides us with the ability to nest our rules which is a great feature but can be dangerous when you have to make
sure your styles can easily be customized by the user. As a general rule we defined that a maximum of 3 nesting layers
is perfect. It provides us with enough room to use nesting throughout our styling and doesn't produce overly complicated
selectors. 

Let's take a look on a bad usage of nesting:
```
/** LESS code */
.nav-bar {
    .nav-item {
        .button {
            &:hover { }
            &.feedback-button {
                color: #f00;
             
                &:hover { color: #0f0 }
            }
        }
    }
}
  
/** Processed result */
.nav-bar .nav-item .button.feedback-button { color: #f00 }
.nav-bar .nav-item .button.feedback-button:hover { color: #0f0 }
```

As you can see the processed result provides us with selectors which nobody would like to override to provide custom
styling. To match our guidelines we're splitting the object in two objects and using subclassing:

```
/** Object "Navigation bar" */
.nav-bar {
    .nav-item { /** ...properties here */ }
}
  
/** Object "Button" */
.button {
    &:hover { color: #f00 }
}
/** Subclass "Feedback Button", inherits the properties from the "Button" object */
.feedback-button {
    &:hover { color: #0f0 }

```

## tl;dr - Round up

**Objects** will be named with a noun:

```
.noun {}            // examples: .button, .menu, .textbox, .header
```

**Parent-child** relationships will be named a noun too:
 
```
.noun {}            // parent:  .post
.noun--noun {}      // child:   .post-title
```

**Subclasses** using a prefixed adjective which defines the type:

```
.adjective--noun {} // examples: .dropdown--button, .tooltip--button
```

**Modifiers** are using state classes:

```
.is--state {}       // state: .is--active, .is--hidden
```

**Classes**, which will be applied to element using **JavaScript** using the prefix `js--`:

```
.js--noun {}        // examples: .js--slider, .js--modal
```

**Support** classes using the prefix `has--` and defining the feature:

```
.has--property {}   // examples: .has--boxshadow, .has--localstorage
```
*You'll set support classes by hand, these classes will be set using [Modernizr](https://modernizr.com/).*
