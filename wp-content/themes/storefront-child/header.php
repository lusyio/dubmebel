<?php
/**
 * The header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="content">
 *
 * @package storefront
 */

?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
    <!-- Yandex.Metrika counter -->
    <script type="text/javascript">
        (function (m, e, t, r, i, k, a) {
            m[i] = m[i] || function () {
                (m[i].a = m[i].a || []).push(arguments)
            };
            m[i].l = 1 * new Date();
            k = e.createElement(t), a = e.getElementsByTagName(t)[0], k.async = 1, k.src = r, a.parentNode.insertBefore(k, a)
        })
        (window, document, "script", "https://mc.yandex.ru/metrika/tag.js", "ym");

        ym(66755890, "init", {
            clickmap: true,
            trackLinks: true,
            accurateTrackBounce: true
        });
    </script>
    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-176421783-1"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', 'UA-176421783-1');
    </script>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="yandex-verification" content="0395359e061a0ba6" />
    <link rel="profile" href="http://gmpg.org/xfn/11">
    <link rel="pingback" href="<?php bloginfo('pingback_url'); ?>">
    <?php wp_head(); ?>
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/css/lightbox.min.css"
          integrity="sha512-ZKX+BvQihRJPA8CROKBhDNvoc2aDMOdAlcm7TUQY+35XYtrd3yh95QOOhsPDQY9QnKE0Wqag9y38OIgEvb88cA=="
          crossorigin="anonymous"/>
</head>

<body <?php body_class(); ?>>

<?php do_action('storefront_before_site'); ?>

