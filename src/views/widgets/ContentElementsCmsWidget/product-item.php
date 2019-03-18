<?php
/**
 * @author Semenov Alexander <semenov@skeeks.com>
 * @link http://skeeks.com/
 * @copyright 2010 SkeekS (СкикС)
 * @date 06.03.2015
 *
 * @var \skeeks\cms\models\CmsContentElement $model
 *
 */
/* @var $this yii\web\View */
$shopProduct = $model->shopProduct;
$shopCmsContentElement = $model;
//$model->name = $model->name . " ({$model->relatedPropertiesModel->getSmartAttribute('brand')})";
?>
<li class="col-lg-4 col-sm-4 col-xs-6">
    <? if ($shopCmsContentElement->shopProduct && $shopCmsContentElement->shopProduct->product_type == \skeeks\cms\shop\models\ShopProduct::TYPE_OFFERS) : ?>
    <div class="shop-item shop-item-offers">
        <? else : ?>
        <div class="shop-item shop-item-offers">
            <? endif; ?>
            <div class="thumbnail catalog_list">
                <!-- product image(s) -->
                <a class="shop-item-image" href="<?= $model->url; ?>" data-pjax="0">
                    <img src="<?= \skeeks\cms\helpers\Image::getSrc(
                        \Yii::$app->imaging->thumbnailUrlOnRequest($model->image ? $model->image->src : null,
                            new \skeeks\cms\components\imaging\filters\Thumbnail([
                                'w' => 230,
                                'h' => 230,
                                'm' => \Imagine\Image\ManipulatorInterface::THUMBNAIL_INSET,
                            ]), $model->code
                        )); ?>" title="<?= $model->name; ?>" alt="<?= $model->name; ?>" class="img_list_catalog"/>
                </a>
                <!-- /product image(s) -->
                <!-- hover buttons -->
                <div class="shop-option-over" style="display: none;">
                    <!-- replace data-item-id width the real item ID - used by js/view/demo.shop.js -->
                    <a class="btn btn-default" data-pjax="0" href="<?= $model->url; ?>"><i
                                class="fa fa-arrow-right size-20"></i></a>
                </div>
                <!-- /hover buttons -->
                <!-- product more info -->
                <? if ($shopProduct && $shopProduct->minProductPrice && $shopProduct->baseProductPrice && $shopProduct->baseProductPrice->money->getAmount() > 0) : ?>
                    <? if ($shopProduct->minProductPrice->id != $shopProduct->baseProductPrice->id) : ?>
                        <div class="shop-item-info">
                        <span class="label label-danger">Скидка: <?= \Yii::$app->formatter->asPercent(
                                (100 - ($shopProduct->minProductPrice->money->convertToCurrency("RUB")->getAmount() * 100 / $shopProduct->baseProductPrice->money->convertToCurrency("RUB")->getAmount())) / 100
                            ); ?></span>
                        </div>
                    <? endif; ?>
                <? endif; ?>
                <!--<div class="shop-item-info">
                    <span class="label label-success">NEW</span>
                    <span class="label label-danger">SALE</span>
                </div>-->
                <!-- /product more info -->
            </div>
            <div class="shop-item-summary text-center">
                <h2><?= $model->name; ?></h2>
                <!-- rating -->
                <!-- /rating -->
                <? if ($shopProduct && $shopProduct->baseProductPrice) : ?>
                    <? if ($shopCmsContentElement->shopProduct->product_type == \skeeks\cms\shop\models\ShopProduct::TYPE_OFFERS) : ?>
                        <!-- price -->
                        <div class="shop-item-price">
                            <? if ($shopProduct->minProductPrice->id == $shopProduct->baseProductPrice->id) : ?>
                                от <?= \Yii::$app->money->convertAndFormat($shopProduct->minProductPrice->money); ?>
                            <? else : ?>
                                <span
                                        class="line-through"><?= \Yii::$app->money->convertAndFormat($shopProduct->baseProductPrice->money); ?></span>
                                <span
                                        class="sx-discount-price">от <?= \Yii::$app->money->convertAndFormat($shopProduct->minProductPrice->money); ?></span>
                            <? endif; ?>
                        </div>
                        <!-- /price -->
                    <? else : ?>
                        <!-- price -->
                        <div class="shop-item-price">
                            <? if ($shopProduct->minProductPrice->id == $shopProduct->baseProductPrice->id) : ?>
                                <?= \Yii::$app->money->convertAndFormat($shopProduct->minProductPrice->money); ?>
                            <? else : ?>
                                <span
                                        class="line-through"><?= \Yii::$app->money->convertAndFormat($shopProduct->baseProductPrice->money); ?></span>
                                <span
                                        class="sx-discount-price"><?= \Yii::$app->money->convertAndFormat($shopProduct->minProductPrice->money); ?></span>
                            <? endif; ?>
                        </div>
                        <!-- /price -->
                    <? endif; ?>

                <? endif; ?>

            </div>
            <div class="sx-offer-show">
                <div class="row">
                    <div class="col-sm-12" style="padding: 15px; text-align: center;">
                        <a href="<?= $model->url; ?>" class="btn btn-primary" data-pjax="0">Подробнее</a>
                    </div>
                </div>
            </div>
        </div>
</li>
