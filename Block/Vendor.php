<?php

namespace ShopForBuild\CustomVendors\Block;


class Vendor extends \Vnecoms\VendorsPriceComparison\Block\Vendor
{
    /**
     * Prepare product data
     *
     * @param \Magento\Catalog\Model\Product $product
     * @return boolean|Ambigous <\Magento\Framework\Model\mixed, multitype:>
     */
    protected function _prepareProductData(\Magento\Catalog\Model\Product $product){
        $vendorId = $product->getVendorId();
        if(!$vendorId) return false;
        if(!isset($this->_vendors[$vendorId])){
            $vendor = $this->_vendorFactory->create();
            $vendor->load($vendorId);
            if(!$vendor->getId()) return false;

            /* Add vendor home page URL if the homepage is installed*/
            if(class_exists('Vnecoms\VendorsPage\Helper\Data')){
                $om = \Magento\Framework\App\ObjectManager::getInstance();
                $helper = $om->create('Vnecoms\VendorsPage\Helper\Data');
                $vendor->setData('pc_home_page',$helper->getUrl($vendor));
            }
            $vendor->setData('pc_title',$this->_configHelper->getVendorConfig('general/store_information/name', $vendorId));
            $vendor->setData('pc_description',$this->_configHelper->getVendorConfig('general/store_information/short_description', $vendorId));
            $vendor->setData('pc_logo_url',$this->getLogoUrl($vendor));
            $vendor->setData('pc_logo_width',$this->getLogoWidth());
            $vendor->setData('pc_logo_height',$this->getLogoHeight());
            $vendor->setData('pc_address',$this->getAddress($vendor));
            $vendor->setData('pc_country_name', $vendor->getCountryName($this->_design->getLocale()));
            $vendor->setData('pc_sales_count',$this->getSalesCount($vendor));
            $vendor->setData(
                'pc_joined_date',
                $this->formatDate($vendor->getCreatedAt(),\IntlDateFormatter::MEDIUM)
            );

            $this->_vendors[$vendorId] = $vendor;
        }

        $productData = $product->getData();
        $productData['final_price'] = $this->priceCurrency->convert($product->getFinalPrice());
        $productData['min_price'] = $this->priceCurrency->convert($product->getMinPrice());
        $productData['minimal_price'] = $this->priceCurrency->convert($product->getMinimalPrice());
        $productData['max_price'] = $this->priceCurrency->convert($product->getMaxPrice());
        $productData['price'] = $this->priceCurrency->convert($product->getPrice());

        $productData['pc_product_url'] = $product->getProductUrl();
        $productData['pc_addtocart_url'] = $this->getAddToCartUrl($product);
        $productData['pc_vendor'] = $this->_vendors[$vendorId]->getData();

        /** Get Vendor Shipping Price
         * return Product Data if have
         */
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();

        $_shipping = $objectManager->create('Vnecoms\VendorsCustomPriceComparison\Model\Api\ShipmentForProductEstimation');
        $_shipping_price = $_shipping->estimateByProductAndAddress($productData['sku']);
        if($_shipping_price < 0) return false ;

        /** End */
       // var_dump("FGDEDD");die(" END");
        return $productData;
    }

    /**
     * (non-PHPdoc)
     * @see \Magento\Framework\View\Element\AbstractBlock::toHtml()
     */
    public function toHtml(){
        //var_dump("FGDEDD");die(" END");
        /** Edit Operator to Get All Products have Shipping Price  */
        if(!$this->getProduct() || !$this->getProducts()) return '';
        /** End */

        return parent::toHtml();
    }
}
