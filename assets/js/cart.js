jQuery(document).ready(function() {
    jQuery(document.body).on('updated_cart_totals', () => {
        jQuery("[name='update_cart']").removeAttr('disabled');
        setTimeout(() => update_button_qty(), 10);
    })
    jQuery('div.woocommerce').on('change', '.qty', () => jQuery("[name='update_cart']").trigger("click"))
    update_button_qty()
    // document.querySelectorAll('.woocommerce-remove-coupon').forEach(e => e.innerText = 'X')
    moveDivInMovile()
})

const update_button_qty = () => {
    jQuery('.shop_table ._actions a').click(function() { action_cart(this) })
    jQuery('#cc_cart .woocommerce-cart-form .shop_table tbody tr td.product-quantity .plus').html('<i class="fas fa-caret-right"></i>')
    jQuery('#cc_cart .woocommerce-cart-form .shop_table tbody tr td.product-quantity .minus').html('<i class="fas fa-caret-left"></i>')
    jQuery('#cc_cart .woocommerce-cart-form .shop_table tbody tr td.product-quantity a').css('display', 'block')
    
}

function moveDivInMovile() {
    document.querySelectorAll('.woocommerce-cart-form__cart-item.cart_item ._product').forEach(el => {
        el.children[0].append(el.children[1])
    })
}

function action_cart(element){
    if( !jQuery(element).attr('data-type') ) return
    jQuery.ajax({
        url: cart_ajax.url,
        type: 'post',
        data: {
            action: cart_ajax.action,
            type: jQuery(element).attr('data-type'),
            value: jQuery(element).attr('data-value'),
            cart_item: jQuery(element).attr('data-cart_item'),
            product_id: jQuery(element).attr('data-product_id'),
            variation_id: jQuery(element).attr('data-variation_id'),
        },
        success: function(data) {
            console.log(data)
            jQuery("[name='update_cart']").removeAttr('disabled')
            jQuery("[name='update_cart']").trigger("click")
        }
    });
}