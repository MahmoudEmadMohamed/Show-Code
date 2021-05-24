<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace ShopForBuild\CustomVendors\Observer;

use Magento\Framework\Event\ObserverInterface;

class ProductSaveBefore implements ObserverInterface
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
        try{
            /**
             * get Configurable Product Data from Request
             * @param Internal SKU received in Request and set to All Simple Products
             */
            $_Product = $this->_request->getParam('product');//Product Data

            $internal_sku = $_Product['internal_sku'];//Internal SKU

            $suffix = explode(" ",$observer->getEvent()->getproduct()->getData('sku'));

            if(isset($suffix[1]))$internal_sku = $_Product['internal_sku'] . $suffix[1];

            $observer->getEvent()->getproduct()->setData('internal_sku',$internal_sku);

        }catch(Excpetion $e){
            Mage::log(print_r($e->getMessage(),1),'null','mage32173.log');
        }

        return;

    }
}
