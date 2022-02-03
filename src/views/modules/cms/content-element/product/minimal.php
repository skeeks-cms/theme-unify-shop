<?php
/**
 * @link https://cms.skeeks.com/
 * @copyright Copyright (c) 2010 SkeekS
 * @license https://cms.skeeks.com/license/
 * @author Semenov Alexander <semenov@skeeks.com>
 */
/**
 * @var $this yii\web\View
 * @var $model \skeeks\cms\shop\models\ShopCmsContentElement
 * @var $singlPage \skeeks\cms\themes\unifyshop\cmsWidgets\product\ShopProductSinglPage
 * @var $priceHelper \skeeks\cms\shop\helpers\ProductPriceHelper
 * @var $shopOfferChooseHelper \skeeks\cms\shop\helpers\ShopOfferChooseHelper
 * @var $shopProduct \skeeks\cms\shop\models\ShopProduct
 */

?>
<section class="sx-minimal-page">
    <div class="container sx-container to-cart-fly-wrapper">
        <? $pjax = \skeeks\cms\widgets\Pjax::begin(); ?>
        <?

if ($model->shopProduct->isOfferProduct || $model->shopProduct->isOfferProduct) {

    $data = \skeeks\cms\shop\components\ShopComponent::productDataForJsEvent($model);
    $jsData = \yii\helpers\Json::encode($data);
    $this->registerJs(<<<JS
sx.onReady(function() {
    sx.Shop.trigger("detail", {$jsData});
});
    
JS
    );
}
    ?>
        <div class="row">
            <div class="col-md-12">
                <?= $this->render('@app/views/breadcrumbs', [
                    'model'    => $model,
                    'isShowH1' => $singlPage->is_show_title_in_breadcrumbs,
                ]); ?>
            </div>
        </div>
        <div class="d-flex flex-row sx-main-product-container">
            <div class="sx-product-page--left-col">
                <div class="sx-product-images">

                    <?
                    $isAdded = \Yii::$app->shop->cart->getShopFavoriteProducts()->andWhere(['shop_product_id' => $model->id])->exists();
                    ?>

                    <div class="sx-favorite-product"
                         data-added-icon-class="fas fa-heart"
                         data-not-added-icon-class="far fa-heart"
                         data-is-added="<?= (int)$isAdded ?>"
                         data-product_id="<?= (int)$model->id ?>"
                    >
                        <a href="#" class="sx-favorite-product-trigger" data-pjax="0" style="font-size: 22px;">
                            <? if ($isAdded) : ?>
                                <i class="fas fa-heart"></i>
                            <? else : ?>
                                <i class="far fa-heart"></i>
                            <? endif; ?>
                        </a>
                    </div>

                    <?= $this->render("@app/views/modules/cms/content-element/product/" . $singlPage->images_view_file, [
                        'model' => $model,
                    ]); ?>
                </div>
            </div>
            <div class="sx-product-page--right-col sx-col-product-info">
                <div class="sx-right-product-info product-info ss-product-info" style="min-height: 100%;">
                    <? if ($singlPage->is_show_title_in_short_description) : ?>
                        <h1 class="h4"><?= $model->seoName; ?></h1>
                    <? endif; ?>
                    <div class="product-info-header">
                        <?
                        /*var_dump($shopOfferChooseHelper);die;*/

                        echo $this->render("@app/views/modules/cms/content-element/_product-right-top-info", [
                            'singlPage'   => $singlPage,
                            'model'       => $model,
                            //'shopProduct'           => $shopProduct,
                            'priceHelper' => $priceHelper,
                        ]); ?>

                        <?
                        echo $this->render("@app/views/modules/cms/content-element/_product-price", [
                            'model'                 => $model,
                            'shopProduct'           => $shopProduct,
                            'priceHelper'           => $priceHelper,
                            'shopOfferChooseHelper' => $shopOfferChooseHelper,
                        ]); ?>
                        <?php
                        /**
                         * @var $shopCmsContentProperty \skeeks\cms\shop\models\ShopCmsContentProperty
                         */
                        if ($shopCmsContentProperty = \skeeks\cms\shop\models\ShopCmsContentProperty::find()->where(['is_vendor' => 1])->one()) : ?>
                            <?php
                            $brandId = $model->relatedPropertiesModel->getAttribute($shopCmsContentProperty->cmsContentProperty->code);
                            $brand = \skeeks\cms\models\CmsContentElement::findOne((int)$brandId);
                            ?>
                            <?php if ($brand) : ?>
                                <div class="sx-short-brand-info row g-mb-20" style="background: #92929212;
    padding: 5px;">
                                    <div class="col-md-8 my-auto">
                                        <?php echo $brand->name; ?>
                                    </div>
                                    <?php if ($brand->image) : ?>
                                        <div class="col-md-4 my-auto" style=" text-align: right;">
                                            <img class="img-fluid" src="<?php echo $brand->image->src; ?>" style="max-height: 40px;"/>
                                        </div>
                                    <?php endif; ?>

                                </div>
                            <?php endif; ?>
                        <?php endif; ?>
                        <? if ($model->productDescriptionShort) : ?>
                            <div class="sx-description-short">
                                <?= $model->productDescriptionShort; ?>
                            </div>
                        <? endif; ?>

                        <div class="sx-properties-wrapper sx-columns-1">
                        <?= $this->render("@app/views/modules/cms/content-element/_product-info-".$singlPage->info_block_view_type, [
                            'singlPage'             => $singlPage,
                            'model'                 => $model,
                            'shopProduct'           => $shopProduct,
                            'priceHelper'           => $priceHelper,
                            'shopOfferChooseHelper' => $shopOfferChooseHelper,
                        ]); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <? $pjax::end(); ?>
    </div>


    <?= $this->render("@app/views/modules/cms/content-element/_product-bottom-info", [
        'model'                 => $model,
        'shopProduct'           => $shopProduct,
        'priceHelper'           => $priceHelper,
        'shopOfferChooseHelper' => $shopOfferChooseHelper,
    ]); ?>
</section>