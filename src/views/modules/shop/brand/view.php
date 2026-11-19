<?php
/**
 * @link https://cms.skeeks.com/
 * @copyright Copyright (c) 2010 SkeekS
 * @license https://cms.skeeks.com/license/
 * @author Semenov Alexander <semenov@skeeks.com>
 */
/* @var $this yii\web\View */

if (@$isShowMainImage !== false) {
    $isShowMainImage = true;
}
$this->theme->element_content_layout = 'no-col';
$image = $model->logo;
$description = $model->description_full;

$brandTree = \skeeks\cms\models\CmsTree::find()->cmsSite()->andWhere(['view_file' => 'brands'])->one();
if ($brandTree) {
    \Yii::$app->breadcrumbs->parts = [];
    \Yii::$app->breadcrumbs->setPartsByTree($brandTree)->append([
        'name' => $model->name,
        'url'  => $model->url,
    ]);
}

$brandProductsTreeIdsQuery = \skeeks\cms\shop\models\ShopCmsContentElement::find()
    ->cmsSite()
    ->active()
    ->innerJoinWith("shopProduct as shopProduct")
    ->andWhere(["shopProduct.brand_id" => $model->id])
    ->select([
        \skeeks\cms\shop\models\ShopCmsContentElement::tableName().".tree_id",
    ])
    ->groupBy([\skeeks\cms\shop\models\ShopCmsContentElement::tableName().".tree_id"]);

$cmsTrees = \skeeks\cms\models\CmsTree::find()
    ->cmsSite()
    ->andWhere(['id' => $brandProductsTreeIdsQuery])
    ->all();
$treeIds = \yii\helpers\ArrayHelper::getColumn($cmsTrees, 'id');
$hasCollectionsEnabledTree = false;
foreach ($cmsTrees as $cmsTree) {
    if ($cmsTree->shop_has_collections) {
        $hasCollectionsEnabledTree = true;
        break;
    }
}

$dataProvider = new \yii\data\ActiveDataProvider([
    'query' => \skeeks\cms\shop\models\ShopCmsContentElement::find()->cmsSite()->active()->select([
        \skeeks\cms\shop\models\ShopCmsContentElement::tableName().".*",
    ]),
]);
$dataProvider->pagination->defaultPageSize = \Yii::$app->view->theme->productListPerPageSize;
$dataProvider->query->with('shopProduct');
$dataProvider->query->with('shopProduct.baseProductPrice');
$dataProvider->query->with('image');
$dataProvider->query->with('images');
$dataProvider->query->innerJoinWith('shopProduct as shopProduct');
$dataProvider->query->andWhere(["shopProduct.brand_id" => $model->id]);
$dataProvider->query->groupBy(\skeeks\cms\shop\models\ShopCmsContentElement::tableName().".id");

$filtersWidget = new \skeeks\cms\themes\unifyshop\filters\StandartShopFiltersWidget([
    'activeFormConfig' => [
        'action' => $model->url,
    ],
]);
$baseQuery = clone $dataProvider->query;

