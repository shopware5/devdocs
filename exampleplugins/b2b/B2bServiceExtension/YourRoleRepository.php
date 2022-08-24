<?php declare(strict_types=1);

namespace B2bServiceExtension;

use Shopware\B2B\Role\Framework\RoleEntity;
use Shopware\B2B\Role\Framework\RoleRepository;

class YourRoleRepository extends RoleRepository
{
    public $myService;

    public function __construct()
    {
        $args = func_get_args();

        $this->myService = array_pop($args);

        parent::__construct(... $args);
    }

    /**
     * @param RoleEntity $role
     * @return RoleEntity
     */
    public function updateRole(RoleEntity $role): RoleEntity
    {
        return parent::updateRole($role);
    }
}
