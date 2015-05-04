---
layout: default
title: Use grunt to watch and compile LESS
github_link: designers-guide/using-grunt/index.md
indexed: true
---

[Grunt](http://gruntjs.com/) is a JavaScript task runner.

## Installation

Make sure you have installed nodejs and npm on your system:

## Install nodejs/npm in Ubuntu 14.04

```
sudo apt-get update
sudo apt-get install nodejs npm
sudo ln -s /usr/bin/nodejs /usr/bin/node
```

## Install the Grunt CLI

```
sudo npm install -g grunt-cli
```

## Dump theme configuration

```
./bin/console sw:theme:dump:configuration
```

## Install project dependencies

```
cd themes/
npm install
```

## Start file watch

```
grunt
grunt --shopId 1 # optionally specify shopId
```

![Grunt Screenshot](grunt-screenshot.png)
