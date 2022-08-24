---
layout: default
title: Method Structure
github_link: shopware-enterprise/b2b-suite/technical/method-structure.md
indexed: true
menu_title: Method Structure
menu_order: 3
menu_style: numeric
menu_chapter: true
group: Shopware Enterprise
subgroup: B2B-Suite
subsubgroup: Technical Documentation
---

<div class="toc-list"></div>

## Replaceable functions

Almost every function in the B2B-Suite is replaceable but not all are guaranteed to be compatible to every version change.
Only the framework domain has guaranteed rules to limit the changes of each method per release version.
The methods in other domains have dependencies on the Shopware core and have to be adjusted if changes are made.

### Protected functions in framework

Protected functions with an `@internal` comment are **not** guaranteed to be compatible or changed to minor versions changes.

Example:
```php
<?php declare(strict_types=1);

namespace Shopware\B2B\Common\Controller;

[...]

class GridHelper
{    
    [...]
    
    /**
     * @internal
     * @param Request $request
     * @param SearchStruct $struct
     */
    protected function extractLimitAndOffset(Request $request, SearchStruct $struct)
    {
        $struct->offset = $request->getParam('offset', null);
        $struct->limit = $request->getParam('limit', null);
    }

    [...]
}
```

### Public functions in framework

Public functions are made to be compatible and not be changed until major version changes.
