<?php
/**
 * @link https://cms.skeeks.com/
 * @copyright Copyright (c) 2010 SkeekS
 * @license https://cms.skeeks.com/license/
 * @author Semenov Alexander <semenov@skeeks.com>
 *
 */
/**
 * @property \skeeks\cms\shop\models\ShopCollection $model
 */
\skeeks\assets\unify\base\UnifyHsPopupAsset::register($this);
\skeeks\cms\themes\unifyshop\assets\components\ShopUnifyProductPageAsset::register($this);
\skeeks\cms\themes\unifyshop\assets\components\ShopUnifyProductCardAsset::register($this);

$product = null;
$this->registerCss(<<<CSS
.js-carousel.slick-initialized .js-slide, .js-carousel.slick-initialized .js-thumb {
    margin-right: 10px;
    margin-top: 10px;
    cursor: pointer;
}

.cbp-filter-item a {
    border: 1px solid;
    font-size: 16px;
}

@media (max-width: 768px) {
    ul.sx-properties
    {
        -moz-column-count: 1;
        column-count: 1;
    }
}

.sx-container {
    overflow: hidden;
}

CSS
);

$infoModel = $model;

$this->title = "Коллекция ".$infoModel->seoName." " . ($infoModel->brand ? $infoModel->brand->name : "") ."";
$properties = [];
?>




<?
/**
 * @var \skeeks\cms\shop\models\ShopCmsContentElement[] $collectionProducts
 */
$query = \skeeks\cms\shop\models\ShopCmsContentElement::find()
    ->cmsSite()
    ->active()
    //->joinWith("cmsContentElementPropertyValues.element as cmsElement")
    ->innerJoinWith("shopProduct as shopProduct", false)
    ->innerJoinWith("shopProduct.collections as collections", false)
    ->andWhere([
        'collections.id' => $model->id //Товары
    ]);

//\Yii::$app->shop->filterBaseContentElementQuery($query);
//\Yii::$app->shop->filterByQuantityQuery($query);

$collectionProducts = $query->all();
?>

<?php if ($collectionProducts) : ?>

    <?
    skeeks\assets\unify\base\UnifyHsCubeportfolioAsset::register($this);

    $this->registerJs(<<<JS
$.HSCore.components.HSCubeportfolio.init('.cbp');
JS
    );
    $ids = \yii\helpers\ArrayHelper::map($collectionProducts, "id", 'id');

    $allProps = \skeeks\cms\models\CmsContentElementProperty::find()
        ->leftJoin(\skeeks\cms\models\CmsContentProperty::tableName().' ccp', 'ccp.id ='.skeeks\cms\models\CmsContentElementProperty::tableName().'.property_id')
        ->andWhere(['element_id' => $ids])
        ->groupBy(['property_id'])
        ->select(['property_id'])
        ->asArray()
        ->all();


    $allValues = \skeeks\cms\models\CmsContentElementProperty::find()
        ->leftJoin(\skeeks\cms\models\CmsContentProperty::tableName().' ccp', 'ccp.id ='.skeeks\cms\models\CmsContentElementProperty::tableName().'.property_id')
        ->andWhere(['element_id' => $ids])
        ->groupBy(['property_id', 'value'])
        ->all();

    /**
     * @var $properties \skeeks\cms\models\CmsContentProperty[]
     */
    $properties = \skeeks\cms\models\CmsContentProperty::find()->orderBy(['priority' => SORT_ASC])->andWhere(['id' => \yii\helpers\ArrayHelper::map($allProps, "property_id", "property_id")])->all();
    foreach ($properties as $property) {

    }

    $placesProps = \skeeks\cms\models\CmsContentElementProperty::find()
        ->leftJoin(\skeeks\cms\models\CmsContentProperty::tableName().' ccp', 'ccp.id ='.skeeks\cms\models\CmsContentElementProperty::tableName().'.property_id')
        ->joinWith("valueEnum as valueEnum")
        ->andWhere(['ccp.code' => 'ceramic_type_of_goods'])
        ->andWhere(['element_id' => $ids])
        ->groupBy(['value_enum'])
        ->orderBy(['valueEnum.priority' => SORT_ASC])
    ;

    $firstProduct = $collectionProducts[0];
    if ($firstProduct && $firstProduct->cmsTree) {
        \Yii::$app->breadcrumbs->setPartsByTree($firstProduct->cmsTree);
        \Yii::$app->breadcrumbs->append($model->name);
    }
    ?>
