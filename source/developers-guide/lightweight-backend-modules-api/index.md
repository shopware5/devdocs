---
layout: default
title: Lightweight backend modules api
github_link: developers-guide/lightweight-backend-modules-api/index.md
shopware_version: 5.1.0
indexed: true
---

## postMessage API documentation

`getInfo()` 

Returns information about the API

**Returns**: `Object`

---

`getVersion()` 

Returns the version string of the API

**Returns**: `string`

---

`getName()` 

Returns the name of the API

**Returns**: `string`

---

`getInstance()` 

Returns the instance UUID which is used for the communication of an app

**Returns**: `string | null`

---

`getComponentName()` 

Returns the techName of the module window.

**Returns**: `string | null`

---

`isInitialized()` 

Returns if the API is initialized.

**Returns**: `boolean`

---

`openModule(payload)`

Opens a module in the Shopware backend.

**Parameters**

**payload**: `Object`, Opens a module in the Shopware backend.

**Example**:
```js
postMessageApi.openModule({
    name: 'Shopware.apps.Article'
});
```

**Example Detail View for Articles**:
```js
postMessageApi.openModule({
    name: 'Shopware.apps.Article'
    action: 'detail',
    params: {
        articleId: 007
    }
});
```

**Example Detail View for Orders**:
```js
postMessageApi.openModule({
    name: 'Shopware.apps.Order'
    params: {
        orderId: 007
    }
});
```

---

`createSubWindow(payload)`

Creates a subwindow for the module.

**Parameters**

**payload**: `Object`, Creates a subwindow for the module.

**Example**:
```js
postMessageApi.createSubWindow({
    width: 500,
    height: 500,
    component: 'customSubWindow',
    url: 'your/url',
    title: 'Plugin Konfiguration'
});
```

---

`sendMessageToSubWindow(payload)`

Sends a message to a subwindow

**Parameters**

**payload**: `Object`, Sends a message to a subwindow

**Returns**: `RpcRequestObject`

**Example**:
```js
postMessageApi.sendMessageToSubWindow({
    name: 'customSubWindow',
    params: {
        msg: 'Your message',
        foo: [ 'bar', 'batz' ]
    }
});
```

---


`createGrowlMessage(title, text, sticky, log, opts)` 

Provides the ability to create growl messages. The method can create normal or sticky messages.

**Parameters**

**title**: `String`, Title of the growl message

**text**: `String`, Text of the growl message

**sticky**: `Boolean`, Truthy to get a sticky growl message, default: false

**log**: `Boolean`, Enable logging the message of the message, default: true

**opts**: `Object`, Additional configuration params for the sticky growl message, please see Shopware.Notification.createStickyGrowlMessage

**Returns**: `RpcRequestObject`

---

`createConfirmMessage(title, msg, callback, scope, eOpts)`

