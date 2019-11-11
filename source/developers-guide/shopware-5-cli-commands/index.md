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

As a plugin developer, you can create your own custom CLI commands. To do so, you can register the commands as services and tag them with `console.command` in your plugins `services.xml` file:

```xml
<?xml version="1.0" encoding="UTF-8" ?>

<container xmlns="http://symfony.com/schema/dic/services"
    xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
    xsi:schemaLocation="http://symfony.com/schema/dic/services http://symfony.com/schema/dic/services/services-1.0.xsd">

    <services>
        <service
            id="swag_command_example.commands.import_command"
            class="SwagCommandExample\Commands\ImportCommand">
            <tag name="console.command" command="swagcommandexample:import"/>
        </service>
    </services>
</container>
```

The above example registers the `import` command. You now need to implement that command.

Shopware's CLI commands are based on the Symfony 3 Console Component, the documentation of which you can find [here](https://symfony.com/doc/3.4/components/console.html).

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

As you can see, a Shopware CLI command is very similar to a Symfony 3 command. You can use any of the features provided by the Symfony Console component, like you would in Symfony 3 itself.

## Add completion for commands

Shopware uses [stecman/symfony-console-completion](https://github.com/stecman/symfony-console-completion) to add completion features you already know from any CLI.
This composer package automatically adds completion to any command about the option names.
Some option names need values like the `--batch` option from `sw:plugin:update` the package can't know the allowed values.
To add support for option value and argument completion you need to implement the interface `Stecman\Component\Symfony\Console\BashCompletion\Completion\CompletionAwareInterface`.
Both methods the interface expects (`completeOptionValues` and `completeArgumentValues`) behave similarly as they both expect all possible values for the option respectively argument at the cursor position to offer for completion returned as array.
For example for the `--batch` option you can simply write:
```php
public function completeOptionValues($optionName, CompletionContext $context)
{
    if ($optionName === 'batch') {
        return ['active', 'inactive'];
    }

    return [];
}
```
In case of simple filename expansion you implement it like this:
```php
public function completeArgumentValues($argumentName, CompletionContext $context)
{
    if ($argumentName === 'file') {
        return $this->completeInDirectory();
    }

    return [];
}
```

## Use completion in shell

If you change your working directory to the project root you just have to execute the generated shell code like this:
```sh
source <(bin/console _completion --generate-hook)
```
This can be easily added to your `.profile` file.
In case you have to use custom php parameters for the `bin/console` application you just add an alias and register the completion also for this alias.
Therefore you save the generated shell code and look for the rows that look similar to the following snippet and duplicate line 5:
```sh
alias console-debug="php -dzend_extension=xdebug.so ${PROJECT_ROOT}/bin/console"

if [ "$(type -t _get_comp_words_by_ref)" == "function" ]; then
    complete -F _console_12345567890abcdef_complete "console";
    complete -F _console_12345567890abcdef_complete "console-debug";
else
    >&2 echo "Completion was not registered for console:";
    >&2 echo "The 'bash-completion' package is required but doesn't appear to be installed.";
fi;
```
