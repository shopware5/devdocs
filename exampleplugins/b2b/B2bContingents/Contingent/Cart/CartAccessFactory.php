<?php declare(strict_types = 1);

namespace B2bContingents\Contingent\Cart;

use Shopware\B2B\Cart\Framework\CartAccessFactoryInterface;
use Shopware\B2B\Cart\Framework\CartAccessStrategyInterface;
use Shopware\B2B\Cart\Framework\WhiteListCartAccess;
use Shopware\B2B\StoreFrontAuthentication\Framework\Identity;

class CartAccessFactory implements CartAccessFactoryInterface
{
    /**
     * @param Identity $identity
     * @return CartAccessStrategyInterface
     */
    public function createCartAccessForIdentity(Identity $identity): CartAccessStrategyInterface
    {
        return new WhiteListCartAccess(
            new CartAccessStrategyMonday(),
            new CartAccessStrategyFriday()
        );
    }
}
