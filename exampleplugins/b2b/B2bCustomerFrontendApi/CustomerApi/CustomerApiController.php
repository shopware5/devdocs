<?php declare(strict_types=1);

namespace B2bCustomerFrontendApi\CustomerApi;

use Shopware\B2B\Common\Controller\GridHelper;
use Shopware\B2B\Common\MvcExtension\Request;
use Shopware\B2B\Contact\Framework\ContactRepository;
use Shopware\B2B\Contact\Framework\ContactSearchStruct;
use Shopware\B2B\Debtor\Framework\DebtorIdentity;
use Shopware\B2B\StoreFrontAuthentication\Framework\AuthenticationService;

class CustomerApiController
{
    /**
     * @var AuthenticationService
     */
    private $authenticationService;

    /**
     * @var ContactRepository
     */
    private $contactRepository;

    /**
     * @var GridHelper
     */
    private $gridHelper;

    /**
     * @param AuthenticationService $authenticationService
     * @param ContactRepository $contactRepository
     * @param GridHelper $gridHelper
     */
    public function __construct(
        AuthenticationService $authenticationService,
        ContactRepository $contactRepository,
        GridHelper $gridHelper
    ) {
        $this->authenticationService = $authenticationService;
        $this->contactRepository = $contactRepository;
        $this->gridHelper = $gridHelper;
    }

    /**
     * @param Request $request
     * @throws \InvalidArgumentException
     * @return array
     */
    public function indexAction(Request $request): array
    {
        $identity = $this->authenticationService->getIdentity();
        
        if (!($identity instanceof DebtorIdentity)) {
            throw new \InvalidArgumentException('No debtor identity');
        }
        
        $ownerShipContext = $identity->getOwnershipContext();

        $searchStruct = new ContactSearchStruct();

        $this->gridHelper->extractSearchDataInRestApi($request, $searchStruct);

        $contacts = $this->contactRepository->fetchList($ownerShipContext, $searchStruct);

        $totalCount = $this->contactRepository->fetchTotalCount($ownerShipContext, $searchStruct);

        return [
            'data' => [
                'success' => true,
                'contacts' => $contacts,
                'totalCount' => $totalCount,
            ],
        ];
    }
}
