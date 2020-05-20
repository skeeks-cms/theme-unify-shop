<?php
/**
 * @link https://cms.skeeks.com/
 * @copyright Copyright (c) 2010 SkeekS
 * @license https://cms.skeeks.com/license/
 * @author Semenov Alexander <semenov@skeeks.com>
 */
/* @var $model \skeeks\cms\shop\models\ShopCmsContentElement */
/* @var $shopOfferChooseHelper \skeeks\cms\shop\helpers\ShopOfferChooseHelper */
/* @var $shopProduct \skeeks\cms\shop\models\ShopProduct */
/* @var $priceHelper \skeeks\cms\shop\helpers\ProductPriceHelper */
/* @var $singlPage \skeeks\cms\themes\unifyshop\cmsWidgets\product\ShopProductSinglPage */
/* @var $this yii\web\View */
?>
<?
$widget = \skeeks\cms\rpViewWidget\RpViewWidget::beginWidget('product-properties', [
    'model'                   => $model,
    'visible_only_has_values' => true,
    'viewFile'                => '@app/views/widgets/RpWidget/default',
]);
/* $widget->viewFile = '@app/views/modules/cms/content-element/_product-properties';*/
?>

<section class="sx-product-info-wrapper g-mt-0 g-pb-0">
    <div class="container sx-container">

        <? if ($widget->visibleRpAttributes) : ?>
            <div class="row">
                <div class="col-md-12">
                    <h2>Характеристики</h2>
                    <? $widget::end(); ?>
                </div>
            </div>
        <? endif; ?>

        <? if ($model->description_full) : ?>
            <div class="row">
                <div class="col-md-12 sx-content" id="sx-description">
                    <h2>Описание</h2>
                    <?= $model->description_full; ?>
                </div>
            </div>
        <? endif; ?>

        <? if ($singlPage->is_allow_product_review) : ?>
            <div class="row">
                <div class="col-md-12 g-mt-20" id="sx-reviews">
                    <div class="float-right"><a href="#showReviewFormBlock" data-toggle="modal" class="btn btn-primary showReviewFormBtn">Оставить отзыв</a></div>
                    <h2>Отзывы</h2>
                </div>

                <?
                $widgetReviews = \skeeks\cms\reviews2\widgets\reviews2\Reviews2Widget::begin([
                    'namespace'         => 'Reviews2Widget',
                    'viewFile'          => '@app/views/widgets/Reviews2Widget/reviews',
                    'cmsContentElement' => $model,
                ]);
                $widgetReviews::end();
                ?>
            </div>

        <? endif; ?>
    </div>
</section>