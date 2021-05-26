/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
/**
 * @api
 */
 define(
    [
        'mage/storage',
        'Magento_Checkout/js/model/error-processor',
        'Magento_Checkout/js/model/full-screen-loader',
        'Magento_Customer/js/customer-data'
    ],
    function (storage, errorProcessor, fullScreenLoader, customerData) {
        'use strict';

        return function (serviceUrl, payload, messageContainer) {
            fullScreenLoader.startLoader();

            return storage.post(
                serviceUrl, JSON.stringify(payload)
            ).fail(
                function (response) {
                    //errorProcessor.process(response, messageContainer);
                }
            ).success(
                function (response) {
                    console.log("Success");
                }
            ).always(
                function () {
                    fullScreenLoader.stopLoader();
                }
            );
        };
    }
);

