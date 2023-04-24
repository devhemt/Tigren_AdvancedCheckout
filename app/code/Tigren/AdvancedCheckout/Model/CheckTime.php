<?php
/**
 * @author    Tigren Solutions <info@tigren.com>
 * @copyright Copyright (c) 2023 Tigren Solutions <https://www.tigren.com>. All rights reserved.
 * @license   Open Software License ("OSL") v. 3.0
 */

namespace Tigren\AdvancedCheckout\Model;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;

class CheckTime implements \Tigren\AdvancedCheckout\Api\CheckTimeInterface
{

    protected $scopeConfig;

    public function __construct(
        ScopeConfigInterface $scopeConfig
    ) {
        $this->scopeConfig = $scopeConfig;
    }

    public function dayOff()
    {
        $days = $this->scopeConfig->getValue("Tigren_AdvancedCheckout/general/day_off", ScopeInterface::SCOPE_STORE);
        return $days;
    }

    public function dateOff()
    {
        $dates = $this->scopeConfig->getValue("Tigren_AdvancedCheckout/general/date_off", ScopeInterface::SCOPE_STORE);
        return $dates;
    }

    public function deliveryTime()
    {
        $times = $this->scopeConfig->getValue("Tigren_AdvancedCheckout/general/delivery_time",
            ScopeInterface::SCOPE_STORE);
        return $times;
    }
}
