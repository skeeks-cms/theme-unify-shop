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

$shopChooseJoinedProductsHelper = null;
if ($shopProduct->shop_product_model_id) {
    $shopChooseJoinedProductsHelper = new \skeeks\cms\shop\helpers\ShopChooseJoinedProductsHelper([
        'shopProduct'            => $shopProduct,
    ]);
    $model = $shopChooseJoinedProductsHelper->offerCmsContentElement;
    $shopProduct = $model->shopProduct;
}

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
$priceAmount = (float)$price->amount;
if ($shopProduct->tradeOffers) {
    $price = "от ".$priceHelper->basePrice->money;
}
if (!$model->meta_title) {
    if ($priceAmount > 0) {
        $this->title = "{$model->seoName} - цена {$price} купить в интернет-магазине {$suffix}";
    } else {
        $this->title = "{$model->seoName} - купить в интернет-магазине {$suffix}";
    }

}
if (!$model->meta_description) {
    $desc = strip_tags((string)$model->description_short);
    $this->registerMetaTag([
        "name"    => 'description',
        "content" => "✔ {$model->seoName}. ✔ {$desc}. Цена {$price}.",
    ], 'description');
}
if (!$model->meta_keywords) {
    $this->registerMetaTag([
        "name"    => 'keywords',
        "content" => "{$model->seoName}",
    ], 'keywords');
}

$brandSavedFilter = null;
if ($brand = $model->shopProduct->brand) {

    \Yii::$app->breadcrumbs->parts = [];

    if ($model->cmsTree) {
        $q = \skeeks\cms\models\CmsSavedFilter::find()->tree($model->tree_id)->brand($brand);
        $brandSavedFilter = $q->one();

        if (!$brandSavedFilter) {
            $brandSavedFilter = new \skeeks\cms\models\CmsSavedFilter();
            $brandSavedFilter->shop_brand_id = $brand->id;
            $brandSavedFilter->cms_tree_id = $model->tree_id;
            $brandSavedFilter->save();
        }
        \Yii::$app->breadcrumbs->setPartsByTree($model->cmsTree);
    } else {
        \Yii::$app->breadcrumbs->createBase();
    }

    \Yii::$app->breadcrumbs->append([
        'url'  => $brandSavedFilter ? $brandSavedFilter->url : $brand->url,
        'name' => $brand->name,
    ]);

    \Yii::$app->breadcrumbs->append([
        'url'  => $model->url,
        'name' => $model->name,
    ]);

}


$singlPage = \skeeks\cms\themes\unifyshop\cmsWidgets\product\ShopProductSinglPage::beginWidget('product-page');
$singlPage->addCss();
$singlPage::end();

if ($this->theme->product_list_images == 2) {
    \skeeks\cms\themes\unifyshop\assets\ProductListImagesV2Asset::register($this);
} elseif ($this->theme->product_list_images == 1) {
    \skeeks\cms\themes\unifyshop\assets\ProductListImagesAsset::register($this);
}

?>
<section class="sx-product-page-wrapper"
    <?php echo(!$shopProduct->isOffersProduct ? 'itemscope itemtype="https://schema.org/Product"' : ""); ?>
