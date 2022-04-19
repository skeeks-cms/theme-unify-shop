<?php
/**
 * @link https://cms.skeeks.com/
 * @copyright Copyright (c) 2010 SkeekS
 * @license https://cms.skeeks.com/license/
 * @author Semenov Alexander <semenov@skeeks.com>
 */
/* @var $this yii\web\View */
\skeeks\assets\unify\base\UnifyIconSimpleLineAsset::register($this);
?>

<?
$favQuery = \Yii::$app->shop->shopUser->getShopFavoriteProducts();
//\Yii::$app->shop->filterBaseContentElementQuery($favQuery);
$favoriteProducts = $favQuery->count(); ?>
<div class="sx-header-menu-item sx-favorite-products"
     data-total="<?= $favoriteProducts; ?>"
>
    <a href="<?= \yii\helpers\Url::to(['/shop/favorite']) ?>" data-pjax="0" class="sx-icon-wrapper g-text-underline--none--hover" style="position: relative;">
        <!--<i class="far fa-heart" style="width: 30px;"></i>-->
        <span class="sx-favorite-total-wrapper g-color-white g-bg-primary sx-badge" style="<?= $favoriteProducts > 0 ? "" : "display: none;"; ?>">
            <span class="sx-favorite-total"><?= $favoriteProducts; ?></span>
        </span>
        <i class="icon-heart"></i>
        <!--<span class="sx-favorite-total-wrapper" style="<? /*= $favoriteProducts > 0 ? "" : "display: none;"; */ ?>">
            (<span class="sx-favorite-total"><? /*= $favoriteProducts; */ ?></span>)
        </span>-->
    </a>
</div>

<?php if(\Yii::$app->cms->cmsSite->shopSite->is_show_cart) : ?>
<div class="sx-header-menu-item sx-top-cart sx-js-cart <?php echo \Yii::$app->shop->cart->quantity ? "sx-is-full-cart" : ""; ?>" id="sx-top-cart">
    <a href="<?= \yii\helpers\Url::to(['/shop/cart']); ?>" class="sx-icon-wrapper g-text-underline--none--hover" style="position: relative;">
        <span class="sx-badge g-color-white g-bg-primary sx-total-quantity">
            <?= \Yii::$app->shop->cart->quantity ? (int)\Yii::$app->shop->cart->quantity : ""; ?>
        </span>
        <i class="icon-basket"></i>
    </a>
    <a href="<?= \yii\helpers\Url::to(['/shop/cart']); ?>" id="basket-bar-invoker" class="sx-total-money g-text-underline--none--hover" data-pjax="0">
        <? echo ((float) \Yii::$app->shop->cart->money->amount > 0 ) ? \Yii::$app->shop->cart->money : ""; ?>
    </a>
<? endif; ?>