<div id="page" class="hfeed site">
    <?php do_action('storefront_before_header'); ?>

    <header id="masthead" class="site-header <?= is_front_page() ? 'border-bottom-0' : '' ?>" role="banner"
            style="<?php storefront_header_styles(); ?>">
        <div class="container">
            <nav class="navbar p-0 justify-content-between">
                <div class="navbar-brand">
                    <a class="navbar-brand-logo" href="<?php echo esc_url(home_url('/')); ?>">
                        <img src="/wp-content/themes/storefront-child/svg/logo.svg" alt="">
                        <div>
                            <p class="navbar-brand-logo__name"><span>дуб</span>мебель</p>
                            <p class="navbar-brand-logo__small">Интернет-магазин-мебели</p>
                        </div>
                    </a>
                </div>

                <div class="navbar-phone">
                    <img src="/wp-content/themes/storefront-child/svg/main/phone.svg" alt="">
                    <div>
                        <p>
                            <a href="tel:<?php the_field('phone', 19) ?>">
                                <?php the_field('phone', 19) ?>
                            </a>
                        </p>
                        <p><?php the_field('work_time', 19) ?></p>
                    </div>
                </div>


                <div class="navbar-email">
                    <img src="/wp-content/themes/storefront-child/svg/main/mail.svg" alt="">
                    <div>
                        <p>
                            <a href="mailto:<?= get_field('email', 19) ?>">
                                <?= get_field('email', 19) ?>
                            </a>
                        </p>
                        <p>по любым вопросам</p>
                    </div>
                </div>

                <div class="d-lg-flex d-none">
                    <?php get_search_form() ?>
                    <?php if (class_exists('WooCommerce')): ?>
                        <div class="s-header__basket-wr woocommerce z-5 position-relative">
                            <?php
                            global $woocommerce; ?>
                            <a href="<?php echo $woocommerce->cart->get_cart_url() ?>"
                               class="basket-btn basket-btn_fixed-xs text-decoration-none position-relative">
                        <span class="basket-btn__label"><img src="/wp-content/themes/storefront-child/svg/cart.svg"
                                                             alt=""></span>
                                <?php if (sprintf($woocommerce->cart->cart_contents_count) !== 0): ?>
                                    <span class="basket-btn__counter"><?php echo sprintf($woocommerce->cart->cart_contents_count); ?></span>
                                <?php endif; ?>
                            </a>
                        </div>
                    <?php endif; ?>

                </div>
            </nav>

        </div>
        <hr>
        <div class="container">
            <div class="row">
                <div class="col-lg-4 col-md-6 col-9">
                    <div class="dropdown <?= is_front_page() ? 'show' : '' ?>">
                        <button class="btn btn-catalog dropdown-toggle" type="button" id="dropdownMenuButton"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <img src="/wp-content/themes/storefront-child/svg/catalog.svg" alt=""> Каталог
                        </button>
                        <div class="dropdown-menu <?= is_front_page() ? 'show' : '' ?>"
                             aria-labelledby="dropdownMenuButton">
                            <?= get_categories_list() ?>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-3 m-auto d-lg-none d-flex">
                    <div class="ml-auto d-flex d-lg-none">
                        <?php get_search_form() ?>
                        <?php if (class_exists('WooCommerce')): ?>
                            <div class="s-header__basket-wr woocommerce z-5 d-flex position-relative">
                                <?php
                                global $woocommerce; ?>
                                <a href="<?php echo $woocommerce->cart->get_cart_url() ?>"
                                   class="basket-btn basket-btn_fixed-xs text-decoration-none position-relative">
                        <span class="basket-btn__label"><img src="/wp-content/themes/storefront-child/svg/cart.svg"
                                                             alt=""></span>
                                    <?php if (sprintf($woocommerce->cart->cart_contents_count) !== 0): ?>
                                        <span class="basket-btn__counter"><?php echo sprintf($woocommerce->cart->cart_contents_count); ?></span>
                                    <?php endif; ?>
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="col-lg-8 col-12">
                    <nav class="navbar navbar-light navbar-expand-xl h-100 p-0 justify-content-between">
                        <?php
                        wp_nav_menu(array(
                            'theme_location' => 'primary',
                            'container' => 'div',
                            'container_id' => '',
                            'container_class' => 'collapse navbar-collapse justify-content-start mr-5',
                            'menu_id' => false,
                            'menu_class' => 'navbar-nav m-0 h-100 align-items-center',
                            'depth' => 3,
                            'fallback_cb' => 'wp_bootstrap_navwalker::fallback',
                            'walker' => new wp_bootstrap_navwalker()
                        ));
                        ?>
                        <?php if (is_user_logged_in()) : ?>
                            <a class="registration-link"
                               href="<?php echo get_permalink(get_option('woocommerce_myaccount_page_id')); ?>">Мой
                                аккаунт</a>
                        <?php else: ?>
                            <a class="registration-link"
                               href="<?php echo get_permalink(get_option('woocommerce_myaccount_page_id')); ?>">Вход /
                                Регистрация</a>
                        <?php endif; ?>
                        <div class="outer-menu">
                            <button class="navbar-toggler position-relative" type="button" style="z-index: 1">
                                <span class="navbar-toggler-icon"></span>
                            </button>
                            <input class="checkbox-toggle" data-toggle="collapse" data-target="#main-nav"
                                   aria-controls="" aria-expanded="false" aria-label="Toggle navigation"
                                   type="checkbox"/>
                            <div class="menu">
                                <div>
                                    <div class="border-header">
                                        <?php
                                        wp_nav_menu(array(
                                            'theme_location' => 'primary',
                                            'container' => 'div',
                                            'container_id' => 'main-nav',
                                            'container_class' => 'collapse navbar-collapse justify-content-end',
                                            'menu_id' => false,
                                            'menu_class' => 'navbar-nav',
                                            'depth' => 3,
                                            'fallback_cb' => 'wp_bootstrap_navwalker::fallback',
                                            'walker' => new wp_bootstrap_navwalker()
                                        ));
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </nav>
                </div>
            </div>
        </div>

    </header><!-- #masthead -->

    <script>
        jQuery($ => {
            if (document.documentElement.clientWidth < 991) {
                $('.dropdown .dropdown-menu').removeClass('show')
            }
        })
    </script>

    <?php
    /**
     * Functions hooked in to storefront_before_content
     *
     * @hooked storefront_header_widget_region - 10
     * @hooked woocommerce_breadcrumb - 10
     */
    do_action('storefront_before_content');
    ?>

    <div id="content" class="site-content">
        <div class="container">
            <div class="row">

<?php
do_action('storefront_content_top');
