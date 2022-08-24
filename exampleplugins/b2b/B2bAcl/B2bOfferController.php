<?php declare(strict_types=1);

namespace B2bAcl;

use B2bAcl\Offer\OfferCrudService;
use B2bAcl\Offer\OfferRepository;
use B2bAcl\Offer\OfferSearchStruct;
use Shopware\B2B\Common\Controller\B2bControllerRedirectException;
use Shopware\B2B\Common\Controller\GridHelper;
use Shopware\B2B\Common\MvcExtension\Request;
use Shopware\B2B\Common\Repository\CanNotRemoveExistingRecordException;
use Shopware\B2B\Common\Validator\ValidationException;
use Shopware\B2B\StoreFrontAuthentication\Framework\AuthenticationService;

class B2bOfferController
{
    /**
     * @var OfferRepository
     */
    private $offerRepository;

    /**
     * @var AuthenticationService
     */
    private $authenticationService;

    /**
     * @var OfferCrudService
     */
    private $offerCrudService;

    /**
     * @var GridHelper
     */
    private $gridHelper;

    /**
     * @param OfferRepository $offerRepository
     * @param AuthenticationService $authenticationService
     * @param OfferCrudService $offerCrudService
     * @param GridHelper $gridHelper
     */
    public function __construct(
        OfferRepository $offerRepository,
        AuthenticationService $authenticationService,
        OfferCrudService $offerCrudService,
        GridHelper $gridHelper
    ) {
        $this->offerRepository = $offerRepository;
        $this->authenticationService = $authenticationService;
        $this->offerCrudService = $offerCrudService;
        $this->gridHelper = $gridHelper;
    }

    public function indexAction()
    {
        //nth
    }

    /**
     * @param Request $request
     * @return array
     */
    public function gridAction(Request $request): array
    {
        $ownershipContext = $this->authenticationService->getIdentity()->getOwnershipContext();
        $searchStruct = new OfferSearchStruct();

        $this->gridHelper
            ->extractSearchDataInStoreFront($request, $searchStruct);

        $offers = $this->offerRepository
            ->fetchList($ownershipContext, $searchStruct);

        $totalCount = $this->offerRepository
            ->fetchTotalCount($ownershipContext, $searchStruct);

        $maxPage = $this->gridHelper
            ->getMaxPage($totalCount);

        $currentPage = (int) $request
            ->getParam('page', 1);

        $gridState = $this->gridHelper
            ->getGridState($request, $searchStruct, $offers, $maxPage, $currentPage);

        return [
            'gridState' => $gridState,
        ];
    }

    /**
     * @return array
     */
    public function newAction(): array
    {
        return $this->gridHelper->getValidationResponse('offer');
    }

    /**
     * @param Request $request
     */
    public function createAction(Request $request)
    {
        $request->checkPost();
        $post = $request->getPost();

        $serviceRequest = $this->offerCrudService->createNewRecordRequest($post);

        $identity = $this->authenticationService->getIdentity();

        try {
            $offer = $this->offerCrudService
                ->create($serviceRequest, $identity->getOwnershipContext());
        } catch (ValidationException $e) {
            $this->gridHelper->pushValidationException($e);
            throw new B2bControllerRedirectException(
                'new',
                'b2boffer',
                'frontend'
            );
        }

        throw new B2bControllerRedirectException(
            'detail',
            'b2boffer',
            'frontend',
            ['id' => $offer->id]
        );
    }

    /**
     * @param Request $request
     * @return array
     */
    public function detailAction(Request $request): array
    {
        $id = (int) $request->requireParam('id');

        return [
            'offer' => $this->offerRepository->fetchOneById($id),
            'id' => (int) $request->requireParam('id'),
        ];
    }

    /**
     * @param Request $request
     * @return array
     */
    public function editAction(Request $request): array
    {
        $id = (int) $request->requireParam('id');

        if (!$this->gridHelper->getValidationResponse('offer')) {
            return ['offer' => $this->offerRepository->fetchOneById((int) $id)];
        }
    }

    /**
     * @param Request $request
     */
    public function updateAction(Request $request)
    {
        $request->checkPost();

        $post = $request->getPost();

        $ownershipContext = $this->authenticationService->getIdentity()->getOwnershipContext();
        $serviceRequest = $this->offerCrudService->createExistingRecordRequest($post);

        try {
            $this->offerCrudService
                ->update($serviceRequest, $ownershipContext);
        } catch (ValidationException $e) {
            $this->gridHelper
                ->pushValidationException($e);
        }

        throw new B2bControllerRedirectException(
            'edit',
            'b2boffer',
            'frontend',
            ['id' => $serviceRequest->requireParam('id')]
        );
    }

    /**
     * @param Request $request
     */
    public function removeAction(Request $request)
    {
        $request->checkPost();

        $serviceRequest = $this->offerCrudService
            ->createExistingRecordRequest($request->getPost());

        try {
            $this->offerCrudService->remove($serviceRequest);
        } catch (CanNotRemoveExistingRecordException $e) {
            // nth
        }

        throw new B2bControllerRedirectException('grid', 'b2boffer');
    }
}