$priceFiltersHandler = null;
$eavFiltersHandler = null;
$shopDataFiltersHandler = null;
if (\Yii::$app->view->theme->is_allow_filters) {
    $shopDataFiltersHandler = new \skeeks\cms\shop\queryFilter\ShopDataFiltersHandler([
        'baseQuery' => $baseQuery,
    ]);

    $eavFiltersHandler = new \skeeks\cms\shop\queryFilter\ShopEavQueryFilterHandler([
        'baseQuery' => $baseQuery,
    ]);
    $eavFiltersHandler->openedPropertyIds = \Yii::$app->skeeks->site->shopSite->open_filter_property_ids;
    $eavFiltersHandler->viewFile = '@app/views/filters/eav-filters';
    $rpQuery = $eavFiltersHandler->getRPQuery();

    if ($show_filter_property_ids = \Yii::$app->skeeks->site->shopSite->show_filter_property_ids) {
        $rpQuery->andWhere([\skeeks\cms\models\CmsContentProperty::tableName().'.id' => $show_filter_property_ids]);
    }

    if ($treeIds) {
        $rpQuery->andWhere([
            'or',
            ['map.cms_tree_id' => $treeIds],
            ['map.cms_tree_id' => null],
        ]);
    }
    $eavFiltersHandler->initRPByQuery($rpQuery);

    $priceFiltersHandler = new \skeeks\cms\shop\queryFilter\PriceFiltersHandler([
        'baseQuery' => $baseQuery,
        'viewFile'  => '@app/views/filters/price-filter',
    ]);

    $filtersWidget->registerHandler($priceFiltersHandler, "price");
    $filtersWidget->registerHandler($shopDataFiltersHandler, 'data');
    $filtersWidget->registerHandler($eavFiltersHandler, 'eav');
}
$filtersWidget->loadFromRequest();
$filtersWidget->applyToQuery($dataProvider->query);
\Yii::$app->shop->filterByTypeContentElementQuery($dataProvider->query);

$appliedValues = [];
foreach ([$priceFiltersHandler, $shopDataFiltersHandler, $eavFiltersHandler] as $handler) {
    if ($handler && ($handlerAppliedValues = $handler->getApplied())) {
        $appliedValues = \yii\helpers\ArrayHelper::merge($appliedValues, $handlerAppliedValues);
    }
}

$q = clone $dataProvider->query;
$select = [
    \skeeks\cms\models\CmsContentElement::tableName().".id",
];
if (isset($q->select['realPrice'])) {
    $select['realPrice'] = $q->select['realPrice'];
}
$totalOffers = $q->select($select)->limit(-1)->offset(-1)->orderBy([])->count('*');
$dataProvider->setTotalCount($totalOffers);

//Пустую страницу бренда не индексируем, но ссылки с неё обходим
if (!$totalOffers) {
    $this->registerMetaTag([
        'name'    => 'robots',
        'content' => 'noindex, follow',
    ], 'robots');
}

/**
 * Микроразметка страницы бренда: каталог (CollectionPage + ItemList товаров страницы, JSON-LD), не новость.
 * Если в описании бренда уже есть своя разметка CollectionPage, общую не выводим, чтобы не задваивать.
 */
$sxIsCollectionLd = $totalOffers && strpos((string)$model->description_short.(string)$model->description_full, 'CollectionPage') === false;

$hasCollections = $hasCollectionsEnabledTree;
$viewMode = 'product';
if ($hasCollections) {
    \Yii::$app->seo->canUrl->ADDimportant_pnames(['sx-catalog-view']);
    $viewMode = \Yii::$app->request->get("sx-catalog-view", "collection");
    if (\Yii::$app->request->post("sx-catalog-view")) {
        $viewMode = \Yii::$app->request->post("sx-catalog-view");
    }
}

$this->registerCss(<<<CSS
.sx-saved-filters-list .list-inline-item a {
    display: inline-flex;
    min-height: 100%;
    min-width: 20rem;
    overflow: hidden;
    text-align: left;
}
.sx-saved-filters-list .list-inline-item .sx-img-wrapper {
    margin-right: 0.5rem;
}

.sx-img-wrapper img {
    border-radius: var(--base-radius);
}

/*.sx-brand-page {
    min-height: 80vh;
    padding: 1.5rem 0;
}*/

.sx-brand-page .sx-container {
    padding-top: 1.5rem;
    background: white;
    padding-bottom: 1.5rem;
    min-height: 80vh;
}

    


.sx-brand-page .sx-brand-img {
    border-radius: var(--base-radius);
    background: var(--second-bg-color);
    padding: 0.5rem;
}
.sx-brand-page .sx-brand-img img {
    border-radius: var(--base-radius);
}

.sx-properties {
    margin-top: 1.5rem;
    border-radius: var(--base-radius);
    background: var(--second-bg-color);
    padding: 1rem;
}

.sx-properties p:last-child {
    margin-bottom: 0;
}
.sx-properties p {
    margin-bottom: 0.5rem;
}

.sx-categories {
    margin-top: 1.5rem;
}
.sx-description {
    margin-top: 1.5rem;
    
    border-radius: var(--base-radius);
    background: var(--second-bg-color);
    padding: 1rem;
}
.sx-brand-catalog {
    margin-top: 1.5rem;
}
.sx-brand-catalog .sx-catalog-h1-wrapper {
    margin-bottom: 1rem;
}
.sx-brand-catalog .sx-btn-view-mode {
    margin-bottom: 20px;
}

CSS
);
?>


