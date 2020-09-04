<?php

global $product;
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