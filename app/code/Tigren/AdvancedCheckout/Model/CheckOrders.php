<?php
/**
 * @author    Tigren Solutions <info@tigren.com>
 * @copyright Copyright (c) 2023 Tigren Solutions <https://www.tigren.com>. All rights reserved.
 * @license   Open Software License ("OSL") v. 3.0
 */

namespace Tigren\AdvancedCheckout\Model;

use Tigren\AdvancedCheckout\Api\CheckOrdersInterface;
use Magento\Sales\Model\ResourceModel\Order\Collection;

class CheckOrders implements CheckOrdersInterface
{
    private $orderCollectionFactory;

    /**
     * Orders constructor.
     *
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param OrderRepositoryInterface $orderRepository
     */
    public function __construct(
        Collection $orderCollection,
    ) {
        $this->orderCollection = $orderCollection;
    }

    /**
     * Check if customer has any pending orders
     *
     * @param int $customerId
     * @return bool
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function hasNoCompleteOrder($customerId)
    {
        $customerOrders = $this->orderCollection
            ->addAttributeToFilter('customer_id', $customerId)->load()->getData();

        $orders = array();
        foreach ($customerOrders as $customerOrder) {
            $orders[] = $customerOrder['status'];
        }

        foreach ($orders as $order) {
            if ($order != null) {
                if ($order != "complete" && $order != "canceled") {
                    return true;
                }
            }
        }

        return false;
    }
}
