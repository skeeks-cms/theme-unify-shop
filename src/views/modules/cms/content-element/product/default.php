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
 * @var $brandSavedFilter \skeeks\cms\models\CmsSavedFilter
 */

?>
<div class="container sx-container to-cart-fly-wrapper <?php echo \Yii::$app->adult->renderCssClass($model); ?>">
    <? $pjax = \skeeks\cms\widgets\Pjax::begin(); ?>
    <?

    if ($model->shopProduct->isOfferProduct || $model->shopProduct->isSimpleProduct) {

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
    <div class="sx-main-product-container">
        <div class="sx-product-page--left-col">
            <div class="sx-product-images">

                <?
                $isAdded = \Yii::$app->shop->cart->getShopFavoriteProducts()->andWhere(['shop_product_id' => $model->id])->exists();
                ?>

                <?php echo \Yii::$app->adult->renderBlocked($model); ?>

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


                <?= $this->render("@app/views/modules/cms/content-element/product/".$singlPage->images_view_file, [
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
                    <?/*
                    echo $this->render("@app/views/modules/cms/content-element/_product-right-top-info", [
                        'singlPage'   => $singlPage,
                        'model'       => $model,
                        //'shopProduct'           => $shopProduct,
                        'priceHelper' => $priceHelper,
                    ]); */?>

                    <?
                    echo $this->render("@app/views/modules/cms/content-element/_product-price", [
                        'model'                 => $model,
                        'shopProduct'           => $shopProduct,
                        'priceHelper'           => $priceHelper,
                        'shopOfferChooseHelper' => $shopOfferChooseHelper,
                    ]); ?>
                    <?php
                    /**
                     * @var $cmsContentProperty \skeeks\cms\models\CmsContentProperty
                     */
                    if ($cmsContentProperty = \skeeks\cms\models\CmsContentProperty::find()->cmsSite()->andWhere(['is_vendor' => 1])->one()) : ?>
                        <?php
                        $brandId = $model->relatedPropertiesModel->getAttribute($cmsContentProperty->code);
                        $brand = \skeeks\cms\models\CmsContentElement::findOne((int)$brandId);
                        ?>
                        <?php if ($brand && \Yii::$app->mobileDetect->isDesktop) : ?>
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

                    <div class="sx-properties-wrapper sx-columns-1">
                        <ul class="sx-properties" style="padding-left: 0; padding-right: 0; padding-bottom: 0;">
                            <li>
                                <span class="sx-properties--name">
                                    Код
                                </span>
                                <span class="sx-properties--value">
                                    <?= $model->id; ?>
                                </span>
                            </li>
                            <? if ($model->shopProduct->brand) : ?>
                                <li>
                                    <span class="sx-properties--name">
                                        Бренд
                                    </span>
                                    <span class="sx-properties--value">
                                        <?php if($model->shopProduct->brand->logo_image_id) : ?>
                                            <? $logo = $model->shopProduct->brand->logo; ?>

                                            <img class="img-fluid"
                                                 src="<?= \Yii::$app->imaging->thumbnailUrlOnRequest($logo->src,
                                                new \skeeks\cms\components\imaging\filters\Thumbnail([
                                                    'w' => 0,
                                                    'h' => 20,
                                                    'm' => \Imagine\Image\ImageInterface::THUMBNAIL_OUTBOUND,
                                                ]), $model->shopProduct->brand->code
                                            ); ?>" alt="<?= $model->shopProduct->brand->name; ?>">
                                        <?php endif; ?>

                                        <a href="<?php echo $model->shopProduct->brand->url; ?>" data-pjax="0">
                                            <?= $model->shopProduct->brand->name; ?>
                                        </a>
                                    </span>
                                </li>
                            <? endif; ?>
                            <? if ($model->shopProduct->country) : ?>
                                <li>
                                    <span class="sx-properties--name">
                                        Страна
                                    </span>
                                    <span class="sx-properties--value">
                                        <?php if($model->shopProduct->country->flag_image_id) : ?>
                                            <? $flag = $model->shopProduct->country->flag; ?>

                                            <img class="img-fluid"
                                                 src="<?= \Yii::$app->imaging->thumbnailUrlOnRequest($flag->src,
                                                new \skeeks\cms\components\imaging\filters\Thumbnail([
                                                    'w' => 0,
                                                    'h' => 20,
                                                    'm' => \Imagine\Image\ImageInterface::THUMBNAIL_OUTBOUND,
                                                ]), $model->shopProduct->country->alpha2
                                            ); ?>" alt="<?= $model->shopProduct->country->name; ?>">
                                        <?php endif; ?>

                                        <?= $model->shopProduct->country->name; ?>
                                    </span>
                                </li>
                            <? endif; ?>
                            <? if ($model->shopProduct->weight) : ?>
                                <li>
                                    <span class="sx-properties--name">
                                        Вес с упаковкой
                                    </span>
                                    <span class="sx-properties--value">
                                        <?= $model->shopProduct->weightFormatted; ?>
                                    </span>
                                </li>
                            <? endif; ?>
                            <? if ($model->shopProduct->dimensionsFormated) : ?>
                                <li>
                                    <span class="sx-properties--name">
                                        Габариты с упаковкой
                                    </span>
                                    <span class="sx-properties--value">
                                        <?= $model->shopProduct->dimensionsFormated; ?>
                                    </span>
                                </li>
                            <? endif; ?>
                        </ul>
                    </div>

                    <?
                    $this->registerCss(<<<CSS
.sx-fast-links a {
    color: var(--main-text);
}
.sx-fast-links a i {
    font-size: 0.8rem;
}
CSS
);
                    ?>
                    <div class="sx-fast-links">
                        <?php if($model->tree_id) : ?>
                            <p>
                                <a href="<?php echo $model->cmsTree->url; ?>" data-pjax="0">Все <?php echo \skeeks\cms\helpers\StringHelper::strtolower($model->cmsTree->name); ?> <i class="hs-icon hs-icon-arrow-right"></i></a>
                            </p>
                        <?php endif; ?>
                        <?php if($brandSavedFilter) : ?>
                            <p>
                                <a href="<?php echo $brandSavedFilter->url; ?>" data-pjax="0">Все <?php echo \skeeks\cms\helpers\StringHelper::strtolower($model->cmsTree->name); ?> <?php echo $model->shopProduct->brand->name; ?> <i class="hs-icon hs-icon-arrow-right"></i></a>
                            </p>
                        <?php endif; ?>
                        <?php if($model->shopProduct->brand) : ?>
                            <p>
                                <a href="<?php echo $model->shopProduct->brand->url; ?>" data-pjax="0">Страница бренда <?php echo $model->shopProduct->brand->name; ?> <i class="hs-icon hs-icon-arrow-right"></i></a>
                            </p>
                        <?php endif; ?>
                    </div>

                    <? if ($model->productDescriptionShort) : ?>
                        <div class="sx-description-short">
                            <?= $model->productDescriptionShort; ?>
                        </div>
                    <? endif; ?>

                    <?= $this->render("@app/views/modules/cms/content-element/_product-right-bottom-info", [
                        'model'                 => $model,
                        //'shopProduct'           => $shopProduct,
                        'priceHelper'           => $priceHelper,
                        'shopOfferChooseHelper' => $shopOfferChooseHelper,
                    ]); ?>
                </div>
            </div>
        </div>
    </div>
    <? $pjax::end(); ?>
</div>


<section class="sx-product-info-wrapper">
    <div class="container sx-container">
        <?= $this->render("@app/views/modules/cms/content-element/_product-info-".$singlPage->info_block_view_type, [
            'singlPage'             => $singlPage,
            'model'                 => $model,
            'shopProduct'           => $shopProduct,
            'priceHelper'           => $priceHelper,
            'shopOfferChooseHelper' => $shopOfferChooseHelper,
        ]); ?>
    </div>
</section>

<?= $this->render("@app/views/modules/cms/content-element/_product-bottom-info", [
    'model'                 => $model,
    'shopProduct'           => $shopProduct,
    'priceHelper'           => $priceHelper,
    'shopOfferChooseHelper' => $shopOfferChooseHelper,
    'singlPage'             => $singlPage,
]); ?>
