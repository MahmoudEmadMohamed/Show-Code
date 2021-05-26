<?php


namespace ShopForBuild\CustomVendors\Api;


interface CustomPlaceOrderInterface
{
    /**
     *
     * Get for PlaceOrder Api
     * Update Vendors Credit when Order Status change (place/cancled)
    */

    /**
     * @api
     * Decrease Vendor's Balance if payment for login Customer
     * @return string
    */
    public function afterPlaceOrderCustomer();

    /**
     * @api
     * Decrease Vendor's Balance if payment for guest
     * @param string $email
     * @return string
     */
    public function afterPlaceOrderGuest($email);

}
