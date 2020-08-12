<?php
/**
 * Richbee functions and definitions
 *
 * Storefront automatically loads the core CSS even if using a child theme as it is more efficient
 * than @importing it in the child theme style.css file.
 *
 * Uncomment the line below if you'd like to disable the Storefront Core CSS.
 *
 * If you don't plan to dequeue the Storefront Core CSS you can remove the subsequent line and as well
 * as the sf_child_theme_dequeue_style() function declaration.
 */
//add_action( 'wp_enqueue_scripts', 'sf_child_theme_dequeue_style', 999 );
/**
 * Dequeue the Storefront Parent theme core CSS
 */
function sf_child_theme_dequeue_style()
{
    wp_dequeue_style('storefront-style');
    wp_dequeue_style('storefront-woocommerce-style');
}

/**
 * Note: DO NOT! alter or remove the code above this text and only add your custom PHP functions below this text.
 */
function enqueue_child_theme_styles()
{
    wp_enqueue_script('jquery');
// load bootstrap css
    wp_enqueue_style('bootstrap-css', get_stylesheet_directory_uri() . '/inc/assets/css/bootstrap.min.css', false, NULL, 'all');
// fontawesome cdn
    wp_enqueue_style('wp-bootstrap-pro-fontawesome-cdn', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.11.2/css/fontawesome.min.css');
// load bootstrap css
// load AItheme styles
// load WP Bootstrap Starter styles
    wp_enqueue_style('wp-bootstrap-starter-style', get_stylesheet_uri(), array('theme'));
    if (get_theme_mod('theme_option_setting') && get_theme_mod('theme_option_setting') !== 'default') {
        wp_enqueue_style('wp-bootstrap-starter-' . get_theme_mod('theme_option_setting'), get_template_directory_uri() . '/inc/assets/css/presets/theme-option/' . get_theme_mod('theme_option_setting') . '.css', false, '');
    }
    wp_enqueue_style('wp-bootstrap-starter-robotoslab-roboto', 'https://fonts.googleapis.com/css?family=Roboto:300,400,700&display=swap');


    // Internet Explorer HTML5 support
    wp_enqueue_script('html5hiv', get_template_directory_uri() . '/inc/assets/js/html5.js', array(), '3.7.0', false);
    wp_script_add_data('html5hiv', 'conditional', 'lt IE 9');

// load swiper js and css
//    wp_enqueue_script('wp-swiper-js', get_stylesheet_directory_uri() . '/inc/assets/js/swiper.min.js', array(), '', true);
//    wp_enqueue_style('wp-swiper-js', get_stylesheet_directory_uri() . '/inc/assets/css/swiper.min.css', array(), '', true);

// load bootstrap js
    wp_enqueue_script('wp-bootstrap-starter-popper', get_stylesheet_directory_uri() . '/inc/assets/js/popper.min.js', array(), '', true);
    wp_enqueue_script('wp-bootstrap-starter-bootstrapjs', get_stylesheet_directory_uri() . '/inc/assets/js/bootstrap.min.js', array(), '', true);
    wp_enqueue_script('wp-bootstrap-starter-themejs', get_stylesheet_directory_uri() . '/inc/assets/js/theme-script.min.js', array(), '', true);
    wp_enqueue_script('wp-bootstrap-starter-skip-link-focus-fix', get_stylesheet_directory_uri() . '/inc/assets/js/skip-link-focus-fix.min.js', array(), '', true);

    if (is_singular() && comments_open() && get_option('thread_comments')) {
        wp_enqueue_script('comment-reply');
    }
//enqueue my child theme stylesheet
    wp_enqueue_style('child-style', get_stylesheet_uri(), array('theme'));
}

add_action('wp_enqueue_scripts', 'enqueue_child_theme_styles', PHP_INT_MAX);

remove_action('wp_head', 'feed_links_extra', 3); // убирает ссылки на rss категорий
remove_action('wp_head', 'feed_links', 2); // минус ссылки на основной rss и комментарии
remove_action('wp_head', 'rsd_link');  // сервис Really Simple Discovery
remove_action('wp_head', 'wlwmanifest_link'); // Windows Live Writer
remove_action('wp_head', 'wp_generator');  // скрыть версию wordpress

/**
 * Load custom WordPress nav walker.
 */
if (!class_exists('wp_bootstrap_navwalker')) {
    require_once(get_stylesheet_directory() . '/inc/wp_bootstrap_navwalker.php');
}

/**
 * Удаление json-api ссылок
 */
remove_action('wp_head', 'rest_output_link_wp_head');
remove_action('wp_head', 'wp_oembed_add_discovery_links');
remove_action('template_redirect', 'rest_output_link_header', 11);

/**
 * Cкрываем разные линки при отображении постов блога (следующий, предыдущий, короткий url)
 */
remove_action('wp_head', 'start_post_rel_link', 10);
remove_action('wp_head', 'index_rel_link');
remove_action('wp_head', 'adjacent_posts_rel_link_wp_head', 10);
remove_action('wp_head', 'wp_shortlink_wp_head', 10);

/**
 * `Disable Emojis` Plugin Version: 1.7.2
 */
if ('Отключаем Emojis в WordPress') {

    /**
     * Disable the emoji's
     */
    function disable_emojis()
    {
        remove_action('wp_head', 'print_emoji_detection_script', 7);
        remove_action('admin_print_scripts', 'print_emoji_detection_script');
        remove_action('wp_print_styles', 'print_emoji_styles');
        remove_action('admin_print_styles', 'print_emoji_styles');
        remove_filter('the_content_feed', 'wp_staticize_emoji');
        remove_filter('comment_text_rss', 'wp_staticize_emoji');
        remove_filter('wp_mail', 'wp_staticize_emoji_for_email');
        add_filter('tiny_mce_plugins', 'disable_emojis_tinymce');
        add_filter('wp_resource_hints', 'disable_emojis_remove_dns_prefetch', 10, 2);
    }

    add_action('init', 'disable_emojis');

    /**
     * Filter function used to remove the tinymce emoji plugin.
     *
     * @param array $plugins
     * @return   array             Difference betwen the two arrays
     */
    function disable_emojis_tinymce($plugins)
    {
        if (is_array($plugins)) {
            return array_diff($plugins, array('wpemoji'));
        }

        return array();
    }

    /**
     * Remove emoji CDN hostname from DNS prefetching hints.
     *
     * @param array $urls URLs to print for resource hints.
     * @param string $relation_type The relation type the URLs are printed for.
     * @return array                 Difference betwen the two arrays.
     */
    function disable_emojis_remove_dns_prefetch($urls, $relation_type)
    {

        if ('dns-prefetch' == $relation_type) {

            // Strip out any URLs referencing the WordPress.org emoji location
            $emoji_svg_url_bit = 'https://s.w.org/images/core/emoji/';
            foreach ($urls as $key => $url) {
                if (strpos($url, $emoji_svg_url_bit) !== false) {
                    unset($urls[$key]);
                }
            }

        }

        return $urls;
    }

}

/**
 * Удаляем стили для recentcomments из header'а
 */
function remove_recent_comments_style()
{
    global $wp_widget_factory;
    remove_action('wp_head', array($wp_widget_factory->widgets['WP_Widget_Recent_Comments'], 'recent_comments_style'));
}

add_action('widgets_init', 'remove_recent_comments_style');

/**
 * Удаляем ссылку на xmlrpc.php из header'а
 */
remove_action('wp_head', 'wp_bootstrap_starter_pingback_header');

/**
 * Удаляем стили для #page-sub-header из  header'а
 */
remove_action('wp_head', 'wp_bootstrap_starter_customizer_css');

/*
*Обновление количества товара
*/
add_filter('woocommerce_add_to_cart_fragments', 'header_add_to_cart_fragment');

function header_add_to_cart_fragment($fragments)
{
    global $woocommerce;
    ob_start();
    ?>
    <span class="basket-btn__counter"><?php echo sprintf($woocommerce->cart->cart_contents_count); ?></span>
    <?php
    $fragments['.basket-btn__counter'] = ob_get_clean();
    return $fragments;
}

/**
 * Замена надписи на кнопке Добавить в корзину
 */
add_filter('woocommerce_product_single_add_to_cart_text', 'woocust_change_label_button_add_to_cart_single');
function woocust_change_label_button_add_to_cart_single($label)
{

    $label = 'Добавить в корзину';

    return $label;
}


add_filter('woocommerce_checkout_fields', 'custom_override_checkout_fields');
/**
 * Remove field from checkout
 * @param $fields
 * @return mixed
 */
function custom_override_checkout_fields($fields)
{
//    unset($fields['billing']['billing_first_name']);
//    unset($fields['billing']['billing_last_name']);
    unset($fields['billing']['billing_company']);
//    unset($fields['billing']['billing_address_1']);
    unset($fields['billing']['billing_address_2']);
    unset($fields['billing']['billing_city']);
//    unset($fields['billing']['billing_postcode']);
//    unset($fields['billing']['billing_country']);
    unset($fields['billing']['billing_state']);
//    unset($fields['billing']['billing_phone']);
    unset($fields['order']['order_comments']);
//    unset($fields['billing']['billing_email']);
    unset($fields['account']['account_username']);
    unset($fields['account']['account_password']);
    unset($fields['account']['account_password-2']);
    return $fields;
}

add_action('woocommerce_before_order_notes', 'add_custom_checkout_field');

add_filter('woocommerce_checkout_fields', 'override_billing_checkout_fields', 20, 1);
/**
 * Override fields from checkout
 * @param $fields
 * @return mixed
 */
function override_billing_checkout_fields($fields)
{
    $fields['billing']['billing_phone']['placeholder'] = 'Номер телефона';
    $fields['billing']['billing_email']['placeholder'] = 'Email';
    $fields['billing']['billing_postcode']['placeholder'] = 'Индекс';
    $fields['billing']['billing_last_name']['placeholder'] = 'Фамилия';
    $fields['billing']['billing_first_name']['placeholder'] = 'Имя';
    $fields['billing']['billing_address_1']['placeholder'] = 'Город, улица, номер дома и квартиры';
    return $fields;
}

remove_action('storefront_footer', 'storefront_credit', 20);

/**
 * Remove product data tabs
 */
add_filter('woocommerce_product_tabs', 'woo_remove_product_tabs', 98);

function woo_remove_product_tabs($tabs)
{

    unset($tabs['description']);        // Remove the description tab
    unset($tabs['reviews']);            // Remove the reviews tab
    unset($tabs['additional_information']);    // Remove the additional information tab

    return $tabs;
}

//Количество товаров для вывода на странице магазина
add_filter('loop_shop_per_page', 'wg_view_all_products');

function wg_view_all_products()
{
    return '9999';
}

//Удаление сортировки
add_action('init', 'bbloomer_delay_remove');

function bbloomer_delay_remove()
{
    remove_action('woocommerce_after_shop_loop', 'woocommerce_catalog_ordering', 10);
    remove_action('woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 10);
    remove_action('woocommerce_before_shop_loop', 'woocommerce_result_count', 20);
    remove_action('woocommerce_after_shop_loop', 'woocommerce_result_count', 20);

}

/*
*Изменение количетсва товара на строку на страницах woo
*/
add_filter('loop_shop_columns', 'loop_columns');
if (!function_exists('loop_columns')) {
    function loop_columns()
    {
        return 3; // 3 products per row
    }
}


//Удаление сторфронт-кредит
add_action('init', 'custom_remove_footer_credit', 10);

function custom_remove_footer_credit()
{
    remove_action('storefront_footer', 'storefront_credit', 20);
    add_action('storefront_footer', 'custom_storefront_credit', 20);
}


//Добавление favicon
function favicon_link()
{
    echo '<link rel="shortcut icon" type="image/x-icon" href="/favicon.png" />' . "\n";
}

add_action('wp_head', 'favicon_link');

//Изменение entry-content
function storefront_page_content()
{
    ?>
    <div class="row">
        <?php the_content(); ?>
        <?php
        wp_link_pages(
            array(
                'before' => '<div class="page-links">' . __('Pages:', 'storefront'),
                'after' => '</div>',
            )
        );
        ?>
    </div>
    <?php
}

add_filter('woocommerce_sale_flash', 'my_custom_sale_flash', 10, 3);
function my_custom_sale_flash($text, $post, $_product)
{
    return '<span class="onsale">SALE!</span>';
}

// Колонки related
add_filter('woocommerce_output_related_products_args', 'jk_related_products_args');
function jk_related_products_args($args)
{
    $args['posts_per_page'] = 6; // количество "Похожих товаров"
    $args['columns'] = 4; // количество колонок
    return $args;
}

/**
 * Render popular products
 *
 * @return false|string
 */
function get_popular_products()
{
    $args = array(
        'post_type' => array('product'),
        'meta_key' => 'total_sales',
        'orderby' => 'meta_value_num',
        'order' => 'desc'
    );
    $products = wc_get_products($args);
    ob_start();
    ?>
    <div id="popularProducts" class="products-list">
        <div class="container">
            <p class="products-list__header">Популярные товары</p>
            <div class="row">
                <?php
                foreach ($products as $product):
                    $image_id = $product->get_image_id();
                    ?>
                    <div class="col-lg-3 col-12">
                        <a class="card-product-link" href="<?= get_permalink($product->id) ?>">
                            <div class="card-product">
                                <div class="card-product__header">
                                    <div class="card-product__hover">
                                        <img src="<?= wp_get_attachment_image_url($image_id, 'full'); ?>"
                                             alt="<?= $product->name; ?>">
                                    </div>
                                    <p class="card-product__title"><?= $product->name; ?>
                                    <p class="card-product__price"><?= $product->get_price_html(); ?></p>
                                </div>
                            </div>
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
    <?php
    return ob_get_clean();
}


add_filter('woocommerce_get_price_html', 'new_price_html', 100, 2);

/**
 * Change to custom price html
 *
 * @param $price
 * @param $product
 * @return string
 */
function new_price_html($price, $product)
{
    if ($product->regular_price !== $product->price) {
        $sale = round(100 - $product->price / $product->regular_price * 100);
        return '<span class="now-price-sale">' . $product->price . '<span class="woocommerce-Price-currencySymbol"> ' . get_woocommerce_currency_symbol() . '</span>' . '</span>' .
            ' <del> <span class="woocommerce-Price-amount amount">' . $product->regular_price .
            '<span class="woocommerce-Price-currencySymbol"> ' . get_woocommerce_currency_symbol() . '</span>' . '</span></del>' .
            ' <span class="sale-badge">' . $sale . '%</span>';
    }

    return '<span class="now-price">' . $product->price . '<span class="woocommerce-Price-currencySymbol">'
        . get_woocommerce_currency_symbol() . '</span></span>';
}

/**
 * Render category list for dropdown
 *
 * @param string $type
 * @return false|string
 */
function get_categories_list($type = '')
{
    $args = array(
        'taxonomy' => "product_cat",
    );
    $categories = get_terms($args);
    ob_start();
    if ($type === 'footer'):?>
        <div class="row">
            <?php
            if (count($categories) !== 0) {
                echo '<div class="col-12 text-center col-md-6 text-lg-left">';
                echo '<div class="footer-menu">';
                echo '<ul class="menu" id="menu-second">';
                $menu_number = 0;
                foreach ($categories as $key => $category) {
                    $title = $category->name; // заголовок элемента меню (анкор ссылки)
                    $url = get_term_link($category->term_id, 'product_cat'); // URL ссылки
                    if ($menu_number < 4) {
                        echo '<li class="mb-lg-3 mb-3"><a href="' . $url . '">' . $title . '</a></li>';
                    } else {
                        $menu_number = 0;
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
    <?php endif;

    if ($type === 'grid'):?>
        <div class="categories">
            <div class="container">
                <div class="categories-grid">
                    <?php foreach ($categories as $category):
                        if ($category->parent === 0):
                            $thumbnail_id = get_term_meta($category->term_id, 'thumbnail_id', true);
                            $image = wp_get_attachment_url($thumbnail_id); ?>
                            <a href="<?= get_term_link($category->term_id, 'product_cat') ?>"
                               class="categories-grid__item categories-grid__item--<?= $category->slug ?>">
                                <div style="background-image: url('<?= $image ?>')">
                                </div>
                                <p>
                                    <span class="categories-grid__name"><?= $category->name ?></span>
                                    <span class="categories-grid__price">от 4 990 ₽</span>
                                </p>
                            </a>
                        <?php endif;
                    endforeach; ?>
                </div>
            </div>
        </div>
    <?php
    endif;
    if ($type === ''): ?>
        <ul class="dropdown-category-list">
            <?php foreach ($categories as $category):
                $icon = '';
                switch ($category->slug) {
                    case 'antique-furniture':
                        $icon = '/wp-content/themes/storefront-child/svg/category-icons/antique-furniture.svg';
                        break;
                    case 'chairs':
                        $icon = '/wp-content/themes/storefront-child/svg/category-icons/chairs.svg';
                        break;
                    case 'lunch-groups':
                        $icon = '/wp-content/themes/storefront-child/svg/category-icons/lunch-groups.svg';
                        break;
                    case 'rattan-furniture':
                        $icon = '/wp-content/themes/storefront-child/svg/category-icons/rattan-furniture.svg';
                        break;
                    case 'stools':
                        $icon = '/wp-content/themes/storefront-child/svg/category-icons/stools.svg';
                        break;
                    case 'tables':
                        $icon = '/wp-content/themes/storefront-child/svg/category-icons/tables.svg';
                        break;
                    case 'tabletops':
                        $icon = '/wp-content/themes/storefront-child/svg/category-icons/tabletops.svg';
                        break;
                }
                $child_args = array(
                    'taxonomy' => 'product_cat',
                    'hide_empty' => true,
                    'parent' => $category->term_id
                );
                $child_product_cats = get_terms($child_args);
                if ($category->parent === 0):
                    ?>
                    <li>
                        <a href="<?= get_term_link($category->term_id, 'product_cat') ?>">
                            <div>
                                <img class="dropdown-category-list__icon"
                                     src="<?= $icon ?>"
                                     alt="<?= $category->name ?>">
                                <span class="dropdown-category-list__name"><?= $category->name ?></span>
                            </div>
                            <img src="/wp-content/themes/storefront-child/svg/next.svg" alt="">
                        </a>
                        <?php if (count($child_product_cats) !== 0): ?>
                            <div class="dropdown-category-list-sublist">
                                <ul>
                                    <?php foreach ($child_product_cats as $child_product_cat): ?>
                                        <li>
                                            <a href="<?= get_term_link($child_product_cat->term_id, 'product_cat') ?>">
                                                <?= $child_product_cat->name ?>
                                            </a>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        <?php endif; ?>
                    </li>
                <?php
                endif;
            endforeach; ?>
        </ul>
    <?php
    endif;
    return ob_get_clean();
}

/**
 * get post gallery images with info
 *
 * @param null $postvar
 * @param int $pos
 * @return array
 */
function get_post_gallery_images_with_info($postvar = NULL, $pos = 0)
{
    if (!isset($postvar)) {
        global $post;
        $postvar = $post;
    }
    $post_content = $postvar->post_content;
    if ($pos) {
        $post_content = preg_split('~\(:\)~', $post_content)[1];
    }
    preg_match('/\[gallery.*ids=.(.*).]/', $post_content, $ids);
    $images_id = explode(",", $ids[1]);
    $image_gallery_with_info = array();
    foreach ($images_id as $image_id) {
        $attachment = get_post($image_id);
        $image_gallery_with_info[] = array(
            'alt' => get_post_meta($attachment->ID, '_wp_attachment_image_alt', true),
            'caption' => $attachment->post_excerpt,
            'description' => $attachment->post_content,
            'href' => get_permalink($attachment->ID),
            'src' => $attachment->guid,
            'title' => $attachment->post_title
        );
    }
    return $image_gallery_with_info;
}

/**
 * render slider by gallery images
 *
 * @return false|string
 */
function get_slider_from_library_page()
{
    $gallery = get_post_gallery_images_with_info();
    $i = 0;
    $j = 0;
    ob_start(); ?>
    <div class="slider-container">
        <div id="carouselExampleIndicators" class="carousel slide carousel-fade wow fadeIn"
             data-ride="carousel">
            <ol class="carousel-indicators">
                <?php foreach ($gallery as $image_obj) : ?>
                    <li data-target="#carouselExampleIndicators" data-slide-to="<?= $j ?>"
                        class="<?= $j === 0 ? 'active' : '' ?>"></li>
                    <?php
                    $j++;
                endforeach; ?>
            </ol>
            <div class="carousel-inner">
                <?php
                foreach ($gallery as $image_obj) :
                    ?>
                    <div class="carousel-item <?= $i === 0 ? 'active' : '' ?>">
                        <img src="<?= $image_obj['src'] ?>" class="d-block w-100"
                             alt="Gallery image"/>
                        <div class="carousel-item-text">
                            <p class="carousel-item-text__title"><?= $image_obj['title'] ?></p>
                            <p class="carousel-item-text__caption"><?= $image_obj['caption'] ?></p>
                            <p class="carousel-item-text__description"><?= $image_obj['description'] ?></p>
                        </div>
                    </div>
                    <?php
                    $i++;
                endforeach;
                ?>
            </div>
        </div>
    </div>
    <?php
    return ob_get_clean();
}

/**
 * Render pagination for woocommerce
 *
 * @return false|string
 */
function get_pagination_woo()
{
    $total = isset($total) ? $total : wc_get_loop_prop('total_pages');
    $current = isset($current) ? $current : wc_get_loop_prop('current_page');
    $base = isset($base) ? $base : esc_url_raw(str_replace(999999999, '%#%', remove_query_arg('add-to-cart', get_pagenum_link(999999999, false))));
    $format = isset($format) ? $format : '';

    if ($total <= 1) {
        return '';
    }
    ob_start(); ?>
    <nav class="woocommerce-pagination">
        <?php
        echo paginate_links(
            apply_filters(
                'woocommerce_pagination_args',
                array( // WPCS: XSS ok.
                    'base' => $base,
                    'format' => $format,
                    'add_args' => false,
                    'current' => max(1, $current),
                    'total' => $total,
                    'prev_text' => '&larr;',
                    'next_text' => '&rarr;',
                    'type' => 'list',
                    'end_size' => 3,
                    'mid_size' => 3,
                )
            )
        );
        ?>
    </nav>
    <?php
    return ob_get_clean();
}

/**
 * Get products by category slug
 * @param string $slug
 * @return false|string
 */
function get_products_by_category_slug($slug = '')
{
    if ($slug) {
        global $paged;
        $args = array(
            'category' => array($slug),
            'limit' => -1,
            'posts_per_page' => 9,
            'pagination' => true,
            'page' => $paged
        );
    } else {
        $args = [];
    }
    $products = wc_get_products($args);
    ob_start();
    ?>
    <div class="row products-list">
        <?php
        foreach ($products as $product):
            $image_id = $product->get_image_id();
            ?>
            <div class="col-lg-4 col-12">
                <a class="card-product-link" href="<?= get_permalink($product->id) ?>">
                    <div class="card-product">
                        <div class="card-product__header">
                            <div class="card-product__hover">
                                <img src="<?= wp_get_attachment_image_url($image_id, 'full') ?>"
                                     alt="<?= $product->name ?>">
                            </div>
                            <p class="card-product__title"><?= $product->name ?>
                            <p class="card-product__price"><?= $product->get_price_html() ?></p>
                        </div>
                    </div>
                </a>
            </div>
        <?php
        endforeach;
        ?>
    </div>
    <?php
    return ob_get_clean();
}

/**
 * rewrite default function woocommerce_content
 */
function woocommerce_content()
{
    if (is_singular('product')) {

        while (have_posts()) :
            the_post();
            wc_get_template_part('content', 'single-product');
        endwhile;

    } else {
        ?>

        <?php if (woocommerce_product_loop()) : ?>

            <?php do_action('woocommerce_before_shop_loop'); ?>

            <?php woocommerce_product_loop_start(); ?>

            <?php if (wc_get_loop_prop('total')) : ?>
                <?php the_post(); ?>
                <?php wc_get_template_part('content', 'product_cat'); ?>
            <?php endif; ?>

            <?php woocommerce_product_loop_end(); ?>

            <?php do_action('woocommerce_after_shop_loop'); ?>

        <?php
        else :
            do_action('woocommerce_no_products_found');
        endif;
    }
}

/**
 * Render categories with subcategories. Subcategories only for active category
 *
 * @param $active_cat_ID
 */
function get_categories_with_subcategories($active_cat_ID)
{
    $args = array(
        'taxonomy' => 'product_cat',
        'hide_empty' => true,
        'parent' => 0
    );
    $product_cats = get_terms($args);
    foreach ($product_cats as $product_cat) {
        echo '<ul class="category-list">';
        if ($active_cat_ID === $product_cat->term_id) {
            $active = 'active';
        } else {
            $active = '';
        }
        $child_args = array(
            'taxonomy' => 'product_cat',
            'hide_empty' => true,
            'parent' => $product_cat->term_id
        );
        $child_product_cats = get_terms($child_args);
        $isChildActive = false;
        $activeSub = '';
        foreach ($child_product_cats as $child_product_cat) {
            if ($active_cat_ID === $child_product_cat->term_id && $product_cat->term_id === $child_product_cat->parent) {
                $isChildActive = true;
                $activeSub = 'active';
            }
        }
        $link = get_term_link($product_cat->slug, $product_cat->taxonomy);
        echo '<li class="' . $active . '"><a href="' . $link . '">' . $product_cat->name . '</a></li>';
        if ((count($child_product_cats) !== 0 && $active_cat_ID === $product_cat->term_id) || (count($child_product_cats) !== 0 && $isChildActive)) {
            echo '<ul class="subcategory-list">';
            foreach ($child_product_cats as $child_product_cat) {
                $linkSub = get_term_link($child_product_cat->slug, $child_product_cat->taxonomy);
                echo '<li class="' . $activeSub . '"><a href="' . $linkSub . '">' . $child_product_cat->name . '</a></li>';
            }
            echo '</ul>';
        }
        echo '</ul>';
    }
}

/**
 * Get subcategories from parent category ID
 * @param $parent_cat_ID
 * @param $active_cat_ID
 */
function woocommerce_subcats_from_parentcat_by_ID($parent_cat_ID, $active_cat_ID)
{
    $args = array(
        'hierarchical' => 1,
        'show_option_none' => '',
        'hide_empty' => 0,
        'parent' => $parent_cat_ID,
        'taxonomy' => 'product_cat'
    );

    $subcats = get_categories($args);
    echo '<ul class="subcategory-list">';
    foreach ($subcats as $subcat) {
        if ($active_cat_ID === $subcat->term_id) {
            $active = 'active';
        } else {
            $active = '';
        }
        $link = get_term_link($subcat->slug, $subcat->taxonomy);
        echo '<li class="' . $active . '"><a href="' . $link . '">' . $subcat->name . '</a></li>';
    }
    echo '</ul>';
}

remove_action('woocommerce_shop_loop_subcategory_title', 'woocommerce_template_loop_category_title', 10);

/**
 * Change the breadcrumb
 */
add_filter('woocommerce_breadcrumb_defaults', 'new_woocommerce_breadcrumbs', 20);
function new_woocommerce_breadcrumbs()
{
    return array(
        'delimiter' => ' / ',
        'wrap_before' => '<div class="new-storefront-breadcrumb"><div class="container"><div class="row"><div class="col-12"><nav class="woocommerce-breadcrumb">',
        'wrap_after' => '</nav></div></div></div></div>',
        'home' => _x('Home', 'breadcrumb', 'woocommerce'),
    );
}

/**
 * Render delivery block
 *
 * @return string
 */
function get_delivery_block()
{
    return '
    <div class="delivery">
    <div class="container">
        <div class="row">
            <div class="col-lg-7 col-12">
                <div class="delivery-cards">
                    <div class="delivery-cards__item">
                        <img src="/wp-content/themes/storefront-child/svg/main/delivery/factory.svg" alt="">
                        <p>Поставки от производителя</p>
                        <p>Экономьте свой бюджет, заказывая напрямую у производителя</p>
                    </div>
                    <div class="delivery-cards__item">
                        <img src="/wp-content/themes/storefront-child/svg/main/delivery/diploma.svg" alt="">
                        <p>Высочайшее качество мебели</p>
                        <p>Используем материалы, прошедшие проверку временем</p>
                    </div>
                    <div class="delivery-cards__item">
                        <img src="/wp-content/themes/storefront-child/svg/main/delivery/telemarketer.svg" alt="">
                        <p>Круглосуточная поддержка</p>
                        <p>Мы поможем вам по любому вопросу в любое время</p>
                    </div>
                    <div class="delivery-cards__item">
                        <img src="/wp-content/themes/storefront-child/svg/main/delivery/delivery-truck.svg" alt="">
                        <p>Доставка строго в оговоренные сроки</p>
                        <p>Доставим точно ко времени, не срывая планируемые сроки</p>
                    </div>
                    <div class="delivery-cards__item">
                        <img src="/wp-content/themes/storefront-child/svg/main/delivery/percentage.svg" alt="">
                        <p>Скидки уже со второго заказа</p>
                        <p>Нашим клиентам ма дарим скидку на каждую следующую покупку</p>
                    </div>
                    <div class="delivery-cards__item">
                        <img src="/wp-content/themes/storefront-child/svg/main/delivery/money-back.svg" alt="">
                        <p>Гарантия на возврат средств</p>
                        <p>Мы вернем деньги в случае обнаружения заводского брака</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-5 col-12">
                <div class="delivery-card">
                    <div class="delivery-card__body">
                        <p class="delivery-card__header">Бесплатная доставка</p>
                        <p>при заказе от</p>
                        <p class="delivery-card__price">20 000₽</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
';
}

/**
 * Render product props by product id
 *
 * @param $productId
 * @return false|string
 */
function get_product_props($productId = '')
{
    if (!$productId) {
        global $product;
        $productId = $product->id;
    }

    $fields = get_field_objects($productId);
    $empty = true;
    foreach ($fields as $field) {
        if ($field['value'] === '') {
            $empty = true;
        } else {
            $empty = false;
        }
    }
    ob_start();
    if (!$empty):
        ?>
        <div class="col-12">
            <div class="products-props-list">
                <p class="products-props-list__title">Характеристики:</p>
                <div class="row ">
                    <?php foreach ($fields as $field):
                        if ($field['value'] !== ''):?>
                            <div class="col-lg-6 col-12">
                                <p class="products-props-list__item">
                                    <span class="products-props-list__item--label"><?= $field['label'] ?>:</span>
                                    <span class="products-props-list__item--value"><?= $field['value'] ?></span>
                                </p>
                            </div>
                        <?php
                        endif;
                    endforeach; ?>
                </div>
            </div>
        </div>
    <?php
    endif;
    return ob_get_clean();
}

// Удаление инлайн-скриптов из хедера
add_filter('storefront_customizer_css', '__return_false');
add_filter('storefront_customizer_woocommerce_css', '__return_false');
add_filter('storefront_gutenberg_block_editor_customizer_css', '__return_false');

add_action('wp_print_styles', static function () {
    wp_styles()->add_data('woocommerce-inline', 'after', '');
});

add_action('init', static function () {
    remove_action('wp_head', 'wc_gallery_noscript');
});
add_action('init', static function () {
    remove_action('wp_head', 'wc_gallery_noscript');
});
// Конец удаления инлайн-скриптов из хедера

add_filter('woocommerce_account_menu_items', 'custom_remove_downloads_my_account', 999);

function custom_remove_downloads_my_account($items)
{
    unset($items['downloads']);
    return $items;
}

add_action('wp_footer', 'custom_quantity_fields_script');
/**
 * Custom quantity field
 */
function custom_quantity_fields_script()
{
    ?>
    <script type='text/javascript'>
        jQuery(function ($) {
            if (!String.prototype.getDecimals) {
                String.prototype.getDecimals = function () {
                    let num = this,
                        match = ('' + num).match(/(?:\.(\d+))?(?:[eE]([+-]?\d+))?$/);
                    if (!match) {
                        return 0;
                    }
                    return Math.max(0, (match[1] ? match[1].length : 0) - (match[2] ? +match[2] : 0));
                }
            }
            $(document.body).on('click', '.plus, .minus', function () {
                let $qty = $(this).closest('.quantity').find('.qty'),
                    currentVal = parseFloat($qty.val()),
                    max = parseFloat($qty.attr('max')),
                    min = parseFloat($qty.attr('min')),
                    step = $qty.attr('step');

                if (!currentVal || currentVal === '' || currentVal === 'NaN') currentVal = 0;
                if (max === '' || max === 'NaN') max = '';
                if (min === '' || min === 'NaN') min = 0;
                if (step === 'any' || step === '' || step === undefined || parseFloat(step) === 'NaN') step = 1;

                if ($(this).is('.plus')) {
                    if (max && (currentVal >= max)) {
                        $qty.val(max);
                    } else {
                        $qty.val((currentVal + parseFloat(step)).toFixed(step.getDecimals()));
                    }
                } else {
                    if (min && (currentVal <= min)) {
                        $qty.val(min);
                    } else if (currentVal > 0) {
                        $qty.val((currentVal - parseFloat(step)).toFixed(step.getDecimals()));
                    }
                }

                // Trigger change event
                $qty.trigger('change');
            });
        });
    </script>
    <?php
}

add_action('wp', 'remove_zoom_lightbox_theme_support', 99);

function remove_zoom_lightbox_theme_support()
{
    remove_theme_support('wc-product-gallery-zoom');
}

remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40);
remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_price', 10);
add_action('woocommerce_single_product_summary', 'woocommerce_template_single_meta', 10);
add_action('woocommerce_single_product_summary', 'woocommerce_template_single_price', 15);

add_filter('woocommerce_sale_flash', 'hide_sale_flash');
function hide_sale_flash()
{
    return false;
}

/**
 * Add new fields to checkout
 * @param $checkout
 */
function add_custom_checkout_field($checkout)
{
    woocommerce_form_field('passport-series', array(
        'type' => 'text',
        'class' => array('form-row form-row-first'),
        'label' => 'Серия паспорта',
        'placeholder' => 'Серия паспорта',
        'required' => false,
        'default' => '',
    ), $checkout->get_value('passport-series'));
    woocommerce_form_field('passport-number', array(
        'type' => 'text',
        'class' => array('form-row form-row-last'),
        'label' => 'Номер паспорта',
        'placeholder' => 'Номер паспорта',
        'required' => false,
        'default' => '',
    ), $checkout->get_value('passport-number'));
    woocommerce_form_field('passport-date', array(
        'type' => 'text',
        'class' => array('form-row-wide'),
        'label' => 'Когда выдан',
        'placeholder' => 'Дата выдачи',
        'required' => false,
        'default' => '',
    ), $checkout->get_value('passport-date'));
    woocommerce_form_field('passport-place', array(
        'type' => 'text',
        'class' => array('form-row-wide'),
        'label' => 'Кем выдан',
        'placeholder' => 'Кем выдан',
        'required' => false,
        'default' => '',
    ), $checkout->get_value('passport-place'));
}

add_action('woocommerce_checkout_process', 'bbloomer_validate_new_checkout_field');
/**
 * Validate new checkout fields
 */
function validate_new_checkout_field()
{
    if (!$_POST['passport-series']) {
        wc_add_notice('Пожалуйста введите серию паспорта', 'error');
    }
    if (!$_POST['passport-number']) {
        wc_add_notice('Пожалуйста введите номер паспорта', 'error');
    }
    if (!$_POST['passport-date']) {
        wc_add_notice('Пожалуйста введите дату выдачи паспорта', 'error');
    }
    if (!$_POST['passport-place']) {
        wc_add_notice('Пожалуйста введите место выдачи паспорта', 'error');
    }
}

add_action('woocommerce_checkout_update_order_meta', 'save_new_checkout_field');
/**
 * Save new checkouts fields
 * @param $order_id
 */
function save_new_checkout_field($order_id)
{
    if ($_POST['passport-series']) {
        update_post_meta($order_id, '_passport-series', esc_attr($_POST['passport-series']));
    }
    if ($_POST['passport-number']) {
        update_post_meta($order_id, '_passport-number', esc_attr($_POST['passport-number']));
    }
    if ($_POST['passport-date']) {
        update_post_meta($order_id, '_passport-date', esc_attr($_POST['passport-date']));
    }
    if ($_POST['passport-place']) {
        update_post_meta($order_id, '_passport-place', esc_attr($_POST['passport-place']));
    }
}

add_action('woocommerce_admin_order_data_after_billing_address', 'show_new_checkout_field_order', 10, 1);
/**
 * Show new checkout fields on order page
 * @param $order
 */
function show_new_checkout_field_order($order)
{
    $order_id = $order->get_id();
    if (get_post_meta($order_id, '_passport-series', true)) {
        echo '<p><strong>Серия паспорта:</strong> ' . get_post_meta($order_id, '_passport-series', true) . '</p>';
    }
    if (get_post_meta($order_id, '_passport-number', true)) {
        echo '<p><strong>Номер паспорта:</strong> ' . get_post_meta($order_id, '_passport-number', true) . '</p>';
    }
    if (get_post_meta($order_id, '_passport-date', true)) {
        echo '<p><strong>Дата выдачи паспорта:</strong> ' . get_post_meta($order_id, '_passport-date', true) . '</p>';
    }
    if (get_post_meta($order_id, '_passport-place', true)) {
        echo '<p><strong>Кем выдан паспорт:</strong> ' . get_post_meta($order_id, '_passport-place', true) . '</p>';
    }
}

add_action('woocommerce_email_after_order_table', 'show_new_checkout_field_emails', 20, 4);
/**
 * Show new checkout fields in email
 * @param $order
 * @param $sent_to_admin
 * @param $plain_text
 * @param $email
 */
function show_new_checkout_field_emails($order, $sent_to_admin, $plain_text, $email)
{
    $order_id = $order->get_id();
    if (get_post_meta($order_id, '_passport-series', true)) {
        echo '<p><strong>Серия паспорта:</strong> ' . get_post_meta($order_id, '_passport-series', true) . '</p>';
    }
    if (get_post_meta($order_id, '_passport-number', true)) {
        echo '<p><strong>Номер паспорта:</strong> ' . get_post_meta($order_id, '_passport-number', true) . '</p>';
    }
    if (get_post_meta($order_id, '_passport-date', true)) {
        echo '<p><strong>Дата выдачи паспорта:</strong> ' . get_post_meta($order_id, '_passport-date', true) . '</p>';
    }
    if (get_post_meta($order_id, '_passport-place', true)) {
        echo '<p><strong>Кем выдан паспорт:</strong> ' . get_post_meta($order_id, '_passport-place', true) . '</p>';
    }
}

function disable_shipping_calc_on_cart($show_shipping)
{
    if (is_cart()) {
        return false;
    }
    return $show_shipping;
}

add_filter('woocommerce_cart_ready_to_calc_shipping', 'disable_shipping_calc_on_cart', 99);


/**
 *  Render admin-ajax address in variable 'my_ajaxurl'
 */
function my_ajax_url()
{
    if (is_page('checkout')) {
        wp_enqueue_script('my_script_handle', 'MY_JS_URL', array('jquery'));
        wp_localize_script('my_script_handle', 'my_ajaxurl', (array)admin_url('admin-ajax.php'));
    }
}

add_action('wp_enqueue_scripts', 'my_ajax_url');

/**
 * Check if WooCommerce is active
 */
if (in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')), true)) {

    /**
     *  init delline method
     */
    function delline_shipping_method_init()
    {
        if (!class_exists('WC_delline_Shipping_Method')) {
            class WC_delline_Shipping_Method extends WC_Shipping_Method
            {

                /**
                 * Constructor for your shipping class
                 *
                 * @access public
                 * @return void
                 */
                public function __construct()
                {
                    $this->id = 'delline_shipping_method'; // Id for your shipping method. Should be uunique.
                    $this->method_title = __('delline Shipping Method');  // Title shown in admin
                    $this->method_description = __('Description of delline shipping method'); // Description shown in admin

                    $this->init();

                    $this->enabled = isset($this->settings['enabled']) ? $this->settings['enabled'] : 'yes';
                    $this->title = isset($this->settings['title']) ? $this->settings['title'] : __('delline Shipping');
                    $this->priceSet = isset($this->settings['priceSet']) ? $this->settings['priceSet'] : 405;


                    if ($this->enabled === 'yes') {
                        add_action('woocommerce_checkout_before_order_review', 'delline_html_on');
                    }
                }

                /**
                 * Init your settings
                 *
                 * @access public
                 * @return void
                 */
                public function init()
                {
                    // Load the settings API
                    $this->init_form_fields(); // This is part of the settings API. Override the method to add your own settings
                    $this->init_settings(); // This is part of the settings API. Loads settings you previously init.

                    // Save settings in admin if you have any defined
                    add_action('woocommerce_update_options_shipping_' . $this->id, array($this, 'process_admin_options'));
                }

                /**
                 * Define settings field for this shipping
                 * @return void
                 */
                public function init_form_fields()
                {

                    $this->form_fields = array(

                        'enabled' => array(
                            'title' => __('Enable'),
                            'type' => 'checkbox',
                            'description' => __('Enable this shipping.'),
                            'default' => 'yes'
                        ),
                        'title' => array(
                            'title' => __('Title'),
                            'type' => 'text',
                            'description' => __('Title to be display on site'),
                            'default' => __('delline Shipping')
                        ),
                        'priceSet' => array(
                            'title' => __('Delivery cost'),
                            'type' => 'text',
                            'description' => __('Default delivery cost'),
                            'default' => __('405')
                        ),
                    );
                }

                /**
                 * calculate_shipping function.
                 *
                 * @access public
                 * @param mixed $package
                 * @return void
                 */
                public function calculate_shipping($package)
                {
                    $rate = array(
                        'id' => $this->id,
                        'label' => $this->title,
                        'cost' => $this->priceSet,
                    );

                    // Register the rate
                    $this->add_rate($rate);
                }
            }
        }
    }

    add_action('woocommerce_shipping_init', 'delline_shipping_method_init');

    /**
     * add delline shipping method
     *
     * @param $methods
     * @return mixed
     */
    function add_delline_shipping_method($methods)
    {
        $methods['delline_shipping_method'] = 'WC_delline_Shipping_Method';
        return $methods;
    }

    add_filter('woocommerce_shipping_methods', 'add_delline_shipping_method');

    // wp_ajax_ - только для зарегистрированных пользователей
    add_action('wp_ajax_delline_delivery_cost_request', 'delline_delivery_cost_request');
    add_action('wp_ajax_adjust_shipping_rate', 'adjust_shipping_rate');
    add_action('wp_ajax_get_city_list', 'get_city_list');

// wp_ajax_nopriv_ - только для незарегистрированных
    add_action('wp_ajax_nopriv_delline_delivery_cost_request', 'delline_delivery_cost_request');
    add_action('wp_ajax_nopriv_adjust_shipping_rate', 'adjust_shipping_rate');
    add_action('wp_ajax_nopriv_get_city_list', 'get_city_list');

    /**
     * Render dellin widget
     * @return false|string
     * @todo add checkbox for street
     */
    function get_dellin_widget()
    {
        $cart = WC()->cart->get_cart();
        $weight = 0;
        $volume = 0;
        $max_weight = 0;
        foreach ($cart as $cart_item) {
            $productId = $cart_item['product_id'];
            $quantity = $cart_item['quantity'];
            $max_weight = (float)get_field('product_weight_with_package', $productId);
            if ($max_weight < (float)get_field('product_weight_with_package', $productId)) {
                $max_weight = (float)get_field('product_weight_with_package', $productId);
            }
            $weight += ((float)$quantity * (float)get_field('product_weight_with_package', $productId));
            $volume += ((float)$quantity * (float)get_field('package_volume', $productId));
        }
        ob_start();
        ?>
        <p class="form-row form-row-wide address-field validate-required" id="billing_address_1_field"
           data-priority="50">
            <label for="cityList" class="">
                Куда&nbsp;<abbr class="required" title="обязательно">*</abbr></label>
            <span class="woocommerce-input-wrapper">
                  <input name="city" type="text" id="cityList" list="datalist"
                         placeholder="Населенный пункт" autocomplete="off"
                         data-weight="<?= $weight ?>" data-volume="<?= $volume ?>"
                         data-maxweight="<?= $max_weight ?>"
                  />
                    <datalist id="datalist">
                    </datalist>
            </span>
        </p>
        <script type="text/javascript">
            jQuery($ => {
                let cityListEl = $('#cityList');
                cityListEl.on('keyup', function () {
                    let q = $(this).val()
                    if (q.length > 2) {
                        jQuery.post({
                            url: my_ajaxurl,
                            data: {
                                action: "get_city_list",
                                q: q
                            }
                        }, res => {
                            const $obj = $.parseJSON(res)
                            $(this).next('datalist').html('')
                            $obj.cities.map(item => {
                                $(this).next('datalist').append('<option value="' + item.code + '">' + item.aString + '</option>')
                            })
                        })
                    }
                })

                cityListEl.on('focusout', function () {
                    const weight = $(this).data('weight')
                    const volume = $(this).data('volume')
                    const maxWeight = $(this).data('maxweight')
                    const cityCode = $(this).val()
                    if (!isNaN(parseInt(cityCode))) {
                        ajaxFormRequest(weight, volume, maxWeight, cityCode, my_ajaxurl)
                    } else {
                        /**
                         * @todo Сделать оформления, если населенный пункт не выбран из загруженного списка
                         */
                        console.log('Выберите населенный пункт из списка')
                    }
                })
            })


            const ajaxFormRequest = (weight, volume, maxWeight, cityCode, url) => {
                jQuery.post({
                    url: url,
                    data: {
                        action: 'delline_delivery_cost_request',
                        weight,
                        volume,
                        maxWeight,
                        cityCode
                    }
                }, res => {
                    changeDeliveryCost(url)
                })
            }

            const changeDeliveryCost = (url) => {
                console.log('Смена стоимости')
                jQuery.post({
                    url: url,
                    data: {
                        action: 'adjust_shipping_rate',
                    }
                }, res => {
                    /**
                     * Update delivery info
                     */
                    let input = jQuery('#billing_address_1');
                    let text = input.val().trim();
                    input.val(text + 'delline' + Math.random());
                    jQuery(document.body).trigger('update_checkout');
                })
            }
        </script>
        <?php
        return ob_get_clean();
    }

    /**
     * Request to delline for delivery cost
     */
    function delline_delivery_cost_request()
    {
        $weight = $_POST['weight'];
        $volume = $_POST['volume'];
        $max_weight = $_POST['maxWeight'];
        $city_code = $_POST['cityCode'];

//        var_dump("вес", $weight);
//        var_dump("объем", $volume);
//        var_dump("вес самого большого", $max_weight);

        $data = array(
            "appkey" => "022BC94E-12D2-42C6-B6E5-A7A418A760E1",
            "delivery" => array(
                "deliveryType" => array(
                    "type" => "auto"
                ),
                "arrival" => array(
                    "variant" => "terminal",
                    "city" => "7800000000000000000000000",
                ),
                "derival" => array(
                    "produceDate" => date("Y-m-d", time() + 86400 * 7),
                    "variant" => "address",
                    "address" => array(
                        "street" => $city_code,
                    ),
                    "time" => array(
                        "worktimeEnd" => "19:30",
                        "worktimeStart" => "9:00",
                        "breakStart" => "12:00",
                        "breakEnd" => "13:00",
                        "exactTime" => false
                    ),
                ),
            ),
            "members" => array(
                "requester" => array(
                    "role" => "sender",
                    "uid" => "ae62f076-d602-4341-b691-45bf8dfe4a10"
                )
            ),
            "cargo" => array(
                "length" => 1,
                "width" => 1,
                "weight" => $max_weight,
                "height" => 1,
                "totalVolume" => $volume,
                "totalWeight" => $weight,
                "oversizedWeight" => 0,
                "oversizedVolume" => 0,
                "hazardClass" => 0,
            ),
            "payment" => array(
                "paymentCity" => $city_code,
                "type" => "cash"
            ),
        );

        $json = json_encode($data);
        $url = ('https://api.dellin.ru/v2/calculator.json');
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json"));
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch);
        $obj = json_decode($result, true);

        $price_minimal = $obj["data"]["priceMinimal"];
        $new_rate = $obj["data"]["availableDeliveryTypes"][$price_minimal];
        var_dump("Минимальная стоимость доставки", $new_rate);
        setcookie('new_rate', $new_rate, time() + (300), '/');
        curl_close($ch);
        wp_die();
    }

    /**
     *  Update shipping rate from request
     *
     * @param $rates
     * @return mixed
     */
    function adjust_shipping_rate($rates)
    {
        foreach ($rates as $rate) {
            if ((isset($_COOKIE['new_rate']) && $rate->id === 'delline_shipping_method')) {
                $rate->cost = $_COOKIE['new_rate'];
            }
        }
        return $rates;
    }

    add_filter('woocommerce_package_rates', 'adjust_shipping_rate', 50, 1);

    /**
     *  Request for cities delline
     *
     * @return mixed
     */
    function get_city_list()
    {
        $q = $_POST['q'];
        $data = array(
            "appkey" => "022BC94E-12D2-42C6-B6E5-A7A418A760E1",
            "q" => $q,
            "limit" => 20,
        );
        $json = json_encode($data);
        $url = ('https://api.dellin.ru/v2/public/kladr.json');
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json"));
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch);
        curl_close($ch);

        echo $result;

        wp_die();

    }
}