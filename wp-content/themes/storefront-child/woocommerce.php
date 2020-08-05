<?php
/**
 * The template for displaying Woocommerce Product
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package WP_Bootstrap_Starter
 */

get_header(); ?>

<?php if (is_product() || is_cart() || is_checkout()): ?>
    <section id="primary" class="content-area col-sm-12">
        <main id="main" class="site-main" role="main">

            <?php woocommerce_content(); ?>
            <?php
            if (is_product()) {
                comments_template();
            } ?>
        </main>
    </section>
<?php else: ?>
    <?php get_sidebar(); ?>
    <section id="primary" class="content-area col-sm-12 archive-product-page">
        <main id="main" class="site-main" role="main">
            <?php woocommerce_content(); ?>
        </main>
    </section>
<?php endif; ?>

<?php
get_footer();
