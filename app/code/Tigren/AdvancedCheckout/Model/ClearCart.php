<?php
/**
 * @author    Tigren Solutions <info@tigren.com>
 * @copyright Copyright (c) 2023 Tigren Solutions <https://www.tigren.com>. All rights reserved.
 * @license   Open Software License ("OSL") v. 3.0
 */

namespace Tigren\AdvancedCheckout\Model;

use Magento\Checkout\Model\Cart;


class ClearCart implements \Tigren\AdvancedCheckout\Api\ClearCartInterface
{
    /**
     * @var Cart
     */
    protected $cartManagement;


    public function __construct(
        Cart $cartManagement,
    ) {
        $this->cartManagement = $cartManagement;
    }

    /**
     * Clear cart
     * @return bool
     */
    public function clearCart()
    {
        $this->cartManagement->truncate()->save();

        return true;
    }
}
