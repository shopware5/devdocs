---
layout: default
title: Managing third-party dependencies with npm
github_link: designers-guide/managing-third-party-dependencies-with-npm/index.md
shopware_version: 5.2
indexed: true
group: Frontend Guides
subgroup: General Resources
menu_title: Managing dependencies with NPM
menu_order: 100
---

<div class="toc-list"></div>

## Introduction
Modern web applications like a Shopware web shop have to deal with many frontend dependencies. There are several tools
available which help manage these dependencies. In the following guide we'll provide you with the information you
need to install additional third-party dependencies with `npm`, the default package manager for the javascript runtime
environment `node.js`, in combination with `grunt`, a javascript task runner. This setup will be used in Shopware starting with version 5.2 to manage frontend dependencies.

<div class="is-center">
    <img src="logo-npm-normal.png" alt="NPM logo">
</div>

## Requirements
The guide assumes that you have the following applications / tools installed on your local machine:

* Shopware version 5.2.x or newer
* `node.js` version 5.0.x or newer
* `npm` version 3.5.x or newer
* `grunt-cli` version 0.1.x or newer

## Installation
In order to get started, you need to install the frontend dependencies of Shopware. In the `themes/Frontend/Responsive/` directory of your Shopware, run the following command:

```bash
npm install && npm run build
```

*Mac OS X users may need to use sudo and Windows users may need to execute the command shell as Administrator*

If everything goes well, you should see a similar output:

```
$ sudo npm install && npm run build
npm WARN deprecated lodash@0.9.2: lodash@<2.0.0 is no longer maintained. Upgrade to lodash@^3.0.0
shopwareresponsivetheme@1.0.0 /www/sw5-git/themes/Frontend/Responsive
+-- grunt@0.4.5
| +-- async@0.1.22
| +-- coffee-script@1.3.3
| +-- colors@0.6.2
| +-- dateformat@1.0.2-1.2.3
| +-- eventemitter2@0.4.14
| +-- exit@0.1.2
| +-- findup-sync@0.1.3
| | +-- glob@3.2.11
| | | +-- inherits@2.0.1
| | | `-- minimatch@0.3.0
| | `-- lodash@2.4.2
| +-- getobject@0.1.0
| +-- glob@3.1.21
| | +-- graceful-fs@1.2.3
| | `-- inherits@1.0.2
| +-- grunt-legacy-log@0.1.3
| | +-- grunt-legacy-log-utils@0.1.1
| | | +-- lodash@2.4.2
| | | `-- underscore.string@2.3.3
| | +-- lodash@2.4.2
| | `-- underscore.string@2.3.3
| +-- grunt-legacy-util@0.2.0
| +-- hooker@0.2.3
| +-- iconv-lite@0.2.11
| +-- js-yaml@2.0.5
| | +-- argparse@0.1.16
| | | +-- underscore@1.7.0
| | | `-- underscore.string@2.4.0
| | `-- esprima@1.0.4
| +-- lodash@0.9.2
| +-- minimatch@0.2.14
| | +-- lru-cache@2.7.3
| | `-- sigmund@1.0.1
| +-- nopt@1.0.10
| | `-- abbrev@1.0.7
| +-- rimraf@2.2.8
| +-- underscore.string@2.2.1
| `-- which@1.0.9
+-- grunt-contrib-clean@0.7.0
+-- grunt-contrib-copy@0.8.2
| +-- chalk@1.1.1
| | +-- ansi-styles@2.1.0
| | +-- escape-string-regexp@1.0.4
| | +-- has-ansi@2.0.0
| | | `-- ansi-regex@2.0.0
| | +-- strip-ansi@3.0.0
| | `-- supports-color@2.0.0
| `-- file-sync-cmp@0.1.1
+-- jquery@2.1.4
+-- jquery.event.swipe@0.5.4
| `-- jquery.event.move@1.3.6
+-- jquery.transit@0.9.12
+-- masonry-layout@3.3.2
| +-- fizzy-ui-utils@1.0.1
| | +-- desandro-matches-selector@1.0.3
| | `-- doc-ready@1.0.3
| +-- get-size@1.2.2
| | `-- desandro-get-style-property@1.0.4
| `-- outlayer@1.4.2
|   +-- eventie@1.0.6
|   `-- wolfy87-eventemitter@4.3.0
+-- normalize.css.less@3.0.3
+-- open-sans-fontface@1.4.0
+-- picturefill@3.0.1
`-- pocketgrid-less@1.0.0


> shopwareresponsivetheme@1.0.0 build /www/sw5-git/themes/Frontend/Responsive
> grunt

Running "clean:vendors" (clean) task
>> 1 path cleaned.

Running "createVendorDir" task
>> Directory "frontend/_public/vendors" was created successfully.

Running "copy:jquery.event.move" (copy) task
Copied 2 files

Running "copy:jquery.event.swipe" (copy) task
Copied 2 files

Running "copy:jquery.transit" (copy) task
Copied 2 files

Running "copy:jquery" (copy) task
Copied 4 files

Running "copy:masonry" (copy) task
Copied 2 files

Running "copy:normalize-less" (copy) task
Copied 3 files

Running "copy:pocketgrid-less" (copy) task
Copied 3 files

Running "copy:picturefill" (copy) task
Copied 3 files

Running "copy:open-sans-fontface" (copy) task
Copied 20 files

Running "copy:open-sans-fontface-readme" (copy) task
Copied 1 file

Running "createFontHtaccess" task
>> File "frontend/_public/vendors/fonts/open-sans-fontface/.htaccess" was
>> created successfully.

