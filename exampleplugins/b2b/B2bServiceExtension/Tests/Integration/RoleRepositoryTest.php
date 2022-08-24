<?php declare(strict_types=1);

namespace B2bServiceExtension\Tests\Integration;

use Shopware\B2B\Common\Filter\EqualsFilter;
use Shopware\B2B\Common\Filter\LikeFilter;
use Shopware\B2B\Common\Repository\CanNotInsertExistingRecordException;
use Shopware\B2B\Common\Repository\CanNotRemoveExistingRecordException;
use Shopware\B2B\Common\Repository\CanNotUpdateExistingRecordException;
use Shopware\B2B\Common\Repository\NotFoundException;
use Shopware\B2B\Role\Framework\RoleAssignmentEntity;
use Shopware\B2B\Role\Framework\RoleEntity;
use Shopware\B2B\Role\Framework\RoleRepository;
use Shopware\B2B\Role\Framework\RoleSearchStruct;
use Shopware\B2B\StoreFrontAuthentication\Framework\OwnershipContext;
use Shopware\B2BTest\Common\KernelTestCaseTrait;
use Shopware\B2BTest\Debtor\DebtorFactoryTrait;

class RoleRepositoryTest extends \PHPUnit_Framework_TestCase
{
    use KernelTestCaseTrait;
    use DebtorFactoryTrait;

    /**
     * @return RoleRepository
     */
    private function getRepository()
    {
        $this->importFixturesFileOnce(__DIR__ . '/../test_fixtures.sql');

        return self::getKernel()->getContainer()
            ->get('b2b_role.repository');
    }

    public function test_removeRole_throws_if_record_is_new()
    {
        $role = static::createNewRoleEntity();
        $this->expectException(CanNotRemoveExistingRecordException::class);
        $this->getRepository()->removeRole($role);
    }

    public function test_removeRole_removes_a_role()
    {
        $role = static::createNewRoleEntity();
        $role->id = 11;

        $repository = $this->getRepository();

        $connection = self::getKernel()->getContainer()->get('dbal_connection');

        self::assertEquals(1, $connection->fetchColumn('SELECT COUNT(*) FROM b2b_role WHERE id=11;'));

        $role = $repository->removeRole($role);

        self::assertEquals(0, $connection->fetchColumn('SELECT COUNT(*) FROM b2b_role WHERE id=11;'));
        $id = $role->id;

        self::assertNull($id);
    }

    public function test_fetchTotalCount_returns_an_int_only()
    {
        $count = $this->getRepository()->fetchTotalCount(new RoleSearchStruct(), $this->createOwnershipContext());
        self::assertEquals(12, $count);
    }

    public function test_fetchTotalCount_roles_filters()
    {
        $searchStruct = new RoleSearchStruct();
        $searchStruct->filters[] = new LikeFilter('role', 'name', 'Qa');

        $count = $this->getRepository()->fetchTotalCount($searchStruct, $this->createOwnershipContext());
        self::assertEquals(1, $count);
    }

    public function test_fetchList_returns_only_roles()
    {
        $role = $this->getRepository()->fetchList(new RoleSearchStruct(), $this->createOwnershipContext());

        // check if default sorting is id desc
        $id = $role[0]->id;
        foreach ($role as $value) {
            static::assertGreaterThanOrEqual((int) $value->id, (int) $id);
        }

        self::assertCount(12, $role);
        self::assertContainsOnlyInstancesOf(RoleEntity::class, $role);
    }

    public function test_fetchList_roles_limit_and_offset()
    {
        $searchStruct = new RoleSearchStruct();
        $searchStruct->offset = 0;
        $searchStruct->limit = 1;
        $searchStruct->orderBy = 'name';
        $searchStruct->filters[] = new EqualsFilter('role', 'id', 11);

        $role = $this->getRepository()
            ->fetchList($searchStruct, $this->createOwnershipContext());

        self::assertCount(1, $role);
        self::assertEquals(11, $role[0]->id);
    }

    public function test_fetchList_roles_order_by_and_direction_asc()
    {
        $searchStruct = new RoleSearchStruct();
        $searchStruct->orderBy = 'name';
        $searchStruct->orderDirection = 'asc';

        $role = $this->getRepository()
            ->fetchList($searchStruct, $this->createOwnershipContext());

        self::assertCount(12, $role);
    }

