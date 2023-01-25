---
title: Quick Tip - Shopping worlds without AJAX
tags:
- javascript
- shoppingworlds
- emotions

categories:
- dev

authors: [stp]
github_link: blog/_posts/2017-06-26-shopping-worlds-without-ajax.md

---

Today we're back with a short & simple tip which allows you to load shopping worlds at any place in your store front without the need of loading them using AJAX. Do to so, please create your own frontend theme, if you haven't one in place already. Please refer to our [Templating Getting Started Guide](https://developers.shopware.com/designers-guide/getting-started/#custom-themes) on how to create your own custom theme.

After creating your own theme, please create a new JavaScript file in your `_public/src/js` directory & place the following content into it:

```
window.StateManager
    .removePlugin('.emotion--wrapper', 'swEmotionLoader')
    .addPlugin('.emotion--wrapper:not(.emotion--non-ajax)', 'swEmotionLoader')
    .addPlugin('.emotion--non-ajax *[data-emotion="true"]', 'swEmotion');
```

We're removing the `swEmotionLoader` jQuery plugin which is the entry point for an AJAX shopping world. We're removing it because we have to modify the selector for the jQuery plugin. In the next line we're adding the same plugin but with a different selector. The selector allows us to add the class `emotion--non-ajax` to the element which should contain the shopping world later on. Last but not least, we're adding the jQuery plugin `swEmotion` to the plugin queue with our `emotion--non-ajax` class in place.

After you've added the content to the JavaScript file, make sure you've registered the file in the `$javascript` array in your `Theme.php` file. If you need further information, please head over to our [CSS & JS Files Usage Guide](https://developers.shopware.com/designers-guide/css-and-js-files-usage/#add-javascript-files).

With these changes in place, you're now able to include shopping worlds literally at any place in your store front with the following code snippet:

```
<div class="emotion--wrapper emotion--non-ajax">
    {action module="widgets" controller="emotion" action="index" emotionId="7"}
</div>
```

The argument `emotionId` in the widget call lets you choose what shopping world you would like to include. To get an overview of all available shopping worlds, please refer to the `s_emotion` database table. 

You may have to customize the styling of the shopping world, depending on what section of the store front you're using it.
