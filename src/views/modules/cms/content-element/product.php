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
\skeeks\assets\unify\base\UnifyHsPopupAsset::register($this);
\skeeks\cms\themes\unifyshop\assets\components\ShopUnifyProductPageAsset::register($this);

$this->registerJs(<<<JS
$.HSCore.components.HSRating.init($('.js-rating-show'), {
  spacing: 2
});
sx.Shop.trigger("viewProduct");
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


//Работа с ценой
$priceHelper = \Yii::$app->shop->shopUser->getProductPriceHelper($model);


$suffix = \Yii::$app->name;
$price = $priceHelper->basePrice->money;
if ($shopProduct->tradeOffers) {
    $price = "от " . $priceHelper->basePrice->money;
}
if (!$model->meta_title) {
    $this->title = "{$model->seoName} - цена {$price} купить в интернет-магазине {$suffix}";
}
if (!$model->meta_description) {
    $desc = strip_tags($model->description_short);
    $this->registerMetaTag([
        "name" => 'description',
        "content" => "✔ {$model->seoName}. ✔ {$desc}. Цена {$price}."
    ], 'description');
}
if (!$model->meta_keywords) {
    $this->registerMetaTag([
        "name" => 'keywords',
        "content" => "{$model->seoName}"
    ], 'keywords');
}




$singlPage = \skeeks\cms\themes\unifyshop\cmsWidgets\product\ShopProductSinglPage::beginWidget('product-page');
$singlPage->addCss();
$singlPage::end();
?>
<section class="sx-product-page-wrapper"
    <?php echo(!$shopProduct->isOffersProduct ? 'itemscope itemtype="https://schema.org/Product"' : ""); ?>
>

    <?php if (!$shopProduct->isOffersProduct) : ?>
        <meta itemprop="name" content="<?= \yii\helpers\Html::encode($model->name); ?> <?= $priceHelper->basePrice->money; ?>"/>
        <link itemprop="url" href="<?= $model->absoluteUrl; ?>"/>
        <meta itemprop="description" content="<?= $model->productDescriptionShort ? \yii\helpers\Html::encode(strip_tags($model->productDescriptionShort)) : '-'; ?>"/>
        <meta itemprop="sku" content="<?= $model->id; ?>"/>
        <? if ($model->mainProductImage) : ?>
            <link itemprop="image" href="<?= $model->mainProductImage->absoluteSrc; ?>">
        <? endif; ?>
        
        <?php if($shopProduct->rating_value) : ?>
            <span itemscope itemtype="https://schema.org/AggregateRating" itemprop="aggregateRating">
                <meta itemprop="bestRating" content="<?php echo \Yii::$app->skeeks->site->shopSite->max_product_rating_value; ?>">
                <meta itemprop="ratingValue" content="<?php echo $shopProduct->rating_value; ?>">
                <meta itemprop="ratingCount" content="<?php echo $shopProduct->rating_count; ?>">
            </span>
        <?php endif; ?>
    
    <?php endif; ?>

    <?php echo $this->render("@app/views/modules/cms/content-element/product/". (\Yii::$app->mobileDetect->isMobile ? "mobile" : \Yii::$app->view->theme->product_page_view_file), [
        'model'                 => $model,
        'singlPage'             => $singlPage,
        'priceHelper'           => $priceHelper,
        'shopProduct'           => $shopProduct,
        'shopOfferChooseHelper' => $shopOfferChooseHelper,
    ]); ?>
</section>


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
