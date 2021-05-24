jQuery(document).ready(() => {
    jQuery('#select_cat').select2()
    jQuery('#select_cat').change(function() {
        jQuery('.woocommerce-product-search').attr('action', jQuery(this).val())
    })
    jQuery('.wcmenucart').click(function(e) {
        e.stopPropagation()
        location.href = jQuery(this).attr('href')
    })
    jQuery('header a.mobile-menu').click(() => jQuery('.oceanwp-sidr-overlay').click(() => {
        jQuery('.sidr-class-toggle-sidr-close').removeClass('opened')
        jQuery('html').removeClass('noScroll')
    }))
    jQuery('.sidr-class-menu-item-submenu').click(function(e) {
        e.preventDefault()
        let id = jQuery(this).attr('data-id')
        jQuery(`.sidr-class-hmenu[data-id=0]`).addClass('sidr-class-hmenu-left')
        jQuery(`.sidr-class-hmenu[data-id=${id}]`).removeClass('sidr-class-hmenu-right')
    })
    jQuery('a.sidr-class-menu-item.sidr-class-menu_title').click(function(e) {
        e.preventDefault()
        jQuery(`.sidr-class-hmenu[data-id=0]`).removeClass('sidr-class-hmenu-left')
        jQuery(this).parents('ul.sidr-class-hmenu').addClass('sidr-class-hmenu-right')
    })
    jQuery('.menu_account').click(function(e) {
        e.preventDefault()
        jQuery('#sidr').before('<div class="oceanwp-sidr-overlay"></div>')
        jQuery('#sidr-account').removeClass('account-right')
        remove_menu()
    })

    jQuery('.menu_account, .hamburger-box').click(function(e) {
        jQuery('html,body').addClass('noScroll')
    })

    document.querySelectorAll('.sidr-class-customer-profile, .customer-profile').forEach(el => {
        el.insertAdjacentHTML('beforeend',`
            <svg width="14" height="14" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 512 512" xml:space="preserve" role="img" aria-hidden="true" focusable="false">
                <path d="M505.943,6.058c-8.077-8.077-21.172-8.077-29.249,0L6.058,476.693c-8.077,8.077-8.077,21.172,0,29.249
                    C10.096,509.982,15.39,512,20.683,512c5.293,0,10.586-2.019,14.625-6.059L505.943,35.306
                    C514.019,27.23,514.019,14.135,505.943,6.058z">
                </path>
                <path d="M505.942,476.694L35.306,6.059c-8.076-8.077-21.172-8.077-29.248,0c-8.077,8.076-8.077,21.171,0,29.248l470.636,470.636
                    c4.038,4.039,9.332,6.058,14.625,6.058c5.293,0,10.587-2.019,14.624-6.057C514.018,497.866,514.018,484.771,505.942,476.694z">
                </path>
            </svg>
        `) 
    })

    document.querySelectorAll('.account-menu-cont-icon-close').forEach(el => {
        el.addEventListener('click', help )
    })

    jQuery('.oceanwp-off-canvas-filter').click(() => {
        jQuery('.sidr-class-toggle-sidr-close').removeClass('opened')
        jQuery('html').addClass('noScroll')
    })
    jQuery('.oceanwp-off-canvas-overlay').click(() => {
        jQuery('.sidr-class-toggle-sidr-close').removeClass('opened')
        jQuery('html').removeClass('noScroll')
    })
    jQuery('.sidr-class-customer-profile svg').click(help)
    jQuery('.oceanwp-off-canvas-close').click(help)

    document.querySelectorAll('.footer_col')[1].appendChild(document.getElementById('woocommerce_product_categories-5'))
    document.querySelector('#woocommerce_product_categories-5 .widget-title').remove()
    document.getElementById('woocommerce_product_categories-5').style.display = 'block'
    
})

function help() {
    jQuery('.oceanwp-sidr-overlay').remove()
    jQuery('#sidr-account').addClass('account-right')
    jQuery('html, body').removeClass('noScroll')
    jQuery('.sidr-class-toggle-sidr-close').click()
}


window.onscroll = function() { sticky(); };

function sticky() {
    if (jQuery('body').hasClass('woocommerce-account')) return
    let parent = jQuery('#site-header').parents('#wrap'),
        header = document.querySelector('#site-header'),
        sticky = header.offsetTop;
    if (window.pageYOffset > sticky + 80) {
        parent.css('padding-top', `${jQuery('#site-header').height()}px`)
        header.classList.add('sticky');
    } else {
        header.classList.remove('sticky');
        parent.css('padding-top', '')
    }
}

function remove_menu() {  
    jQuery('.oceanwp-sidr-overlay').click(help)
    jQuery('.customer-profile svg').click(help)
}