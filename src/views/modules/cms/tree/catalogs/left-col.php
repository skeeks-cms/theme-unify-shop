<?php
/**
 * @link https://cms.skeeks.com/
 * @copyright Copyright (c) 2010 SkeekS
 * @license https://cms.skeeks.com/license/
 * @author Semenov Alexander <semenov@skeeks.com>
 */

/**
 * @var                                                                $this yii\web\View
 * @var                                                                $model \skeeks\cms\models\CmsTree
 * @var \yii\data\ActiveDataProvider                                   $dataProvider
 * @var \skeeks\cms\themes\unifyshop\filters\StandartShopFiltersWidget $filtersWidget
 * @var array                                                          $agregateCategoryData
 */
$totalOffers = (int)\yii\helpers\ArrayHelper::getValue($agregateCategoryData, 'offerCount', 0);
$catalogSettings = \skeeks\cms\themes\unifyshop\cmsWidgets\catalog\ShopCatalogPage::beginWidget("catalog");
$catalogSettings::end();

$totalOffers = (int)\yii\helpers\ArrayHelper::getValue($agregateCategoryData, 'offerCount', 0);
$priceFilter = $filtersWidget->getPriceHandler();
$eavFilter = $filtersWidget->getEavHandler();
$shopFilter = $filtersWidget->getShopDataHandler();
$appliedValues = [];
if ($priceFilter) {
    $appliedValues = $priceFilter->getApplied();
}
if ($shopFilter) {
    $appliedValues = \yii\helpers\ArrayHelper::merge($appliedValues, $shopFilter->getApplied());
}
if ($eavFilter) {
    $appliedValues = \yii\helpers\ArrayHelper::merge($appliedValues, $eavFilter->getApplied());
}

