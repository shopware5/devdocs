---
layout: default
title: CRUD service
github_link: shopware-enterprise/b2b-suite/technical/crud-services.md
indexed: true
menu_title: CRUD service
menu_order: 6
menu_style: numeric
menu_chapter: true
group: Shopware Enterprise
subgroup: B2B-Suite
subsubgroup: Technical Documentation
---

<div class="alert alert-info">
You can download a plugin showcasing the topic <a href="{{ site.url }}/exampleplugins/B2bAcl.zip">here</a>. 
</div>

<div class="toc-list"></div>

## The Pattern

A repeating pattern used throughout the B2B-Suite are CRUD services. The B2B-Suite ships with it's own entities, and therefore provides the means to **cr**eate **u**pdate and **d**elete them. Although these entities may have special requirements, there is a exclusively used naming convention and pattern used to implement all CRUD operations.

The Diagram below shows the usually implemented objects with their outside dependencies.

![image](/assets/img/b2b/crud-service.svg)

## The Entity

There always is an entity representing the data that has to be written. Entities are uniquely identifiable storage objects, with public properties and only a few convenience functions. An example entity looks like this:

```php
<?php

namespace Shopware\B2B\Role\Framework;

use Shopware\B2B\Common\CrudEntity;

class RoleEntity implements CrudEntity
{
    /**
     * @var int
     */
    public $id;

    /**
     * @var string
     */
    public $name;

    /**
     * @var string
     */
    public $debtorEmail;

    /**
     * @return bool
     */
    public function isNew(): bool
    {
        return ! (bool) $this->id;
    }

    /**
     * @return array
     */
    public function toDatabaseArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            's_user_debtor_email' => $this->debtorEmail
        ];
    }

    /**
     * @param array $roleData
     * @return CrudEntity
     */
    public function fromDatabaseArray(array $roleData): CrudEntity
    {
        $this->id = (int) $roleData['id'];
        $this->name = (string) $roleData['name'];
        $this->debtorEmail = (string) $roleData['s_user_debtor_email'];

        return $this;
    }

    /**
     * @param array $data
     */
    public function setData(array $data)
    {
        foreach ($data as $key => $value) {
            if (!property_exists($this, $key)) {
                continue;
            }

            $this->{$key} = $value;
        }
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return get_object_vars($this);
    }

    /**
     * {@inheritdoc}
     */
    public function jsonSerialize(): array
    {
        return $this->toArray();
    }
}
```

The convenience interface `Shopware\B2B\Common\CrudEntity` is not required to assign context to the object. Furthermore, the definition whether an entity can be stored or retrieved from storage can only securely be determined if corresponding repository methods exist.

## The Repository

There always is a repository, that handles all storage and retrieval functionality. Contrary to Shopware default repositories they do not use the ORM and do not expose queries. A sample repository might look like this:

```php
<?php

namespace Shopware\B2B\Role\Framework;

use Doctrine\DBAL\Connection;

class RoleRepository
{
    /**
     * @var Connection
     */
    private $connection;

    /**
     * @param Connection $connection
     */
    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @param int $id
     * @return CrudEntity
     * @throws \Shopware\B2B\Common\Repository\NotFoundException
     */
    public function fetchOneById(int $id): CrudEntity
    {
        [...]
    }

    /**
     * @param RoleEntity $role
     * @return RoleEntity
     * @throws \Shopware\B2B\Common\Repository\CanNotInsertExistingRecordException
     */
    public function addRole(RoleEntity $role): RoleEntity
    {
        [...]
    }

    /**
     * @param RoleEntity $role
     * @return RoleEntity
     * @throws \Shopware\B2B\Common\Repository\CanNotUpdateExistingRecordException
     */
    public function updateRole(RoleEntity $role): RoleEntity
    {
        [...]
    }

    /**
     * @param RoleEntity $roleEntity
     * @return RoleEntity
     * @throws \Shopware\B2B\Common\Repository\CanNotRemoveExistingRecordException
     */
    public function removeRole(RoleEntity $roleEntity): RoleEntity
    {
        [...]
    }
}
```

