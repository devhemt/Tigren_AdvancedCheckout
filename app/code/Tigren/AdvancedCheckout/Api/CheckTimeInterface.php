<?php

namespace Tigren\AdvancedCheckout\Api;

interface CheckTimeInterface
{
    /**
     * Clear cart
     * @return bool
     */
    public function dayOff();

    /**
     * Clear cart
     * @return bool
     */
    public function dateOff();

    /**
     * Clear cart
     * @return bool
     */
    public function deliveryTime();
}
