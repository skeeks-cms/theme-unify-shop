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

$singlPage = \skeeks\cms\themes\unifyshop\cmsWidgets\product\ShopProductSinglPage::beginWidget('product-page');
$singlPage->addCss();
$singlPage::end();

$product = null;
$this->registerCss(<<<CSS

.products-section {
    padding-top: 3rem;
    padding-bottom: 3rem;
}

.sx-filters-block-header {
    display: none;
}
ul.sx-properties {
    font-size: 1.2rem;
}

.js-carousel.slick-initialized .js-slide, .js-carousel.slick-initialized .js-thumb {
    margin-right: 10px;
    margin-top: 10px;
    cursor: pointer;
}
.sx-js-pagination {
    display: none;
}
@media (max-width: 768px) {
    ul.sx-properties
    {
        -moz-column-count: 1;
        column-count: 1;
    }
}

CSS
);

$infoModel = $model;

$this->title = "Коллекция ".$infoModel->seoName." ".($infoModel->brand ? $infoModel->brand->name : "")."";

$properties = [];

$mainTree = '';
?>


<?
/**
 * @var \skeeks\cms\shop\models\ShopCmsContentElement[] $collectionProducts
 */
$query = \skeeks\cms\shop\models\ShopCmsContentElement::find()
    //->cmsSite()
    ->active()
    ->joinWith([
    'shopProduct' => function ($q) {
            $q->alias('sp')
              ->joinWith(['collections collections'], false);
        }
    ], false)
    //->joinWith("cmsContentElementPropertyValues.element as cmsElement")
    //->innerJoinWith("shopProduct as sp", false)
    /*->innerJoinWith("shopProduct.collections as collections", false)*/
    /*->joinWith([
        'shopProduct sp' => function($qtmp) {
            $qtmp->joinWith(['collections collections'], false);
        }
    ], false)*/
    ->andWhere([
        'collections.id' => $model->id //Товары
    ]);

//\Yii::$app->shop->filterBaseContentElementQuery($query);
//\Yii::$app->shop->filterByQuantityQuery($query);

$queryForProperties = clone $query;
$queryForProperties->select([\skeeks\cms\shop\models\ShopCmsContentElement::tableName().'.id']);

$collectionProducts = $query->count();

?>

