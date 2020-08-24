<?php
/**
 * @link https://cms.skeeks.com/
 * @copyright Copyright (c) 2010 SkeekS
 * @license https://cms.skeeks.com/license/
 * @author Semenov Alexander <semenov@skeeks.com>
 */
/* @var $this yii\web\View */
/* @var $model \skeeks\cms\shop\models\ShopCmsContentElement */
skeeks\assets\unify\base\UnifyHsRatingAsset::register($this);
$this->registerJs(<<<JS
$.HSCore.components.HSRating.init($('.js-rating-show'), {
  spacing: 2
});
JS
);
$shopOfferChooseHelper = null;

$shopProduct = $model->shopProduct;
//Если это страница товара-предложения
if ($shopProduct->isOfferProduct) {
    $shopProductOffer = $shopProduct;
    //$shopProduct = $shopProduct->shopProductWhithOffers;
    //$model = $shopProduct->cmsContentElement;
    $shopOfferChooseHelper = new \skeeks\cms\shop\helpers\ShopOfferChooseHelper([
        'shopProduct'            => $shopProduct->shopProductWhithOffers,
        'offerCmsContentElement' => $shopProductOffer->cmsContentElement,
    ]);

    if ($shopOfferChooseHelper->offerCmsContentElement && $model->id != $shopOfferChooseHelper->offerCmsContentElement->id) {
        $model = $shopOfferChooseHelper->offerCmsContentElement;
    }

} elseif ($shopProduct->isOffersProduct) {
    //Если это страница товара с предложением
    $shopOfferChooseHelper = new \skeeks\cms\shop\helpers\ShopOfferChooseHelper([
        'shopProduct' => $shopProduct,
    ]);

    if ($shopOfferChooseHelper->offerCmsContentElement && $model->id != $shopOfferChooseHelper->offerCmsContentElement->id) {
        $model = $shopOfferChooseHelper->offerCmsContentElement;
    }
}


$infoModel = $model;
if ($shopProduct->main_pid) {
if ($shopProduct->shopMainProduct->isOfferProduct) {
    $infoModel = $shopProduct->shopMainProduct->shopProductWhithOffers->cmsContentElement;
} else {
    $infoModel = $shopProduct->shopMainProduct->cmsContentElement;
}
}

//Работа с ценой
$priceHelper = \Yii::$app->shop->shopUser->getProductPriceHelper($model);

$singlPage = \skeeks\cms\themes\unifyshop\cmsWidgets\product\ShopProductSinglPage::beginWidget('product-page');
$singlPage->addCss();
$singlPage::end();
?>
<section class="sx-product-card-wrapper g-mt-0 g-pb-0 to-cart-fly-wrapper"
    <?php echo(!$shopProduct->isOffersProduct ? 'itemscope itemtype="http://schema.org/Product"' : ""); ?>
>

    <?php if (!$shopProduct->isOffersProduct) : ?>
        <meta itemprop="name" content="<?= \yii\helpers\Html::encode($model->name); ?> <?= $priceHelper->basePrice->money; ?>"/>
        <link itemprop="url" href="<?= $model->absoluteUrl; ?>"/>
        <meta itemprop="description" content="<?= $model->productDescriptionShort ? \yii\helpers\Html::encode(strip_tags($model->productDescriptionShort)) : '-'; ?>"/>
        <meta itemprop="sku" content="<?= $model->id; ?>"/>
        <? if ($model->mainProductImage) : ?>
            <link itemprop="image" href="<?= $model->mainProductImage->absoluteSrc; ?>">
        <? endif; ?>
    <?php endif; ?>


    <div class="container sx-container g-py-20">

        <? $pjax = \skeeks\cms\widgets\Pjax::begin(); ?>

        <div class="row">
            <div class="col-md-12">
                <?= $this->render('@app/views/breadcrumbs', [
                    'model'    => $model,
                    'isShowH1' => $singlPage->is_show_title_in_breadcrumbs
                    /*'isShowLast' => true,
                    'isShowH1'   => false,*/
                ]); ?>
            </div>
        </div>


        <div class="row g-mt-20">
            <div class="col-md-<?= $singlPage->width_col_images; ?>">
                <div class="sx-product-images g-ml-40 g-mr-40">
                    <?= $this->render("_product-images", [
                        'model' => $model,
                    ]); ?>
                </div>
            </div>

            <div class="col-md-<?= 12 - $singlPage->width_col_images; ?> sx-col-product-info">
                <div class="sx-right-product-info product-info ss-product-info" style="min-height: 100%;">
                    <? if ($singlPage->is_show_title_in_short_description) : ?>
                        <h1 class="h4 g-font-weight-600"><?= $model->seoName; ?></h1>
                    <? endif; ?>
                    <div class="product-info-header">


                        <?= $this->render("@app/views/modules/cms/content-element/_product-right-top-info", [
                            'singlPage'   => $singlPage,
                            'model'       => $model,
                            //'shopProduct'           => $shopProduct,
                            'priceHelper' => $priceHelper,
                        ]); ?>

                        <?= $this->render("@app/views/modules/cms/content-element/_product-price", [
                            'model'                 => $model,
                            'shopProduct'           => $shopProduct,
                            'priceHelper'           => $priceHelper,
                            'shopOfferChooseHelper' => $shopOfferChooseHelper,
                        ]); ?>


                        <?php
                        /**
                         * @var $shopCmsContentProperty \skeeks\cms\shop\models\ShopCmsContentProperty
                         */
                        if($shopCmsContentProperty = \skeeks\cms\shop\models\ShopCmsContentProperty::find()->where(['is_vendor' => 1])->one()) : ?>
                            <?php 
                            $brandId = $infoModel->relatedPropertiesModel->getAttribute($shopCmsContentProperty->cmsContentProperty->code);
                            $brand = \skeeks\cms\models\CmsContentElement::findOne((int)$brandId);
                            ?>
                            <?php if($brand) : ?>
                            <div class="sx-short-brand-info row g-mb-20" style="background: #92929212;
    padding: 5px;">
                                <div class="col-md-6 my-auto">
                                Бренд: <?php echo $brand->name; ?>
                                </div>
                                <?php if($brand->image) : ?>
                                <div class="col-md-6 my-auto" style=" text-align: right;">
                                    <img src="<?php echo $brand->image->src; ?>" style="max-height: 40px;" />
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

</section>


<?= $this->render("@app/views/modules/cms/content-element/_product-info-".$singlPage->info_block_view_type, [
    'singlPage'             => $singlPage,
    'model'                 => $model,
    'shopProduct'           => $shopProduct,
    'priceHelper'           => $priceHelper,
    'shopOfferChooseHelper' => $shopOfferChooseHelper,
]); ?>


<?= $this->render("@app/views/modules/cms/content-element/_product-bottom-info", [
    'model'                 => $model,
    'shopProduct'           => $shopProduct,
    'priceHelper'           => $priceHelper,
    'shopOfferChooseHelper' => $shopOfferChooseHelper,
]); ?>



<?
$modal = \yii\bootstrap\Modal::begin([
    'header'       => 'Оставить заявку',
    'id'           => 'sx-order',
    'toggleButton' => false,
    'size'         => \yii\bootstrap\Modal::SIZE_DEFAULT,
]);
?>
<?= \skeeks\modules\cms\form2\cmsWidgets\form2\FormWidget::widget([
    'form_code' => 'feedback',
    'namespace' => 'FormWidget-feedback',
    'viewFile'  => 'with-messages'
    //'viewFile' => '@app/views/widgets/FormWidget/fiz-connect'
]); ?>

<?
$modal::end();
?>
