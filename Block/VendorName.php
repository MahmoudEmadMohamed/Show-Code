<?php
namespace ShopForBuild\CustomVendors\Block;

/**
 * Class View
 * @package Vnecoms\Vendors\Block\Profile\Address
 */
class VendorName extends \Vnecoms\Vendors\Block\Profile
{
     /**
     * Get vendor name
     *
     * @return string
     */
    public function getName()
    {
        $name = $this->getVendor()->getName();
        
        return $name;
    }
}
