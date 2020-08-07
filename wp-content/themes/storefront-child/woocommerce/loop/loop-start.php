<?php
/**
 * Product Loop Start
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/loop/loop-start.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see         https://docs.woocommerce.com/document/template-structure/
 * @package     WooCommerce/Templates
 * @version     3.3.0
 */

if (!defined('ABSPATH')) {
    exit;
}
$args = array(
    'taxonomy' => "product_cat",
);

$activeCat = get_queried_object();

$categories = get_terms($args);

$parentCatsId = get_ancestors($activeCat->term_id, 'product_cat');
$parentCategory = get_term($parentCatsId[0], 'product_cat');
?>
<div class="row">
    <div class="col-lg-3 col-12">
        <?php
        echo get_categories_with_subcategories($activeCat->term_id);
        ?>
    </div>
    <div class="col-lg-9">
        <?php
        if ($activeCat->name !== 'product'): ?>
            <p class="category-filter-title">Показаны <?= $activeCat->name ?></p>
        <?php endif; ?>
        <div class="row">