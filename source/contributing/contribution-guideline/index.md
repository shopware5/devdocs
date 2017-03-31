---
layout: default
title: Contribution Guideline 
github_link: contributing/contribution-guideline/index.md
indexed: true
tags: [pullrequest, github, guideline, contribute, git, pull-request, fork]
menu_title: Contribution Guideline
menu_order: 20
group: Contributing
---

<div class="toc-list"></div>

## Introduction
First of all, thank you! You have decided to contribute code to our software and become a member of the large shopware community. We appreciate your hard work and want to handle it with the most possible respect.

To ensure the quality of our code and our products we have created a small guideline we all should endorse to. It helps you and us to collaborate with our software. Following these guidelines will help us to integrate your changes in our daily workflow. 

## Requirements for a successful pull request
To avoid that your pull request gets rejected, you should always check that you provided all necessary information, so that we can integrate your changes in our internal workflow very easily. Here is a check-list with some requirements you should always consider when committing new changes.

- Did you fill out the [pull request info template](https://github.com/shopware/shopware/blob/5.2/.github/PULL_REQUEST_TEMPLATE.md) as detailed as possible?
- Do you made entries in the correct `Upgrade.md` file with a small documentation of your changes?
- Does your pull request address the correct shopware version? Breaks and features cannot be merged in a patch release.
- Is your implementation missing some important parts? For example translations, downward compatibility, compatibility to important plugins, etc.
- Did you provide the necessary tests for your implementation?
- Is there already an existing pull request tackling the same issue?

Pull requests which do not fulfill these requirements will never be accepted by our team. To avoid that your changes go through unnecessary workflow cycles make sure to check this list with every pull request.

## The developing workflow on GitHub
When you create a new pull request on GitHub normally it will get a first sight within a week. We do regular meetings to screen all new pull requests on GitHub. In this meeting there is a team of up to five shopware developers of different specialisations which will discuss your changes. Together we decide what will happen next to your pull request. We will set one of the following labels which indicate the status of the pull request. Here is a list of all possible states.

<table cellpadding="0" cellspacing="0" width="100%">
    <tr>
        <th width="280">Label</th>
        <th>What does it mean?</th>
    </tr>
    <tr>
        <td>
            <img src="/contributing/img/label-incomplete.jpg" />
        </td>
        <td>
            Your pull request is incomplete. It is either missing some of the necessary information, or your code implementation is not sufficient to fix the issue. Mostly there will be a comment by our developers which gives you further information of what is missing.
        </td>
    </tr>
    <tr>
        <td>
            <img src="/contributing/img/label-tests.jpg" />
        </td>
        <td>
            When you want to commit a new feature or bigger change it is highly necessary that you provide the corresponding tests for it. We only accept new features or bug fixes which are completely tested.
        </td>
    </tr>
    <tr>
        <td>
            <img src="/contributing/img/label-question.jpg" />
        </td>
        <td>
            Our developers have a question about your code and want to talk with you. They will either comment directly in your code or in the main conversation of the pull request. Try to give them all needed information as detailed as possible so that they can understand what you want to achieve with your changes.
        </td>
    </tr>
    <tr>
        <td>
            <img src="/contributing/img/label-declined.jpg" />
        </td>
        <td>
            Your pull request was declined by our developers and is closed. No reason to be sad. It can have very different reasons. We understand that it sometimes can be hard to understand the reason behind this. Mostly there will be a comment by our developers about why it was declined.
        </td>
    </tr>
    <tr>
        <td>
            <img src="/contributing/img/label-scheduled.jpg" />
        </td>
        <td>
            Yeaha! You made the first step towards the holy grail. Your changes had been reviewed by our developers and they decided that you provided a good benefit for our product. Your pull request will be imported to our ticket system and will go through our internal workflow. You will find a comment containing the ticket number to follow the status.
        </td>
    </tr>
    <tr>
        <td>
            <img src="/contributing/img/label-pick.jpg" />
        </td>
        <td>
            You are a lucky one! The changes you provide are only a small fix which is easy to test and implement. Our developers decided to quickly integrate this to our software.
        </td>
    </tr>
    <tr>
        <td>
            <img src="/contributing/img/label-accepted.jpg" />
        </td>
        <td>
            Your changes are finally accepted. The pull request passed our internal workflow. Your changes will be released with one of the next releases.
        </td>
    </tr>
</table>

<div class="alert alert-warning">
<b>Important</b>: The first three labels (Incomplete, Missing Tests, Question) mean that you have to take action! After the label was added you have up to one week to update the pull request and provide the missing information or implementation. If there is no reaction from you within that week the pull request will be declined without further reason.
</div>

## Why a pull request gets declined
So the worst thing happened, your pull request was declined. No reason to be upset. We know that it sometimes can be hard to understand why your pull request was rejected. We want be as transparent as possible, but sometimes it can also rely on internal decisions. Here is a list with common reasons why we reject a pull request.

- The pull request does not fulfill the requirements of the list above.
- You did not updated your pull request with the necessary info after a specific label was added.
- The change you made is already a part of a current change by shopware and is handled internally.
- The benefit of your change is not relevant for the whole product but only for your personal intent.
- The benefit of your change is too minor. Sometimes we do not have enough resources to handle every small change.
- Your change implements a feature which does not fit to our roadmap or our company values.


