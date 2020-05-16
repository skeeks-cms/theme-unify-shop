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

$shopProduct = $model->shopProduct;
$priceHelper = \Yii::$app->shop->cart->getProductPriceHelper($model);

//Если этот товар привязан к главному
$infoModel = $model;
if ($shopProduct->main_pid) {
    if ($shopProduct->shopMainProduct->isOfferProduct) {
        $element = $shopProduct->shopMainProduct->cmsContentElement;
        $infoModel = $element->parentContentElement;
        $infoModel->name = $element->name;
    } else {
        $infoModel = $shopProduct->shopMainProduct->cmsContentElement;
    }
}


$shopOfferChooseHelper = null;
if ($shopProduct->isOffersProduct) {
    $shopOfferChooseHelper = new \skeeks\cms\shop\helpers\ShopOfferChooseHelper([
        'shopProduct' => $shopProduct,
    ]);
}
$singlPage = \skeeks\cms\themes\unifyshop\cmsWidgets\product\ShopProductSinglPage::beginWidget('product-page');
$singlPage->addCss();
$singlPage::end();
?>
<section class="sx-product-card-wrapper g-mt-0 g-pb-0 to-cart-fly-wrapper" itemscope itemtype="http://schema.org/Product">
    <meta itemprop="name" content="<?= \yii\helpers\Html::encode($infoModel->name); ?><?= $priceHelper->basePrice->money; ?>"/>
    <link itemprop="url" href="<?= $model->absoluteUrl; ?>"/>
    <meta itemprop="description" content="<?= $infoModel->description_short ? \yii\helpers\Html::encode($infoModel->description_short) : '-'; ?>"/>
    <meta itemprop="sku" content="<?= $model->id; ?>"/>

    <? if ($infoModel->image) : ?>
        <link itemprop="image" href="<?= $infoModel->image->absoluteSrc; ?>">
    <? endif; ?>

    <div class="container sx-container g-py-20">
        <div class="row">
            <div class="col-md-12">
                <?= $this->render('@app/views/breadcrumbs', [
                    'model'    => $infoModel,
                    'isShowH1' => $singlPage->is_show_title_in_breadcrumbs
                    /*'isShowLast' => true,
                    'isShowH1'   => false,*/
                ]); ?>
            </div>
        </div>

        <? $pjax = \skeeks\cms\widgets\Pjax::begin(); ?>
        <div class="row">
            <div class="col-md-<?= $singlPage->width_col_images; ?>">
                <div class="sx-product-images g-ml-40 g-mr-40">
                    <?= $this->render("_product-images", [
                        'model'                 => $infoModel,
                        'shopOfferChooseHelper' => $shopOfferChooseHelper,

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
                            'model'                 => $model,
                            'shopProduct'           => $shopProduct,
                            'priceHelper'           => $priceHelper,
                            'shopOfferChooseHelper' => $shopOfferChooseHelper,
                        ]); ?>

                        <?= $this->render("@app/views/modules/cms/content-element/_product-price", [
                            'model'                 => $model,
                            'shopProduct'           => $shopProduct,
                            'priceHelper'           => $priceHelper,
                            'shopOfferChooseHelper' => $shopOfferChooseHelper,
                        ]); ?>


                        <? if ($infoModel->description_short) : ?>
                            <div class="sx-description-short">
                                <?= $infoModel->description_short; ?>
                                <? if ($infoModel->description_full) : ?>
                                    <p>
                                        <a href="#sx-description" class="sx-scroll-to g-font-size-13 sx-dashed g-brd-primary--hover g-color-primary--hover">
                                            Подробнее
                                        </a>
                                    </p>
                                <? endif; ?>
                            </div>
                        <? endif; ?>

                        <?= $this->render("@app/views/modules/cms/content-element/_product-right-bottom-info", [
                            'model'                 => $infoModel,
                            'shopProduct'           => $shopProduct,
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



<?= $this->render("@app/views/modules/cms/content-element/_product-info-" . $singlPage->info_block_view_type, [
    'model'                 => $infoModel,
    'shopProduct'           => $shopProduct,
    'priceHelper'           => $priceHelper,
    'shopOfferChooseHelper' => $shopOfferChooseHelper,
]); ?>


<?= $this->render("@app/views/modules/cms/content-element/_product-bottom-info", [
    'model'                 => $infoModel,
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
