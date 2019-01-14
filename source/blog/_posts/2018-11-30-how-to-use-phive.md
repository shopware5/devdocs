---
title: What is phive and why you should use it!
tags:
- Deployment
- Phive
- Phar

categories: 
- dev

authors: [tn]
github_link: /blog/_posts/2018-11-30-how-to-use-phive.md

---

At the beginning of this year we decided to create a new enterprise accelerator for our enterprise eco system. The so called Pricing Engine. With this new project we evaluated the PHAR Installation and Verification Environment (PHIVE) to manage all our *.phar dev tools.

Normally we store our dev tools in the document root of our git repository. As an example in the B2B-Suite we have seven several phar files (deptrac, phpDocumentator, phpcpd, phploc, phpstan, psh and security-checker). With phive we can manage all our dev tools with a phive.xml file. As you can see in the below example, you can define the version which should be used, the position where it should be placed and at last if it should be a symlink or a real copy of the phar file. Normally phive downloads the specific phar file and symlinks it in the place where it has to be.

```xml
<?xml version="1.0" encoding="UTF-8"?>
<phive xmlns="https://phar.io/phive">
  <phar name="php-cs-fixer" version="^2.12.2" installed="2.13.0" location="./tools/php-cs-fixer" copy="true"/>
  <phar name="shopwarelabs/psh" version="^1.2.0" installed="1.2.0" location="psh" copy="true"/>
  <phar name="phpstan" version="^0.10.1" installed="0.10.3" location="./tools/phpstan" copy="true"/>
  <phar name="shopwarelabs/sw-cli-tools" version="^0.0.1" installed="0.0.1" location="./tools/sw" copy="true"/>
</phive>
```  

This sounds very interesting isn't it? But how difficult is it to create a new software project and use phive for managing all dev tools?

## Setup a new Project with phive
The question is very easy to answer, it is as easy as to create a new composer project. As a preparation we have to install phive on our system. To do it use the snippet below:

```bash
wget -O phive.phar https://phar.io/releases/phive.phar
wget -O phive.phar.asc https://phar.io/releases/phive.phar.asc
gpg --keyserver pool.sks-keyservers.net --recv-keys 0x9D8A98B29B2D5D79
gpg --verify phive.phar.asc phive.phar
chmod +x phive.phar
sudo mv phive.phar /usr/local/bin/phive
```

After the installation we can easily install php-cs-fixer with ```phive install php-cs-fixer```. In this example php-cs-fixer is an alias and corresponds to the GitHub repository [FriendsOfPHP/PHP-CS-Fixer](https://github.com/FriendsOfPHP/PHP-CS-Fixer). You can find alternative installation instructions below:

```bash
phive install php-cs-fixer
phive install FriendsOfPHP/PHP-CS-Fixer
phive install https://github.com/FriendsOfPHP/PHP-CS-Fixer/releases/download/v2.13.1/php-cs-fixer.phar
```

You can use the already mentioned alias, or an github repository with a maintained release section or the direct url to the specific file.  

## Own phar files
To install own phar files with phive you have several ways to achieve this: 

* GitHub Releases (phive install username/project)
* registered alias on phar.io (phive install alias)
* explicit url (phive install https://server/file-1.0.0.phar)

We will have a more detailed look in the GitHub Releases way and we will use [psh](https://github.com/shopwareLabs/psh) as an example for that. We have the following requirements:

* GPG Key for your project
* GPG Key must be public available on the SKS Keyservers 
* already generated phar file.

If all requirements fit we open the console and go to our project directory where the phar file is stored. Now we will use this command ```gpg -u psh@example.com --detach-sign --output psh.phar.asc psh.phar``` to sign our generated phar file. After that we have a new file psh.phar.asc. Both files has to be uploaded to our existing version under https://github.com/shopwareLabs/psh/releases. That was all the work we needed for that. If you want access your phar file via url you have to store the .asc file in the same directory.

Now you can use ```phive install shopwareLabs/psh``` to install psh.phar. We have already [created](https://github.com/phar-io/phar.io/commit/d88298103c9fe7a99fdc00d930f505c83b67ada0) an alias for psh so a ```phive install psh``` is also possible. If you want to create your own alias you just have to create a pull request for [phar-io/phar.io](https://github.com/phar-io/phar.io) 

## Conclusion
Phive is a very good tool to manage the ecosystem around the needed dev tools. You escape easily the dependency hell and manage updates of your tool has never been so easy. Furthermore you save much space in your repository. The usage of OpenPGP/GnuPG secures the hole workflow of getting the needed phar files. 

At the moment we uses phive only for the Pricing Engine. In the future we will integrate phive in our other projects. Who does not use phive at the moment, should start not later than now.
