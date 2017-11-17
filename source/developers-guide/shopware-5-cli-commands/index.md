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


## Creating custom Shopware CLI commands

As a plugin developer, you can create your own custom CLI commands. To do so, you can register the commands in your plugins base file like this:

```php
<?php

namespace SwagCommandExample;

use Shopware\Components\Plugin;
use Shopware\Components\Console\Application;

use SwagCommandExample\Commands\ImportCommand;

class SwagCommandExample extends Plugin
{
    public function registerCommands(Application $application)
    {
        $application->add(new ImportCommand());
    }
}
```

Since Shopware 5.2.2 you may also register commands as services and tag them with `console.command` in your plugins `services.xml` file:

```xml
<?xml version="1.0" encoding="UTF-8" ?>

<container xmlns="http://symfony.com/schema/dic/services"
    xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
    xsi:schemaLocation="http://symfony.com/schema/dic/services http://symfony.com/schema/dic/services/services-1.0.xsd">

    <services>
        <service
            id="swag_command_example.commands.import_command"
            class="SwagCommandExample\Commands\ImportCommand">
            <tag name="console.command"/>
        </service>
    </services>
</container>
```

The above example registers the `import` command. You now need to implement that command.

Shopware's CLI commands are based on the Symfony 2 Console Component, the documentation of which you can find [here](http://symfony.com/doc/current/components/console/introduction.html).

```php
<?php

namespace SwagCommandExample\Commands;

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
            ->setName('swagcommandexample:import')
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