<? endif; ?>
<section class="sx-product-card-wrapper g-mt-0 g-pb-0 to-cart-fly-wrapper">
    <? if ($model->image) : ?>
        <link itemprop="image" href="<?= $model->image->absoluteSrc; ?>">
    <? endif; ?>
    <div class="container sx-container g-py-20">
        <div class="row">
            <div class="col-md-12">
                <?= $this->render('@app/views/breadcrumbs', [
                    'model'    => $model,
                    'isShowH1' => false,
                ]); ?>
            </div>
        </div>
        <? $pjax = \skeeks\cms\widgets\Pjax::begin(); ?>
        <div class="row g-mt-20">
            <div class="col-md-7">
                <div class="sx-product-images g-ml-40 g-mr-40">
                    <? /*= $this->render("_product-images", [
                        'model'                 => $model,
                        'shopOfferChooseHelper' => null,

                    ]); */ ?>

                    <?
                    $images = [];
                    if ($model->image) {
                        $images[] = $model->image;
                    }
                    if ($model->images) {
                        $images = \yii\helpers\ArrayHelper::merge($images, $model->images);
                    }
                    if (!$images) {
                        $images = false;
                    }
                    echo $this->render("@app/views/modules/cms/content-element/product/_product-images", [
                        'images' => $images,
                        'model' => $model,
                    ]); ?>
                </div>
            </div>
            <div class="col-md-5 sx-col-product-info" style="background: #fafafa; padding: 20px;">
                <div class="sx-right-product-info product-info ss-product-info" style="min-height: 100%;">
                    <h1 class="h2" style="margin-bottom: 1rem;">Коллекция <?= $model->seoName; ?> <?= $infoModel->brand ? $infoModel->brand->name : ""; ?></h1>

                    <div class="sx-properties-wrapper sx-columns-1">
                        <ul class="sx-properties">
                            <?php if($infoModel->brand) : ?>
                                <li>
                                    <span class="sx-properties--name">
                                    Бренд
                                    </span>
                                    <span class="sx-properties--value">
                                    <?= $infoModel->brand->name; ?>
                                    </span>
                                </li>
                                <? if ($infoModel->brand->country) : ?>
                                    <li>
                                        <span class="sx-properties--name">
                                        Страна
                                        </span>
                                        <span class="sx-properties--value">
                                        <?= $infoModel->brand->country->name; ?>
                                        </span>
                                    </li>
                                <? endif; ?>

                            <?php endif; ?>




                        </ul>
                    </div>

                    <? if ($collectionProducts && $placesProps->exists()) : ?>
                        <?
                        $this->registerCss(<<<CSS
.sx-prices {
    font-size: 1.4rem;
}
.sx-prices ul .sx-properties--name {
    color: black;
}
.sx-prices ul .sx-properties--value {
    color: var(--primary-color);
    font-weight: bold;
}
CSS
                        );

                        $this->registerJs(<<<JS
$(".sx-prices li").each(function() {
    var id = $(this).data("id");
    var jVal = $(".sx-properties--value", $(this));
    
    var min = 0;
    var jMin = null;
    
    $(".id" + id).each(function() {
        var jPrice = $(".sx-new-price", $(this));
        var amount = jPrice.data("amount");
        
        if (min == 0 || amount < min) {
            min = amount;
            jMin = jPrice;
        }
    });
    
    if (jMin !== null) {
        jVal.empty().append("от " + jMin.text());
    }
});
JS
                        );
                        ?>
                        <!-- Cube Portfolio Blocks - Filter -->
                        <h4 style="margin-bottom: 1rem;">Цены на элементы коллекции:</h4>
                        <div class="sx-properties-wrapper sx-columns-1 sx-prices">
                            <ul class="sx-properties">
                                <? foreach ($placesProps->all() as $placePropId) :
                                    $placeProp = \skeeks\cms\models\CmsContentPropertyEnum::findOne($placePropId->value_enum);
                                    if ($placeProp) :
                                        ?>
                                        <li data-id="<?php echo $placeProp->id; ?>">
                                            <span class="sx-properties--name">
                                            <?= \skeeks\cms\helpers\StringHelper::ucfirst($placeProp->value); ?>
                                            </span>
                                            <span class="sx-properties--value">
                                                от ...
                                            </span>
                                        </li>

                                    <? endif; ?>
                                <? endforeach; ?>
                            </ul>
                        </div>
                    <? endif; ?>


                    <button style="margin-top: 20px;" onclick="new sx.classes.Location().href('#portfolio-section')" class="btn btn-xxl btn-block btn-primary g-font-size-18">Смотреть товары коллекции</button>
                </div>
            </div>
        </div>
        <? $pjax::end(); ?>
    </div>
