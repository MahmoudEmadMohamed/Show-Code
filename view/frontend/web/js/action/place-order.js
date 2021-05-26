/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

/**
 * @api
 */
define([
    'Magento_Checkout/js/model/quote',
    'Magento_Checkout/js/model/url-builder',
    'Magento_Customer/js/model/customer',
    'Magento_Checkout/js/model/place-order',
    'ShopForBuild_CustomVendors/js/model/after-place-order'
], function (quote, urlBuilder, customer, placeOrderService, afterPlaceOrder) {
    'use strict';

    return function (paymentData, messageContainer) {
        var serviceUrl, payload, afterPlaceUrl;

        payload = {
            cartId: quote.getQuoteId(),
            billingAddress: quote.billingAddress(),
            paymentMethod: paymentData
        };

        if (customer.isLoggedIn()) {
            serviceUrl = urlBuilder.createUrl('/carts/mine/payment-information', {});
            afterPlaceUrl = urlBuilder.createUrl('/custom/vendor/order/api/customer', {});
        } else {
            serviceUrl = urlBuilder.createUrl('/guest-carts/:quoteId/payment-information', {
                quoteId: quote.getQuoteId()
            });
            afterPlaceUrl = urlBuilder.createUrl('/custom/vendor/order/api/guest', {});
            payload.email = quote.guestEmail;
        }
        var res = placeOrderService(serviceUrl, payload, messageContainer);

        var res2 = afterPlaceOrder(afterPlaceUrl, payload, messageContainer);

        return res;
    };
});
