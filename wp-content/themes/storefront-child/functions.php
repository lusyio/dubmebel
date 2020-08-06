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
    wp_enqueue_script('wp-swiper-js', get_stylesheet_directory_uri() . '/inc/assets/js/swiper.min.js', array(), '', true);
    wp_enqueue_style('wp-swiper-js', get_stylesheet_directory_uri() . '/inc/assets/css/swiper.min.css', array(), '', true);

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

/**
 * Удаляем поля адрес и телефон, если нет доставки
 */

add_filter('woocommerce_checkout_fields', 'new_woocommerce_checkout_fields', 10, 1);

function new_woocommerce_checkout_fields($fields)
{
    if (!WC()->cart->needs_shipping()) {
        unset($fields['billing']['billing_address_1']); //удаляем Населённый пункт
        unset($fields['billing']['billing_address_2']); //удаляем Населённый пункт
        unset($fields['billing']['billing_city']); //удаляем Населённый пункт
        unset($fields['billing']['billing_postcode']); //удаляем Населённый пункт
        unset($fields['billing']['billing_country']); //удаляем Населённый пункт
        unset($fields['billing']['billing_state']); //удаляем Населённый пункт
        unset($fields['billing']['billing_company']); //удаляем Населённый пункт
        unset($fields['billing']['phone']); //удаляем Населённый пункт

    }
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
    } else {
        return '<span class="now-price">' . $product->price . '<span class="woocommerce-Price-currencySymbol">'
            . get_woocommerce_currency_symbol() . '</span></span>';
    }
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
                            <div class="categories-grid__item categories-grid__item--<?= $category->slug ?>"
                                 style="background-image: url('<?= $image ?>')">
                                <p class="categories-grid__name"><?= $category->name ?></p>
                                <p class="categories-grid__price">от 4 990 ₽</p>
                            </div>
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
        array_push($image_gallery_with_info, array(
                'alt' => get_post_meta($attachment->ID, '_wp_attachment_image_alt', true),
                'caption' => $attachment->post_excerpt,
                'description' => $attachment->post_content,
                'href' => get_permalink($attachment->ID),
                'src' => $attachment->guid,
                'title' => $attachment->post_title
            )
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
                                <img src="<?= wp_get_attachment_image_url($image_id, 'full'); ?>"
                                     alt="<?= $product->name; ?>">
                            </div>
                            <p class="card-product__title"><?= $product->name; ?>
                            <p class="card-product__price"><?= $product->get_price_html(); ?></p>
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


    ob_start();
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
    return ob_get_clean();
}

// Удаление инлайн-скриптов из хедера
add_filter('storefront_customizer_css', '__return_false');
add_filter('storefront_customizer_woocommerce_css', '__return_false');
add_filter('storefront_gutenberg_block_editor_customizer_css', '__return_false');

add_action('wp_print_styles', function () {
    wp_styles()->add_data('woocommerce-inline', 'after', '');
});

add_action('init', function () {
    remove_action('wp_head', 'wc_gallery_noscript');
});
add_action('init', function () {
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

