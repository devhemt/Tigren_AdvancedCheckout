<?php
/**
 * @author    Tigren Solutions <info@tigren.com>
 * @copyright Copyright (c) 2023 Tigren Solutions <https://www.tigren.com>. All rights reserved.
 * @license   Open Software License ("OSL") v. 3.0
 */

namespace Tigren\AdvancedCheckout\Model;

use Psr\Log\LoggerInterface;
use Magento\Catalog\Model\ProductRepository;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Quote\Model\QuoteFactory;
use function PHPUnit\Framework\isEmpty;


class CheckMultiOrder implements \Tigren\AdvancedCheckout\Api\CheckMultiOrderInterface
{
    protected $logger;
    protected $productRepository;
    protected $searchCriteriaBuilder;
    private $checkoutSession;
    protected $quoteFactory;

    public function __construct(
        LoggerInterface $logger,
        ProductRepository $productRepository,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        QuoteFactory $quoteFactory,
        CheckoutSession $checkoutSession
    ) {
        $this->logger = $logger;
        $this->productRepository = $productRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->quoteFactory = $quoteFactory;
        $this->checkoutSession = $checkoutSession;
    }

    /**
     * @inheritdoc
     */

    public function checkMultiOrder($productId, $attributeCode)
    {
        $response = ['success' => false, 'message' => 'false'];
        $quoteId = $this->checkoutSession->getQuote()->getId();
        $quote = \Magento\Framework\App\ObjectManager::getInstance()
            ->create(\Magento\Quote\Model\Quote::class)
            ->load($quoteId);


        $items = $quote->getAllItems();
        $cartProductIds = array();
        $cartProductQtys = array();


        foreach ($items as $item) {
            $cartProductIds[] = $item->getProductId();
            $cartProductQtys[$item->getProductId()] = $item->getQty();
        }


        $searchCriteria = $this->searchCriteriaBuilder
            ->addFilter('entity_id', $productId)
            ->create();

        $product = $this->productRepository->getList($searchCriteria)->getItems();
        if (!empty($product)) {
            $product = reset($product);
            $customAttributeValue = $product->getData($attributeCode);
            $flag = false;
            if ($cartProductQtys[$productId] > 1 && $customAttributeValue == 0 && $customAttributeValue != null) {
                $flag = true;
            }

            if ($flag) {
                $response = ['success' => true, 'message' => 'success'];
            }

        }

        return json_encode($response);
    }
}