<?php if ($collectionProducts) : ?>

    <?
    //$ids = \yii\helpers\ArrayHelper::map($collectionProducts, "id", 'id');

    $allProps = \skeeks\cms\models\CmsContentElementProperty::find()
        ->leftJoin(\skeeks\cms\models\CmsContentProperty::tableName().' ccp', 'ccp.id ='.skeeks\cms\models\CmsContentElementProperty::tableName().'.property_id')
        ->andWhere(['element_id' => $queryForProperties])
        ->groupBy(['property_id'])
        ->select(['property_id'])
        ->asArray()
        ->all();


    $allValues = \skeeks\cms\models\CmsContentElementProperty::find()
        ->leftJoin(\skeeks\cms\models\CmsContentProperty::tableName().' ccp', 'ccp.id ='.skeeks\cms\models\CmsContentElementProperty::tableName().'.property_id')
        ->andWhere(['element_id' => $queryForProperties])
        ->groupBy(['property_id', 'value'])
        ->all();

    /**
     * @var $properties \skeeks\cms\models\CmsContentProperty[]
     */
    $properties = \skeeks\cms\models\CmsContentProperty::find()->orderBy(['priority' => SORT_ASC])->andWhere(['id' => \yii\helpers\ArrayHelper::map($allProps, "property_id", "property_id")])->all();
    /*foreach ($properties as $property) {

    }*/

    $placesProps = \skeeks\cms\models\CmsContentElementProperty::find()
        ->leftJoin(\skeeks\cms\models\CmsContentProperty::tableName().' ccp', 'ccp.id ='.skeeks\cms\models\CmsContentElementProperty::tableName().'.property_id')
        ->joinWith("valueEnum as valueEnum")
        ->andWhere(['ccp.code' => 'ceramic_type_of_goods'])
        ->andWhere(['element_id' => $queryForProperties])
        ->groupBy(['value_enum'])
        ->orderBy(['valueEnum.priority' => SORT_ASC]);

    $firstProductQuery = clone $query;
    $firstProductQuery->limit(1);

    $firstProduct = $firstProductQuery->one();

    /**
     * @var $firstProduct \skeeks\cms\shop\models\ShopCmsContentElement
     */
    if ($firstProduct && $firstProduct->cmsTree) {
        \Yii::$app->breadcrumbs->setPartsByTree($firstProduct->cmsTree);
        \Yii::$app->breadcrumbs->append($model->name);

        $mainTree = $firstProduct->cmsTree->name;
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
            <div class="sx-main-product-container">
                <div class="sx-product-page--left-col">
                    <div class="sx-product-images">

                        <? if ($model->shopCollectionStickers) : ?>
                            <?
                            $this->registerCss(<<<CSS
.sx-productpage-labels {
    top: 1.5rem;
}
CSS
                            )
                            ?>
                            <div class="sx-labels sx-productpage-labels">
                                <? foreach ($model->shopCollectionStickers as $sticker) : ?>
                                    <div style="background: <?php echo $sticker->color ? $sticker->color : "green"; ?>"
                                         class="sx-product-label sx-collection-label-<?php echo $sticker->id; ?>"><?php echo $sticker->name; ?></div>
                                <? endforeach; ?>
                            </div>
                        <? endif; ?>


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
                        if (\Yii::$app->mobileDetect->isDesktop) {
                            echo $this->render("@app/views/modules/cms/content-element/product/_product-images-vertical", [
                                'images' => $images,
                                'model'  => $model,
                            ]);
                        } else {
                            echo $this->render("@app/views/modules/cms/content-element/product/_product-images", [
                                'images' => $images,
                                'model'  => $model,
                            ]);
                        }
                        ?>
                    </div>
                </div>
                <div class="sx-product-page--right-col sx-col-product-info">
                    <div class="sx-right-product-info product-info ss-product-info" style="min-height: 100%;">
                        <h1 class="h2" style="margin-bottom: 1rem;">Коллекция <?= $model->seoName; ?> <?= $infoModel->brand ? $infoModel->brand->name : ""; ?></h1>

                        <div class="product-info-header">
                            <div class="sx-properties-wrapper sx-columns-1">
                                <ul class="sx-properties">


                                    <? if ($infoModel->brand) : ?>
                                        <li>
                                            <span class="sx-properties--name">
                                                Бренд
                                            </span>
                                            <span class="sx-properties--value">
                                                <?php if ($infoModel->brand->logo_image_id) : ?>
                                                    <? $logo = $infoModel->brand->logo; ?>

                                                    <img class="img-fluid"
                                                         src="<?= \Yii::$app->imaging->thumbnailUrlOnRequest($logo->src,
                                                             new \skeeks\cms\components\imaging\filters\Thumbnail([
                                                                 'w' => 0,
                                                                 'h' => 20,
                                                                 'm' => \Imagine\Image\ImageInterface::THUMBNAIL_OUTBOUND,
                                                             ]), $infoModel->brand->code
                                                         ); ?>" alt="<?= $infoModel->brand->name; ?>">
                                                <?php endif; ?>

                                                <a href="<?php echo $infoModel->brand->url; ?>" data-pjax="0">
                                                    <?= $infoModel->brand->name; ?>
                                                </a>
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

                                    <?php if ($collectionProducts) : ?>
                                        <li>
                                            <span class="sx-properties--name">
                                            Всего товаров
                                            </span>
                                            <span class="sx-properties--value">
                                            <?= \Yii::$app->formatter->asInteger($collectionProducts); ?> шт.
                                            </span>
                                        </li>
                                    <?php endif; ?>

                                    <?php if ($firstProduct) : ?>
                                        <li>
                                            <span class="sx-properties--name">
                                            Цена товара от
                                            </span>
                                            <span class="sx-properties--value">
                                                <?= $firstProduct->shopProduct->minProductPrice->money; ?>
                                            </span>
                                        </li>
                                    <?php endif; ?>


                                </ul>
                            </div>

                            <? if ($collectionProducts && $placesProps->exists() && 1 == 2) : ?>
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
                                                    <span class="sx-properties--value" style="white-space: nowrap;">
                                                от ...
                                            </span>
                                                </li>

                                            <? endif; ?>
                                        <? endforeach; ?>
                                    </ul>
                                </div>
                            <? endif; ?>


                            <button style="margin-top: 20px;" onclick="new sx.classes.Location().href('#products-section')" class="btn btn-xxl btn-block btn-primary g-font-size-18">Смотреть товары коллекции</button>
                        </div>
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
    <?php
    \skeeks\cms\themes\unify\widgets\filters\assets\FiltersWidgetAsset::register($this);
    $pjax = \skeeks\cms\widgets\PjaxLazyLoad::begin();

    $dataProvider = new \yii\data\ActiveDataProvider([
        'query' => $query,
    ]);

    ?>
    <?php if ($pjax->isPjax) :
        ?>
        <div class="products-section" id="products-section">
            <div class="sx-container container">
                <?


                $filtersWidget = new \skeeks\cms\themes\unifyshop\filters\StandartShopFiltersWidget([
                    'activeFormConfig' => [
                        'action'  => $model->url,
                        'options' => [
                            'data' => [
                                'pjax' => 1,
                            ],
                        ],
                    ],
                ]);


                $baseQuery = clone $dataProvider->query;

                $eavFiltersHandler = null;
                $shopDataFiltersHandler = null;
                /*$shopDataFiltersHandler = new \skeeks\cms\shop\queryFilter\ShopDataFiltersHandler([
                    'baseQuery' => $baseQuery,
                ]);*/

                $eavFiltersHandler = new \skeeks\cms\shop\queryFilter\ShopEavQueryFilterHandler([
                    'baseQuery' => $baseQuery,
                ]);

                $eavFiltersHandler->openedPropertyIds = \Yii::$app->skeeks->site->shopSite->open_filter_property_ids;
                $eavFiltersHandler->viewFile = '@app/views/filters/eav-filters';
                $rpQuery = $eavFiltersHandler->getRPQuery();

                if ($show_filter_property_ids = \Yii::$app->skeeks->site->shopSite->show_filter_property_ids) {
                    $rpQuery->andWhere([\skeeks\cms\models\CmsContentProperty::tableName().'.id' => $show_filter_property_ids]);
                }
                /*$treeIds = [$model->id];
                $rpQuery->andWhere([
                    'or',
                    ['map.cms_tree_id' => $treeIds],
                    ['map.cms_tree_id' => null],
                ]);*/
                //print_r($rpQuery->createCommand()->rawSql);die;
                $eavFiltersHandler->initRPByQuery($rpQuery);
                $priceFiltersHandler = new \skeeks\cms\shop\queryFilter\PriceFiltersHandler([
                    'baseQuery' => $baseQuery,
                    'viewFile'  => '@app/views/filters/price-filter',
                ]);

                /*$filtersWidget
                    ->registerHandler($priceFiltersHandler, "price");*/

                /*$filtersWidget
                    ->registerHandler($shopDataFiltersHandler, 'data');*/

                $filtersWidget
                    ->registerHandler($eavFiltersHandler, 'eav');

                $filtersWidget->getEavHandler()->viewFile = \Yii::$app->mobileDetect->isMobile ? '@app/views/filters/eav-filters' : '@app/views/filters/eav-filters-inline';

                \yii\helpers\ArrayHelper::remove($_GET, "code");
                $filtersWidget->loadFromRequest();

                $filtersWidget->applyToQuery($dataProvider->query);

                \Yii::$app->shop->filterByTypeContentElementQuery($dataProvider->query);

                $dataProvider->query->addSelect([
                    \skeeks\cms\shop\models\ShopCmsContentElement::tableName() . ".*"
                ])

                ?>
                <?php
                if (!\Yii::$app->mobileDetect->isMobile) {
                    $filtersWidget->getSortHandler()->viewFile = '@app/views/filters/sort-filter-inline';
                    $filtersWidget->getAvailabilityHandler()->viewFile = '@app/views/filters/availability-filter-inline';
                }

                echo $filtersWidget->run();
                ?>


                <?php echo $this->render("@app/views/products/product-list", [
                    'dataProvider' => $dataProvider,
                ]); ?>
            </div>
        </div>

    <?php else : ?>
        <div class="products-section" id="products-section">
            <div class="sx-container container">

                <!--Загрузка фильтров...-->

                <?php echo $this->render("@app/views/products/product-list", [
                    'dataProvider' => $dataProvider,
                ]); ?>
            </div>
        </div>

    <?php endif; ?>
    <? $pjax::end(); ?>
<?php endif; ?>

<?php

$title = [];

$title[] = 'Коллекция';
$title[] = $infoModel->name;

if ($infoModel->brand) {
    $title[] = $infoModel->brand->name;
}

if ($mainTree) {
    $title[] = " / ".$mainTree;
}

$title[] = "в магазине ".\Yii::$app->cms->cmsSite->name;

$this->title = implode(' ', $title);

$this->registerMetaTag([
    'property' => 'og:title',
    'content'  => $this->title,
], 'og:title');

?>