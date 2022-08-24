<?php declare(strict_types = 1);

namespace B2bAcl\Offer;

use Shopware\B2B\Acl\Framework\AclRepository;
use Shopware\B2B\Acl\Framework\AclUnsupportedContextException;
use Shopware\B2B\Common\Service\AbstractCrudService;
use Shopware\B2B\Common\Service\CrudServiceRequest;
use Shopware\B2B\StoreFrontAuthentication\Framework\OwnershipContext;

class OfferCrudService extends AbstractCrudService
{
    /**
     * @var OfferRepository
     */
    private $offerRepository;

    /**
     * @var OfferValidationService
     */
    private $offerValidationService;

    /**
     * @var AclRepository
     */
    private $aclRepository;

    /**
     * @param OfferRepository $offerRepository
     * @param OfferValidationService $offerValidationService
     * @param AclRepository $aclRepository
     */
    public function __construct(
        OfferRepository $offerRepository,
        OfferValidationService $offerValidationService,
        AclRepository $aclRepository
    ) {
        $this->offerRepository = $offerRepository;
        $this->offerValidationService = $offerValidationService;
        $this->aclRepository = $aclRepository;
    }

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
                'description',
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
                'description',
            ]
        );
    }

    /**
     * @param CrudServiceRequest $request
     * @param OwnershipContext $ownershipContext
     * @throws \Shopware\B2B\Common\Repository\CanNotInsertExistingRecordException
     * @throws \Shopware\B2B\Common\Validator\ValidationException
     * @return OfferEntity
     */
    public function create(CrudServiceRequest $request, OwnershipContext $ownershipContext): OfferEntity
    {
        $data = $request->getFilteredData();
        $data['sUserId'] = $ownershipContext->shopOwnerUserId;

        $offer = new OfferEntity();

        $offer->setData($data);

        $validation = $this->offerValidationService
            ->createInsertValidation($offer);

        $this->testValidation($offer, $validation);

        $offer = $this->offerRepository
            ->addOffer($offer);

        try {
            $this->aclRepository->allow(
                $ownershipContext,
                $offer->id
            );
        } catch (AclUnsupportedContextException $e) {
            return $offer;
        }

        return $offer;
    }

    /**
     * @param CrudServiceRequest $request
     * @param OwnershipContext $ownershipContext
     * @throws \Shopware\B2B\Common\Validator\ValidationException
     * @throws \Shopware\B2B\Common\Repository\CanNotUpdateExistingRecordException
     * @return OfferEntity
     */
    public function update(CrudServiceRequest $request, OwnershipContext $ownershipContext): OfferEntity
    {
        $data = $request->getFilteredData();
        $offer = new OfferEntity();
        $offer->setData($data);
        $offer->id = (int) $offer->id;
        $offer->sUserId = $ownershipContext->shopOwnerUserId;

        $validation = $this->offerValidationService
            ->createUpdateValidation($offer);

        $this->testValidation($offer, $validation);

        $this->offerRepository
            ->updateOffer($offer);

        return $offer;
    }

    /**
     * @param CrudServiceRequest $request
     * @throws \Shopware\B2B\Common\Repository\CanNotRemoveUsedRecordException
     * @throws \Shopware\B2B\Common\Repository\CanNotRemoveExistingRecordException
     * @return OfferEntity
     */
    public function remove(CrudServiceRequest $request): OfferEntity
    {
        $data = $request->getFilteredData();
        $offer = new OfferEntity();
        $offer->setData($data);

        $this->offerRepository
            ->removeOffer($offer);

        return $offer;
    }
}
