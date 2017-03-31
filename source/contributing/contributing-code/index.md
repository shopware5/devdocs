---
layout: default
title: Contributing Code 
github_link: contributing/contributing-code/index.md
indexed: true
tags: [pullrequest, github, contribute, git, pull-request, fork]
menu_title: Contributing Code
menu_order: 10
group: Contributing
---

<div class="toc-list"></div>

## Configure Git

Set up your user information with your real name and a working email address:

```git config --global user.name "Your Name"```

```git config --global user.email you@example.com```


## Create a Fork
Navigate to the [Shopware Github Repository](https://github.com/shopware/shopware) and click the **"Fork"**-Button in the upper right hand corner.

<img src="/contributing/img/github-fork-button.png"/>

This will create a "copy" of the entire Shopware repository into your personal user namespace.

## Clone your fork to your local machine

After the "forking action" has completed, clone your fork locally (this will create a `shopware` directory):

```git clone git@github.com:USERNAME/shopware.git```

Add the shopware repository as `upstream` remote:

```cd shopware```

```git remote add upstream https://github.com/shopware/shopware.git```

Verify the new remote named `upstream`:

```git remote -v```

```origin    git@github.com:USERNAME/shopware.git (fetch)```

```origin    git@github.com:USERNAME/shopware.git (push)```

```upstream    https://github.com/shopware/shopware.git (fetch)```

```upstream    https://github.com/shopware/shopware.git (push)```

Now that you have the shopware source code locally on your machine please follow the [Git Installation Instructions](https://github.com/shopware/shopware#installation-via-git).

## Create a new Feature branch

Each time you want to work on a patch, create a feature branch:

```git fetch upstream```

```git checkout -b my-new-feature upstream/5.2```

The first command will fetch the latest updates from the upstream project (shopware).
The second will create a new branch named `my-new-feature`, that is based off the `5.2`-branch of the `upstream` remote.

## Submit your pull request

Push your branch to your github fork:

```git push origin my-new-feature```

## Create a Pull Request on Github
Navigate back to the [Shopware Github Repository](https://github.com/shopware/shopware) and click the **"Compare & pull request"-Button**.

<img src="/contributing/img/github-create-pull-request.png"/>

Before creating your pull request make sure that it fits our [contribution guideline](/contributing/contribution-guideline/).

### How to create a Pull Request

- [https://git-scm.com/book](https://git-scm.com/book)
- [https://try.github.io](https://try.github.io)