<?php
/*
Template Name: about
Template Post Type: post, page, product
*/
?>

<?php get_header(); ?>

</div>
</div>

<div class="contacts">
    <div class="container">

        <?php

        $image = get_the_post_thumbnail_url();
        $vk_link = get_field('vk_link');
        $instagram_link = get_field('instagram_link');
        $facebook_link = get_field('instagram_link');
        $phone = get_field('phone');
        $work_time = get_field('work_time');
        $email = get_field('email');
        $address = get_field('address');

        if ($image): ?>
            <p class="contacts__title">Контакты:</p>
            <div class="row">
                <div class="col-lg-6 col-12">
                    <div class="card-contacts">
                        <?php if ($phone) : ?>
                            <p class="card-contacts__title">Телефон:</p>
                            <p class="card-contacts__value"><a href="tel:<?= $phone ?>"><?= $phone ?></a></p>
                        <?php endif; ?>
                        <?php if ($email) : ?>
                            <p class="card-contacts__title">Email:</p>
                            <p class="card-contacts__value"><a href="mailto:<?= $email ?>"><?= $email ?></a></p>
                        <?php endif; ?>
                        <?php if ($address) : ?>
                            <p class="card-contacts__title">Адрес:</p>
                            <p class="card-contacts__value"><?= $address ?></p>
                        <?php endif; ?>
                        <?php if ($work_time) : ?>
                            <p class="card-contacts__title">График работы:</p>
                            <p class="card-contacts__value"><?= $work_time ?></p>
                        <?php endif; ?>
                        <div class="card-contacts-socials">
                            <?php if ($vk_link): ?>
                                <a class="card-contacts-socials__icon card-contacts-socials__vk"
                                   href="<?= $vk_link ?>">
                                    <img src="/wp-content/themes/storefront-child/svg/about/vk.svg" alt="vk">
                                </a>
                            <?php endif; ?>
                            <?php if ($instagram_link): ?>
                                <a class="card-contacts-socials__icon card-contacts-socials__inst"
                                   href="<?= $instagram_link ?>">
                                    <img src="/wp-content/themes/storefront-child/svg/about/inst.svg" alt="inst">
                                </a>
                            <?php endif; ?>
                            <?php if ($facebook_link): ?>
                                <a class="card-contacts-socials__icon card-contacts-socials__fb"
                                   href="<?= $facebook_link ?>">
                                    <img src="/wp-content/themes/storefront-child/svg/about/fb.svg" alt="fb">
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-12">
                    <img src="<?= $image ?>" alt="">
                </div>
            </div>
        <?php else: ?>
            <p class="contacts__title">Контакты:</p>
            <div class="row">
                <div class="col-lg-12">
                    <div class="card-contacts">
                        <?php if ($phone) : ?>
                            <p class="card-contacts__title">Телефон:</p>
                            <p class="card-contacts__value"><a href="tel:<?= $phone ?>"><?= $phone ?></a></p>
                        <?php endif; ?>
                        <?php if ($email) : ?>
                            <p class="card-contacts__title">Email:</p>
                            <p class="card-contacts__value"><a href="mailto:<?= $email ?>"><?= $email ?></a></p>
                        <?php endif; ?>
                        <?php if ($address) : ?>
                            <p class="card-contacts__title">Адрес:</p>
                            <p class="card-contacts__value"><?= $address ?></p>
                        <?php endif; ?>
                        <?php if ($work_time) : ?>
                            <p class="card-contacts__title">График работы:</p>
                            <p class="card-contacts__value"><?= $work_time ?></p>
                        <?php endif; ?>
                        <div class="card-contacts-socials">
                            <?php if ($vk_link): ?>
                                <a class="card-contacts-socials__icon card-contacts-socials__vk"
                                   href="<?= $vk_link ?>">
                                    <img src="/wp-content/themes/storefront-child/svg/about/vk.svg" alt="vk">
                                </a>
                            <?php endif; ?>
                            <?php if ($instagram_link): ?>
                                <a class="card-contacts-socials__icon card-contacts-socials__inst"
                                   href="<?= $instagram_link ?>">
                                    <img src="/wp-content/themes/storefront-child/svg/about/inst.svg" alt="inst">
                                </a>
                            <?php endif; ?>
                            <?php if ($facebook_link): ?>
                                <a class="card-contacts-socials__icon card-contacts-socials__fb"
                                   href="<?= $facebook_link ?>">
                                    <img src="/wp-content/themes/storefront-child/svg/about/fb.svg" alt="fb">
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
        <div class="map">
            <iframe src="https://yandex.com/map-widget/v1/?um=constructor%3A22dfad516a125381ce1fb29e71e87bc3da8e55ed861d0b9a554aac037446b613&amp;source=constructor"
                    width="100%" height="400" frameborder="0"></iframe>
        </div>
    </div>
</div>

<?= get_delivery_block(); ?>

<?php get_footer(); ?>
