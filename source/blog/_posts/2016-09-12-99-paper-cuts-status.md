---
title: Status of the 99 papercuts project
github_link: blog/_posts/2016-09-12-99-paper-cuts-status.md
authors: [bc]
---

We are now on day 8 of our project 99 Papercuts, with 7 days to go.

From now until Monday, 19th September, you can participate in our [99 Papercuts Project](https://developers.shopware.com/99-paper-cuts/) and make Shopware better - one papercut at a time.

The feedback so far has been pretty good; we've already received several contributions and [merged](https://github.com/shopware/shopware/pulls?q=is%3Apr+%5BPapercut%5D+is%3Aclosed) a good amount of pull requests.
We also noticed increased activity in our [IRC Channel](/contributing/irc/), where you can get in touch with the Community.

You can help by reviewing the [open](https://github.com/shopware/shopware/pulls?utf8=%E2%9C%93&q=%3Apr%20is%3Aopen%20%5BPapercut%5D) pull requests and providing feedback.

## New Pull Request Workflow

If you are a regular contributor to Shopware you may have noticed that we changed the pull request workflow for the time of the papercuts project.

Normally, when you open a pull request we will first schedule it. That means a script fetches the pull request's information and creates a ticket in our Issue Tracker. In one of our next scrum sprints, the team will place the tickets with the status "scheduled" into the sprint and work on it.

When a team member is working on your pull request he imports it from GitHub into our internal git system. We use the excellent [Hub](https://hub.github.com/) tool from the GitHub team for that. 
The final code review as well as QA will take place in our internal systems. If all of that is fine, the code will be merged into one of the mainline branches (at the time of writing either `5.2`, or `5.3`). 

A cronjob will push the latest changes from our internal git system to GitHub where the pull request is then finally closed.

During the time of the papercuts project we changed that workflow.
Pull Requests will be merged directly on GitHub into the dedicated `99-papercuts` branch. 
A team member will perform the code review, test directly on GitHub and provide feedback if changes are necessary.

GitHub released a new feature just in time that supports this workflow quite well: [Improved collaboration with Forks](https://github.com/blog/2247-improving-collaboration-with-forks).

We use [Travis CI](https://travis-ci.org/shopware/shopware/pull_requests) to run our automated tests and [Style CI](https://styleci.io/repos/5682970) to check that all the merged code adheres to our [Coding Standards](/developers-guide/coding-standards/).

Tickets have already been created in our Issue Tracker, and we have no scheduling phase since we are all working on the papercuts project.

## Get involved and participate
Now it's your turn. Find a papercut bug that bothers you and try to fix it.

<a href="{{ site.url }}/99-paper-cuts/">
    <img style="width: 80%; margin: auto; display:block" src="{{ site.url }}/99-paper-cuts/img/paper-cuts-logo.png">
</a>