<section class="sx-brand-page">
    <div class="container sx-container">
        <div class="row">
            <div class="col-md-12">

                <? /* if (!$this->theme->is_image_body_begin) : */ ?>

                <? /* endif; */ ?>
                <div class="sx-content">
                    <div class="d-flex">

                        <? if ($image) : ?>
                            <div class="sx-brand-img my-auto">
                                <img src="<?= \Yii::$app->imaging->thumbnailUrlOnRequest($image ? $image->src : null,
                                    new \skeeks\cms\components\imaging\filters\Thumbnail([
                                        'w' => 100,
                                        'h' => 100,
                                    ]), $model->code
                                ) ?>" title="<?= $model->seoName; ?>" alt="<?= $model->seoName; ?>" class="img-responsive"/>
                            </div>
                        <? endif; ?>
                        <div class="col my-auto">

                            <?= $this->render('@app/views/breadcrumbs', [
                                'model' => $model,
                            ]) ?>

                        </div>
                    </div>


                    <?/*
                    $infoModel = $model;

                    $widget = \skeeks\cms\rpViewWidget\RpViewWidget::beginWidget('brand-properties', [
                        'model'                   => $infoModel,
                        'visible_only_has_values' => true,
                    ]);
                    */?><!--
                    <?/* if ($widget->rpAttributes) : */?>
                        <div class="row">
                            <div class="col-md-6 col-12">
                                <div class="sx-properties">

                                    <?/* $widget::end(); */?>
                                </div>
                            </div>
                        </div>
                    --><?/* endif; */?>


                    <!--Товары и категории-->
                    <?php if ($cmsTrees) : ?>
                        <div class="sx-categories">
                                <div class="sx-saved-filters-list">
                                    <!--<h3>Категории:</h3>-->
                                    <ul class="list-unstyled list-inline" style="margin-bottom: 10px;">
                                        <? foreach ($cmsTrees as $cmsTree) : ?>
                                            <?php
                                            $sf = \skeeks\cms\models\CmsSavedFilter::find()->cmsSite()
                                                ->andWhere(['cms_tree_id' => $cmsTree->id])
                                                ->andWhere(['shop_brand_id' => $model->id])
                                                ->one();

                                            if (!$sf) {
                                                $sf = new \skeeks\cms\models\CmsSavedFilter();
                                                $sf->cms_tree_id = $cmsTree->id;
                                                $sf->shop_brand_id = $model->id;

                                                if (!$sf->save()) {
                                                    print_r($sf->errors);
                                                    die;
                                                }
                                            }
                                            echo $this->render("@app/views/modules/cms/tree/catalogs/_category", [
                                                'isActive'     => 0,
                                                'image'        => $sf->image,
                                                'seoName'      => $sf->seoName,
                                                'displayName'  => $sf->cmsTree->name,
                                                'code'         => $sf->cmsTree->code,
                                                'url'          => $sf->url,
                                                'description'  => $sf->propertyValueName,
                                                'image_width'  => 100,
                                                'image_height' => 100,
                                            ]); ?>
                                        <? endforeach; ?>
                                    </ul>
                                </div>
                        </div>
                    <?php endif; ?>

                    <div class="sx-brand-catalog">
                        <div class="row">
                            <div class="order-md-2 sx-content-col-main">
                                <div class="sx-catalog-h1-wrapper">
                                    <h2 class="sx-breadcrumbs-h1 sx-catalog-h1">
                                        Товары <?= $model->seoName; ?>
                                        <?php if ($appliedValues) : ?>
                                            <span class="sx-applied-filters-text">+ применены фильтры</span>
                                        <?php endif; ?>
                                    </h2>
                                    <div class="sx-catalog-total-offers" style="color: #979797; display: contents; margin-top: auto; margin-left: 12px; font-size: 15px;">
                                        (<?php echo \Yii::t('app', '{n, plural, =0{нет&nbsp;товаров} =1{#&nbsp;товар} one{#&nbsp;товар} few{#&nbsp;товара} many{#&nbsp;товаров} other{#&nbsp;товаров}}', ['n' => $totalOffers]); ?>)
                                    </div>
                                </div>

                                <?php if ($appliedValues) : ?>
                                    <div class="sx-saved-filters-list sx-saved-filters-list--after" style="margin-top: 0;">
                                        <ul class="list-unstyled list-inline" style="margin-bottom: 10px;">
                                            <?php foreach ($appliedValues as $data) : ?>
                                                <?php
                                                $name = \yii\helpers\ArrayHelper::getValue($data, "name");
                                                $value = \yii\helpers\ArrayHelper::getValue($data, "value");
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
                                            <li class="list-inline-item sx-active" style="margin-bottom: 5px;">
                                                <a href="<?php echo $model->url; ?>" class="btn btn-default">
                                                    <span data-toggle="tooltip" data-html="true" data-original-title="Сбросить примененные фильтры">Очистить все</span>
                                                    <i class="hs-icon hs-icon-close sx-close-btn"></i>
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                <?php endif; ?>

                                <div class="row sx-mobile-filters-block js-sticky-block" id="sx-mobile-filters-block" data-has-sticky-header="true" data-start-point="#sx-mobile-filters-block" data-end-point=".sx-footer">
                                    <div class="col-12 sx-mobile-filters-block--inner">
                                        <div class="btn-group" style="width: 100%;">
                                            <? if (\Yii::$app->view->theme->is_allow_filters) : ?>
                                                <a href="#" class="sx-btn-filter btn sx-btn-white sx-icon-arrow-down--after">Фильтры</a>
                                            <? endif; ?>
                                            <a href="#" class="btn dropdown-toggle sx-btn-white sx-btn-sort-select sx-icon-arrow-down--after" data-toggle="dropdown">
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
                                        <div class="sx-filters-selected-wrapper" style="display: none;"></div>
                                    </div>
                                </div>

                                <?php if ($hasCollections) : ?>
                                    <div class="btn-group btn-block sx-btn-view-mode" role="group" aria-label="Basic example">
                                        <a href="<?php echo \yii\helpers\Url::current(['sx-catalog-view' => 'collection']); ?>" type="button" class="btn btn-xl <?php echo $viewMode == "collection" ? "btn-primary" : "btn-secondary"; ?>">Коллекции</a>
                                        <a href="<?php echo \yii\helpers\Url::current(['sx-catalog-view' => 'product']); ?>" type="button" class="btn btn-xl <?php echo $viewMode == "product" ? "btn-primary" : "btn-secondary"; ?>">Товары</a>
                                    </div>
                                <?php endif; ?>

                                <?php if ($hasCollections && $viewMode == "collection") : ?>
                                    <?php
                                    $this->registerCss(<<<CSS
.sx-brand-catalog .sorting,
.sx-brand-catalog .sx-btn-sort-select {
    display: none;
}
CSS
                                    );

                                    $collectionsDataProvider = new \yii\data\ActiveDataProvider([
                                        'query'      => \skeeks\cms\themes\unifyshop\helpers\CollectionsByProducts::query($dataProvider->query),
                                        'pagination' => [
                                            'pageSize' => 24,
                                        ],
                                    ]);
                                    ?>
                                    <?php echo $this->render("@app/views/collections/collection-list", [
                                        'dataProvider' => $collectionsDataProvider,
                                    ]); ?>
                                <?php elseif ($totalOffers > 0) : ?>
                                    <?php
                                    //Товары для ItemList записывает product-list (только выведенные на странице)
                                    if ($sxIsCollectionLd) {
                                        $this->params['sxCatalogLdItems'] = [];
                                    }
                                    ?>
                                    <?php echo $this->render("@app/views/products/product-list", [
                                        'dataProvider' => $dataProvider,
                                    ]); ?>
                                <?php else : ?>
                                    <div class="sx-catalog-no-products" style="display: flex; align-items: center; justify-content: center; min-height: 300px;">
                                        <div class="sx-empty-content" style="text-align: center;">
                                            <div class="h1">Товары не найдены!</div>
                                            <?php if ($appliedValues) : ?>
                                                <div style="margin-bottom: 20px;">У вас применены фильтры, попробуйте изменить условия!</div>
                                                <a href="<?php echo $model->url; ?>" class="btn btn-primary btn-xxl">Сбросить фильтры</a>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="order-md-1 g-bg-secondary sx-content-col-left">
                                <div class="sx-col-left-block">
                                    <div class="js-sticky-block-no">
                                        <div class="sx-filters-left-wrapper js-scrollbar-no">
                                            <?php
                                            \skeeks\cms\themes\unify\widgets\filters\assets\FiltersWidgetAsset::register($this);
                                            $pjax = \skeeks\cms\widgets\PjaxLazyLoad::begin(); ?>
                                            <?php if ($pjax->isPjax) : ?>
                                                <? echo $filtersWidget->run(); ?>
                                            <?php else : ?>
                                                <? $form = \yii\widgets\ActiveForm::begin([]); ?>
                                                <? \yii\widgets\ActiveForm::end(); ?>
                                            <?php endif; ?>
                                            <? $pjax::end(); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <?php if ($description) : ?>
                        <div class="sx-description">
                            <?= $description; ?>
                        </div>

                    <?php endif; ?>


                </div>
            </div>
        </div>
    </div>

