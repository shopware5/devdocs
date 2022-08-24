<?php declare(strict_types = 1);

namespace B2bAuditLog;

use Shopware\B2B\AuditLog\Framework\AuditLogEntity;
use Shopware\B2B\AuditLog\Framework\AuditLogIndexEntity;
use Shopware\B2B\AuditLog\Framework\AuditLogRepository;
use Shopware\B2B\AuditLog\Framework\AuditLogSearchStruct;
use Shopware\B2B\AuditLog\Framework\AuditLogService;
use Shopware\B2B\AuditLog\Framework\AuditLogValueDiffEntity;
use Shopware\B2B\Common\Controller\GridHelper;
use Shopware\B2B\Common\MvcExtension\Request;
use Shopware\B2B\StoreFrontAuthentication\Framework\AuthenticationService;

class AuditLogController
{
    const REFERENCE_TABLE = 'reference_table';

    const REFERENCE_ID = 100;

    const REFERENCE_SUB_TABLE = 'reference_sub_table';

    const REFERENCE_SUB_ID = 200;

    /**
     * @var AuthenticationService
     */
    private $authenticationService;

    /**
     * @var AuditLogRepository
     */
    private $auditLogRepository;

    /**
     * @var GridHelper
     */
    private $auditLogGridHelper;

    /**
     * @var AuditLogService
     */
    private $auditLogService;

    /**
     * @param AuthenticationService $authenticationService
     * @param AuditLogService $auditLogService
     * @param AuditLogRepository $auditLogRepository
     * @param GridHelper $auditLogGridHelper
     */
    public function __construct(
        AuthenticationService $authenticationService,
        AuditLogService $auditLogService,
        AuditLogRepository $auditLogRepository,
        GridHelper $auditLogGridHelper
    ) {
        $this->authenticationService = $authenticationService;
        $this->auditLogRepository = $auditLogRepository;
        $this->auditLogGridHelper = $auditLogGridHelper;
        $this->auditLogService = $auditLogService;
    }

    public function indexAction()
    {
        // nth
    }

    /**
     * @param Request $request
     * @return array
     */
    public function gridAction(Request $request): array
    {
        $auditLogSearchStruct = new AuditLogSearchStruct();

        $this->auditLogGridHelper
            ->extractSearchDataInStoreFront($request, $auditLogSearchStruct);

        $auditLogs = $this->auditLogRepository
            ->fetchList(self::REFERENCE_TABLE, self::REFERENCE_ID, $auditLogSearchStruct);

        $totalCount = $this->auditLogRepository
            ->fetchTotalCount(self::REFERENCE_TABLE, self::REFERENCE_ID, $auditLogSearchStruct);

        $maxPage = $this->auditLogGridHelper
            ->getMaxPage($totalCount);

        $currentPage = (int) $request->getParam('page', 1);

        $gridState = $this->auditLogGridHelper
            ->getGridState($request, $auditLogSearchStruct, $auditLogs, $maxPage, $currentPage);

        return [
            'gridState' => $gridState,
        ];
    }

    /**
     * @param Request $request
     * @return array
     */
    public function createAction(Request $request)
    {
        $identity = $this->authenticationService
            ->getIdentity();

        $auditLogValue = new AuditLogValueDiffEntity();
        $auditLogValue->newValue = 'newValue';
        $auditLogValue->oldValue = 'oldValue';
        $auditLogValue->comment = 'audit log comment';

        $auditLog = new AuditLogEntity();
        $auditLog->logValue = $auditLogValue;
        $auditLog->logType = 'logType';

        $auditLogIndex = new AuditLogIndexEntity();
        $auditLogIndex->referenceId = self::REFERENCE_ID;
        $auditLogIndex->referenceTable = self::REFERENCE_TABLE;

        $auditLogSubIndex = new AuditLogIndexEntity();
        $auditLogSubIndex->referenceId = self::REFERENCE_SUB_ID;
        $auditLogSubIndex->referenceTable = self::REFERENCE_SUB_TABLE;

        $auditLog = $this->auditLogService
            ->createAuditLog($auditLog, $identity, [$auditLogIndex, $auditLogSubIndex]);

        return [
            'auditLog' => $auditLog,
        ];
    }
}
