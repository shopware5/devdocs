---
title: Managing Ant complexity
tags:
    - ant
    - build
    - clean code
    - software layer
github_link: blog/_posts/2016-03-09-managing-ant-complexities.md
authors: [jp]
---

We @shopware use different tools for our highly automated development setups. We use Docker and Vagrant to set up operating system environments for development and testing, 
we use composer, bower and npm to manage library dependencies and we use Bamboo, Scrutinizer and Travis as integrated testing environments.

All these setups work a little different. A Vagrant virtual machine and a Travis build share just the basic operating system type, but nothing else. 
Therefore it is still necessary to rely on tools that help you build your application in 2016. And here Ant is the default solution. In the following paragraphs
I want to share some problems and solutions we encountered in the past few months concerning Ant.
  
## Quick introduction to Ant 
  
### What is a Build?
  
Historically the necessity to build an application stems from compiled languages like C or Java. They often need to include and precompile certain modules 
either from third parties or operating system dependent. These are tasks that can not be done through the application itself because it does not compile as long as it 
is incomplete. Build scripts also usually employ configuration files that can set e.g. compiler values or en- and disable features in the resulting compiled application.

In more recent years build scripts have evolved to contain different targets. So one can create a production version of the application, prepare and execute the unit 
tests suite or install a development version.

Although PHP is a interpreted language, many modern applications also depend on build scripts as the layer between the operating system requirements and a working installation.
Thus everything that can be automatically installed, configured and executed is usually present as a build script.
 
### Ant
 
Ant is the de facto standard for a mostly operating system independent build system. It is completely written in Java and therefore works on all major operating systems. 
Moreover the Linux, OSX and Windows versions all share a library of common tasks that allow you to create portable scripts. If you have ever encountered a project with a `build.xml`
you most certainly have seen an Ant build script.
  
Ant uses XML as its primary syntax; so a typical Ant file might look like this:

```
<project name="Enterprise Dashboard" basedir="../">

    <!--
        Main build targets
    -->
    <target name="install" depends="build-composer-install, apply-migrations">

    </target>

    <!--
        Apply Migration
    -->
    <target name="apply-migrations">
        <exec executable="php" dir="${basedir}/app" failonerror="true">
            <arg line="console doctrine:migrations:migrate"/>
        </exec>
    </target>

    <!--
        Composer build dependencies
    -->
    <target name="check-composer-binary">
        <available file="${basedir}/composer.phar" property="composer.binary.present"/>
    </target>

    <!-- Download composer -->
    <target name="install-composer-binary" depends="check-composer-binary"  unless="composer.binary.present">
        <exec executable="bash" failonerror="true">
            <arg value="-c" />
            <arg value="curl -s https://getcomposer.org/installer | php" />
        </exec>
    </target>

    <!-- selfupdate composer -->
    <target name="update-composer-binary" depends="install-composer-binary">
        <exec executable="php">
            <arg value="composer.phar" />
            <arg value="self-update" />
            <arg value="--no-interaction" />
        </exec>
    </target>

    <!-- execute composer install -->
    <target name="build-composer-install" depends="update-composer-binary">
        <exec executable="php" failonerror="true">
            <arg value="composer.phar" />
            <arg value="install" />
            <arg value="--no-interaction" />
            <arg value="--optimize-autoloader" />
        </exec>
    </target>

</project>
```

In fact this is the very first build.xml file we had in our project. The typical buildfile you might stumble upon contains a project 
which consists of many different targets. Per default all these targets are publicly available and can be called just by executing 
`ant __TARGET_NAME__` from your buildfiles directory.

Executing a target will set the Ant engine in motion, parse the buildfile and extract the correct order of targets. As you can see targets
can depend on each other and almost always will.

Each target then contains tasks. A task is the most basic unit of work in Ant and basically maps to a shell command. Either directly through the
`<exec />` task or more abstract with `<copy />`, `<available />` or `<zipfile />`.

## The problem

### XML

