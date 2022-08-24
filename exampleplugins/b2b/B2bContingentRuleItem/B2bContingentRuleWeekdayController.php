<?php declare(strict_types = 1);

namespace B2bContingentRuleItem;

use Shopware\B2B\Common\MvcExtension\Request;
use Shopware\B2B\ContingentRule\Framework\ContingentRuleRepository;

class B2bContingentRuleWeekdayController
{
    /**
     * @var ContingentRuleRepository
     */
    private $contingentRuleRepository;

    /**
     * @param ContingentRuleRepository $contingentRuleRepository
     */
    public function __construct(ContingentRuleRepository $contingentRuleRepository)
    {
        $this->contingentRuleRepository = $contingentRuleRepository;
    }

    /**
     * @param Request $request
     */
    public function newAction(Request $request)
    {
        // nth
    }

    /**
     * @param Request $request
     * @return array
     */
    public function editAction(Request $request)
    {
        $id = (int) $request->getParam('id');

        return [
            'rule' => $this->contingentRuleRepository
                ->fetchOneById($id),
        ];
    }
}
