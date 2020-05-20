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
$this->registerCss(<<<CSS
.sx-product-info-accordion .card {
    border-radius: 0;
    margin-bottom: 10px;
}
CSS
);
?>

<section class="sx-product-info-wrapper g-mt-0 g-pb-0">
    <div class="container sx-container">
        <div class="row">
            <div class="col-md-12">
                <div id="sx-product-info-accordion" class="sx-product-info-accordion">

                    <? if ($widget->visibleRpAttributes) : ?>
                        <div class="card">
                            <div class="card-header" id="sx-properties-header">
                                <a class="h3" href="#" data-toggle="collapse" data-target="#sx-properties" aria-expanded="true" aria-controls="sx-properties">
                                    Характеристики
                                </a>
                            </div>
                            <div id="sx-properties" class="collapse" aria-labelledby="sx-properties-header" data-parent="#sx-product-info-accordion">
                                <div class="card-body">
                                    <? $widget::end(); ?>
                                </div>
                            </div>
                        </div>
                    <? endif; ?>

                    <? if ($model->description_full) : ?>

                        <div class="card">
                            <div class="card-header" id="sx-description-header">
                                <a class="h3" href="#" data-toggle="collapse" data-target="#sx-description" aria-expanded="true" aria-controls="sx-description">
                                    Описание
                                </a>
                            </div>
                            <div id="sx-description" class="collapse" aria-labelledby="sx-description-header" data-parent="#sx-product-info-accordion">
                                <div class="card-body">
                                    <?= $model->description_full; ?>
                                </div>
                            </div>
                        </div>

                    <? endif; ?>

                    <? if ($singlPage->is_allow_product_review) : ?>

                        <div class="card">
                            <div class="card-header" id="sx-reviews-header">
                                <a class="h3" href="#" data-toggle="collapse" data-target="#sx-reviews" aria-expanded="true" aria-controls="sx-reviews">
                                    Отзывы
                                </a>
                            </div>
                            <div id="sx-reviews" class="collapse" aria-labelledby="sx-reviews-header" data-parent="#sx-product-info-accordion">
                                <div class="card-body">

                                    <div class="col-12">
                                        <a href="#showReviewFormBlock" data-toggle="modal" class="btn btn-primary showReviewFormBtn">Оставить отзыв</a>
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
                            </div>
                        </div>
                    <? endif; ?>
                </div>
            </div>
        </div>

    </div>
</section>

