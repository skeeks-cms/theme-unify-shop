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
//Если этот товар привязан к главному
$infoModel = $model;
/*if ($model->main_cce_id) {
    $shopMainProduct = $model->mainCmsContentElement->shopProduct;
    if ($shopMainProduct->isOfferProduct) {
        $infoModel = $shopMainProduct->shopProductWhithOffers->cmsContentElement;
    } else {
        $infoModel = $model->mainCmsContentElement;
    }
}*/
if ($shopProduct->isOfferProduct) {
    $infoModel = $shopProduct->shopProductWhithOffers->cmsContentElement;
}
?>
<?
$widget = \skeeks\cms\rpViewWidget\RpViewWidget::beginWidget('product-properties', [
    'model'                   => $infoModel,
    'visible_only_has_values' => true,
]);
$widget->viewFile = '@app/views/widgets/RpWidget/'.$singlPage->properties_view_file;
?>

<div class="row">
    <div class="col-md-12">
        <div id="sx-product-info-accordion" class="sx-product-info-accordion">

            <? if ($widget->rpAttributes) : ?>
                <div class="card">
                    <div class="card-header " id="sx-properties-header">
                        <a class="h2 sx-main-text-color" href="#" data-toggle="collapse" data-target="#sx-properties" aria-expanded="true" aria-controls="sx-properties">
                            Характеристики
                            <i class="hs-icon hs-icon-arrow-bottom float-right"></i>
                        </a>
                    </div>
                    <!--data-parent="#sx-product-info-accordion-->

                    <div id="sx-properties" class="collapse" aria-labelledby="sx-properties-header">
                        <div class="card-body">
                            <? $widget::end(); ?>
                        </div>
                    </div>
                </div>
            <? endif; ?>

            <? if ($model->productDescriptionFull) : ?>

                <div class="card">
                    <div class="card-header" id="sx-description-header">
                        <a class="h2 sx-main-text-color" href="#" data-toggle="collapse" data-target="#sx-description" aria-expanded="true" aria-controls="sx-description">
                            Описание
                            <i class="hs-icon hs-icon-arrow-bottom float-right"></i>
                        </a>
                    </div>
                    <div id="sx-description" class="collapse" aria-labelledby="sx-description-header">
                        <div class="card-body">
                            <div class="sx-product-description">
                                <?= $model->productDescriptionFull; ?>
                            </div>
                        </div>
                    </div>
                </div>

            <? endif; ?>
        </div>
    </div>
</div>

