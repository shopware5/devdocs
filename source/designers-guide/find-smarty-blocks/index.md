---
layout: default
title: Finding smarty blocks in Shopware
github_link: designers-guide/find-smarty-blocks/index.md
indexed: true
---

## Introducing
In the following we'll guide throught the process on how to find smarty blocks in the template files of Shopware. It's not always as easy as it seems like but there are some tips which should finding the necessary block as easy as possible.

## Anatomy of Shopware - A templater's perspective.
First of all, we would like to start with the anatomy of Shopware. If you understand the structure of the system, you know where you have to search for a block.

### Modules
The main parts of the application are called modules. Here are the currently available modules in Shopware:

* API
* Backend
* Frontend
* Widgets

In a theme we've added the newsletter and document as a module as well. Here's the typical directoy structure of a storefront theme:

```
.
├── documents
├── frontend
├── newsletter
├── documents
└── Theme.php
```
*Directory structure of a typical theme*

As you can see the mentioned modules are available as a directoy in theme.

### Sections
The next divisions in the modules are called "sections". Sections are parts of modules, for example we have the section "detail" which represents the product detail page. We're using the sections to separate the different views in the frontend. Here's a full list of all available sections in the frontend:

* account 
* blog
* campaign
* checkout
* compare
* custom
* detail
* error
* forms
* home
	* Contains the homepage of the storefront 
* index
	* Contains the HTML skeleton 
* listing
* newsletter
	* Contains the newsletter signup for the frontend, it has nothing to do with the newsletter templates 
* note
* register
* robots_txt
	* Contains template for the robots.txt
* search
* sitemap
	* Contains the sitemap which is accessible in the storefront 
* sitemap_mobile_xml
	* Contains the template for the sitemap_mobile.xml. You can find [more information here](https://support.google.com/webmasters/answer/34648?hl=en).
* sitemap_xml
	* Contains the sitemap.xml which will be used by Web crawlers like the google or bing bot. 
* tellafriend

If you're a developer you'll notice that the sections are matching our frontend controllers.

### Actions
We divived our section even more into parts which are called "actions". Actions are representing a part of the storefront. For example we have the actions "index" and "detail" in the blog section. The "index" action represents the listing of all available blog articles. On the other hand we have the "detail" action which represents the detail page of a blog article.

Please keep in mind that actions can be omitted. In this case Shopware always assumes that you want to jump to the "index" action.


## How to find a Smarty block
Now you know all about the anatomy of Shopware, you have the necessary knowledge to find almost any block in the storefront.

Let us take a closer look at the blog directoy of your theme which is located under ```frontend/blog```. Here's the directoy structure of it:

```
.
├── comment
├── atom.tpl
├── bookmarks.tpl
├── box.tpl
├── comments.tpl
├── detail.tpl
├── filter.tpl
├── header.tpl
├── images.tpl
├── index.tpl
├── listing.tpl
├── listing_actions.tpl
├── listing_sidebar.tpl
└── rss.tpl
```
*Directory structure of the "frontend/blog" folder*

As you can see there's a bunch of files in the directory. Don't get confused. There are severals ways to terminate the action and therefor the associated template file in your theme.

### Using the web dev console in your favorite browser
To make your life easier we added the section and action as a class to the ```html``` element. You can use your web developer console take a look on it:

![Chrome Web Dev Tools](web-dev-console.jpg)

*Inspection of the "body" element*

In the screenshot above, we're using the Chrome web developer tools and we're on the detail page of a blog article. As you can see there are two classes on the ```body``` element. The classes can help you to terminate the currently active section and action. The syntax looks like this:

```
is--ctl-[section]
```
*Syntax example for the currently active section*

```
is--act-[action]
```
*Syntax example for the currently active action*

Based on this information, you know that the template file ```detail.tpl``` (the action) in the directoy ```frontend/blog``` (section) will be used.

## Example: Remove the comments on the blog detail page
Now let's get into the real world. Let's say, we want to remove the comment section of the blog's detail page. How can I find the Smarty block which I need to overwrite to get rid of the comment section? Here's the how you can to that:

1. Terminate the correct template file using the two classes on the ```body``` element. In our case, it's the ```detail.tpl``` in the directoy ```frontend/blog``` in the theme.
2. Now we need a class in the HTML source which can be used to terminate the comment section. In this example, we'll use the HTML class ```blog--comments-wrapper``` which can be used to search for a wrapping block.
3. Using the search function in your IDE, you'll see that the template file ```frontend/blog/comments.tpl``` was found.
4. When you open up the file you'll see that there's no Smarty block around the outer ```div```-box.
5. The next step is to find the place where we including the file. Once again use the search function in your IDE and search for the path of the file e.g. ```frontend/blog/comments.tpl```
6. You'll end up with the file ```frontend/blog/detail.tpl``` which contains the specific include we're looking for.

Now we have found the Smarty block named ```frontend_blog_detail_comments``` and we can overwrite it in our theme like this:

```smarty
{extends file="parent:frontend/blog/detail.tpl"}

{* Remove the comment section *}
{block name='frontend_blog_detail_comments'}{/block}
```

## Special note to widget templates
You may end up searching in your project and simply can't find the necessary template file. You usually looking for a Smarty block or template file which is used or defined by a widget. Widgets are independet parts inside Shopware which controls itself and comes with a separate template file.

We classified widgets as a separate section in Shopware, which means that the template files are not located in the ```frontend``` directory of your theme. You can find them in the ```widgets``` directory.

If you see a call to the Smarty ```action``` plugin in a template file like this:

```smarty
{action module=widgets controller=listing action=tag_cloud sController=index}
```

The ```action``` plugin works with a similar approach like the rest of the template system of Shopware. Let us take a look on the syntax of the plugin.

* module
	* Basically the module we [outlined above](#modules)
* controller
	* The controller which for example fetches the necessary data from the database. The template files for the controller are located in the directory ```widgets/[controller]``` in your theme.
* action
	* The action which will be called in the controller. The action name defines the template file name as well. For the above example the template file name is ```tag_cloud.tpl```

Therefore the template file for the Smarty ```action``` plugin is located under ```widgets/listing/tag_cloud.tpl```.