$hasCollections = false;
if ($model->shop_has_collections) {
    \Yii::$app->seo->canUrl->ADDimportant_pnames(['sx-catalog-view']);

    if ($model->shop_show_collections) {
        $viewMode = \Yii::$app->request->get("sx-catalog-view", "collection");
    } else {
        $viewMode = \Yii::$app->request->get("sx-catalog-view", "product");
    }

    if (\Yii::$app->request->post("sx-catalog-view")) {
        $viewMode = \Yii::$app->request->post("sx-catalog-view");
    }

    $hasCollections = true;
}
?>
<section class="">
    <div class="container sx-container">
        <div class="row">
            <div class="order-md-2 sx-content-col-main">
                <?= $this->render('@app/views/breadcrumbs', [
                    'model'      => @$model,
                    'isShowH1'   => false,
                    'isShowLast' => true,
                ]) ?>
                <div class="sx-catalog-h1-wrapper">
                    <h1 class="sx-breadcrumbs-h1 sx-catalog-h1">
                        <?php echo $model->seoName; ?>
                        <?php if (!$savedFilter && $appliedValues) : ?>
                            <span class="sx-applied-filters-text">+ применены фильтры</span>
                        <?php endif; ?>

                    </h1>
                    <div class="sx-catalog-total-offers" style="color: #979797; display: contents;
    margin-top: auto;
    margin-left: 12px;
    font-size: 15px;">
                        (<?php echo \Yii::t('app', '{n, plural, =0{нет&nbsp;товаров} =1{#&nbsp;товар} one{#&nbsp;товар} few{#&nbsp;товара} many{#&nbsp;товаров} other{#&nbsp;товаров}}', ['n' => $totalOffers]); ?>)
                    </div>
                </div>

                <?php if (@$description_short && !\Yii::$app->mobileDetect->isMobile) : ?>
                    <div class="sx-content sx-description-short">
                        <?= @$description_short; ?>
                    </div>
                <?php endif; ?>




                <?
                //Если есть применненые фильтры и это не сохраненный фильтр
                if (!$savedFilter && $appliedValues): ?>
                    <div class="sx-saved-filters-list sx-saved-filters-list--after" style="margin-top: 0px;">
                        <ul class="list-unstyled list-inline" style="margin-bottom: 10px;">
                            <?php /*if ($priceFilter->f || $priceFilter->t) : */ ?><!--
                                <?php
                            /*                                $priceTitle = "";
                                                            $priceTitleData = [];
                                                            if ($priceFilter->f) {
                                                                $f = \Yii::$app->formatter->asDecimal($priceFilter->f);
                                                                $priceTitleData[] = "от <b>{$f}</b>";
                                                            }
                                                            if ($priceFilter->t) {
                                                                $t = \Yii::$app->formatter->asDecimal($priceFilter->t);
                                                                $priceTitleData[] = "до <b>{$t}</b>";
                                                            }
                                                            $priceTitleData[] = \Yii::$app->money->currency_symbol;
                                                            $priceTitle = implode(" ", $priceTitleData);
                                                            */ ?>
                                <?php /*echo $this->render("@app/views/modules/cms/tree/catalogs/_filter", [
                                    'isActive'    => true,
                                    'value_id'    => "",
                                    'property_id' => "price",
                                    'seoName'     => $model->seoName." по цене ".$priceTitle,
                                    'displayName' => $model->name." по цене ".$priceTitle,
                                ]); */ ?>
                            --><?php /*endif; */ ?>

                            <?php foreach ($appliedValues as $data) : ?>
                                <?php 
                                $name = \yii\helpers\ArrayHelper::getValue($data, "name"); ?>
                                <?php $value = \yii\helpers\ArrayHelper::getValue($data, "value"); ?>
                                <?php
                                $property_id = '';
                                if ($property_id = \yii\helpers\ArrayHelper::getValue($data, "property_id")) {

                                } elseif ($property = \yii\helpers\ArrayHelper::getValue($data, "property")) {
                                    $property_id = $property->id;
                                }
                                ?>
                                <?php echo $this->render("@app/views/modules/cms/tree/catalogs/_filter", [
                                    'isActive'    => true,
                                    'value_id'    => $value,
                                    'property_id' => $property_id,
                                    'seoName'     => $model->seoName." ".\skeeks\cms\helpers\StringHelper::lcfirst($name),
                                    'displayName' => $model->name." ".\skeeks\cms\helpers\StringHelper::lcfirst($name),
                                ]); ?>
                            <?php endforeach; ?>

                            <li class="list-inline-item sx-active" style="margin-bottom: 5px;" data-value_id="38" data-property_id="field-e-f34">
                                <a href="<?php echo $model->url; ?>" class="btn btn-default">
                            <span data-toggle="tooltip" data-html="true" title="" data-original-title="Сбросить примененные фильтры">
                            Очистить все </span>
                                    <i class="hs-icon hs-icon-close sx-close-btn" data-toggle="tooltip" title="" data-original-title=""></i>
                                </a>
                            </li>

                        </ul>
                    </div>

                <?php else : ?>
                    <?
                    $savedFilters = [];
                    if (!$model->activeChildren && !\Yii::$app->mobileDetect->isMobile) {
                        /**
                         * @var $sf \skeeks\cms\models\CmsSavedFilter
                         */
                        //todo: вынести в шаблон
                        $savedFiltersQ = $model->getCmsSavedFilters()
                            ->joinWith("cmsTree as cmsTree")
                            ->with("cmsTree")
                            ->with("cmsContentProperty")
                            ->with("valueContentElement")
                            ->with("valueContentPropertyEnum")
                            ->orderBy([\skeeks\cms\models\CmsSavedFilter::tableName().".priority" => SORT_DESC])
                            ->andWhere([
                                'cmsTree.id' => $model->id,
                            ]);
                        //->groupBy(['cmsContentProperty.id'])


                        $savedFilters = $savedFiltersQ
                            ->limit(10)
                            ->all();


                    }
                    ?>
                    <?php if ($savedFilters || $savedFilter) : ?>

                        <?
                        if ($savedFilter) {
                            //Нужно проверить текущий фильтр добавлени или нет?
                            $savedFilters = \yii\helpers\ArrayHelper::map($savedFilters, "id", function ($model) {
                                return $model;
                            });

                            if (!isset($savedFilters[$savedFilter->id])) {
                                $savedFilters = \yii\helpers\ArrayHelper::merge([
                                    $savedFilter->id => $savedFilter,
                                ], $savedFilters);
                            }
                        }

                        ?>
                        <div class="sx-saved-filters-list sx-saved-filters-list--after" style="margin-top: 0px;">
                            <ul class="list-unstyled list-inline" style="margin-bottom: 10px;">

                                <?php if ($priceFilter->f || $priceFilter->t) : ?>
                                    <?php
                                    $priceTitle = "";
                                    $priceTitleData = [];
                                    if ($priceFilter->f) {
                                        $f = \Yii::$app->formatter->asDecimal($priceFilter->f);
                                        $priceTitleData[] = "от <b>{$f}</b>";
                                    }
                                    if ($priceFilter->t) {
                                        $t = \Yii::$app->formatter->asDecimal($priceFilter->t);
                                        $priceTitleData[] = "до <b>{$t}</b>";
                                    }
                                    $priceTitleData[] = \Yii::$app->money->currency_symbol;
                                    $priceTitle = implode(" ", $priceTitleData);
                                    ?>
                                    <?php echo $this->render("@app/views/modules/cms/tree/catalogs/_filter", [
                                        'isActive'    => true,
                                        'value_id'    => "",
                                        'property_id' => "price",
                                        'seoName'     => $model->seoName." по цене ".$priceTitle,
                                        'displayName' => $model->name." по цене ".$priceTitle,
                                    ]); ?>
                                <?php endif; ?>

                                <? foreach ($savedFilters as $sf) : ?>
                                    <?php echo $this->render("@app/views/modules/cms/tree/catalogs/_filter", [
                                        'isActive'    => (@$savedFilter && $sf->id == $savedFilter->id),
                                        'value_id'    => $sf->propertyValueForFilter,
                                        'property_id' => $sf->propertyIdForFilter,
                                        'seoName'     => $sf->seoName,
                                        'displayName' => $sf->shortSeoName,
                                        'url'         => $sf->url,
                                    ]); ?>
                                <? endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>


                    <?
                    //Показ подкатегорий с применнеными фильтрами
                    $savedFilters = [];
                    if (@$model && $savedFilter && $model->activeChildren) {
                        //todo: вынести в шаблон
                        $savedFilters = \skeeks\cms\models\CmsSavedFilter::find()
                            ->cmsSite()
                            //->joinWith("cmsContentProperty as cmsContentProperty")
                            ->joinWith("cmsTree as cmsTree")
                            ->with("cmsContentProperty")
                            ->with("cmsTree")
                            ->orderBy([
                                'cmsTree.level'    => SORT_ASC,
                                'cmsTree.priority' => SORT_ASC,
                            ])
                            ->andWhere([
                                'cmsTree.id' => $model->getDescendants(null, false)->select(['id']),
                            ])
                            //->groupBy(['cmsContentProperty.id'])
                            ->limit(200);

                        if ($savedFilter->value_content_element_id) {
                            $savedFilters->andWhere(['value_content_element_id' => $savedFilter->value_content_element_id]);
                        } elseif ($savedFilter->value_content_property_enum_id) {
                            $savedFilters->andWhere(['value_content_property_enum_id' => $savedFilter->value_content_property_enum_id]);
                        } elseif ($savedFilter->shop_brand_id) {
                            $savedFilters->andWhere(['shop_brand_id' => $savedFilter->shop_brand_id]);
                        } elseif ($savedFilter->country_alpha2) {
                            $savedFilters->andWhere(['country_alpha2' => $savedFilter->country_alpha2]);
                        }

                        $savedFilters = $savedFilters->all();
                    }

                    ?>
                    <?php if (count($savedFilters)) : ?>
                        <div class="sx-saved-filters-list sx-saved-filters-list--before">
                            <!--<div class="h5 sx-sub-title">Другие товары с опцией «<?php /*echo $savedFilter->propertyValueName; */ ?>»:</div>-->

                            <?php
                            $savedFiltersData = \skeeks\cms\models\CmsSavedFilter::formatFilters($savedFilters);
                            ?>
                            <? foreach ($savedFiltersData as $savedFiltersRow) : ?>
                                <!--<div class="h4 sx-sub-title"><?php /*echo \yii\helpers\ArrayHelper::getValue($savedFiltersRow, "name"); */ ?></div>-->
                                <ul class="list-unstyled list-inline" style="margin-bottom: 10px;">
                                    <? foreach (\yii\helpers\ArrayHelper::getValue($savedFiltersRow, "savedFilters") as $sf) : ?>
                                        <?php echo $this->render("@app/views/modules/cms/tree/catalogs/_category", [
                                            'isActive'           => (@$savedFilter && $sf->id == $savedFilter->id),
                                            'image'              => $sf->image,
                                            'seoName'            => $sf->seoName,
                                            'displayName'        => $sf->cmsTree->name,
                                            'code'               => $sf->cmsTree->code,
                                            'url'                => $sf->url,
                                            'description'        => $sf->propertyValueName,
                                            'adult_css_class'    => \Yii::$app->adult->renderCssClass($sf->cmsTree),
                                            'adult_blocked_html' => \Yii::$app->adult->renderBlocked($sf->cmsTree),
                                        ]); ?>
                                    <? endforeach; ?>
                                </ul>
                            <? endforeach; ?>
                        </div>
                    <?php endif; ?>


                    <? if (\Yii::$app->cms->currentTree && \Yii::$app->view->theme->is_show_catalog_subtree_before_products && !$savedFilter) : ?>
                        <?php if ($model->activeChildren) : ?>
                            <div class="sx-saved-filters-list sx-saved-filters-list--before">
                                <ul class="list-unstyled list-inline" style="margin-bottom: 10px;">
                                    <? foreach ($model->activeChildren as $childdren) : ?>
                                        <?php echo $this->render("@app/views/modules/cms/tree/catalogs/_category", [
                                            'isActive'           => false,
                                            'image'              => $childdren->image,
                                            'seoName'            => $childdren->seoName,
                                            'displayName'        => $childdren->name,
                                            'code'               => $childdren->code,
                                            'url'                => $childdren->url,
                                            'description'        => "",
                                            'adult_css_class'    => \Yii::$app->adult->renderCssClass($childdren),
                                            'adult_blocked_html' => \Yii::$app->adult->renderBlocked($childdren),

                                        ]); ?>
                                    <? endforeach; ?>
                                </ul>
                            </div>
                        <?php endif; ?>
                    <? endif; ?>

                <?php endif; ?>










                <?php if (\Yii::$app->mobileDetect->isMobile) {
                    //\skeeks\assets\unify\base\UnifyHsStickyBlockAsset::register($this);
                } else {
                    \skeeks\assets\unify\base\UnifyHsScrollbarAsset::register($this);
                }; ?>
                <div class="row sx-mobile-filters-block js-sticky-block" id="sx-mobile-filters-block" data-has-sticky-header="true" data-start-point="#sx-mobile-filters-block" data-end-point=".sx-footer">
                    <div class="col-12 sx-mobile-filters-block--inner">
                        <div class="btn-group" style="width: 100%;">
                            <? if (\Yii::$app->view->theme->is_allow_filters) : ?>
                                <a href="#" class="sx-btn-filter btn sx-btn-white sx-icon-arrow-down--after">Фильтры</a>
                            <? endif; ?>
                            <!--<a href="#" class="sx-btn-sort btn sx-btn-white text-left sx-icon-arrow-down--after">Сортировка</a>-->
                            <a href="#" class="btn dropdown-toggle sx-btn-white sx-btn-sort-select sx-icon-arrow-down--after" data-toggle="dropdown" style="">
                                <?php echo $filtersWidget->getSortHandler()->valueAsText; ?>
                            </a>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                <? foreach ($filtersWidget->getSortHandler()->getSortOptions() as $code => $name) : ?>
                                    <a class="dropdown-item sx-select-sort sx-filter-action" href="#" data-filter="#s-value" data-filter-value="<?php echo $code; ?>"><?php echo $name; ?></a>
                                <? endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row sx-fast-filters">
                    <div class="col-12">
                        <span class="sorting sx-filters-form">
                            <? if ($filtersWidget->getSortHandler()) : ?>
                                <?php echo $filtersWidget->getSortHandler()->renderVisible(); ?>
                            <? endif; ?>
                            <? if ($filtersWidget->getAvailabilityHandler()) : ?>
                                <?= $filtersWidget->getAvailabilityHandler()->renderVisible(); ?>
                            <? endif; ?>
                        </span>
                        <div class="sx-filters-selected-wrapper" style="display: none;">
                        </div>
                    </div>
                </div>


                <?php if ($hasCollections) : ?>
                    <div class="btn-group btn-block sx-btn-view-mode" style="margin-bottom: 20px;" role="group" aria-label="Basic example">
                        <a href="<?php echo \yii\helpers\Url::current(['sx-catalog-view' => 'collection']); ?>" type="button" class="btn btn-xl <?php echo $viewMode == "collection" ? "btn-primary" : "btn-secondary"; ?>">Коллекции</a>
                        <a href="<?php echo \yii\helpers\Url::current(['sx-catalog-view' => 'product']); ?>" type="button"
                           class="btn btn-xl <?php echo $viewMode == "product" ? "btn-primary" : "btn-secondary"; ?>">Товары</a>
                    </div>

                <?php endif; ?>


                <?php if ($hasCollections && $viewMode == "collection") : ?>

                    <?php
                    $this->registerCss(<<<CSS
.sorting, .sx-btn-sort-select {
    display: none;
}
CSS
                    );
                    /**
                     * @var $query \yii\db\ActiveQuery
                     */
                    $query = $dataProvider->query;
                    $realPrice = '';
                    $select = [
                        \skeeks\cms\models\CmsContentElement::tableName().".id",
                    ];
                    if (isset($query->select['realPrice'])) {
                        $realPrice = $query->select['realPrice'];
                        $select['realPrice'] = $realPrice;
                    }
                    $query->select($select);
                    $query->addSelect(['collection_id' => "collections.id"]);
                    $query->innerJoinWith("shopProduct.collections as collections", false);
                    //$query->with("shopProduct.collections as collections");
                    $query->groupBy(['collections.id']);
                    /*$query->orderBy(['collections.created_at' => SORT_DESC]);*/
                    /*$query->orderBy(['collections.show_counter' => SORT_DESC]);*/

                    $q = \skeeks\cms\shop\models\ShopCollection::find()->innerJoin(['main' => $query], [
                        'main.collection_id' => new \yii\db\Expression(\skeeks\cms\shop\models\ShopCollection::tableName().".id"),
                    ]);
                    $q->orderBy([\skeeks\cms\shop\models\ShopCollection::tableName() . ".show_counter" => SORT_DESC])
                    ?>

                    <?php echo $this->render("@app/views/collections/collection-list", [
                        'dataProvider' => new \yii\data\ActiveDataProvider([
                            'query'      => $q,
                            'pagination' => [
                                'pageSize' => 24,
                            ],
                        ]),
                    ]); ?>

                <?php else: ?>

                    <?php if ($totalOffers > 0): ?>
                        <?php echo $this->render("@app/views/products/product-list", [
                            'dataProvider' => $dataProvider,
                        ]); ?>
                    <?php else : ?>
                        <?php echo $this->render("@app/views/modules/cms/tree/catalogs/_no-products", [
                            'savedFilter'   => $savedFilter,
                            'cmsTree'       => $model,
                            'filtersWidget' => $filtersWidget,
                        ]); ?>
                    <?php endif; ?>
                <?php endif; ?>




                <?php if (@$description_short && \Yii::$app->mobileDetect->isMobile) : ?>
                    <div class="sx-content sx-description-short" style="margin-top: 20px;">
                        <?= @$description_short; ?>
                    </div>
                <?php endif; ?>


                <?php if (@$description) : ?>
                    <div class="sx-content sx-description-full" style="margin-top: 20px;">
                        <?= @$description; ?>
                    </div>
                <?php endif; ?>


                <?
                //Показ подкатегорий с применнеными фильтрами
                $savedFilters = [];
                if (@$model && $savedFilter) {
                    //todo: вынести в шаблон
                    $savedFilters = \skeeks\cms\models\CmsSavedFilter::find()
                        ->cmsSite()
                        //->joinWith("cmsContentProperty as cmsContentProperty")
                        ->joinWith("cmsTree as cmsTree")
                        ->with("cmsContentProperty")
                        ->with("cmsTree")
                        ->orderBy([
                            'cmsTree.level'    => SORT_ASC,
                            'cmsTree.priority' => SORT_ASC,
                        ])

                        //->groupBy(['cmsContentProperty.id'])
                        ->limit(200);

                    if ($savedFilter->value_content_element_id) {
                        $savedFilters->andWhere(['value_content_element_id' => $savedFilter->value_content_element_id]);
                    } elseif ($savedFilter->value_content_property_enum_id) {
                        $savedFilters->andWhere(['value_content_property_enum_id' => $savedFilter->value_content_property_enum_id]);
                    } elseif ($savedFilter->shop_brand_id) {
                        $savedFilters->andWhere(['shop_brand_id' => $savedFilter->shop_brand_id]);
                    } elseif ($savedFilter->country_alpha2) {
                        $savedFilters->andWhere(['country_alpha2' => $savedFilter->country_alpha2]);
                    }

                    $savedFilters = $savedFilters->all();
                }

                ?>
                <?php if (count($savedFilters) > 1) : ?>
                    <div class="sx-saved-filters-list sx-saved-filters-list--before">
                        <div class="h3 sx-title">Разделы, где встречаются товары с опцией «<?php echo $savedFilter->propertyValueName; ?>»</div>

                        <?php
                        $savedFiltersData = \skeeks\cms\models\CmsSavedFilter::formatFilters($savedFilters);
                        ?>
                        <? foreach ($savedFiltersData as $savedFiltersRow) : ?>
                            <!--<div class="h4 sx-sub-title"><?php /*echo \yii\helpers\ArrayHelper::getValue($savedFiltersRow, "name"); */ ?></div>-->
                            <ul class="list-unstyled list-inline" style="margin-bottom: 10px;">
                                <? foreach (\yii\helpers\ArrayHelper::getValue($savedFiltersRow, "savedFilters") as $sf) : ?>
                                    <?php echo $this->render("@app/views/modules/cms/tree/catalogs/_category", [
                                        'isActive'           => (@$savedFilter && $sf->id == $savedFilter->id),
                                        'image'              => $sf->image,
                                        'seoName'            => $sf->seoName,
                                        'displayName'        => $sf->cmsTree->name,
                                        'code'               => $sf->cmsTree->code,
                                        'url'                => $sf->url,
                                        'description'        => $sf->propertyValueName,
                                        'adult_css_class'    => \Yii::$app->adult->renderCssClass($sf->cmsTree),
                                        'adult_blocked_html' => \Yii::$app->adult->renderBlocked($sf->cmsTree),
                                    ]); ?>
                                <? endforeach; ?>
                            </ul>
                        <? endforeach; ?>
                    </div>
                <?php endif; ?>


                <?
                $savedFilters = [];
                if (@$model) {
                    //todo: вынести в шаблон
                    $savedFilters = $model->getCmsSavedFilters()
                        ->joinWith("cmsContentProperty as cmsContentProperty")
                        ->with("cmsTree")
                        ->with("cmsContentProperty")
                        ->with("valueContentElement")
                        ->with("valueContentPropertyEnum")
                        ->orderBy(['cmsContentProperty.priority' => SORT_ASC])
                        //->groupBy(['cmsContentProperty.id'])
                        ->limit(200)
                        ->all();
                }

                ?>
                <?php if ($savedFilters) : ?>
                    <?php
                    /**
                     * @link https://cms.skeeks.com/
                     * @copyright Copyright (c) 2010 SkeekS
                     * @license https://cms.skeeks.com/license/
                     * @author Semenov Alexander <semenov@skeeks.com>
                     */
                    /* @var $this yii\web\View */
                    $this->registerCss(<<<CSS
.sx-small::before {
    content: " ";
    background: linear-gradient(to bottom, #403d3d00, #ffffffd4);
    height: 73px;
    width: 100%;
    position: absolute;
    bottom: 0;
    z-index: 11;
}

.sx-small {
    max-height: 145px;
    position: relative;
    overflow: hidden;
}
.sx-more .btn {
    /*padding: 20px;
    font-size: 20px;*/
    margin-top: 0.5rem;
}

CSS
                    );

                    $this->registerJs(<<<JS
if ($(".sx-spoiler").height() > 200) {
    $(".sx-spoiler").addClass("sx-small");
    $(".sx-more").show();
}

$(".sx-more .btn").on("click", function() {
     $(".sx-spoiler").removeClass("sx-small");
     $(".sx-more").hide();
     return false;
});
JS
                    );
                    ?>

                    <div class="sx-saved-filters-list sx-saved-filters-list--after sx-spoiler" style="margin-top: 1.5rem;">
                        <?php
                        $savedFiltersData = \skeeks\cms\models\CmsSavedFilter::formatFilters($savedFilters);
                        ?>
                        <div class="h3 sx-title">Быстрый подбор товаров из раздела «<?php echo $savedFilter ? $savedFilter->getCmsTree()->one()->name : $model->name; ?>»</div>
                        <? foreach ($savedFiltersData as $savedFiltersRow) : ?>
                            <div class="h5 sx-sub-title"><?php echo \yii\helpers\ArrayHelper::getValue($savedFiltersRow, "name"); ?></div>
                            <ul class="list-unstyled list-inline" style="margin-bottom: 10px;">
                                <? foreach (\yii\helpers\ArrayHelper::getValue($savedFiltersRow, "savedFilters") as $sf) : ?>

                                    <?php echo $this->render("@app/views/modules/cms/tree/catalogs/_filter", [
                                        'isActive'    => (@$savedFilter && $sf->id == $savedFilter->id),
                                        'value_id'    => $sf->value_content_element_id ? $sf->value_content_element_id : $sf->value_content_property_enum_id,
                                        'property_id' => $sf->cms_content_property_id,
                                        'seoName'     => $sf->seoName,
                                        'displayName' => $sf->propertyValueNameInflected,
                                        'url'         => $sf->url,
                                    ]); ?>

                                    <?php /*echo $this->render("@app/views/modules/cms/tree/catalogs/_saved-filter", [
                                        'sf'          => $sf,
                                        'savedFilter' => $savedFilter,
                                        'cmsTree'     => $model,
                                        'displayName' => "propertyValueNameInflected",
                                    ]); */ ?>
                                <? endforeach; ?>
                            </ul>
                        <? endforeach; ?>
                    </div>
                    <div class="sx-more">
                        <button class="btn btn-default btn-block">
                            Показать еще
                        </button>
                    </div>

                <?php endif; ?>

            </div>
            <div class="order-md-1 g-bg-secondary sx-content-col-left" style="">
                <?= $this->render('@app/views/modules/cms/tree/catalogs/_left-col', [
                    'catalogSettings' => $catalogSettings,
                    'filtersWidget'   => $filtersWidget,
                    'model'           => $model,
                    'savedFilter'     => @$savedFilter,
                    'appliedValues'   => $appliedValues,
                ]); ?>
            </div>
        </div>
    </div>
</section>

