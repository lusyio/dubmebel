<?php
/**
 * The template for displaying product content within loops
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.6.0
 */

defined('ABSPATH') || exit;

$args = array(
    'taxonomy' => "product_cat",
);

global $wp_query;
$activeCat = $wp_query->get_queried_object();

$categories = get_terms($args);
foreach ($categories as $category):
    $active = 'non-active';
    if ($activeCat->slug === $category->slug) {
        $active = 'active';
    }
    if ($activeCat->parent === $category->term_id) {
        $active = 'active';
    }
    if ($category->parent === 0) :
        $thumbnail_id = get_term_meta($category->term_id, 'thumbnail_id', true);
        $image = wp_get_attachment_url($thumbnail_id);
        ?>
        <div class="col-lg-4 col-sm-6 col-12 d-flex">
            <a class="d-flex w-100" href="<?= get_term_link($category->term_id, 'product_cat') ?>">
                <div id="<?= $category->slug ?>" class="category-card d-flex w-100 <?= $active ?>"
                     style='background-image: url("<?= $image ?>")'>
                    <div class="category-card__body">
                        <p class="category-card__name"><?= $category->name ?></p>
                        <p class="category-card__description"><?= $category->description ?></p>
                    </div>
                </div>
            </a>
        </div>
    <?php
    endif;
endforeach;