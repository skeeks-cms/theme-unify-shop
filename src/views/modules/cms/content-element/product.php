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

            <div class="col-md-<?= $singlPage->width_col_short_info; ?> sx-col-product-info">
                <div class="product-info ss-product-info">
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
                                <p>
                                    <a href="#sx-description" class="sx-scroll-to g-font-size-13 sx-dashed g-brd-primary--hover g-color-primary--hover">
                                        Подробнее
                                    </a>
                                </p>
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


    <div class="container">

        <?
        $widget = \skeeks\cms\rpViewWidget\RpViewWidget::beginWidget('product-properties', [
            'model'                   => $infoModel,
            'visible_properties'      => @$visible_items,
            'visible_only_has_values' => true,
            'viewFile'                => '@app/views/widgets/RpWidget/default',
        ]);
        /* $widget->viewFile = '@app/views/modules/cms/content-element/_product-properties';*/
        ?>

        <? if ($widget->visibleRpAttributes) : ?>
            <div class="row">
                <div class="col-md-12">
                    <h2>Характеристики</h2>
                    <? $widget::end(); ?>
                </div>
            </div>
        <? endif; ?>

        <? if ($infoModel->description_full) : ?>
            <div class="row">
                <div class="col-md-12 sx-content" id="sx-description">
                    <h2>Описание</h2>
                    <?= $infoModel->description_full; ?>
                </div>
            </div>
        <? endif; ?>
    </div>
</section>


<? if (\Yii::$app->unifyShopTheme->is_allow_product_review) : ?>
    <section class="g-brd-gray-light-v4 g-brd-top g-mt-20 g-mb-20">
        <div class="container">

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
        </div>
    </section>
<? endif; ?>


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
