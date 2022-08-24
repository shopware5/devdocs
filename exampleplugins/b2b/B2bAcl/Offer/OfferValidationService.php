<?php declare(strict_types = 1);

namespace B2bAcl\Offer;

use Shopware\B2B\Common\Validator\ValidationBuilder;
use Shopware\B2B\Common\Validator\Validator;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class OfferValidationService
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
     * @param OfferEntity $offer
     * @return Validator
     */
    public function createInsertValidation(OfferEntity $offer): Validator
    {
        return $this->createCrudValidation($offer)
            ->validateThat('id', $offer->id)
            ->isBlank()
            ->getValidator($this->validator);
    }

    /**
     * @param OfferEntity $offer
     * @return Validator
     */
    public function createUpdateValidation(OfferEntity $offer): Validator
    {
        return $this->createCrudValidation($offer)
            ->validateThat('id', $offer->id)
            ->isInt()

            ->getValidator($this->validator);
    }

    /**
     * @param OfferEntity $offer
     * @return ValidationBuilder
     */
    private function createCrudValidation(OfferEntity $offer): ValidationBuilder
    {
        return $this->validationBuilder

            ->validateThat('name', $offer->name)
            ->isNotBlank();
    }
}
