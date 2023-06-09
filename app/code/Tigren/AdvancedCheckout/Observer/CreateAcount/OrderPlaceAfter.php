<?php
/**
 * @author    Tigren Solutions <info@tigren.com>
 * @copyright Copyright (c) 2023 Tigren Solutions <https://www.tigren.com>. All rights reserved.
 * @license   Open Software License ("OSL") v. 3.0
 */

namespace Tigren\AdvancedCheckout\Observer\CreateAcount;

use Magento\Customer\Model\AddressFactory;
use Magento\Customer\Model\CustomerFactory;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

class OrderPlaceAfter implements ObserverInterface
{
    /**
     * @var CustomerFactory
     */
    protected $customerFactory;

    /**
     * @var AddressFactory
     */
    protected $addressFactory;

    public function __construct(
        CustomerFactory $customerFactory,
        AddressFactory $addressFactory,
    ) {
        $this->customerFactory = $customerFactory;
        $this->addressFactory = $addressFactory;
    }

    public function execute(Observer $observer)
    {
        $order = $observer->getEvent()->getOrder();

        if ($order->getCustomerIsGuest()) {

            $shippingAddress = $order->getShippingAddress();

            if ($shippingAddress != null) {

                $password = bin2hex(random_bytes(8));

                $customer = $this->customerFactory->create();
                $customer->setWebsiteId(1);
                $customer->setEmail($shippingAddress->getEmail());
                $customer->setFirstname($shippingAddress->getFirstname());
                $customer->setLastname($shippingAddress->getLastname());
                $customer->setPassword($password);
                $customer->save();

                if ($customer->getId()) {
                    $address = $this->addressFactory->create();
                    $address->setCustomerId($customer->getId());
                    $address->setFirstname($shippingAddress->getFirstname());
                    $address->setLastname($shippingAddress->getLastname());
                    $address->setTelephone($shippingAddress->getTelephone());
                    $address->setCountryId($shippingAddress->getCountryId());
                    $address->setPostcode($shippingAddress->getPostcode());
                    $address->setCity($shippingAddress->getCity());
                    $address->setStreet($shippingAddress->getStreet());
                    $address->setIsDefaultBilling(true);
                    $address->setIsDefaultShipping(true);
                    $address->save();
                    $order->setCustomerId($customer->getId());
                }
            }

            $order->save();
        }
    }
}

