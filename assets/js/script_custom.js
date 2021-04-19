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
            <div class="account-menu-cont-icon-close">
                <i class="fas fa-times" style="font-size: 17px;"></i> 
            </div>
        `) 
    })

    document.querySelectorAll('.account-menu-cont-icon-close').forEach(el => {
        el.addEventListener('click', help )
    })
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
}