var config = {
    'config': {
        'mixins': {
            'Magento_Checkout/js/view/shipping': {
                'Freeua_CheckoutSteps/js/view/shipping-payment-mixin': true
            },
            'Magento_Checkout/js/view/payment': {
                'Freeua_CheckoutSteps/js/view/shipping-payment-mixin': true
            }
        }
    }
}