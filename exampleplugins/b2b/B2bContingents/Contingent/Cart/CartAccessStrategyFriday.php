<?php declare(strict_types = 1);

namespace B2bContingents\Contingent\Cart;

use Shopware\B2B\Cart\Framework\CartAccessContext;
use Shopware\B2B\Cart\Framework\CartAccessStrategyInterface;
use Shopware\B2B\Cart\Framework\MessageCollection;

class CartAccessStrategyFriday implements CartAccessStrategyInterface
{
    /**
     * @param CartAccessContext $context
     * @param MessageCollection $messageCollection
     * @return bool
     */
    public function isAllowed(CartAccessContext $context, MessageCollection $messageCollection): bool
    {
        return date('D') === 'Fri';
    }
}
