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
<section class="sx-mobile-product-page <?php echo \Yii::$app->adult->renderCssClass($model); ?>">
    <div class="container sx-container to-cart-fly-wrapper">

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
            <div class="col-12">
                <?= $this->render('@app/views/breadcrumbs', [
                    'model'    => $model,
                    'isShowH1' => true,
                ]); ?>
            </div>
        </div>


        <div class="row">

            <div class="col-12">
                <div class="sx-product-images">
                    <?php echo \Yii::$app->adult->renderBlocked($model); ?>

                    <?= $this->render("@app/views/modules/cms/content-element/product/_product-images", [
                        'model' => $model,
                    ]); ?>
                </div>
            </div>

            <div class="col-12 sx-col-product-info">
                <div class="sx-right-product-info product-info ss-product-info">
                    <? /* if ($singlPage->is_show_title_in_short_description) : */ ?><!--
                    <h1 class="h4"><? /*= $model->seoName; */ ?></h1>
                --><? /* endif; */ ?>
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
    font-size: 1rem;
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

        <div class="sx-properties-wrapper sx-columns-1">
            <?= $this->render("@app/views/modules/cms/content-element/_product-info-v1", [
                'singlPage'             => $singlPage,
                'model'                 => $model,
                'shopProduct'           => $shopProduct,
                'priceHelper'           => $priceHelper,
                'shopOfferChooseHelper' => $shopOfferChooseHelper,
            ]); ?>
        </div>



    </div>

    <?= $this->render("@app/views/modules/cms/content-element/_product-bottom-info", [
            'model'                 => $model,
            'shopProduct'           => $shopProduct,
            'priceHelper'           => $priceHelper,
            'shopOfferChooseHelper' => $shopOfferChooseHelper,
        ]); ?>

</section>