</section>


<section style="margin-bottom: 20px; margin-top: 20px;">
    <div class="container sx-container">

        <div class="sx-properties-wrapper">
            <ul class="sx-properties">
                <?php foreach ($properties as $prop) : ?>
                    <?php if (!in_array($prop->code, ['collection', 'sku'])) : ?>
                        <li data-prop-id="<?php echo $prop->id; ?>">
                            <span class="sx-properties--name">
                                <?php echo $prop->name; ?>
                            </span>
                            <span class="sx-properties--value">
                                <?
                                /**
                                 * @var \skeeks\cms\models\CmsContentElementProperty $valueData
                                 */
                                $valueNumbers = [];
                                $valueEnums = [];
                                foreach ($allValues as $valueData) {
                                    if ($valueData->property_id == $prop->id) {

                                        $valueNumbers[] = $valueData->value;
                                        $valueEnums[] = $valueData->value_enum;
                                    }
                                }


                                ?>
                                <?php if ($prop->property_type == \skeeks\cms\relatedProperties\PropertyType::CODE_ELEMENT) : ?>
                                    <?php
                                    $elements = \skeeks\cms\models\CmsContentElement::find()->andWhere(['id' => $valueEnums])->all();
                                    echo implode(", ", \yii\helpers\ArrayHelper::map($elements, 'id', 'name')); ?>

                                <?php elseif ($prop->property_type == \skeeks\cms\relatedProperties\PropertyType::CODE_LIST) : ?>
                                    <?php
                                    $enums = \skeeks\cms\models\CmsContentPropertyEnum::find()->andWhere(['id' => $valueEnums])->all();
                                    echo implode(", ", \yii\helpers\ArrayHelper::map($enums, 'id', 'value')); ?>
                                <?php elseif ($prop->property_type == \skeeks\cms\relatedProperties\PropertyType::CODE_NUMBER) : ?>
                                    <?php echo implode(", ", $valueNumbers); ?>
                                <?php elseif ($prop->property_type == \skeeks\cms\relatedProperties\PropertyType::CODE_STRING) : ?>
                                    <?php echo implode(", ", $valueNumbers); ?>
                                <?php endif; ?>

                                <?php if ($prop->cms_measure_code) : ?>
                                    <?php echo $prop->cmsMeasure->symbol; ?>
                                <?php endif; ?>

                            </span>
                        </li>
                    <?php endif; ?>


                <?php endforeach; ?>
            </ul>
        </div>
    </div>
</section>


<?php
$description = $model->description_full;
?>

<? if ($description) : ?>
    <section style="margin-bottom: 20px; margin-top: 20px;">
        <div class="container sx-container">
            <div class="sx-description-full">
                <?= $description; ?>
            </div>
        </div>

    </section>
<? endif; ?>

