---
title: Impressions and Results of the open hackathon
tags:
- hackathon
- swagathon
- open
- community

categories:
- dev

authors: [bc]
indexed: true

github_link: blog/_posts/2016-07-14-open-hackathon-review.md
---

<img src="/blog/img/open-hackathon-2016/cody_mate.jpg" alt="" width="140" class="is-float-left" />
Last year in august we ran our [first Open Hackathon](https://en.shopware.com/impressive-outcome-from-the-shopware-open-hackathon/) together with attendees from the shopware community in Münster. It was a great success so naturally we repeated the event this year again.
This year we ran the event in our new Shopware HQ in Schöppingen where we had plenty of space to get creative. Also our beach was the perfect place to work outside, play some volleyball or just chill. You can find a general write-up over at our main website at [shopware.com (GER)](https://de.shopware.com/erster-open-hackathon-im-neuen-shopware-headquarter-/).

<div style="clear:both"></div>

## The projects

Considering we had an eclectic mixture of Shopware employees and participants from the Community, the range of projects spanned from a meal ordering system for our employee catering to a third party service integration from one our partners that also attended.

You can find most of the code that was written during the hackathon on [GitHub](https://github.com/ShopwareHackathon).

### Shopware Plugin for PhpStorm
A team lead by [Daniel Espendiller](https://twitter.com/BigHaehnchen) worked on new features for the [Shopware Plugin for PhpStorm](https://plugins.jetbrains.com/plugin/7410?pr=).
This resulted in some cool features like initial support for the new plugins system and a project installer to download and unpack shopware directly from within PhpStorm.

![](/blog/img/open-hackathon-2016/phpstorm-plugin.jpg)

### Code name m2e (Meal to employee)
For a few weeks now, we've enjoyed the luxury of employee catering here at the Shopware HQ. But, up until now, the ordering process has involved a giant spreadsheet in a confluence document (yikes!).

To slimline the whole ordering process a RESTfuel API based on an existing Symfony application was created that is operated with a nice Angular based frontend.
In the future, this application will also be used to so that participants in our in-house Shopware Academy can choose their meals.

![](/blog/img/open-hackathon-2016/m2e_erd.jpg)

### Unsplash
Another team integrated [unplash](https://unsplash.com/) into Shopware's Media Manger. Unsplash provides do-whatever-you-want, high-resolution photos via an API. This API was integrated into the Media Manager so that you can find photos for Shopping Worlds / Storytelling etc. from Unsplash.

![](/blog/img/open-hackathon-2016/unsplash.png)
<div style="clear:both"></div>

### Meleven Image Cloud Integration
<img src="/blog/img/open-hackathon-2016/meleven-logo.png" class="is-float-left" style="width:175px" />
A few participants from our partner shopmacher worked on an integration to the [meleven](http://www.meleven.de/) image cloud. This goes further than our current CDN support, since as all thumbnails are generated on-the-fly by meleven.

<div style="clear:both"></div>
### Plugin System Developer Experience Improvements
Improving the developer experience of the [new plugin system](developers-guide/plugin-system/) was another project that will have a direct impact for Shopware plugin developers. This involved topics like automatic registration of JS/LESS files and creating a more convenient way to register customer controllers.

### Shopware goes composer (part 2)
<img src="/blog/img/open-hackathon-2016/logo-composer-transparent4.png" class="is-float-left" style="width:175px" />

During the last (internal) hackathon, I worked on a project called [Shopware goes Composer](blog/2016/02/11/projects-of-the-first-internal-hackathon-in-2016/#shopware-goes-composer), the goal of which was to deploy Shopware via git as a composer dependency as well as install plugins via composer. During this hackathon, the team made great progress to reach this goal. There are still a lot of patches that need to be merged in the main shopware branch but "soon" it should be possible to deploy Shopware from git.

<div style="clear:both"></div>

### Product Listing Improvements
The next project will probably have the greatest visible impact for Shopware merchants. The team was working on Shopware listing improvements such as "custom sort orders per category" or "variants in products listing".

## Glimpse into the Open Hackathon

![](/blog/img/open-hackathon-2016/breakfast.jpg)
