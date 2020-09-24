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
\skeeks\cms\shop\widgets\ShopGlobalWidget::widget();
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
<div class="sx-header-menu-item" id="sx-top-cart">
    <a href="<?= \yii\helpers\Url::to(['/shop/cart']); ?>" class="sx-cart-small-open-trigger g-color-main g-text-underline--none--hover" style="font-size: 24px !important; position: relative;">
            <span class="sx-badge g-color-white g-bg-primary sx-count-quantity" style="<?= \Yii::$app->shop->cart->quantity > 0 ? "" : "display: none;"; ?>">
                <?= \Yii::$app->shop->cart->quantity ? (int) \Yii::$app->shop->cart->quantity : ""; ?>
            </span>
        <i class="fa fa-shopping-cart"></i>
    </a>

    <? if (!\Yii::$app->mobileDetect->isMobile) : ?>
        <? $pjax = \skeeks\cms\widgets\Pjax::begin([
            'isBlock' => 'false',
            'id'      => 'sx-cart',
            'options' => [
                'tag' => 'span',
            ],
        ]); ?>
        <? if (\Yii::$app->shop->cart->money->amount > 0) : ?>
            <a href="<?= \yii\helpers\Url::to(['/shop/cart']); ?>" id="basket-bar-invoker" class="g-text-underline--none--hover" data-pjax="0">
                <?= \Yii::$app->shop->cart->money; ?>
            </a>
        <? endif; ?>
        <? $pjax::end(); ?>
    <? endif; ?>
</div>
<!-- End Basket -->