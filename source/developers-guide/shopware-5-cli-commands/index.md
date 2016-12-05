---
layout: default
title: Shopware 5 CLI commands
github_link: developers-guide/shopware-5-cli-commands/index.md
indexed: true
group: Developer Guides
subgroup: General Resources
menu_title: CLI commands
menu_order: 110
---

Shopware 5 includes several CLI commands that you can use to run operations that, for any reason, can or should not be executed from the browser interface. These commands will help you perform otherwise complicated tasks, or optimize, in the background, certain aspects of your shop, which will make it perform better.

Besides the commands included in the core, plugins can define their own CLI commands, that can be executed like a core command, provided the related plugin is installed and active.

## Using Shopware's CLI commands

Shopware's CLI commands are executed by typing the following command on the shell interface, inside your Shopware installation directory

```
php bin/console <command> [arguments and options]
```

To get a full list of the available CLI commands in your Shopware installation, simply type:

```
php bin/console
```

You can get detailed description for a specific command using the following syntax:

```
php bin/console <command> --help
```

This will provide you with a description of the command's functionality, along with a detailed explanation of each of the command's arguments and options.


## Creating custom Shopware's CLI commands

As a plugin developer, you can create your own custom CLI commands. To do so, your plugin's `Bootstrap.php` must first register the command itself:

```php
<?php

class Shopware_Plugins_Frontend_SwagExample_Bootstrap extends Shopware_Components_Plugin_Bootstrap
{
    public function install()
    {
        $this->subscribeEvent(
            'Shopware_Console_Add_Command',
            'onAddConsoleCommand'
        );

        return true;
    }

    public function afterInit()
    {
        $this->get('Loader')->registerNamespace(
            'ShopwarePlugins\\SwagExample',
            __DIR__ . '/'
        );
    }

    public function onAddConsoleCommand(Enlight_Event_EventArgs $args)
    {
        return new ArrayCollection(array(
            new \ShopwarePlugins\SwagExample\Commands\ImportCommand(),
            new \ShopwarePlugins\SwagExample\Commands\ListCommand(),
        ));
    }
}
```

The above example registers 2 commands, `import` and `list`. You now need to implement these commands.

Shopware's CLI commands are based on the Symfony 2 Console Component, which documentation you can find [here](http://symfony.com/doc/current/components/console/introduction.html).

```php
<?php

namespace Shopware\Commands;

use Shopware\Commands\ShopwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ImportCommand extends ShopwareCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('swagexample:import')
            ->setDescription('Import data from file.')
            ->addArgument(
                'filepath',
                InputArgument::REQUIRED,
                'Path to file to read data from.'
            )
            ->setHelp(<<<EOF
The <info>%command.name%</info> imports data from a file.
EOF
            )
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $filePath = $input->getArgument('filepath');

        $em = $this->container->get('models');

        $output->writeln('<info>'.sprintf("Got filepath: %s.", $filePath).'</info>');
    }
}
```

As you can see, a Shopware CLI command is very similar to a Symfony 2 command. You can use any of the features provided by the Symfony Console component, like you would in Symfony 2 itself.
