# Shopware Development Documentation

## Install
This site is generated with [Sculpin][2], a PHP based static site generator.

First you have to [install Sculpin][3] and run the install command in the project directory.
This can be done via the `init.sh` shell script in the project root.

```
./init.sh
```

This will download sculping and install the required dependencies.

## Running the website locally

```
./watch.sh
```

This will start a local webserver at <http://localhost:8000/>.

## Automatic Plugin Packaging

Foreach Plugin in the `exampleplugins` directory a corresponding ZIP package will be created that can be installed via the shopware plugin manager.
It is important to put the plugin into the proper Frontend/Backend/Core subdirectory.

### Example

`exampleplugins/Frontend/SwagSloganOfTheDay/` will result in `exampleplugins/SwagSloganOfTheDay.zip` in the generated output directory. Please not that the subdirectory is not part of the resulting directory/filename.

[2]: https://sculpin.io/
[3]: https://sculpin.io/download

### Table of contents in your markdown documents
You have the ability to generate a table of content list for your document. Simply place the following code into your document and a table of contents will appear there magically:

```
<div class="toc-list"></div>
```

If you want, you can customize the behavior of the toc list as well. You can define the text of the headline using the attribute `data-headline` and you can define the max depth of headlines you wanna include into the list with the attribute `data-depth`:

```
<div class="toc-list" data-depth="1" data-headline="Awesome table of contents"></div>
```