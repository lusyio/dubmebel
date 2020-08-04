<?php
/*
Template Name: main-landing
Template Post Type: post, page, product
*/
?>

<?php get_header(); ?>


</div>
</div>
<div class="main-slider"
     style="background: url('<?= get_the_post_thumbnail_url(12, 'full') ?>') center no-repeat; background-size: cover">
    <div class="container" style="height: 400px">

    </div>
</div>

<div class="categories">
    <div class="container">

    </div>
</div>

<?= get_popular_products(); ?>

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


<?php get_footer(); ?>
