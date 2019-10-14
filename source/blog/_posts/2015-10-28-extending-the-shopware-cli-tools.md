---
title: Extending the Shopware CLI Tools
tags: [tech, internal]

categories:
- dev

authors: [tg]
indexed: true
github_link: blog/_posts/2015-10-28-extending-the-shopware-cli-tools.md
---

A while ago, my colleague [Daniel Nögel](/blog/authors/dn/) wrote a [blog post about the Shopware CLI tools](/blog/2014/11/27/shopware-cli-tools/), its basic features and an introduction to building extensions for them.

As we add [more useful features](https://github.com/shopwareLabs/sw-cli-tools/pull/42) to the [CLI tools project](https://github.com/shopwareLabs/sw-cli-tools), we take this opportunity to do an analysis of how we implemented the Data Generator, an approach you can also use to develop your own extensions for the CLI tools.

## The Data Generator

Shops come in all sizes, big and small. And while we can quickly create small test shops using the [Demo data plugins](http://store.shopware.com/search?sSearch=demodaten) or the [Shopware 4 demo data (page in German)](http://community.shopware.com/Shopware-4-Demo-/Beispiel-Daten_detail_896.html), sometimes we need a custom shop with a large number of articles, categories or orders, in order to ensure a certain features performs as expected in all scenarios.

For this purposed, we developed the Data Generator tools. We have been using these tools internally for a while now, as a kind of "implement as you go" side project, where we would add more features or fix certain bugs as we needed them. However, after undergoing a much needed cleanup, we believe that the Data Generator is ready to help our developers as much as it has helped us so far. So we are releasing them to the community, as part of our CLI tools.

At this point is worth mentioning a few things:
+ Like the rest of the CLI tools, the Data Generator is provided as is, without warranty or support.
+ The data generation process is destructive, meaning it will delete data that exists on your database.
+ Should you find a bug that you want to fix, or want to add a new feature, pull requests are always welcome.

## Installing, configuring and using the CLI tools
 
You can find instruction on how to install, configure and use the CLI tools in [this blog post](/blog/2014/11/27/shopware-cli-tools/). For the following steps, we assume you have downloaded the CLI tools code from [the github project page](https://github.com/shopwareLabs/sw-cli-tools) and opened the code in your favourite IDE.


## Developing the DataGenerator extension

In these first steps, we will explain how we created the `DataGenerator` extension. For now, just focus on understanding the current process. We will help you develop your own command later on.

As the Shopware CLI tools are designed to be extended, we started by creating our own extension directory. We called it `DataGenerator`, and you can find it inside the `src/Extensions/Shopware` directory. Next, we declared our extension. That was done using the `Bootstrap.php`. The file itself is not very big, but has some relevant content:

+ `class Bootstrap implements ContainerAwareExtension, ConsoleAwareExtension`: the `Bootstrap` class name and implementing `ConsoleAwareExtension` are both required. As we plan on using the DI container, we also implement the optional `ContainerAwareExtension` interface.
+ `public function setContainer(ContainerBuilder $container)`: this is where we specify our services that will be available in the DI container. Don't worry about its content for now.
+ `public function getConsoleCommands()`: this is where we declared our custom command. Our DataGenerator extension has only one command, but each extension can have as many commands as we (reasonably) want.

Now that the `Bootstrap.php` file has declared our extension, it's time to implement the actual command.
 
## Implementing the CreateData command

Implementing any command in the Shopware CLI tools is as easy as [creating a Symfony Console Command](http://symfony.com/doc/current/cookbook/console/console_command.html). If you have used Symfony's Console Commands before, you will feel right at home. 

We created a `src/Extensions/Shopware/Command` directory to house our custom `CreateDataCommand.php` file. The command itself has no Shopware specific logic: this is a pure Symfony2 Console Command implementation. The only feature we added is that, by extending the `ShopwareCli\Command\BaseCommand` class, we automatically inject the DI container into the command. If your custom command doesn't need the DI container, feel free to skip this intermediate abstract class, and have your command directly extend the Symfony's `Symfony\Component\Console\Command\Command` class, like any Symfony Console Command would. 

Next, we implemented some empty stub methods to make our command respect the requirements of the abstract class it implements, and voila, we had a fully functional command that did... absolutely nothing. 

## Implementing your own custom command

Up until now, we have described the steps we took to create a Shopware CLI Tools extension and custom command. These steps are common to the creation of all Shopware CLI Tools extension and commands, and are all the boilerplate code you will need. If you want to implement your own custom extension and command, just follow the steps above using different names for the extension directory and command class, and you should achieve the same result: an empty yet functional command.

In the next steps, we will discuss how we implemented the Data Generator. This is specific logic for the purpose of this command, but we hope that this will help you structure your own commands in a way that makes them maintainable and, why not, fun to develop.

### The Command class

As a rule of thumb, the Command class shouldn't do much besides handling input validation and delegating work to actual worker services. If you take a closer look at all the existing Command classes in the Shopware CLI Tools, you will see that all of them parse and validate the input the user provides (or should have provided), and then make extensive usage of the DI container and its services to do the actual actions the command is supposed to do.

### The Services directory

Most extensions will have a `Services` directory. This is only a convention: you can place your service implementations anywhere in your extension, but it's always a good idea to keep things organized. Once your services are implemented, you can declare them in your `Bootstrap.php`'s `setContainer()` method (see the DataGenerator's bootstrap file as an example). Your services can rely on preexisting services from both other extensions or the Shopware CLI Tools "core" (see the `src/Services` directory).

### The Struct directory

Odds are that, if your data structure is complex enough, it might be useful to rely on specific classes to store/handle it. Some existing extensions rely on Structs to do this. Structs are classes that have little or no logic at all, and focus instead on holding structured data. You can read more about the benefits of working with Struct [here](https://qafoo.com/blog/016_struct_classes_in_php.html). The Shopware CLI Tools provide a `Struct` abstract class you might want to use if you decide to define Structs in your extension, but this is only a helper class: you can define your own custom Structs without it, and they will work just as well. As with services, placing Structs in the `Structs` directory is nothing more than an organization best practice. Structs do not need to be declared in your Bootstrap file, so you only need to `use <...>;` them in your other classes to have access to them.