<?php if ($collectionProducts) : ?>

    <section id="portfolio-section" class="g-pb-10 g-brd-gray-light-v4">
        <div class="container sx-container">

            <? if ($placesProps->exists()) : ?>
                <!-- Cube Portfolio Blocks - Filter -->
                <ul id="filterControls1" class="d-block list-inline">
                    <li class="list-inline-item cbp-filter-item cbp-filter-item-active" role="button" data-filter="*">
                        <a href="#" onclick="return false;" class="btn btn-sm u-btn-outline-darkgray g-mr-10">Показать все</a>
                    </li>
                    <? foreach ($placesProps->all() as $placePropId) :
                        $placeProp = \skeeks\cms\models\CmsContentPropertyEnum::findOne($placePropId->value_enum);
                        if ($placeProp) :
                            ?>

                            <li class="list-inline-item cbp-filter-item" role="button" data-filter=".id<?= $placeProp->id ?>">
                                <a href="#" onclick="return false;" class="btn btn-sm u-btn-outline-darkgray g-mr-10"><?= $placeProp->value; ?></a>
                            </li>
                        <? endif; ?>
                    <? endforeach; ?>
                </ul>
                <!-- End Cube Portfolio Blocks - Filter -->
            <? endif; ?>

            <div class="cbp sx-product-list" data-controls="#filterControls1" data-animation="quicksand" data-x-gap="0" data-y-gap="10"
                 data-media-queries='[{"width": 1500, "cols": 4}, {"width": 1100, "cols": 4}, {"width": 800, "cols": 4}, {"width": 480, "cols": 3}, {"width": 300, "cols": 1}]'>
                <? foreach ($collectionProducts as $product) :
                    /**
                     * @var $product \skeeks\cms\shop\models\ShopCmsContentElement
                     */
                    $priceHelper = \Yii::$app->shop->cart->getProductPriceHelper($product);
                    $shopProduct = $product->shopProduct;

                    $types = $product->relatedPropertiesModel->getAttribute('ceramic_type_of_goods');
                    ?>


                    <div class=" item d-flex cbp-item identity sx-product-card-wrapper <? if ($types) : ?>
            <? foreach ($types as $typeId) {
                        echo "id".$typeId;
                    } ?>
