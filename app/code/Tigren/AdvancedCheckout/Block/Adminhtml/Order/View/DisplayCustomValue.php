<?php
/**
 * @author    Tigren Solutions <info@tigren.com>
 * @copyright Copyright (c) 2023 Tigren Solutions <https://www.tigren.com>. All rights reserved.
 * @license   Open Software License ("OSL") v. 3.0
 */

namespace Tigren\AdvancedCheckout\Block\Adminhtml\Order\View;

use Magento\Sales\Api\Data\OrderInterface;

class DisplayCustomValue extends \Magento\Backend\Block\Template
{
    public function __construct(
        \Magento\Backend\Block\Widget\Context $context,
        OrderInterface $orderInterface,
        array $data = []
    ) {
        $this->orderInterface = $orderInterface;
        parent::__construct($context, $data);
    }

    public function getDelivery()
    {
        $orderId = $this->getRequest()->getParam('order_id');
        $order = $this->orderInterface->load($orderId);

        return $order->getDelivery();
    }
}
