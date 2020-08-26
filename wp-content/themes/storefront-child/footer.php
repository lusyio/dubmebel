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

                <?= get_categories_list('footer') ?>
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
                <a href="<?= get_page_link('2439') ?>">Политика конфиденциальности</a>
            </p>
            <p class="mb-0 credits-block">
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/imask/6.0.5/imask.min.js"
        integrity="sha512-0ctiD2feH7vdHZ5jjAKNYts4h+IBRq7Nm5wACMJG1Pj5AQyprSHzX/ijMm77AcLLW5cemQptH+9EcviiKCC0cQ=="
        crossorigin="anonymous"></script>
<script>
    if (typeof (document.getElementById('billing_phone')) != 'undefined' && document.getElementById('billing_phone') != null) {
        const phoneMask = IMask(
            document.getElementById('billing_phone'), {
                mask: '+{7}(000)000-00-00'
            });
    }

    jQuery(function ($) {
        const swiper = new Swiper('.swiper-container', {
            spaceBetween: 15,
            slidesPerView: 3,
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },

            breakpoints: {
                640: {
                    slidesPerView: 3,
                    spaceBetween: 15,
                },
                768: {
                    slidesPerView: 4,
                    spaceBetween: 15,
                },
                1024: {
                    slidesPerView: 5,
                    spaceBetween: 15,
                },
            }
        });

        $('.single input[type=phone]').on('keyup', function () {
            let newString = $(this).val().replace(/\D/g, '')
            const btn = $(this).parents('.single').find('input[type=submit]')
            if (newString.length === 11) {
                $(this).removeClass('is-invalid')
                $(this).addClass('is-valid')
                btn.prop('disabled', false)
            } else {
                $(this).addClass('is-invalid')
                $(this).removeClass('is-valid')
                btn.prop('disabled', true)
            }
        })

        $('.search-form button').on('click', function () {
            if ($('.search-form input').val() === '') {
                return false
            }
        })
    })
</script>

<?php do_action('storefront_after_footer'); ?>

</div><!-- #page -->

<?php wp_footer(); ?>
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
<noscript>
    <div><img src="https://mc.yandex.ru/watch/66755890" style="position:absolute; left:-9999px;" alt=""/></div>
</noscript>
<!-- /Yandex.Metrika counter -->

</body>
</html>
