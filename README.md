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

## Generate production site and deploy to server

```
./deploy.sh
```

The production ready files will be generated in the directory `output_prod`.

[2]: https://sculpin.io/
[3]: https://sculpin.io/download
