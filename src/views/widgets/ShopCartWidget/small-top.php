<?php
/**
 * @author Semenov Alexander <semenov@skeeks.com>
 * @link http://skeeks.com/
 * @copyright 2010 SkeekS (СкикС)
 * @date 21.09.2015
 */
/* @var $this yii\web\View */
/* @var $widget \skeeks\cms\shop\widgets\cart\ShopCartWidget */
\skeeks\cms\themes\unifyshop\assets\ShopUnifyCartAsset::register($this);
$this->registerJs(<<<JS
    (function(sx, $, _)
    {
        new sx.classes.shop.SmallCart(sx.Shop, 'sx-cart', {
            'delay': 500
        });
    })(sx, sx.$, sx._);
JS
);
?>


<!-- Basket -->
<div class="u-basket d-inline-block g-valign-middle g-mr-30 g-pt-8">
    <a href="#!" id="basket-bar-invoker" class="sx-cart-small-open-trigger u-icon-v1 g-color-main g-text-underline--none--hover g-width-20 g-height-20" aria-controls="basket-bar" aria-haspopup="true" aria-expanded="false" data-dropdown-event="hover" data-dropdown-target="#basket-bar"
       data-dropdown-type="css-animation" data-dropdown-duration="300" data-dropdown-hide-on-scroll="false" data-dropdown-animation-in="fadeIn" data-dropdown-animation-out="fadeOut">
                    <span class="u-badge-v1--sm g-color-white g-bg-primary g-rounded-50x sx-count-baskets">
                        <?= \Yii::$app->shop->shopFuser->countShopBaskets ? \Yii::$app->shop->shopFuser->countShopBaskets : ""; ?>
                    </span>
        <i class="fa fa-shopping-cart"></i>
    </a>

    <div id="basket-bar" class="u-basket__bar u-dropdown--css-animation u-dropdown--hidden g-brd-top g-brd-2 g-brd-primary g-color-main g-mt-20" aria-labelledby="basket-bar-invoker">
        <div class="js-scrollbar g-height-280">

            <? \skeeks\cms\modules\admin\widgets\Pjax::begin([
                'id' => 'sx-cart',
            ]) ?>

            <? if (\Yii::$app->shop->cart->shopBaskets) : ?>
                <? foreach (\Yii::$app->shop->cart->shopBaskets as $shopBasket) : ?>

                    <!-- Product -->
                    <div class="u-basket__product">
                        <div class="row align-items-center no-gutters">
                            <div class="col-4 g-pr-20">
                                <a href="#!" class="u-basket__product-img">
                                    <img src="<?= \skeeks\cms\helpers\Image::getSrc(\Yii::$app->imaging->getImagingUrl($shopBasket->image ? $shopBasket->image->src : null,
                                        new \skeeks\cms\components\imaging\filters\Thumbnail())); ?>" alt="<?= $shopBasket->name; ?>">
                                </a>
                            </div>

                            <div class="col-8">
                                <h6 class="g-font-weight-600 g-mb-0">

                                    <a href="<?= $shopBasket->url; ?>" class="g-color-main g-color-main--hover g-text-underline--none--hover"><?= $shopBasket->name; ?></a>

                                </h6>
                                <small class="g-color-gray-dark-v5 g-font-size-14"><?= $shopBasket->quantity ?> x <?= $shopBasket->money; ?></small>
                            </div>
                        </div>

                        <button class="u-basket__product-remove" type="button">&times;</button>
                    </div>
                    <!-- End Product -->

                <? endforeach; ?>

            <? else : ?>
                <a class="text-center" href="#">
                    <h6>Ваша корзина пуста</h6>
                </a>
            <? endif; ?>


            <? \skeeks\cms\modules\admin\widgets\Pjax::end(); ?>


        </div>

        <div class="g-brd-top g-brd-gray-light-v4 g-pa-15 g-pb-20">
            <div class="d-flex flex-row align-items-center justify-content-between g-letter-spacing-1 g-font-size-16 g-mb-15">
                <strong class="text-uppercase g-font-weight-600">Subtotal</strong>
                <strong class="g-color-primary g-font-weight-600"><?= \Yii::$app->shop->cart->money; ?></strong>
            </div>

            <div class="d-flex flex-row align-items-center justify-content-between g-font-size-18">
                <a href="<?= \yii\helpers\Url::to(['/shop/cart']); ?>" class="btn u-btn-outline-primary rounded-0 g-width-120">View Cart</a>
                <a href="<?= \yii\helpers\Url::to(['/shop/cart']); ?>" class="btn u-btn-primary rounded-0 g-width-120">Checkout</a>
            </div>
        </div>
    </div>
</div>
<!-- End Basket -->