---
title: Shopware CLI Tools
tags: [tech, internal]

categories:
- dev

authors: [dn]
---

The Shopware CLI Tools are extensible and powerful console tools based on the symfony console tools.

They were developed to solve common shopware related tasks like setting up shopware, installing plugins from VCS or even uploading plugins to the community store. For this reason they are currently used by many developers and technical engineers in shopware - and since the tools are available on GitHub under the MIT license they will hopefully also help you to enjoy working with shopware even more :).

![CLI tools in action](/blog/img/shopware-cli-tools.png)

## What do they do?
By default the CLI tools will allow you to

- setup shopware from VCS (e.g. GitHub)
- setup shopware from a release package (e.g 4.3.0)
- checkout a plugin from stash/github/bitbucked and install / activate it
- zip plugins from a local directory or VCS in a way, that is supported by shopware's plugin manager and community store
- self-update of the tools

Internally we also use the tools to

- upload plugins to community store
- creating boilerplate code for new plugins
- generate demo data
- Once these extensions are fully tested and functional, the will also be available for the public.

## Using the CLI tools
If you have installed the CLI tool (as a phar package or as a checkout from GitHub), you should make the "sw" command globally available in your shell by adding the directory of the CLI tools to your PATH environment variable.

Done that, you can just type

`sw install:vcs`

and the CLI tools will setup a shopware installation from your configured repository.

`sw install:release`

will allow you to setup shopware from a official install package.

The command

`sw plugin:install`

is also very powerful: It supports multiple repository sources, so you can have a repository for the shopware premium plugins, one for your own plugins and one for other plugins, for example. The above command will list all available plugins sorted by origin and allow you to install one or more of them easily into a given shopware root.

## Configuring the CLI tools
The main configuration file of the CLI tools should be put in ~/.config/sw-cli-tools/config.yaml. If you are using the phar package, a default config.yaml can be used, so that the above config.yaml ist just used to overwrite the "default" config.yaml of your phar archive.

The default template of the config.yaml can be found here. As you can see, you can not only configure the plugin repositories: You can also manage the auto-update-functionality, your database configuration for the create shops or custom scripts and deltas that should be run after each shopware setup.

You are even able to configure "ShopwareInstallRepos" - these are repositories that are checkout into your shopware installation any time you run sw install:vcs. This way you can not only checkout shopware itself, you can also checkout plugins or other extensions into the shopware root.

## Extending the CLI tools
If you want to know, how the extensions work, just have a look at the src/Extensions directory of the CLI tools. All the main commands are implemented as extensions. The only difference is, that you should create your extension in ~/.config/sw-cli-tools/extensions if you want it to be detected automatically.

Once you create a folder like ~/.config/sw-cli-tools/extensions/VENDOR/NAME, you can just create a Bootsrap.php inside. The namespace of that Bootstrap is VENDOR\NAME, any by implementing the interfaces ContainerAwareExtension and ConsoleAwareExtension, you can make the cli tools fetch your container extensions or console command by implementing the corresponding methods:

```php
public function setContainer(ContainerBuilder $container)
{
	$container->register('my_service', 'VENDOR\NAME\MY_SERVICE')
                  ->addArgument(new Reference('git_util'))
		  ->addArgument(new Reference('io_service'));
}

public function getConsoleCommands()
{
    return array(
       new MyFancyCommand(),
       new HelloWorldCommand()
    );
}
```

This is all you need to add powerful new console commands or extend the DI container.
