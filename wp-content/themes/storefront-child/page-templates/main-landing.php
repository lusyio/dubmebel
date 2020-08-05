<?php
/*
Template Name: main-landing
Template Post Type: post, page, product
*/
?>

<?php get_header(); ?>

</div>
</div>

<?= get_slider_from_library_page() ?>

<?= get_categories_list('grid') ?>

<?= get_popular_products(); ?>

<?= get_delivery_block(); ?>

<?php get_footer(); ?>
