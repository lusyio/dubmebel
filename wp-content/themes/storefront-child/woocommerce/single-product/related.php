<?php
/**
 * Related Products
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/related.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see         https://docs.woocommerce.com/document/template-structure/
 * @package     WooCommerce/Templates
 * @version     3.9.0
 */

if (!defined('ABSPATH')) {
    exit;
}

global $product;


$content = get_the_content();

if ($content):?>

    <div class="col-12">
        <div class="woocommerce-product-details__short-description">
            <p class="woocommerce-product-details__title">Описание:</p>
            <?= $content; ?>
        </div>
    </div>

<?php endif;

echo get_product_props($product->id);

wp_reset_postdata();
