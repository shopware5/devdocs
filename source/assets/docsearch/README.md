# Algoia DocDocs custom styling
This section of the devdocs contains the custom styling for the [Algolia Docsearch](https://github.com/algolia/docsearch).

## Installation
First make sure you have `sass` installed on your system. If you haven't installed it already, follow this [guide](http://sass-lang.com/install).

Next resolve the Node.js dependencies the following command:

```
npm install
```

Now you're ready to modify the SCSS files in the `src` directory.

## Compiling
The build script can be called using NPM:

```
npm run build:css
```

The compiled CSS file will be placed in the `soure/assets/css` directory, so it will be automatically copied to the correct destination when compiling the devdocs using the provided commands / scripts.