Without getting into the debate on the best [formatting for XML, verbosity of XML](http://c2.com/cgi/wiki?XmlSucks), the arbitrary decision on what is an [attribute and what is 
a tag](http://www.ibm.com/developerworks/xml/library/x-eleatt/index.html) or any other [weak point of XML](http://c2.com/cgi/wiki?XmlIsTooComplex), let me just give you one example.
  
  
Let's execute a composer install. This is the simple shell command to do this:
```
> php composer.phar install --no-interaction --optimize-autoload
```

This is the equal statement in Ant-XML:
```
<exec executable="php" failonerror="true">
    <arg value="composer.phar" />
    <arg value="install" />
    <arg value="--no-interaction" />
    <arg value="--optimize-autoloader" />
</exec>
```


### Order of execution

By looking at the single build.xml above we will now try to extract the order of execution. Lets first look at the **install** target.

The tree below is a representation of all build targets in the order *install* states its dependencies.

```
install
├── build-composer-install
│   ├── build-composer-binary
│       ├── update-composer-install
│           ├── install-composer-binary
│               ├── check-composer-binary
├── apply-migrations
```

So this is supposed to just install the composer dependencies and execute a console command? Obvious? No magic involved... For installing the application which 
just expects that all composer dependencies are available and that the database was migrated we end up with this weird tree like structure. Where each target 
depends on another target. I personally can not understand what is happening by looking at the tree, so I transform this into into a simpler recipe like this 
 
```
1. check-composer-binary
2. install-composer-binary
3. update-composer-binary
4. build-composer-binary
5. build-composer-install
6. apply-migrations
7. echo "Installation done"
```

Now it becomes clear what the writer of the buildfile expected to happen. One could play this even further by describing what he indented to do
 
```
1. install-composer-dependencies
2. apply-migrations
3. echo "Installation done"
```

Oh, this is actually in the file, although a little bit hidden:

```
...
<target name="install" depends="build-composer-install, apply-migrations"> 
...
```

I think you see the issue with the Ant syntax now. The intend of the author is rarely matched by the syntax of Ant. Instead Ant forces you to think in a chain of dependencies that are
composed to a tree.

### User left alone

So if writing Ant scripts is cumbersome, what about the other way around? Does it communicate intend and options to developers using it? We can easily examine this by 
just thinking about what can go wrong. Of course installing the application is now easier then it was before. Just execute `ant install` and be done with it. So Ant fulfills 
its original purpose here, but where does the information come from what build target to execute in which case? Splitting your build into too many targets is s source of potential 
confusion, because Ant makes executing `ant apply-migrations` just as easy, although this task has a implicit dependency on the composer files.

So the tree like structure of Ant targets create problems when a dependency is missing or only present in a parent node. The composition of different targets is potentially error 
prone, because dependencies between these nodes are not correctly configured. Let's take a look at an alternative way to create the script:

```
install
├── apply-migrations
    ├── build-composer-install
        ├── build-composer-binary
            ├── update-composer-install
                ├── install-composer-binary
                    ├── check-composer-binary
```

Now applying migrations is dependent on a successful composer install which fixes the problem in the first place. Problem solved, blog finished... Wait not so fast... How can I now 
create a different chain for a different target? If certain chain elements should be reusable and others not we can only copy and paste for the newly created targets. So in the end
in our application we could not get a suitable chain of targets that would be robust and secure for the user. Furthermore it meant splitting everything to the least common denominator
so that we gained an uncontrollable amount of syntax without gaining features. The result was a mess to read, understand and handle.


### Self explaining

Ant heavily depends on documentation. You have to create documents to state the original intend, describe securely executable targets, describe the applications expected dependencies.
Either in the form of recipes and tables in README files or inline through comments. And now you have to sync your knowledge from the external documentation with the implementation. So
if it should really be well documented you will start with something like this:

```
1. check-composer-binary
2. install-composer-binary
3. update-composer-binary
4. build-composer-binary
5. build-composer-install
6. apply-migrations
7. echo "Installation done"
```

And have to map it to the buildfile. I just put the comments in the code block below, to remove the noise. Let's see how fast this can be matched up:

```
<project name="Enterprise Dashboard" basedir="../">

    <!--
        Main build targets
    -->
    [-----]
    
    <!--
        Apply Migration
    -->
    [-----]

    <!--
        Composer build dependencies
    -->
    [-----]

    <!-- Download composer -->
    [-----]

    <!-- selfupdate composer -->
    [-----]

    <!-- execute composer install -->
    [-----]

</project>
```

So how long does it take to match it? And remember I already removed the noise for you. I think the problem is obvious now:

* The comments have to be really close to what you expect.
* The order of targets in the file is not arbitrary. But how should you order it? most important first? Order of execution? There are good reasons for both viewpoints. 
* You never know on which level of abstraction you are. Is it a high level group target or a low level dependency.

And as always: Since comments don't depend on the actual code executed it is very easy to get them out of sync with the implementation present.
 
### It grows on you

This kind of model does hardly scale at all. Over the course of 5 months it grew from it's original 60 lines to around 470 lines. It gained the 
responsibility for cleanup, unit test execution, and package build. Of course we could have split the tasks into different files, but after refactoring
the buildfile as an experiment we soon noticed that it would not gain readability, but just verbosity.

## Conclusion

I don't just want to reiterate the conclusions already drawn but introduce a new one: 

**The problem is Ant uses the same mechanism for abstraction and flow control.** 

To me the mechanism of abstraction is different from the mechanism of flow control. I control abstraction by creating reusable entities and the flow by 
rearranging these reusable entities in unique ways. Ant does not. It just creates a chain of actions that can depend on each other in any order necessary. Moreover
Ant expects that information is not decided upon but created from the outset through properties. This may lead to more stable builds but the way to getting this
stability was neither pleasant nor easy. So we started investigating alternatives. 

## Ant lesser known features

As it turns out the described features above are not everything that Ant provides. There are a few very interesting additions to the Ant way of things.
 
### Macros 

Since version 1.6 *(2003!)* Ant supports a way to create custom tasks with custom input by creating a macro. It works a lot like targets by also being a sequence 
of tasks, with the one exception: **Macros allow you to pass arguments**.

So let's take a look how *hello world* might look like using a macro:

```
<macrodef name="say-hello"> <!-- define the name how to call this macro -->
    <attribute name="to-the" required="true"/> <!-- define attributes you either require or give default values -->
    <sequential> <!-- syntax: the calls start here -->
        <echo>Hello {@to-the}</echo> <!-- the Ant tasks from your project -->
    </sequential> <!-- -->
</macrodef> <!-- -->
```

Which then can be called with from every target just by this:
  
```
<target name="calls-a-macro">
    <say-hello to-the="world" />
</target>
```

will produce

```
> ant calling-a-macro
[echo] Hello world
```
Macros can therefore be reused with different options from different callers. No need to try to set a property once per target chain, but just set it when it's called.

*Side Note: This is by far not everything to macros. They allow for parallel execution of tasks, can have there own properties, can even have there own collections of tags as input.*
 
### Inline conditions

In Version 1.9.1 *(2013)* Ant gained another very interesting feature: Generic conditions on every XML tag. To enable it you have to first extend your project tag with 
a little bit of configuration magic

```
<project 
    [...]
    xmlns:if="ant:if"
    xmlns:unless="ant:unless"
    [...]
>
```

And now you can add it to literally everything!

```
<target name="uses-inline-conditions">
    <property name="test" value="1" />

    <echo if:set="test2">Foo</echo> <!-- no output -->
    <echo unless:set="test2">Bar</echo> <!-- output -->
    <echo if:set="test">Baz</echo> <!-- output -->
    <echo unless:set="test">Biz</echo> <!-- no output -->
</target>
```

Although the syntax is strange, it just works and after a while becomes a second nature.

### Conclusion 

Are you thinking what I think? ***We can build functions with this :)***. On their own both features are interesting, but only become useful in 
certain edge cases, because both lack key ingredients.