Since it seams to be a sufficient workload for a single object to just interact with the storage layer, there is no additional validation of any sort. Everything that is solvable in PHP only is not part of this object. Notice that the exceptions are all typed and can be caught easily by the implementation code.

## The Validation Service

Every entity has a corresponding `ValidationService`

```php
<?php

namespace Shopware\B2B\Role\Framework;

use Shopware\B2B\Common\Validator\ValidationBuilder;
use Shopware\B2B\Common\Validator\Validator;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class RoleValidationService
{
    /**
     * @var ValidationBuilder
     */
    private $validationBuilder;

    /**
     * @var ValidatorInterface
     */
    private $validator;

    /**
     * @param ValidationBuilder $validationBuilder
     * @param ValidatorInterface $validator
     */
    public function __construct(
        ValidationBuilder $validationBuilder,
        ValidatorInterface $validator
    ) {
        $this->validationBuilder = $validationBuilder;
        $this->validator = $validator;
    }

    /**
     * @param RoleEntity $role
     * @return Validator
     */
    public function createInsertValidation(RoleEntity $role): Validator
    {

        [...]

    }

    /**
     * @param RoleEntity $role
     * @return Validator
     */
    public function createUpdateValidation(RoleEntity $role): Validator
    {

        [...]

    }
```

It provides assertions that can be evaluated by a controller and printed to the user.

## The CRUD Service

Services are the real entry point to an entity. They are reusable and not dependant of any specific I/O mechanism.

They are not allowed to depend on HTTP implementations directly, and therefore provide their own request classes that contain the source independent required raw data. Notice that they are also used to initially filter a possibly larger request and they allow just the right data points to enter the service, although the contents is validated by the `ValidationService`.

```php
<?php

namespace Shopware\B2B\Role\Framework;

use Shopware\B2B\Common\Service\AbstractCrudService;
use Shopware\B2B\Common\Service\CrudServiceRequest;

class RoleCrudService extends AbstractCrudService
{
    [...]

    /**
     * @param array $data
     * @return CrudServiceRequest
     */
    public function createNewRecordRequest(array $data): CrudServiceRequest
    {
        return new CrudServiceRequest(
            $data,
            [
                'name',
                'debtorEmail'
            ]
        );
    }

    /**
     * @param array $data
     * @return CrudServiceRequest
     */
    public function createExistingRecordRequest(array $data): CrudServiceRequest
    {
        return new CrudServiceRequest(
            $data,
            [
                'id',
                'name',
                'debtorEmail'
            ]
        );
    }

    [...]
}
```
With a filled `CrudServiceRequest` you then call the actual action you want the service to perform. Keep in mind that there may be other parameters required. For example an `Identity` determining if the currently logged in user may even access the requested data.

```php
<?php

namespace Shopware\B2B\Role\Framework;

use Shopware\B2B\Common\Service\AbstractCrudService;
use Shopware\B2B\Common\Service\CrudServiceRequest;

class RoleCrudService extends AbstractCrudService
{
    [...]

    /**
     * @param CrudServiceRequest $request
     * @return RoleEntity
     * @throws \Shopware\B2B\Common\Validator\ValidationException
     */
    public function create(CrudServiceRequest $request): RoleEntity
    {

        [...]

    }

    /**
     * @param CrudServiceRequest $request
     * @return RoleEntity
     * @throws \Shopware\B2B\Common\Validator\ValidationException
     */
    public function update(CrudServiceRequest $request): RoleEntity
    {

        [...]

    }

    /**
     * @param CrudServiceRequest $request
     * @return RoleEntity
     */
    public function remove(CrudServiceRequest $request): RoleEntity
    {

        [...]

    }
}
```