    public function test_fetchList_roles_order_by_invalid()
    {
        $searchStruct = new RoleSearchStruct();
        $searchStruct->orderBy = 'lastname::foooooo';
        $searchStruct->orderDirection = 'desc';

        $this->expectException(\Doctrine\DBAL\DBALException::class);

        $roles = $this->getRepository()
            ->fetchList($searchStruct, $this->createOwnershipContext());
    }

    public function test_fetchList_returns_arrays_for_empty_resultsets()
    {
        $searchStruct = new RoleSearchStruct();
        $searchStruct->filters[] = new EqualsFilter('role', 'id', PHP_INT_MAX);

        $role = $this->getRepository()
            ->fetchList($searchStruct, $this->createOwnershipContext());
        self::assertCount(0, $role);
    }

    public function test_addRole_throws_if_the_role_already_has_an_id()
    {
        $role = $this->createNewRoleEntity();

        $role->id = PHP_INT_MAX;
        $this->expectException(CanNotInsertExistingRecordException::class);
        $this->getRepository()->addRole($role);
    }

    public function test_addRole_creates_a_valid_new_role()
    {
        $role = $this->createNewRoleEntity();
        $role->name = 'newRole';

        $repository = $this->getRepository();

        $connection = self::getKernel()->getContainer()->get('dbal_connection');

        self::assertEquals(
            0,
            (int) $connection->fetchColumn('SELECT COUNT(*) FROM b2b_role WHERE name = "newRole";')
        );

        $role = $repository->addRole($role);

        self::assertEquals(
            1,
            (int) $connection->fetchColumn('SELECT COUNT(*) FROM b2b_role WHERE name = "newRole";')
        );

        $id = $role->id;
        self::assertNotEmpty($id);
    }

    public function test_fetchOneById_throws_NotFoundException_for_invalid_id()
    {
        $this->expectException(NotFoundException::class);
        $this->getRepository()->fetchOneById((int) uniqid());
    }

    public function test_fetchOneById_for_valid_ids()
    {
        $user = $this->getRepository()->fetchOneById(11);
        static::assertEquals('Einkauf', $user->name);
    }

    public function test_updateRole_throws_for_a_new_role()
    {
        $role = $this->createNewRoleEntity();
        $this->expectException(CanNotUpdateExistingRecordException::class);
        $this->getRepository()->updateRole($role);
    }

    public function test_updateRole_update_role()
    {
        $role = $this->createExistingRoleEntity();

        $role = $this->getRepository()->updateRole($role);
        $id = $role->id;
        self::assertEquals(11, $id);
    }

    public function test_fetchList_returns_only_roles_with_assignment_id()
    {
        $search = new RoleSearchStruct();

        $roles = $this->getRepository()->fetchAllRolesAndCheckForContactAssignment(11, $search, $this->createOwnershipContext());

        self::assertCount(12, $roles);

        self::assertEquals(11, $roles[11]->assignmentId);
        self::assertContainsOnlyInstancesOf(RoleAssignmentEntity::class, $roles);

        self::assertEquals(11, $roles[11]->toDatabaseArray()['assignmentId']);
    }

    public function test_fetchList_has_no_selection()
    {
        $search = new RoleSearchStruct();

        $roles = $this->getRepository()->fetchAllRolesAndCheckForContactAssignment((int) uniqid(), $search, $this->createOwnershipContext());

        self::assertCount(12, $roles);
        foreach ($roles as $role) {
            self::assertEquals(null, $role->assignmentId);
        }
        self::assertContainsOnlyInstancesOf(RoleAssignmentEntity::class, $roles);
    }

    public function test_fetchList_has_no_role_for_another_debtor()
    {
        $search = new RoleSearchStruct();

        $roles = $this->getRepository()->fetchAllRolesAndCheckForContactAssignment(11, $search, $this->createOwnershipContext());

        self::assertCount(12, $roles);

        self::assertArrayNotHasKey(144, $roles);
    }

    /**
     * @return RoleEntity
     */
    public static function createExistingRoleEntity(): RoleEntity
    {
        $role = self::createNewRoleEntity();
        $role->id = 11;

        return $role;
    }

    /**
     * @return RoleEntity
     */
    public static function createNewRoleEntity(): RoleEntity
    {
        $role = new RoleEntity();
        $role->name = 'rolle';
        $role->contextOwnerId = self::createContactIdentity()->getOwnershipContext()->contextOwnerId;

        return $role;
    }

    /**
     * @return OwnershipContext
     */
    private function createOwnershipContext(): OwnershipContext
    {
        return self::createContactIdentity()->getOwnershipContext();
    }
}