<? endif; ?>">
                        <article class="sx-product-card h-100 to-cart-fly-wrapper">

                            <?
                            $isAdded = \Yii::$app->shop->cart->getShopFavoriteProducts()->andWhere(['shop_product_id' => $product->id])->exists();
                            ?>
                            <div class="sx-favorite-product"
                                 data-added-icon-class="fas fa-heart"
                                 data-not-added-icon-class="far fa-heart"
                                 data-is-added="<?= (int)$isAdded ?>"
                                 data-product_id="<?= (int)$shopProduct->id ?>"
                            >
                                <a href="#" class="sx-favorite-product-trigger" data-pjax="0" style="font-size: 22px;">
                                    <? if ($isAdded) : ?>
                                        <i class="fas fa-heart"></i>
                                    <? else : ?>
                                        <i class="far fa-heart"></i>
                                    <? endif; ?>
                                </a>
                            </div>

                            <? if ($product->shopProduct && $product->shopProduct->baseProductPrice && $product->shopProduct->minProductPrice && $product->shopProduct->minProductPrice->id != $product->shopProduct->baseProductPrice->id) :

                                $percent = (int)($priceHelper->percent * 100); ?>
                                <div class="sx-product-card--sale">
                                    <div><span class="number">-<?= (int)$percent; ?></span><span class="percent">%</span></div>
                                    <div class="caption">скидка</div>
                                </div>
                            <? endif; ?>
                            <div class="sx-product-card--photo">
                                <a href="<?= $product->url; ?>" data-pjax="0">
                                    <img class="to-cart-fly-img" src="<?= \Yii::$app->imaging->thumbnailUrlOnRequest($product->image ? $product->image->src : \skeeks\cms\helpers\Image::getCapSrc(),
                                        new \skeeks\cms\components\imaging\filters\ThumbnailFix([
                                            'w' => 300,
                                            //'m' => \Imagine\Image\ImageInterface::THUMBNAIL_INSET,
                                        ]), $product->code
                                    ); ?>" title="<?= \yii\helpers\Html::encode($product->name); ?>" alt="<?= \yii\helpers\Html::encode($product->name); ?>"/>
                                </a>
                            </div>
                            <div class="sx-product-card--info">

                                <? if (isset($shopProduct)) : ?>
                                    <div class="">
                                        <? if ($priceHelper && \Yii::$app->cms->cmsSite->shopSite->is_show_prices) : ?>
                                            <?
                                            $prefix = "";
                                            if ($shopProduct->isOffersProduct) {
                                                $prefix = \Yii::t('skeeks/unify-shop', 'from')." ";
                                            }
                                            ?>
                                            <? if ($priceHelper->hasDiscount && (float)$priceHelper->minMoney->getAmount() > 0) : ?>
                                                <span class="new sx-new-price sx-list-new-price g-color-primary"
                                                      data-amount="<?= $priceHelper->minMoney->getAmount(); ?>"><?= $prefix; ?><?= $priceHelper->minMoney; ?></span>
                                                <span class="old sx-old-price sx-list-old-price" data-amount="<?= $priceHelper->minMoney->getAmount(); ?>"><?= $prefix; ?><?= $priceHelper->basePrice->money; ?></span>
                                            <? else : ?>
                                                <? if ((float)$priceHelper->minMoney->getAmount() > 0) : ?>
                                                    <div class="new sx-new-price sx-list-new-price g-color-primary"
                                                         data-amount="<?= $priceHelper->minMoney->getAmount(); ?>"><?= $prefix; ?><?= $priceHelper->minMoney; ?>
                                                        <? if ($this->theme->catalog_is_show_measure == 1) : ?>
                                                            <span class="sx-measure">/ <?= $shopProduct->measure->symbol; ?></span>
                                                        <? endif; ?>
                                                    </div>
                                                <? endif; ?>
                                            <? endif; ?>
                                        <? endif; ?>
                                    </div>
                                <? endif; ?>
                                <div class="sx-product-card--title">
                                    <a href="<?= $product->url; ?>" title="<?= $product->productName; ?>" data-pjax="0"
                                       class="sx-product-card--title-a sx-main-text-color g-text-underline--none--hover"><?= $product->productName; ?></a>
                                </div>
                                <? if (isset($shopProduct)) : ?>
                                    <div class="sx-product-card--actions">
                                        <? if ($priceHelper && \Yii::$app->cms->cmsSite->shopSite->is_show_prices && (float)$priceHelper->minMoney->getAmount() == 0) : ?>
                                            <? if (
                                                //$shopProduct->quantity > 0 &&
                                                \Yii::$app->skeeks->site->shopSite->is_show_button_no_price && !$shopProduct->isOffersProduct) : ?>
                                                <?= \yii\helpers\Html::tag('button', "<i class=\"icon cart\"></i>".\Yii::t('skeeks/unify-shop', 'To cart'), [
                                                    'class'   => 'btn btn-primary js-to-cart to-cart-fly-btn',
                                                    'type'    => 'button',
                                                    'onclick' => new \yii\web\JsExpression("sx.Shop.addProduct({$shopProduct->id}, 1); return false;"),
                                                ]); ?>

                                            <? else : ?>
                                                <?= \yii\helpers\Html::tag('a', "Подробнее", [
                                                    'class' => 'btn btn-primary',
                                                    'href'  => $product->url,
                                                    'data'  => ['pjax' => 0],
                                                ]); ?>
                                            <? endif; ?>

                                        <? else : ?>
                                            <?

                                            $shopStoreProducts = $shopProduct->getShopStoreProducts(\Yii::$app->shop->allStores)->all();
                                            $quantityAvailable = 0;
                                            if ($shopStoreProducts) {
                                                foreach ($shopStoreProducts as $shopStoreProduct) {
                                                    $quantityAvailable = $quantityAvailable + $shopStoreProduct->quantity;
                                                }
                                            }

                                            if (
                                                !$shopStoreProducts || $quantityAvailable > 0
                                                //&& !$shopProduct->isOffersProduct
                                                && \Yii::$app->cms->cmsSite->shopSite->is_show_cart
                                            ) : ?>
                                                <?= \yii\helpers\Html::tag('button', "<i class=\"icon cart\"></i>".\Yii::t('skeeks/unify-shop', 'To cart'), [
                                                    'class'   => 'btn btn-primary js-to-cart to-cart-fly-btn',
                                                    'type'    => 'button',
                                                    'onclick' => new \yii\web\JsExpression("sx.Shop.addProduct({$shopProduct->id}, 1); return false;"),
                                                ]); ?>
                                            <? else : ?>
                                                <?= \yii\helpers\Html::tag('a', "Подробнее", [
                                                    'class' => 'btn btn-primary',
                                                    'href'  => $product->url,
                                                    'data'  => ['pjax' => 0],
                                                ]); ?>
                                            <? endif; ?>
                                        <? endif; ?>
                                    </div>
                                <? endif; ?>


                            </div>

                        </article>
                    </div>

                <?php endforeach; ?>
            </div>

        </div>
    </section>
<?php endif; ?>