</section>
<?php
if ($sxIsCollectionLd) {
    $sxLdUrl = $model->getUrl(true);
    $sxLd = [
        '@context' => 'https://schema.org',
        '@type'    => 'CollectionPage',
        '@id'      => $sxLdUrl.'#webpage',
        'url'      => $sxLdUrl,
        'name'     => (string)$model->seoName,
    ];

    //Описание то же, что в meta description страницы (его задаёт BrandController)
    $sxLdDescription = '';
    if (isset($this->metaTags['description']) && preg_match('/content="([^"]*)"/', $this->metaTags['description'], $sxLdMatch)) {
        $sxLdDescription = trim(\yii\helpers\Html::decode($sxLdMatch[1]));
    }
    if ($sxLdDescription !== '') {
        $sxLd['description'] = $sxLdDescription;
    }

    $sxLdBrand = [
        '@type' => 'Brand',
        'name'  => (string)$model->name,
    ];
    if ($model->logo) {
        $sxLdBrand['logo'] = $model->logo->absoluteSrc;
    }
    $sxLd['about'] = $sxLdBrand;

    $sxLdItems = (array)\yii\helpers\ArrayHelper::getValue($this->params, 'sxCatalogLdItems', []);
    if ($sxLdItems) {
        $sxLdPosition = (int)$dataProvider->pagination->offset;
        $sxLdList = [];
        foreach ($sxLdItems as $sxLdItem) {
            $sxLdPosition++;
            $sxLdList[] = [
                '@type'    => 'ListItem',
                'position' => $sxLdPosition,
                'url'      => $sxLdItem['url'],
                'name'     => $sxLdItem['name'],
            ];
        }

        $sxLd['mainEntity'] = [
            '@type'           => 'ItemList',
            'numberOfItems'   => (int)$totalOffers,
            'itemListElement' => $sxLdList,
        ];
    }

    echo \yii\helpers\Html::script(
        \yii\helpers\Json::encode($sxLd, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG),
        ['type' => 'application/ld+json']
    );
}
unset($this->params['sxCatalogLdItems']);
?>

