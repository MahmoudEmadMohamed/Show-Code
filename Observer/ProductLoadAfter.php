<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace ShopForBuild\CustomVendors\Observer;

use Magento\Framework\Event\ObserverInterface;

class ProductLoadAfter implements ObserverInterface
{
    protected $_request;
    protected $resourceConnection;
    public function __construct(
        \Magento\Framework\App\RequestInterface $request,
        \Magento\Framework\App\ResourceConnection $resourceConnection
    )
    {
        $this->resourceConnection = $resourceConnection;
        $this->_request = $request;
    }
    /**
     * Add multiple vendor order row for each vendor.
     *
     * @param \Magento\Framework\Event\Observer $observer
     * @return self
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {

        $productId = $this->_request->getParam('id');

        if(empty($productId)) {
            //You can apply saving data here like "categoryLoadAfter Observer"
        } else {
            $product = $observer->getEvent()->getProduct();
            $customAttribute = '';
            $customAttribute = $product->getInternalSku();

            $product->setData('internal_sku',$customAttribute);
            echo "<script>var customAttribute = '" . $customAttribute ."';</script>";
        }
    }
}
