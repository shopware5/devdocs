<?php declare(strict_types=1);

namespace B2bLogin\Contact;

use Shopware\B2B\Common\Validator\ValidationBuilder;
use Shopware\B2B\Common\Validator\Validator;
use Shopware\B2B\Contact\Framework\ContactEntity;
use Shopware\B2B\Contact\Framework\ContactRepository;
use Shopware\B2B\Contact\Framework\ContactValidationService;
use Shopware\B2B\Debtor\Framework\DebtorIdentityLoader;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class B2bContactValidationService extends ContactValidationService
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
     * @var ContactRepository
     */
    private $contactRepository;

    /**
     * @var DebtorIdentityLoader
     */
    private $debtorRepository;

    /**
     * @param ValidationBuilder $validationBuilder
     * @param ValidatorInterface $validator
     * @param ContactRepository $contactRepository
     * @param DebtorIdentityLoader $debtorRepository
     */
    public function __construct(
        ValidationBuilder $validationBuilder,
        ValidatorInterface $validator,
        ContactRepository $contactRepository,
        DebtorIdentityLoader $debtorRepository
    ) {
        parent::__construct($validationBuilder, $validator, $contactRepository, $debtorRepository);
        $this->validationBuilder = $validationBuilder;
        $this->validator = $validator;
        $this->contactRepository = $contactRepository;
        $this->debtorRepository = $debtorRepository;
    }

    /**
     * @param ContactEntity $contact
     * @return Validator
     */
    public function createInsertValidation(ContactEntity $contact): Validator
    {
        $validation = $this->createCrudValidation($contact)
            ->validateThat('id', $contact->id)
                ->isBlank();

        if (empty($contact->email)) {
            return $validation->getValidator($this->validator);
        };

        $validation->validateThat('email', $contact->email)
                ->isUnique(function () use ($contact) {
                    return 0 === $this->contactRepository->hasByEmail($contact->email);
                })
                ->isUnique(function () use ($contact) {
                    return !$this->debtorRepository->hasDebtorWithEmail($contact->email);
                })
                ->isNotBlank()
                ->isEmail()

            ->getValidator($this->validator);
    }

    /**
     * @param ContactEntity $contact
     * @return Validator
     */
    public function createUpdateValidation(ContactEntity $contact): Validator
    {
        $validation = $this->createCrudValidation($contact)
            ->validateThat('id', $contact->id)
                ->isNotBlank();

        if (empty($contact->email)) {
            return $validation->getValidator($this->validator);
        };

        $validation->validateThat('email', $contact->email)
            ->isUnique(function () use ($contact) {
                return 2 > $this->contactRepository->hasByEmail($contact->email);
            })
            ->isUnique(function () use ($contact) {
                return 3 > $this->debtorRepository->fetchDebtorWithEmailCount($contact->email);
            })
            ->isEmail()
            ->getValidator($this->validator);
    }
}
