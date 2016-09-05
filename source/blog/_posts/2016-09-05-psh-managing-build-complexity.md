---
title: PSH - Managing Build complexity
tags:
    - ant
    - build
    - psh
github_link: blog/_posts/2016-09-05-psh-managing-build-complexity.md
authors: [jp]
---

<div class="alert alert-info">
Anyone who is just searching for the link to the repository and does not want to read the awesome story, <a href="https://github.com/shopwareLabs/psh">feel free to click here.</a>
</div>

A few months back I wrote a blog post about [ANT and build scripts](/blog/2016/03/09/managing-ant-complexity) in general. Today we introduced a new Open Source tool that
allows writing build scripts in plain *sh*. So how does that correlate?

Let's start by admitting one thing: **Boy, was I wrong!**

### Why ANT failed (for us)

In my previous post I was admittedly already talking about [downsides to ANT scripting](/blog/2016/03/09/managing-ant-complexity#the-problem), but my actual focus was on
fixing the - in my view - main issue of uncontrollably growing scripts, by applying good old software design principles. In the
time since then me and my team have released the then current project *Enterprise Client Administration* and started development on the new *Shopware B2B Suite*. 
So we had to start over. While creating a plan for the new infrastructure we decided first that we would no longer use ANT. 

The main issues were:
  
* The **verbosity of XML** makes reading even the simplest statements a pain.
* I noticed that besides me actually **nobody else had extended the scripts**.
* We were **abusing ANT** as much as we were using it.

But there are certainly a lot more stories that can be told:
 
#### Templating
 
It is quite common nowadays to have `*.dist` files in your projects as configurable templates for specific runtime environments. The *Enterprise Client Administration* had a few of them

* `properties.yml` - as Symfony configuration
* `docker-compose.override.yml` - Docker settings in a Linux-DEV and in a CI version
* `Vagrantfile` - Vagrant settings for Mac-Developers
* `build.properties` - The ANT properties

A few of these files have shared values, e.g the database server settings were shared between the application, the build system and the virtual machine hosting the database. 
The problem is that in reality all these tools use different formats to basically share the same values. ANT as the center of our build System has no easy way of propagating these values.
Stranded with the alternative of writing one string replace per value, or copying the whole file over and over we were helpless in reducing the lines of code necessary for our environment.
A templating engine would have solved this easily and naturally.

#### Platform independence

Although ANT-Scripts can be executed on multiple platforms hardly any real world script can. If you a) are not developing a Java application and b) need a little more then simple file copies
you will sooner or later start translating [shell commands to ANT XML](https://ant.apache.org/manual/Tasks/exec.html), and let it execute them. For us this happened 
with `composer`, `phpunit`, `nodejs`, `ansible` and many other cases. By that your application is limited by the availability of the shell. So why write XML around it?

#### User Experience

ANT simply is no fun to use. Most failures in script development were not because the thing I tried to achieve is hard to do,
but ANT stands in the way. Either I misread the 20 lines of XML, I need for a simple statement, or I anticipated different [properties](https://ant.apache.org/manual/Tasks/property.html)
to be present. My original solution for this was to use [macros](/blog/2016/03/09/managing-ant-complexity#macros) extensively. This certainly helps a lot, but I must say I would prefer a 
tool that would not give me an existential crisis every time I had to change something.

### Starting a new... 
 
This all meant for us that all XML based build tools and especially ANT were no longer an option. While looking around for a ANT alternative we were starting our development environment
by sharing a few sh scripts and using them to ensure that we could all work - it simply is the most natural format, you open a terminal, type a statement, execute it, verify it worked and copy and 
paste it into a `script.sh` file. Everybody can do it and so it no longer was solely my task to create the environment we would all work in. Times were great! 

But over the process of a one or two weeks some problems started to arise. We had gained a `*.dist` file, Docker had a different MySQL host then local development which meant copying whole 
scripts just to change one or two lines. And worst of all: **If a statement at the beginning of shell script fails execution continues** - something that you will notice far too late.

So what now? ANT? NO! [PHING](https://www.phing.info/) NO!! [Deployer](http://deployer.org/)? Maybe... Wait a minute... Deployer, although it looks very nice on first glance imports 
another concept from ANT that we never wanted to use again - [**Group tasks**](http://deployer.org/docs/tasks). Tasks, or in ANT lingo [Targets](https://ant.apache.org/manual/targets.html), 
to me look exactly like the reason why most ANT scripts simply can't be understood in the first place. [As I described in my last post](/blog/2016/03/09/managing-ant-complexity/#order-of-execution), 
it is extremely hard to get them right, and it is even harder to understand the order of things.

Simply put, we did not want to loose our current bare bones simple process in favor of another language in between. Further we are all PHP developers, so requiring any tool not written in
PHP would always mean that someone had problems installing *(A ANT and ARCH Linux story :) )* it. So I decided to create a thin wrapper, that would require minimal syntax adjustments
and just remove the problems altogether.

### PSH - The PHP Shell Helper

And this is exactly what I did, and we are pleased enough with the solution that we even released it as [Open Source on Github](https://github.com/shopwareLabs/psh). 

So let me give you a small introduction to it:
 
#### Step by step initial setup
 
Let's assume you have the following script: `scripts/install.sh`
  
```
#!/usr/bin/env bash

composer install

ant -f shopware/build/build.xml -Ddb.user=sw -Ddb.password=sw -Ddb.host=localhost -Ddb.name=sw build-unit

ln -s ../../../components/BackendAuth/SwagB2bBackendAuthPlugin shopware/custom/plugins/SwagB2bBackendAuthPlugin

shopware/bin/console sw:plugin:refresh
shopware/bin/console sw:plugin:install SwagB2bBackendAuthPlugin
shopware/bin/console sw:plugin:activate SwagB2bBackendAuthPlugin
```

Easy to read, isn't it? Install all composer dependencies, install shopware, link a plugin to the shopware plugin directory and install it. You run it, it works.
The second time - although you see an error that the symlink already exists - it works too. To prevent the error you can try to delete the symlink again, which would update the script to this:

```
[...]
+ rm shopware/shopware/custom/plugins/SwagB2bBackendAuthPlugin
ln -s ../../../components/BackendAuth/SwagB2bBackendAuthPlugin shopware/custom/plugins/SwagB2bBackendAuthPlugin
[...]
```
Now you do not see the error on updates, but instead after install. Either way **someone will see it** and ask you if this is necessary. 

If you execute it through PSH, the script will fail and you have to mark statements where failure is allowed. This usually gives anyone on a Team
enough confidence that a script actually works.

<div class="alert alert-info">
I assume here you have downloaded the phar archive as described <a href="https://github.com/shopwareLabs/psh#as-a-phar-archive-preferred">in the Installation guide on GitHub</a>.
</div>


So lets create a psh configuration file `.psh.yaml` in your project root to accompany your script:

```
paths:
  - scripts

const: []

dynamic: []
```

Because your script contains one statement that may actually fail we need to tell PSH to ignore failures, by prefixing it with a capital `I:`.

```
#!/usr/bin/env bash

composer install

ant -f shopware/build/build.xml -Ddb.user=sw -Ddb.password=sw -Ddb.host=localhost -Ddb.name=sw build-unit

I: rm shopware/shopware/custom/plugins/SwagB2bBackendAuthPlugin
ln -s ../../../components/BackendAuth/SwagB2bBackendAuthPlugin shopware/custom/plugins/SwagB2bBackendAuthPlugin

shopware/bin/console sw:plugin:refresh
shopware/bin/console sw:plugin:install SwagB2bBackendAuthPlugin
shopware/bin/console sw:plugin:activate SwagB2bBackendAuthPlugin
```

That's it, now we can execute the script by executing:

```
./psh.par install
```

#### Step by step placeholders and environments

Now another colleague tells you that he uses a remote MySQL server and can not use your script as is. To allow your colleague this first we need to replace the
problematic value with a placeholder.

```
[...]
+ ant -f shopware/build/build.xml -Ddb.user=sw -Ddb.password=sw -Ddb.host=__DB_HOST__ -Ddb.name=sw build-unit
- ant -f shopware/build/build.xml -Ddb.user=sw -Ddb.password=sw -Ddb.host=localhost -Ddb.name=sw build-unit
[...]
```

Now we create a new script in a separate directory
 
```
touch remote-scripts/install.sh
```

And put that content in it:

```
#!/usr/bin/env bash

INCLUDE: ../scripts/install.sh
```

PSH now will execute all statements from the original file in this context too.

Now we add to the configuration file
 
 * The constant value in `const`
 * A environment for your colleague called `remote` 

```
paths:
  - scripts

const: 
  DB_HOST: localhost

dynamic: []

environments:
  remote:
    paths:
        - remote-scripts
    const:
      DB_HOST: remote 
```

And now we can execute the `install` script with the different `DB_HOST` by just typing this:

```
./psh.phar remote:install
```

### The bigger picture

I think you now should have an idea how psh works and what it is capable of. Now let's resume our discussion on build scripts:

#### Complexity

So, referencing the title of this post, what is complexity in build processes? As with all Software we want clear, understandable, and obviously deterministic mechanisms so
we are easily able to distinguish success from failure. In build scripts complex decision making is usually part of other infrastructure services. If you use *composer* you
know that it is a complex application, but this does not matter to your build process. During your build you already have a set of dependencies that work together, something you can trust in!
Other complex tasks can be outsourced to *grunt*, *gulp*, *npm*, etc. But your central build system is just [orchestrating](https://en.wikipedia.org/wiki/Orchestration_(computing)) these services. 
And orchestration should be a simple linear path, which means that the build scripts can be boiled down to simple batch files.

#### Stability

This means basically that control flow changes are not important and can be deferred to infrastructure services such as `composer`. Therefore we only need the binary decision `failure` and `success`.
By that measure stability is just a reproducible successful result. Simple, plain easy!


#### Abstraction - Why is XML bad exactly

This is a place where shell scripts truly shine. In ANT even a simple call to an external tool makes it look like you had real work to do. 
Calling composer install? 3 XML tags, 4 lines of code at best!

```
<exec executable="php" failonerror="true">
    <arg value="composer.phar" />
    <arg value="install" />
    <arg value="--no-interaction" />
    <arg value="--optimize-autoloader" />
</exec>
```

But in a shell script that is only one line. 

```
php composer.phar install --no-interaction --optimize-autoload
```

Contrary to ANT scripts you do not want to reuse every statement ever developed written. If they have different options, you will hardly ever want to 
extract some meaningful common abstract call. So to me it looks like shell scripts may very well not need the second layer on form of macros / function calls.

 Are targets important?

Contrary to my ANT post, where I was trying to create a [state machine](/blog/2016/03/09/managing-ant-complexity#target-dependencies) for the build process I have come to the conclusion that
this does not matter during real development if the tool itself is discoverable enough for everyone to understand. Instead I have defined clear needs
for developers. For me theses are:

1. Start the virtual machines / container -> **start**
2. Log into these machines / containers -> **ssh**
3. Execute and update or install -> **init**
4. Clear the installation -> **reset**
5. Execute the test suite -> **unit**

This is basically it. Although more actions for different purposes (CI, demo deployment, etc) exist, typically a developer during development never bothers about them.
So, no! Targets are not important because five different actions are easy enough to remember.

#### Reusability

Build scripts have two dependencies. The **System** they work on and the application they **build**. If the application changes your scripts may fail. If the System you bound to changes your scripts may fail. 
This is a natural progression. Again ANT does not have the upper hand here, `<copy>` may work better as `cp`. But when Apache decided that all files in `sites-enabled` should end with `.conf`, 
everybody had to adapt.

In the past it has become apparent to me, that designing build scripts for reusability is mostly a waste of time, because you just never know which parts can be reused and which not. Instead
I think a quick adaption rate to changes is entirely preferable.

#### Bash only, why not?

So why even use `PSH`? And I get the point. [As was pointed out to me](https://github.com/shopware/devdocs/pull/353#issuecomment-244027980) bash already brings some capabilities that PSH emulates.
So let's discuss this a little bit here.

**`set -e` - Put it at the top of your script and execution fails if a statement fails.**
  
This is a nice solution, but sadly not the default. My personal experience is that optional stuff is missed far too often. Rather then deferring the problem to my colleagues and to the guy developing or 
reviewing the script. It is already guaranteed automated behaviour, that the scripts will fail if anything goes wrong.

**`export DB_HOST` - Bash supports variables, so why reimplemented them?**

Bash supports variables, so why reimplemented them? This mainly boils down to validation. PSH parses and replaces variables in a defined order, and throws exceptions if a variable is not defined.
Bash contrary to that has a behaviour that reminds me [of the good old PHP notice](https://www.sitepoint.com/community/t/php-notice-use-of-undefined-constant-localhost/3934) 
`Notice: Use of undefined constant MY_CONST - assumed 'MY_CONST`.

**`ln` already has an option to recreate the link if it already exists.**

While this is an entirely valid point, there is no guarantee that all services you call have the same option. Or even worse that every version of the called services works like this.
A example for a commonly used application is the [php-cs-fixer](https://github.com/FriendsOfPHP/PHP-CS-Fixer) that returns with a failing exit code if it had to change any file. Depending on the context 
this is the correct or completely wrong approach. And giving it context is part of the build script.

I am sure there are many more arguments, and even more opinions about that, but I hope I made the reasoning behind PSH and its implementation clear. If not feel free to ask.  

### Conclusion

I still am a firm believer in dev-ops and build processes. I want guaranteed state for my application, and I want to be able 
to setup a new development environment, as well as a new demo environment by just executing 2 to 3 statements on the command line.
But most importantly I don't believe that a build tool must be something fundamentally different to tools I use on a every day basis.
There is no real reason for a completely different vocabulary (ANT, Ansible) or a completely different paradigm (ANT, Deployer).
So there is no reason to exclude people that had no time to study the manuals. 

In my last posts conclusion I was very cautious about concluding if I would use ANT macros for future projects. Turns out I 
don't even use ANT anymore. I certainly see the application size argument coming up again. But since sh scripts are easier to read and much less verbose,
I am not sure why PSH should not be applicable to larger projects as well.

But, since this is the Internet, there will always be a better solution, and I am although optimistic, also cautious to see what the future brings.
Try it if you see it fit for your next project, open [issues](https://github.com/shopwareLabs/psh/issues), or create [pull requests](https://github.com/shopwareLabs/psh/pulls). 
I would love to get feedback on this one!
