<?php
function home_shortcode(){
    ob_start();
    ?>
    <section id="top">
        <div class="slider_top swiper-container">
            <div class="swiper-wrapper">
                <div class="swiper-slide"><img src="<?php echo home_url( '/wp-content/themes/home&belle/assets/img/slider_1.jpg' ); ?>"></div>
                <div class="swiper-slide"><img src="<?php echo home_url( '/wp-content/themes/home&belle/assets/img/new_collection_1.jpg' ); ?>"></div>
                <div class="swiper-slide"><img src="<?php echo home_url( '/wp-content/themes/home&belle/assets/img/new_collection_3.jpg' ); ?>"></div>
                <div class="swiper-slide"><img src="<?php echo home_url( '/wp-content/themes/home&belle/assets/img/slider_1.jpg' ); ?>"></div>
                <div class="swiper-slide"><img src="<?php echo home_url( '/wp-content/themes/home&belle/assets/img/new_collection_1.jpg' ); ?>"></div>
                <div class="swiper-slide"><img src="<?php echo home_url( '/wp-content/themes/home&belle/assets/img/new_collection_3.jpg' ); ?>"></div>
            </div>
            <div class="swiper-pagination"></div>
        </div>
    </section>
    <section id="featured_products">
        <div class="_container">
            <h3>Los Más Destacados</h3>
            <?php echo do_shortcode( '[best_selling_products limit="8" columns="-1"]' ) //featured_products; ?>
            <div class="products_slide">
                <?php echo do_shortcode( '[recently_viewed_products limit="6"]' ); ?>
            </div>
        </div>
    </section>
    <section id="new-collection-1" class="new_collections">
        <div class="_container">
            <img src="<?php echo home_url( '/wp-content/themes/home&belle/assets/img/nueva_coleccion.jpg' ); ?>" alt="Nueva Colección">
        </div>
            <div class="products_slide">
                <h3>Juego de Cama</h3>
                <?php echo do_shortcode( '[recent_products category="juego-de-cama" limit="6" columns="-1"]' ); ?>
            </div>
    </section>
    <section id="new-collection-2" class="new_collections">
        <div class="_container">
            <img src="<?php echo home_url( '/wp-content/themes/home&belle/assets/img/lo_mas_vendidos.jpg' ); ?>" alt="Los Más Vendidos">
        </div>
        <div class="products_slide">
            <h3>Los Más Vendidos</h3>
            <?php echo do_shortcode( '[best_selling_products limit="6" columns="-1"]' ); ?>
        </div>
    </section>
    <section id="subscribe">
        <div class="_container">
            <h3>Mantente Informado</h3>
            <p>Suscríbete para recibir información actualizada de nuestros productos.</p>
            <form action="">
                <div>
                    <label class="subscribe-text" for="subscribe_field">Buscar por:</label>
                    <input type="email" id="subscribe_field" class="subscribefield"  name="subscribe_field" autocomplete="" placeholder="Escribir correo electrónico">
                </div>
                <button type="submit" value="subscribe">Suscribir</button>
            </form>
        </div>
    </section>
    <?php
    return ob_get_clean();
}