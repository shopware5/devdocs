---
layout: default
title: ESLint for plugins
github_link: designers-guide/find-smarty-blocks/index.md
indexed: true
group: Frontend Guides
subgroup: General Resources
menu_title: ESLint for plugins
---

### Introduction
Shopware features an automatic static code analyses tool called [ESLint](http://eslint.org/). Combined with a shared configuration called [standardjs](https://standardjs.com/) we provided a convenient way to ensure a consistent code style in the core & in our plugins.

In this guide we'll talking a look on how you as a Shopware plugin developer can use the benefits of ESLint in your own plugin.

### Installation
ESLint has Node.js and NPM as dependencies you have to install beforehand. If you haven't installed them, please go ahead and install it using the provided [download methods](https://nodejs.org/en/download/current/).

#### Installing the dependencies globally
Next we're installing ESLint and it's dependencies globally on your system. You have to install them once and be ready for every other plugin you'll write.

Use the following command to install ESLint, the standardjs configuration & necessary plugins:

```
npm install -g eslint eslint-config-standard eslint-plugin-import eslint-plugin-node eslint-plugin-promise eslint-plugin-standard
```

#### Installating the dependencies per plugin
If you're not feeling comfortable installing the dependencies globally, you can also create a `package.json` file in your plugin directory (e.g. `SwagBundle`). You can create this file using the command `npm init`.

After the file was successfully created, run the following command to install the dependencies as `devDependencies`:

```
npm install --save-dev eslint eslint-config-standard eslint-plugin-import eslint-plugin-node eslint-plugin-promise eslint-plugin-standard
```

### Running ESLint
Starting with [Shopware 5.2.15](http://community.shopware.com/Downloads_cat_448.html) we provided you a ESLint configuration file which can be found under `themes/.eslintrc.js`. We're using this file for our plugins as well to provide a consistent coding styling through the application.

To run ESLint for your plugin, switch to the root of your Shopware installation and run the following command:

```
eslint -c themes/.eslintrc.js <path-to-your-plugin-js>
```

In the following example I'm using the SwagBundle plugin which is installed in the `Local/Frontend` folder:

```
eslint -c themes/.eslintrc.js engine/Shopware/Plugins/Local/Frontend/SwagBundle/Views/frontend/_public/src/js/
```

#### Running ESLint with a `package.json`
Due to the fact you haven't installed the dependencies globally, you have to do a little extra lifting. The best way to run ESLint is using  a NPM script which can be added to the `package.json`.

To add a new script, open up the newly created `package.json` in your plugin directory and add the following snippet to it:

```
{
    // ...
    "scripts": {
    	"lint": "./node_modules/eslint/bin/eslint.js -c ../../../../../../themes/.eslintrc.js Views/frontend/_public/src/js"
    },
    //....
}
```

The script can be called using the following command:

```
npm run lint
```

### Tips & tricks
Using ESLint can cause trouble at first. The following tips & tricks should help you out.

#### Using global variables in your plugin
You're usually working with external libaries or configurations which are defined in a template file. As mentioned before ESLint is a static code analyses tool, therefore you have declare any used globals at the top at of the file which is using the library / configuration:

```
/* global jQuery */
```

#### Using the auto fixer
ESLint comes with a built-in auto fixer for certain rules like [`quotes`](http://eslint.org/docs/rules/quotes). You can simply add the argument `--fix` to let ESLint do the heavy lifting for you:

```
eslint -c themes/.eslintrc.js engine/Shopware/Plugins/Local/Frontend/SwagBundle/Views/frontend/_public/src/js/ --fix
```

If you're using a `package.json` file in your plugin, we highly recommend adding another NPM script:

```
{
    // ...
    "scripts": {
        "lint": "..."
    	"fix": "./node_modules/eslint/bin/eslint.js -c ../../../../../../themes/.eslintrc.js Views/frontend/_public/src/js"
    },
    //....
}
```

### Rules
We're using the [standardjs](https://standardjs.com/) with a couple of modifications to meet our coding style in your jQuery plugins. You can find all rules in our [JavaScript Coding Style](https://developers.shopware.com/designers-guide/javascript-coding-style/) guide.


