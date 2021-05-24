require([
    'jquery'
], function ($) {
    'use strict';
    jQuery(document).ready(function(){
        jQuery(document).ajaxStop(function () {
            $('div[data-index="internal_sku"] input').val(customAttribute);
        });
    });
});