>

    <?php if (!$shopProduct->isOffersProduct) : ?>
        <meta itemprop="name" content="<?= \yii\helpers\Html::encode($model->name); ?>"/>
        <link itemprop="url" href="<?= $model->absoluteUrl; ?>"/>
        <?php
        //Описание для разметки (Яндекс требует его у товара): краткое, затем полное описание товара,
        //затем описание его коллекции, иначе строка из названия, бренда, коллекции и страны;
        //без HTML, одной строкой и не длиннее 500 символов
        $productDescription = '';
        $productDescriptionSources = [(string)$model->productDescriptionShort, (string)$model->productDescriptionFull];
        $productDescriptionCollections = $shopProduct->collections;
        if (!$productDescriptionCollections && $shopProduct->isOfferProduct && $shopProduct->shopProductWhithOffers) {
            $productDescriptionCollections = $shopProduct->shopProductWhithOffers->collections;
        }
        foreach ($productDescriptionCollections as $productDescriptionCollection) {
            $productDescriptionSources[] = (string)$productDescriptionCollection->description_short;
            $productDescriptionSources[] = (string)$productDescriptionCollection->description_full;
        }
        $productDescriptionFacts = [];
        if ($shopProduct->brand && trim((string)$shopProduct->brand->name) !== '') {
            $productDescriptionFacts[] = 'Бренд: '.trim((string)$shopProduct->brand->name);
        }
        if ($productDescriptionCollections) {
            $productDescriptionFacts[] = 'Коллекция: '.trim((string)reset($productDescriptionCollections)->name);
        }
        if ($shopProduct->country && trim((string)$shopProduct->country->name) !== '') {
            $productDescriptionFacts[] = 'Страна: '.trim((string)$shopProduct->country->name);
        }
        //как в товарном фиде: цена в карточке и в Offer — за одну единицу товара
        if ($shopProduct->measure_code && $shopProduct->measure && trim((string)$shopProduct->measure->symbol) !== '') {
            $productDescriptionFacts[] = 'Цена указана за 1 '.trim((string)$shopProduct->measure->symbol);
        }
        $productDescriptionSources[] = rtrim(trim((string)$model->seoName ?: (string)$model->name), '.').($productDescriptionFacts ? '. '.implode('. ', $productDescriptionFacts) : '');
        foreach ($productDescriptionSources as $productDescriptionSource) {
            $productDescriptionSource = preg_replace('/<(script|style)\b[^>]*>.*?<\/\1>/isu', ' ', $productDescriptionSource);
            $productDescriptionSource = html_entity_decode(strip_tags(str_replace('<', ' <', $productDescriptionSource)), ENT_QUOTES | ENT_HTML5, 'UTF-8');
            $productDescriptionSource = trim(preg_replace('/\s+/u', ' ', $productDescriptionSource));
            if ($productDescriptionSource !== '') {
                $productDescription = $productDescriptionSource;
                break;
            }
        }
        if (mb_strlen($productDescription) > 500) {
            $productDescription = mb_substr($productDescription, 0, 500);
            $productDescriptionSpace = mb_strrpos($productDescription, ' ');
            if ($productDescriptionSpace > 400) {
                $productDescription = mb_substr($productDescription, 0, $productDescriptionSpace);
            }
            $productDescription = rtrim($productDescription, " ,.;:—-").'…';
        }
        ?>
        <?php if ($productDescription !== '') : ?>
            <meta itemprop="description" content="<?= \yii\helpers\Html::encode($productDescription); ?>"/>
        <?php endif; ?>
        <meta itemprop="sku" content="<?= $model->id; ?>"/>
        <?php if ($shopProduct->brand && trim((string)$shopProduct->brand->name) !== '') : ?>
            <span itemprop="brand" itemscope itemtype="https://schema.org/Brand">
                <meta itemprop="name" content="<?= \yii\helpers\Html::encode(trim($shopProduct->brand->name)); ?>"/>
            </span>
        <?php endif; ?>
        <? if ($model->mainProductImage) : ?>
            <link itemprop="image" href="<?= $model->mainProductImage->absoluteSrc; ?>">
        <? endif; ?>

        <?php //Те же значения и условие, что у видимого рейтинга (_product-rating.php в _product-price.php) ?>
        <?php if ((float)$shopProduct->rating_value > 0 && (int)$shopProduct->rating_count > 0) : ?>
            <span itemscope itemtype="https://schema.org/AggregateRating" itemprop="aggregateRating">
                <meta itemprop="bestRating" content="<?php echo \Yii::$app->skeeks->site->shopSite->max_product_rating_value; ?>">
                <meta itemprop="ratingValue" content="<?php echo $shopProduct->rating_value; ?>">
                <meta itemprop="ratingCount" content="<?php echo $shopProduct->rating_count; ?>">
            </span>
        <?php endif; ?>
    <?php endif; ?>

    <?php echo $this->render("@app/views/modules/cms/content-element/product/".(\Yii::$app->mobileDetect->isMobile ? "mobile" : \Yii::$app->view->theme->product_page_view_file), [
        'model'                 => $model,
        'brandSavedFilter'      => $brandSavedFilter,
        'singlPage'             => $singlPage,
        'priceHelper'           => $priceHelper,
        'shopProduct'           => $shopProduct,
        'shopOfferChooseHelper' => $shopOfferChooseHelper,
        'shopChooseJoinedProductsHelper' => $shopChooseJoinedProductsHelper,
    ]); ?>

    <? if (\Yii::$app->shop->cmsContentPropertyStiker) : ?>
        <?
        /**
         * @var \skeeks\cms\models\CmsContentPropertyEnum[] $enumLabels
         */
        $enumLabels = $model->relatedPropertiesModel->getEnumByAttribute(\Yii::$app->shop->cmsContentPropertyStiker->code);
        if ($enumLabels) :
        ?>
        <?
            $this->registerJs(<<<JS
$(".sx-product-images").append($(".sx-productpage-labels"));
JS
)
            ?>
        <?
            $this->registerCss(<<<CSS
.sx-productpage-labels {
    top: 1.5rem;
}
CSS
)
            ?>
            <div class="sx-labels  sx-productpage-labels">
                <? foreach ($enumLabels as $enum) : ?>
                    <div style="background: <?php echo $enum->color ? $enum->color : "green"; ?>" class="sx-product-label sx-product-label-<?php echo $enum->code; ?>"><?php echo $enum->value; ?></div>
                <? endforeach; ?>
            </div>
        <? endif; ?>
    <? endif; ?>

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
