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
/* $widget->viewFile = '@app/views/modules/cms/content-element/_product-properties';*/
?>



<? if ($widget->rpAttributes) : ?>
    <div class="row">
        <div class="col-md-12">
            <h2>Характеристики</h2>
            <? $widget::end(); ?>
        </div>
    </div>
<? endif; ?>

<? if ($model->productDescriptionFull) : ?>
    <div class="row" style="margin-top: 20px;">
        <div class="col-md-12 sx-content" id="sx-description">
            <h2>Описание</h2>
            <div class="sx-product-description">
                <?= $model->productDescriptionFull; ?>
            </div>
        </div>
    </div>
<? endif; ?>

