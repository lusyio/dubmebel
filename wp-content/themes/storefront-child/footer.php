<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after
 *
 * @package storefront
 */

?>

</div><!-- .row -->
</div><!-- .container -->

<?php do_action('storefront_before_footer'); ?>

<footer id="colophon" class="site-footer" role="contentinfo">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center col-lg-4 text-lg-left footer-logo mb-lg-0 mb-4">
                <div class="site-info">
                    <a class="navbar-brand-logo" href="<?php echo esc_url(home_url('/')); ?>">
                        <img src="/wp-content/themes/storefront-child/svg/logo.svg" alt="">
                        <div>
                            <p class="navbar-brand-logo__name"><span>дуб</span>мебель</p>
                            <p class="navbar-brand-logo__small">Интернет-магазин-мебели</p>
                        </div>
                    </a>
                    <p class="footer-phone">
                        <a href="tel:<?php the_field('phone', 19) ?>">
                            <?php the_field('phone', 19) ?>
                        </a>
                    </p>
                    <p class="footer-email">
                        <a href="mailto:<?= idn_to_utf8(get_field('email', 19)) ?>">
                            <?= idn_to_utf8(get_field('email', 19)) ?>
                        </a>
                    </p>
                </div>
            </div>
            <div class="col-12 text-center col-lg-3 text-lg-left mb-lg-0 mb-4">
                <p class="footer-menu__header">ДубМебель</p>
                <?php
                if ($menu_items = wp_get_nav_menu_items('second')) {
                    $menu_list = '';
                    echo '<div class=" text-center text-lg-left">';
                    echo '<div class="footer-menu">';
                    echo '<ul class="menu" id="menu-second">';
                    foreach ((array)$menu_items as $key => $menu_item) {
                        $title = $menu_item->title; // заголовок элемента меню (анкор ссылки)
                        $url = $menu_item->url; // URL ссылки
                        echo '<li class="mb-lg-3 mb-3"><a href="' . $url . '">' . $title . '</a></li>';
                    }
                    echo '</ul>';
                    echo '</div>';
                    echo '</div>';
                }
                ?>
            </div>

            <div class="col-12 text-center col-lg-5 text-lg-left mb-lg-0 mb-4">
                <p class="footer-menu__header">Продукция</p>

                <div class="row">
                    <?php
                    if ($menu_items = wp_get_nav_menu_items('second')) {
                        $menu_list = '';
                        echo '<div class="col-12 text-center col-md-6 text-lg-left">';
                        echo '<div class="footer-menu">';
                        echo '<ul class="menu" id="menu-second">';
                        $menu_number = 0;
                        foreach ((array)$menu_items as $key => $menu_item) {
                            $title = $menu_item->title; // заголовок элемента меню (анкор ссылки)
                            $url = $menu_item->url; // URL ссылки
                            if ($menu_number < 4) {
                                echo '<li class="mb-lg-3 mb-3"><a href="' . $url . '">' . $title . '</a></li>';
                            } else {
                                echo '</ul>';
                                echo '</div>';
                                echo '</div>';
                                echo '<div class="col-12 text-center col-md-6 text-lg-left">';
                                echo '<div class="footer-menu">';
                                echo '<ul class="menu" id="menu-second_1">';
                                echo '<li class="mb-lg-3 mb-3"><a href="' . $url . '">' . $title . '</a></li>';
                            }
                            $menu_number++;
                        }
                        echo '</ul>';
                        echo '</div>';
                        echo '</div>';
                    }
                    ?>
                </div>
            </div>

        </div>
    </div>
    <hr>
    <div class="container">
        <div class="d-flex justify-content-between">
            <p class="footer-name-p">
                <?php esc_url(bloginfo('name')); ?> - <?php bloginfo('description'); ?> &copy; <?php echo date('Y'); ?>.
                Все
                права защищены
                /
                <a href="">Политика конфиденциальности</a>
            </p>
            <p class="mb-0">
                <a class="credits" href="https://richbee.ru/" target="_blank"><img
                            src="/wp-content/themes/storefront-child/svg/Richbee-black.svg" alt=""></a>
            </p>
        </div>
    </div>


    <div class="col-full">

        <?php
        /**
         * Functions hooked in to storefront_footer action
         *
         * @hooked storefront_footer_widgets - 10
         * @hooked storefront_credit         - 20
         */
        do_action('storefront_footer');
        ?>

    </div><!-- .col-full -->
</footer><!-- #colophon -->

<?php do_action('storefront_after_footer'); ?>

</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
