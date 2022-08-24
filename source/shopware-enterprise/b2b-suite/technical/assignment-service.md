---
layout: default
title: Assignment service
github_link: shopware-enterprise/b2b-suite/technical/assignment-service.md
indexed: true
menu_title: Assignment service
menu_order: 7
menu_style: numeric
menu_chapter: true
group: Shopware Enterprise
subgroup: B2B-Suite
subsubgroup: Technical Documentation
---

<div class="toc-list"></div>

## The Pattern

A repeating pattern used throughout the B2B-Suite are Assignment Services. The B2B-Suite ships with many new entities, and therefore provides the means to connect them to each other. This is done through M:N assignments in for that purpose alone created components.

The Diagram below shows the usually implemented objects with their outside dependencies.

![image](/assets/img/b2b/assignment-service.svg)


## The Repository

Again the repository is the exclusive access layer to the storage engine. Contrary to CRUD operations there is no object, but just plain integers (The primary keys). The default repository will have these three methods relevant for assignment:

```php
<?php

namespace Shopware\B2B\RoleContact\Framework;

use Doctrine\DBAL\Connection;
use Shopware\B2B\Common\Repository\DbalHelper;

class RoleContactRepository
{
    /**
     * @param int $roleId
     * @param int $contactId
     */
    public function removeRoleContactAssignment(int $roleId, int $contactId)
    {
        [...]
    }

    /**
     * @param int $roleId
     * @param int $contactId
     */
    public function assignRoleContact(int $roleId, int $contactId)
    {
        [...]
    }

    /**
     * @param int $roleId
     * @param int $contactId
     * @return bool
     */
    public function isMatchingDebtorForBothEntities(int $roleId, int $contactId): bool
    {
        [...]
    }
```


## The Service

Services are even smaller. They contain the two relevant methods for assignment. Internally they will check if the assignment is even allowed, and throw exceptions if not.


```php
<?php

namespace Shopware\B2B\RoleContact\Framework;

/**
 * Assigns roles to contacts M:N
 */
class RoleContactAssignmentService
{
    /**
     * @param int $roleId
     * @param int $contactId
     * @throws MismatchingDataException
     */
    public function assign(int $roleId, int $contactId)
    {
        [...]
    }

    /**
     * @param int $roleId
     * @param int $contactId
     */
    public function removeAssignment(int $roleId, int $contactId)
    {
        [...]
    }
}
```