Displays a confirmation message box with Yes and No buttons (comparable to JavaScript's confirm).

**Parameters**

**title**: `String`, Displays a confirmation message box with Yes and No buttons (comparable to JavaScript's confirm).

**msg**: `String`, Displays a confirmation message box with Yes and No buttons (comparable to JavaScript's confirm).

**callback**: `function`, Displays a confirmation message box with Yes and No buttons (comparable to JavaScript's confirm).

**scope**: `Object`, Displays a confirmation message box with Yes and No buttons (comparable to JavaScript's confirm).

**eOpts**: `Object`, Displays a confirmation message box with Yes and No buttons (comparable to JavaScript's confirm).

**Returns**: `RpcRequestObject`

---

```createPromptMessage(title, msg, callback, scope, eOpts)```

Displays a message box with OK and Cancel buttons prompting the user to enter some text (comparable
to JavaScript's prompt).

**Parameters**

**title**: `String`, Displays a message box with OK and Cancel buttons prompting the user to enter some text (comparable
to JavaScript's prompt).

**msg**: `String`, Displays a message box with OK and Cancel buttons prompting the user to enter some text (comparable
to JavaScript's prompt).

**callback**: `function`, Displays a message box with OK and Cancel buttons prompting the user to enter some text (comparable
to JavaScript's prompt).

**scope**: `Object`, Displays a message box with OK and Cancel buttons prompting the user to enter some text (comparable
to JavaScript's prompt).

**eOpts**: `Object`, Displays a message box with OK and Cancel buttons prompting the user to enter some text (comparable
to JavaScript's prompt).

**Returns**: `RpcRequestObject`

---


`createAlertMessage(title, msg)`

Displays a standard read-only message box with an OK button (comparable to the basic JavaScript alert prompt).

**Parameters**

**title**: `String`, Displays a standard read-only message box with an OK button (comparable to the basic JavaScript alert prompt).

**msg**: `String`, Displays a standard read-only message box with an OK button (comparable to the basic JavaScript alert prompt).

**Returns**: `RpcRequestObject`

---


`setTitle(title)`

Sets the window title

**Parameters**

**title**: `String`, Sets the window title

**Returns**: `RpcRequestObject`

**Example**:
```js
postMessageApi.window.setTitle('Your title');
```

---

`getWidth(callback, scope, eOpts)`

Gets the window width from the backend and fires the callback method.

**Parameters**

**callback**: `function`, Gets the window width from the backend and fires the callback method.

**scope**: `Object`, Gets the window width from the backend and fires the callback method.

**eOpts**: `Object`, Gets the window width from the backend and fires the callback method.

**Returns**: `RpcRequestObject`

**Example**:
```js
postMessageApi.window.getWidth(function(width) {
    console.log(width);
});
```

--- 


`setWidth(width)`

Sets the width of the backend window.

**Parameters**

**width**: `String | Number`, Sets the width of the backend window.

**Returns**: `RpcRequestObject`

**Example**:
```js
postMessageApi.window.setWidth(500);
```

----

`getHeight(callback, scope, eOpts)`

Gets the window height from the backend and fires the callback method.

**Parameters**

**callback**: `function`, Gets the window height from the backend and fires the callback method.

**scope**: `Object`, Gets the window height from the backend and fires the callback method.

**eOpts**: `Object`, Gets the window height from the backend and fires the callback method.

**Returns**: `RpcRequestObject`

**Example**:
```js
postMessageApi.window.getHeight(function(height) {
    console.log(height);
});
```

---

`setHeight(height)`

Sets the height of the backend window.

**Parameters**

**height**: `String | Number`, Sets the height of the backend window.

**Returns**: `RpcRequestObject`

**Example**:
```js
postMessageApi.window.setHeight(800);
```

---

`show()`

Shows the backend window.

**Returns**: `RpcRequestObject`

**Example**:
```js
postMessageApi.window.show();
```

---

`hide()`

Hides the backend window

**Returns**: `RpcRequestObject`

**Example**:
```js
postMessageApi.window.hide();
```

---

`destroy()`

Destroys the window and the application.

**Returns**: `RpcRequestObject`

**Example**:
```js
postMessageApi.window.destroy();
```

---

`minimize()`

Minimizes the window to the task bar


**Example**:
```js
postMessageApi.window.minimize();
```

---


`maximize()`

Maximizes the window to the full width and height of the window.


**Example**:
```js
postMessageApi.window.maximize();
```

---

`restore()`

Restores a maximized window back to its original size and position prior to being maximized.


**Example**:
```js
postMessageApi.window.restore();
```

---

`toggleMaximize()`

A shortcut method for toggling between maximize and restore based on the current maximized state of the window.


**Example**:
```js
postMessageApi.window.toggleMaximize();
```

---

`setBodyStyle(payload)`

Sets the body style according to the passed parameters.

**Parameters**

**payload**: `Object`, Sets the body style according to the passed parameters.


**Example**:
```js
postMessageApi.window.setBodyStyle({
    border: '1px solid red',
    padding: '20px 10px'
});
```