Done, without errors.
```

*The warning can be ignored, it's triggers by `grunt`, which uses an older version of `loadash`*

## Managing frontend dependencies

All frontend dependencies are now defined in the `package.json` file, unlike previous versions of Shopware, which used `bower` and the `bower.json` file. The following shows the default dependencies of Shopware:

```json
{
  ...
  "dependencies": {
    "jquery": "^2.1.4",
    "jquery.event.swipe": "^0.5.4",
    "jquery.transit": "^0.9.12",
    "masonry-layout": "^3.3.2",
    "normalize.css.less": "^3.0.3",
    "open-sans-fontface": "^1.4.0",
    "picturefill": "^3.0.1",
    "pocketgrid-less": "^1.0.0"
  },
  "devDependencies": {
    "grunt": "^0.4.5",
    "grunt-contrib-clean": "^0.7.0",
    "grunt-contrib-copy": "^0.8.2"
  }
}
```

### Mapping files

<div class="is-center">
    <img src="logo-grunt.png" alt="Grunt logo">
</div>

The dependencies are downloaded to the `themes/Frontend/Responsive/node_modules/` directory. We're using `grunt` and
a few custom tasks to map the necessary files to the corresponding directories inside the `frontend/_public/vendors` directory.
Here's an example on how to map the files inside the `Gruntfile.js` file:

```
grunt.initConfig({
    clean: { vendors: [ vendorDir ] },
    copy: {
        'jquery.event.move': {
            files: [{
                expand: true,
                src: [
                    nodeDir + '/jquery.event.move/js/jquery.event.move.js',
                    nodeDir + '/jquery.event.move/README.md'
                ],
                dest: vendorDir + '/js/jquery.event.move',
                flatten: true
            }]
        }
    }

    // ...
});
```

*Mapping example for the library `jquery.event.move` in `Gruntfile.js`*

## Adding new dependencies to your theme

The same technology we're using to update our core frontend dependencies can be used to install new dependencies for your project.

1. Create a new frontend theme. You can use either the theme manager in the administration panel or our
CLI tools in order to do so. You can find more information on Shopware 5 themes [here](/designers-guide/getting-started/#custom-themes).
2. Open up your command line interface and switch to your newly created theme. You can find it in your Shopware installation under `/themes/Frontend/`
3. Inside your theme directory, create a new `package.json` file using the following command. You'll be asked a few simple details about your theme, such as its name a license:

```bash
sudo npm init
```

4. Now install the technology stack in your theme using the following command:

```bash
npm install --save grunt grunt-contrib-clean grunt-contrib-copy
```

*Mac OS X users may need to use sudo and Windows users may need to execute the command shell as Administrator*

5. You can now create your own `Gruntfile.js`. Use the following code snippet as template for the file:

```
module.exports = function (grunt) {
    'use strict';

    var vendorDir = 'frontend/_public/vendors',
        nodeDir = 'node_modules';

    grunt.initConfig({
        clean: {
            vendors: [ vendorDir ]
        },
        copy: {}
    });

    grunt.registerTask('createVendorDir', 'Creates the necessary vendor directory', function() {
        // Create the vendorDir when it doesn't exists.
        if (!grunt.file.isDir(vendorDir)) {
            grunt.file.mkdir(vendorDir);

            // Output a success message
            grunt.log.oklns(grunt.template.process(
                'Directory "<%= directory %>" was created successfully.',
                { data: { directory: vendorDir } }
            ));
        }
    });

    grunt.registerTask('default', [ 'clean', 'createVendorDir', 'copy' ]);
    grunt.loadNpmTasks('grunt-contrib-clean');
    grunt.loadNpmTasks('grunt-contrib-copy');
};
```

6. Look up the npm package you want to install on [the npm.js website](https://www.npmjs.com/ "NPM package search"). In this example, we're installing the javascript library [Moment.js](http://momentjs.com/). It implements an easy-to-use API to format dates. Use the following command to install and save the dependency:

```bash
npm install --save moment
```

7. After installing the library, we have to take a look at its directory structure to create the mapping in the `Gruntfile.js` in the next step.

```bash
.
|-- LICENSE
|-- README.md
|-- ender.js
|-- locale
|-- min
|   |-- locales.js
|   |-- locales.min.js
|   |-- moment-with-locales.js
|   |-- moment-with-locales.min.js
|   |-- moment.min.js
|   `-- tests.js
|-- moment.js
|-- package.js
`-- package.json
```
*Directory structure of the `Moment.js` library*

Based on the directory structure we can decide what files we want to copy. Typically we copy the minified version of library, the readme and license files:

* `LICENSE`
* `README.md`
* `min/moment-with-locales.min.js`

8. To map the files, edit the `Gruntfile.js` in the custom theme and change the `copy` task to map the files
to the corresponding directories inside the `frontend/_public/vendors` directory.

```js
'moment.js': {
    files: [{
        expand: true,
        src: [
            nodeDir + '/moment/min/moment-with-locales.min.js',
            nodeDir + '/moment/README.md',
            nodeDir + '/moment/LICENSE'
        ],
        dest: vendorDir + '/js/moment',
        flatten: true
    }]
}
```

*Example mapping library files*

9. The next step and final step is to simply run the `default` task of our `Gruntfile.js` using the following command:

```bash
grunt
```

10. Now include the file `moment-with-locales.min.js` in your `Theme.php`. More information on adding custom javascript files
 to your theme can be found [here](/designers-guide/css-and-js-files-usage/).