* Conditions on their own are not that useful because of the lack of dynamically set properties. 
* Macros on their own are not that useful because they can not make decisions on input variables.
 
But the combination of dynamic input from a caller and the ability to make decisions based on that makes a powerful tool, that might even be enough to 
circumvent Ants shortcomings.

## Software - the theory

As it turns out creating functions and calling them is exactly our job decryption as software developers. And we have a vast body of theory to harness now!
Don't worry I am not going to apply everything I can think of to the art of build scripting, but only a few aspects of each of the following ideas.

### Clean Code - stay in your scope

From [Clean Code](https://de.wikipedia.org/wiki/Clean_Code) I will cherry pick the scope invariant from taken out from the functions/methods chapter. Each macro and target will contain only the same 
level of abstraction. If it starts low level, the next task will also be low level, if it starts abstract it will only contain abstract calls.

This ensures that there is no pollution in mapping intend of the developer to the implementation. Different levels of abstraction call for different macros or targets. 

### Clean Code - naming

Next I will pick the function/method naming conventions from clean code and every book that followed it. Although instead of *set*, *get*, *is*, and *has* we will 
have to swap the language to be more build centric. Descriptive stages of the build process like *install*, *unit* or the environment like *dev*, or *ci* seem more 
appropriate.
 
### Layered Architecture

In the past few paragraphs I have used the term abstraction level and scope very often. It is now time to give it a more formal description by using the term layer:

A layer in Software is ["the allocation of different responsibilities in software project"](https://en.wikipedia.org/wiki/Multilayered_architecture) Everything clear now? Just kidding...

A layer for us means that we can sort the applications classes, files, or functions by their dependencies and see commonalities, so that we can group the files according to this.
So we can for example see, that repository classes never interact with the view directly, but instead always have classes that are called service or at least controller between them. 
So we can safely assume that the Controllers are a layer that is responsible for interacting and negotiating with both classes.

This of course leads to far more complex topics, but for the buildscript at hand it is enough to just create a definition on what a layer is here.    
  

## Software - the practice

We can now create application design that serves a purpose other then just building the application so let's formulate some quality requirements.

* I want to see immediately what build targets are available - this should be done through a convention in the source itself.
* I want every build target to create the desired state on it's own. Especially I don't want to be required to remember any order in which a call should happen. 
* If a build target is broken I want to be able to understand what the author intended, what the application needs and how it is implemented

All this should be done through the source and hand full of conventions.

### Layers

For Ant we defined two layers:

**Macros / technical requirements:** A collection of Ant tasks that is named according to a technical application requirement.

Examples:

* `<composer-install />` - Install composer dependencies, not caring if a composer.phar is present, is globally installed or where it is.
* `<phar-box-build />` - Create a phar file, not caring where the required box.phar comes from.


**Targets / application states:** A state the application can be in.
 
* `<target name="prepare-dev" />` - Have all development requirements installed and ready to install the application as a whole
* `<target name="package" />` - Create a installable package of the application
* `<target name="unit" />` - Execute the test suite

## The result

#### The macros

So now we go to the real meat, how did we define our new macros. As an example I'm showing the `<composer-install />` macro. Note: The comments are not part of the 
macros, because the author deems them redundant ;).

```
<macrodef name="composer-install">
    <attribute name="workingdir" default="."/>
    <attribute name="dev" default=""/>
    <sequential>
        <echo>Installing Composer dependencies</echo>

        <available file="@{workingdir}/composer.phar" property="composer.binary.present"/> <!-- check if already present -->

        <exec executable="bash" failonerror="true" dir="@{workingdir}" unless:true="${composer.binary.present}"> <!-- install if not present -->
            <arg value="-c"/>
            <arg value="curl -s https://getcomposer.org/installer | php"/>
        </exec>

        <exec executable="php" dir="@{workingdir}" if:true="${composer.binary.present}"> <!-- update if present -->
            <arg value="composer.phar"/>
            <arg value="self-update"/>
            <arg value="--no-interaction"/>
        </exec>

        <exec executable="php" failonerror="true" dir="@{workingdir}"> <!-- execute composer install -->
            <arg value="composer.phar"/>
            <arg value="install"/>
            <arg value="--no-interaction"/>
            <arg if:blank="@{dev}" value="--optimize-autoloader"/> <!-- takes a long time - disabled for development -->
            <arg if:blank="@{dev}" value="--no-dev"/> <!-- remove dev requirements if not in development -->
        </exec>
    </sequential>
</macrodef>
```

This now translates to a reusable command, that captures a technical dependency a target or application state might have

```
<composer-install /> <!-- just install the required dependencies -->
<composer-install dev="true" /> <!-- include development dependencies -->
<composer-install working-dir="/var/www/shopware" /> <!-- install dependencies in a specific directory -->
```

I personally think this quite well captures the idea the author had and the requirement the application has. And if anything is wrong with it you can switch to the 
macro definition and fix it.
 
*Side note: An interesting side effect is that this can now be executed and tested in isolation and therefore be developed much quicker.*

### Target naming

We currently use a target naming convention with *_main_target_*-*_environment_modifier_*. So we have a clean number of tasks and task groups.
 
#### Main targets 
 
| Main target   | Meaning                                                                     |
| ------------- |:---------------------------------------------------------------------------:|
| prepare       | Install all application requirements.                                       |
| install       | Install application, can now be executed through the webserver or CLI.      |
| unit          | Execute the unit test suite.                                                |
| package       | Create a deployable package.                                                |

#### Environment modifier

| Name          | Meaning                                                                     |
| ------------- |:---------------------------------------------------------------------------:|
| prod          | Execute target with the intention to create a production application        |
| dev           | Execute target with the intention to create a development application       |


This leads to tasks like 

* `install-dev` -  development ready installation
* `unit-dev` - unit test suite containing only the small unit and integration tests

Which are expressive and follow a extensible ruleset, just the way I like it. So adaptions in the foreseeable future will not be a problem.

#### The new targets

Our new build targets are much more streamlined then the ones previously shown. The infamous buildfile now looks very different. Let's peek at the new install target
 
```
<target name="prepare-dev">
    <composer-install dev="true" />
</target>

<target name="install-dev" depends="prepare-dev">
    <exec-setup/>
</target>
```

It still basically does the same thing as stated above but it is now much clearer what the intend of the developer was:

* `<composer-install dev="true" />` belongs to `prepare-dev` so obviously it is necessary for the preparation of the application that all composer dependencies are 
installed. Even more interesting, `dev="true"` means that installing dev dependencies is necessary for development.  
* `<exec-setup/>` in `install-dev` is responsible to execute the setup scripts, so e.g. migration is no longer explicitly stated, but implicitly part of the setup
process *(The last few months have proven that this collection of tasks grows quicker then anything else)*
* `install-dev` depends on `prepare-dev` regardless of the applications state before calling the install target. The application will self assemble into the desired
state, always!

The size of the files greatly decreased, and at least in my personal opinion the information density is greatly increased here.

### Target dependencies

We now can easily visualize the different application states with their appropriate Ant targets and once the naming is internalized it will no longer be necessary to even look
at the build file

<img src="/blog/img/ant-complexity-build-targets.svg" />

The above graph highlights that

* Every target depends on a previous state, but requires the application as a whole to only be in the initial state.
* There are certain levels that can be modified.
* Although the outset may differ the outcome is always guaranteed. 

 
## Problems encountered

It is kind of curious, that although you depend on a Ant version that must be newer than 2013 it is actually not present everywhere.

### No errors in older versions

Due to the nature of XML older Ant versions will not issue warnings, or interrupt execution if the do not support the conditions. Therefore if you see unexplained behavior
be sure to check

```
ant -version 
```

#### Travis

Travis of all applications has a preinstalled Ant version 1.8+, so if you use the public ci you have to install a new one yourself. This little snippet should help you:

```
before_install:
  - cd $HOME 
  - wget http://www.apache.org/dist/ant/binaries/apache-ant-1.9.6-bin.tar.bz2
  - tar -xf apache-ant-1.9.6-bin.tar.bz2
  - export ANT_HOME=$HOME/apache-ant-1.9.6
  - export PATH=$HOME/apache-ant-1.9.6/bin:$PATH
  - export CLASSPATH=$CLASSPATH:$HOME/apache-ant-1.9.6/lib
  - ant -version
  - cd $TRAVIS_BUILD_DIR
```

## Conclusion

What you see here of course was the result of a longer period of trial and error. But the end result looks promising enough to last for the next year or two at least. 
It is easier to understand, debug and develop the scripts. With no end in sight on when it will no longer scale.

Would I do it again like this on my next project? I am actually not sure...  What I definitely take away here is that Ant scripts can scale and can be readable -  
but for a small project the more common solution to just create targets might be enough. Only time will tell.
