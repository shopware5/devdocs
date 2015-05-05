---
layout: default
title: Debugging shopware
github_link: developers-guide/debugging/index.md
tags:
  - debugging
  - xdebug
  - doctrine
indexed: true
---
Writing and extending a software is only a part of a developer's daily work. Debugging and bug fixing is another relevant part one needs to take care of.
So: What to do if something does not work as it is supposed to work?

## Default log output
First of all you should check, if shopware already logged the error message you are looking for. For that reason you should check the webserver's `error.log` file 
as well as shopware's `logs` folder. Shopware creates a log file per day (if there was something to log).

As we are using AJAX queries in frontend and backend, you should also open up an instance of your browser's developer tools. You might find error messages in the 
javascript console or the network tab. 

As shopware hides exceptions from your customers by default in order to not expose private data, you might want to re-enable error output for a short time. 
Just paste this snippet to your `config.php` file:

```
array(
    'db' => array(
        // your database configuration
    ),
    'front' => [
        'throwExceptions' => true,
        'showException' => true
    ],
)
```

## PHP

### xdebug
Xdebug is a very common and convenient way to debug your php application. It will allow you to debug a request step by step and inspect variables and object at any point.

It can be found in all common linux distributions, e.g. in ubuntu as `php5-xdebug`. After installing the extension, you will need to configure the xdebug php extension,
e.g. in the file `/etc/php5/apache2/conf.d/20-xdebug.ini` (this might vary depending on your distribution and your php setup). Using a local setup, your configuration might
look like this:

```
zend_extension=xdebug.so

xdebug.remote_enable=on
xdebug.remote_host=127.0.0.1
xdebug.remote_port=9000
xdebug.idekey=PhpStorm
```

After restarting the web server, xdebug should already be available. Now you should set up xdebug in your IDE, (e.g. [PhpStorm](https://www.jetbrains.com/phpstorm/help/configuring-xdebug.html)).
In order to comfortably switch xdebug on and off, you might use a browser extension like [Xdebug helper](https://chrome.google.com/webstore/detail/xdebug-helper/eadndfjplgieldjbigjakmdgkmoaaaoc?utm_source=chrome-app-launcher-info-dialog).
This might be very useful, as xdebug might reduce the overal performance of your request.  

### Monolog
Shopware makes use of the monolog logger which allows you to log into files, databases, mails or FirePHP. By default a "CoreLogger" and a "PluginLogger" are set up for usage:

```
Shopware()->PluginLogger()->info("my info");;
Shopware()->PluginLogger()->warning("my warning");;
Shopware()->PluginLogger()->error("my error");;
```

These calls will render the messages "my info", "my warning" and "my error" to the file `logs/plugin_production-YYY-MM-DD.log`.
Depending on the logger configuration you could force monolog to only show info messages if a warning or error occurs later (two fingers crossed handler)
which might also be a huge benefit in productive environments. If multiple plugins write to the "PluginLogger", creating own
loggers with other persistance backends is also an option.

### error_log
Setting up xdebug might not always be possible (e.g. you don't have full admin access over a server) or appropriate for
a quick output check. The `error_log` function us useful in those cases. It allows you, to write output to the webserver's error log file:

```
error_log("Hello world");
```

In addition to that, `error_log` also allows you to define a file to write to. If you don't have access to the server's log
file or you don't want to spam it with debug messages, this call might be useful for you: 

```
error_log(print_r(array('hello', 'world'), true)."\n", 3, Shopware()->DocPath() . '/error.log');
```

This will write the content of the array `array('hello', 'world')` properly to the file `error.log` in your shopware directory.
In addition to that, you can use the linux `tail` command to constantly print out new lines written to that file:

```
tail -f error.log
```

### Debugging complex objects / doctrine
Dumping complex object trees (as doctrine models) might cause your browser or server to freeze. This is the reason why things like this will not work in most cases:
 
```
// bad example
echo "<pre>";
print_r(Shopware()->Shop());
exit();
```

Instead of that you should use the doctrine debug helper to print / log complex objects: 

```
$result = \Doctrine\Common\Util\Debug::dump(Shopware()->Shop())
// now safely log $result with your preferred logger
```

## Frontend templates
Writing frontend templates will confront you with questions like "how was that variable name again" or "which key holds the price". 

### Smarty
For these kind of questions, smarty offers the handy `{debug}` tag. You can just put it in any template block of your
plugin's template or even the core template (its just temporary). You should just make sure, that the block, you
are putting it to, is actually rendered. 

In this example the `{debug}` tag was put to the file `themes/Frontend/Bare/frontend/index/index.tpl` into the block `frontend_index_html`.

```
{block name='frontend_index_html'}
{debug}
// rest of the block
{/block}
```

After clearing the cache and refreshing the page, smarty will generate a new window like this:

<img style="margin-right:10px" src="img/smarty.png" />

As you can see, you have a nice overview of all variables and assignments.

### Debug-Plugin
Shopware also ships with a plugin called "debug" which will allow you to print out template assignments to the `console` tab of your developer tools window.
Just install the plugin using shopware's plugin manager, configure it to your needs and reload the page.

As you can restrict the plugin to your own IP, this is also suitable for production environments.

![debug plugin in action](img/debug_plugin.png)

## ExtJS
Debugging ExtJS errors during development can be very time consuming. Errors like `c is not a constructor` are not helpful
in many cases. For that reason you can include `ext-all-debug.js` instead of the default `ext-all.js` file:
Edit the file `themes/Backend/ExtJs/backend/base/header.tpl` and replace `ext-all.js` with `ext-all-debug.js` in the block `backend/base/header/javascript`. 
After clearing the cache and reloading the backend, the "debug" ExtJS file is included which supports more speaking error messages.

In many cases you will be no way around to debug the error by hand by using the `console.log()` call at certain points. This will help you
to narrow down the error. The following list should help you doing that:

Common backend development mistakes:
 
 * Invalid class names: The name of your ExtJS class (in the `define` call) must match your directory path. E.g. `Views/backend/my_plugin/view/window` 
  should become `Shopware.apps.MyPlugin.view.Window`
 * Referencing a wrong xtype: Whenever you use `xtype` to reference a ExtJS class, you should double check, if the referenced xtype actually exists.
 * Not registering the components: As ExtJS must actually know your components, you need to either register them in the `app.js` file or (when extending
 pre-existing modules) include them using smarty and extending the original original applications `app.js` block.
 * Missing call to `callParent(arguments);`: When implementing own components in ExtJS, you will overwrite base-components
 a lot. Whenever you are implementing a constructor like `initComponent` or `init` you should call `callParent(arguments);`
 so that ExtJS can handle the base component's logic.
 * Smarty errors: Remember that  smarty parses the javascript backend files. For that reason, javascript objects always need
 to have whitespaces before and after the opening and closing curly brace. This also applies for your comments! So if your IDE 
  generates a DocBlock like this:
      
    ```
    // bad example
    /**
     * 
     * @returns {Array}
     */
     function: test() {
        return [];
     }
    ```
    
    Smarty will try to parse the snippet `{Array}` and raise an exception, as this is no valid smarty tag. The same applies
    for objects like this:
    
    ```
    // bad example
    fields = [
        {name:"test"},
        {name:"another test"},
    ]
    ```
    
    A correct example might look like this:
    
    
    ```
    // good example
    fields = [
        { name:"test" },
        { name:"another test" },
    ]
    ```

 
 
 
 
 
 
 
 
 
 
 
 
