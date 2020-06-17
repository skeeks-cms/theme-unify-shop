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
$this->registerJs(<<<JS
$(".nav-link:eq(0)", $(".sx-product-info-wrapper")).click();
JS
);
?>

<section class="sx-product-info-wrapper g-mt-0 g-pb-20">
    <div class="container sx-container">
        <ul class="nav u-nav-v5-1 u-nav-primary g-brd-bottom--md g-brd-gray-light-v4" role="tablist" data-target="nav-5-1-default-hor-border-bottom-left-padding-0" data-tabs-mobile-type="slide-up-down"
            data-btn-classes="btn btn-md btn-block rounded-0 u-btn-outline-lightgray">
            <? if ($widget->rpAttributes) : ?>
                <li class="nav-item">
                    <a class="nav-link sx-main-text-color g-px-0--md g-mr-30--md active" data-toggle="tab" href="#sx-properties" role="tab">Характеристики</a>
                </li>
            <? endif; ?>

            <? if ($model->description_full) : ?>
                <li class="nav-item">
                    <a class="nav-link sx-main-text-color g-px-0--md g-mr-30--md" data-toggle="tab" href="#sx-description" role="tab">Описание</a>
                </li>
            <? endif; ?>

            <? if ($singlPage->is_allow_product_review) : ?>
                <li class="nav-item">
                    <a class="nav-link sx-main-text-color g-px-0--md g-mr-30--md" data-toggle="tab" href="#sx-reviews" role="tab">Отзывы</a>
                </li>
            <? endif; ?>
        </ul>

        <div id="nav-5-1-default-hor-border-bottom-left-padding-0" class="tab-content g-pt-20">
            <? if ($widget->rpAttributes) : ?>
                <div class="tab-pane fade show active" id="sx-properties" role="tabpanel">
                    <div class="card-body-1">
                        <? $widget::end(); ?>
                    </div>
                </div>
            <? endif; ?>
            <? if ($model->description_full) : ?>
                <div class="tab-pane fade show" id="sx-description" role="tabpanel">
                    <div class="card-body-1">
                        <?= $model->description_full; ?>
                    </div>
                </div>
            <? endif; ?>
            <? if ($singlPage->is_allow_product_review) : ?>
                <div class="tab-pane fade show" id="sx-reviews" role="tabpanel">
                    <div class="card-body-1">
                        <div class="row">
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
</section>

