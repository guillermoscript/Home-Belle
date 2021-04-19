jQuery(window).load(function() {
    jQuery('section.related.products').append('<div class="products_slide"><div class="related_products woocommerce swiper-container"><div class="swiper-scrollbar"></div><div><div>')
    jQuery('section.related.products h2').prependTo('section.related.products .products_slide')
    jQuery('section.related.products ul.products').prependTo('section.related.products .swiper-container')
    jQuery('.woocommerce-product-gallery.swiper-container .flex-control-nav, section.related.products .products').addClass('swiper-wrapper')
    jQuery('.woocommerce-product-gallery.swiper-container .flex-control-nav li, section.related.products .products .product').addClass('swiper-slide')
    // document.querySelector('ol.flex-control-nav.flex-control-thumbs').insertAdjacentHTML('afterend',`
    //     <!-- Add Pagination -->
    //     <div class="swiper-pagination"></div>
    // `)

    const swiper_gallery = new Swiper('.woocommerce-product-gallery.swiper-container', {
        slidesPerView: 3,
        pagination: {
            el: '.swiper-pagination',
            clickable: true,
        },
        breakpoints: {
            400: {
              slidesPerView: 4,
            },
            768: {
              slidesPerView: 5,
              pagination: false
            },
        }
    });

    const swiper_woo = new Swiper('.related_products.swiper-container', {
        slidesPerView: 1,
        // scrollbar: {
        //     el: '.swiper-scrollbar',
        //     hide: true,
        // },
        breakpoints: {
            480: { slidesPerView: 2 },
            768: { slidesPerView: 3 },
            1024: { slidesPerView: 4 },
            1200: { slidesPerView: 5 }
        }
    });
    setTimeout(() => {
        jQuery('.variations select').css('width', '100%').select2()
    }, 1000);

    jQuery(document).ajaxComplete(function(event,request, settings){
        // Your code here
        if (document.querySelector('.woocommerce-notices-wrapper').children.length > 1) {
            for (let i = 0; i < document.querySelector('.woocommerce-notices-wrapper').children.length; i++) {
                const element = document.querySelector('.woocommerce-notices-wrapper').children[i];
                element.remove()
            }
        }
        if (/attribute/ig.test(settings.data)) {
            // jQuery('html').html(request.responseText)
            jQuery('.woocommerce-notices-wrapper').append(jQuery(request.responseText).find('.woocommerce-notices-wrapper').html())
        }
    });
})