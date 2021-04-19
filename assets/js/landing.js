jQuery(window).ready(function() {
    jQuery('.products_slide > .woocommerce').addClass('swiper-container')
    jQuery('.products_slide > .woocommerce .products').addClass('swiper-wrapper')
    jQuery('.products_slide > .woocommerce .products .product').addClass('swiper-slide')
    jQuery('.products_slide > .woocommerce').append('<div class="swiper-scrollbar"></div>')
    const swiper = new Swiper('.slider_top.swiper-container', {
        slidesPerView: 1,
        loop: true,
        autoplay: {
            delay: 5000,
            disableOnInteraction: false,
        },
        pagination: {
            el: '.swiper-pagination',
            clickable: true,
        },
    });
    const swiper_woo = new Swiper('.woocommerce.swiper-container', {
        slidesPerView: 1,
        scrollbar: {
            el: '.swiper-scrollbar',
            hide: true,
        },
        breakpoints: {
            480: { slidesPerView: 2 },
            768: { slidesPerView: 3 },
            1024: { slidesPerView: 4 },
            1200: { slidesPerView: 5 }
        }
    });
});