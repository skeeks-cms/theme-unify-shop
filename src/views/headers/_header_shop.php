<?php
/**
 * @link https://cms.skeeks.com/
 * @copyright Copyright (c) 2010 SkeekS
 * @license https://cms.skeeks.com/license/
 * @author Semenov Alexander <semenov@skeeks.com>
 */
/* @var $this yii\web\View */
?>

<? $favoriteProducts = \Yii::$app->shop->cart->getShopFavoriteProducts()->count(); ?>
    <div class="sx-header-menu-item sx-favorite-products"
         data-total="<?= $favoriteProducts; ?>"
    >
        <a href="<?= \yii\helpers\Url::to(['/shop/favorite']) ?>" data-pjax="0" class="g-text-underline--none--hover" style="font-size: 24px !important; position: relative;">
            <i class="far fa-heart" style="width: 30px;"></i>
            <span class="sx-favorite-total-wrapper u-badge-v1--sm g-top-5 g-right-5 g-color-white g-bg-primary g-rounded-50x" style="<?= $favoriteProducts > 0 ? "" : "display: none;"; ?>">
                <span class="sx-favorite-total"><?= $favoriteProducts; ?></span>
            </span>
            <!--<span class="sx-favorite-total-wrapper" style="<?/*= $favoriteProducts > 0 ? "" : "display: none;"; */?>">
                (<span class="sx-favorite-total"><?/*= $favoriteProducts; */?></span>)
            </span>-->
        </a>
    </div>

<?
echo \skeeks\cms\shop\widgets\cart\ShopCartWidget::widget([
    'namespace' => 'ShopCartWidget-small-top',
    'viewFile'  => '@app/views/widgets/ShopCartWidget/small-top',
])
?>