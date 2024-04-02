<?php
/**
 * @link https://cms.skeeks.com/
 * @copyright Copyright (c) 2010 SkeekS
 * @license https://cms.skeeks.com/license/
 * @author Semenov Alexander <semenov@skeeks.com>
 */
/* @var $this yii\web\View */

/**
 * @var $model \skeeks\cms\models\CmsTree
 * @var $savedFilter \skeeks\cms\models\CmsSavedFilter
 */
//print_r($model->toArray());die;
$savedFilter = @$savedFilter;
$dataProvider = new \yii\data\ActiveDataProvider([
    'query' => \skeeks\cms\shop\models\ShopCmsContentElement::find()->cmsSite()->active()->select([
        \skeeks\cms\shop\models\ShopCmsContentElement::tableName() . ".*"
    ]),
]);
//Если нужно учитывать второстепенную привязку разделов, нужно доработать.
$dataProvider->query->cmsTree($model, true, \Yii::$app->view->theme->is_join_second_trees ? true : false);
$dataProvider->pagination->defaultPageSize = \Yii::$app->view->theme->productListPerPageSize;
//$dataProvider->pagination->pageSize = \Yii::$app->view->theme->productListPerPageSize;
$dataProvider->query->with('shopProduct');
$dataProvider->query->with('shopProduct.baseProductPrice');
$dataProvider->query->with('image');
$dataProvider->query->with('images');
$dataProvider->query->innerJoinWith('shopProduct');
$dataProvider->query->groupBy(\skeeks\cms\shop\models\ShopCmsContentElement::tableName().".id");

/*print_r($dataProvider->query);die;*/
//\Yii::$app->shop->filterByMainPidContentElementQuery($dataProvider->query);


$filtersWidget = new \skeeks\cms\themes\unifyshop\filters\StandartShopFiltersWidget([
    'activeFormConfig' => [
        'action' => $model->url,
    ],
]);
$baseQuery = clone $dataProvider->query;

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
    $treeIds = [$model->id];
    $rpQuery->andWhere([
        'or',
        ['map.cms_tree_id' => $treeIds],
        ['map.cms_tree_id' => null],
    ]);
    //print_r($rpQuery->createCommand()->rawSql);die;
    $eavFiltersHandler->initRPByQuery($rpQuery);
    $priceFiltersHandler = new \skeeks\cms\shop\queryFilter\PriceFiltersHandler([
        'baseQuery' => $baseQuery,
        'viewFile'  => '@app/views/filters/price-filter',
    ]);

    $filtersWidget
        ->registerHandler($priceFiltersHandler, "price");

    $filtersWidget
        ->registerHandler($shopDataFiltersHandler, 'data');

    $filtersWidget
        ->registerHandler($eavFiltersHandler, 'eav');
}
$filtersWidget->loadFromRequest();

if ($eavFiltersHandler || $shopDataFiltersHandler) {
    if ($savedFilter) {
        //print_r($eavFiltersHandler->toArray());die;
        $eavFiltersHandler->loadFromSavedFilter($savedFilter);
        $shopDataFiltersHandler->loadFromSavedFilter($savedFilter);
        $filtersWidget->applyToQuery($dataProvider->query);
    } else {
        $filtersWidget->applyToQuery($dataProvider->query);
        //Создать фильтр если не заполнена цена и данные для магазина
        if (!$priceFiltersHandler->getApplied() && !$shopDataFiltersHandler->getApplied()) {
            $savedFilterFromRequest = $eavFiltersHandler->savedFilter;
            if ($savedFilterFromRequest) {
                \Yii::$app->response->redirect($savedFilterFromRequest->url);
                \Yii::$app->end();
            }
        }

        if (!$priceFiltersHandler->getApplied() && !$eavFiltersHandler->getApplied()) {
            $savedFilterFromRequest = $shopDataFiltersHandler->savedFilter;
            if ($savedFilterFromRequest) {
                \Yii::$app->response->redirect($savedFilterFromRequest->url);
                \Yii::$app->end();
            }
        }
    }
} else {
    $filtersWidget->applyToQuery($dataProvider->query);
}



?>
<!--Тут кэш и построение микроразметки-->
<?php

$filtersData = $eavFiltersHandler->getApplied();
$filtersData = \yii\helpers\ArrayHelper::merge($filtersData, $shopDataFiltersHandler->getApplied());
$filtersData = \yii\helpers\ArrayHelper::merge($filtersData, $priceFiltersHandler->getApplied());


$data = \skeeks\cms\shop\components\ShopComponent::getAgregateCategoryData($dataProvider->query, @$savedFilter ? $savedFilter : $model, $filtersData, $filtersWidget->getAvailabilityHandler()->value);
\Yii::$app->shop->filterByTypeContentElementQuery($dataProvider->query);

//print_r($dataProvider->query->createCommand()->rawSql);die;
//Более оптимальный запрос
$q = clone $dataProvider->query;
$realPrice = '';
$select = [
        \skeeks\cms\models\CmsContentElement::tableName().".id"
];
if (isset($q->select['realPrice'])) {
    $realPrice = $q->select['realPrice'];
    $select['realPrice'] = $realPrice;
}
$total = $q->select($select)->limit(-1)->offset(-1)->orderBy([])->count('*');
$dataProvider->setTotalCount($total);

/**
 * Формирование по шаблону
 * Это надо вынести куда нибудь в контроллер
 */
$cmsTreeType = $model->cmsTreeType;
if (!$model->meta_title && $cmsTreeType->meta_title_template) {
    $metaTitle = $cmsTreeType->meta_title_template;
    if (strpos($metaTitle, "{=section.seoName}") !== false) {
        $metaTitle = str_replace("{=section.seoName}", $model->seoName, $metaTitle);
    }
    if (strpos($metaTitle, "{=siteName}") !== false) {
        $metaTitle = str_replace("{=siteName}", \Yii::$app->skeeks->site->name, $metaTitle);
    }
    if (strpos($metaTitle, "{=minMoney}") !== false) {
        $lowPrice = \yii\helpers\ArrayHelper::getValue($data, 'lowPrice', 0);
        $money = new \skeeks\cms\money\Money((string)$lowPrice, \Yii::$app->money->currencyCode);
        $metaTitle = str_replace("{=minMoney}", $money, $metaTitle);
    }

    $this->title = $metaTitle;
    $this->registerMetaTag([
        "name"    => 'og:title',
        "content" => $this->title,
    ], 'og:title');
}

if (!$model->meta_description && $cmsTreeType->meta_description_template) {
    $metaDescription = $cmsTreeType->meta_description_template;
    if (strpos($metaDescription, "{=section.seoName}") !== false) {
        $metaDescription = str_replace("{=section.seoName}", $model->seoName, $metaDescription);
    }
    if (strpos($metaDescription, "{=siteName}") !== false) {
        $metaDescription = str_replace("{=siteName}", \Yii::$app->skeeks->site->name, $metaDescription);
    }
    if (strpos($metaDescription, "{=minMoney}") !== false) {
        $lowPrice = \yii\helpers\ArrayHelper::getValue($data, 'lowPrice', 0);
        $money = new \skeeks\cms\money\Money((string)$lowPrice, \Yii::$app->money->currencyCode);
        $metaDescription = str_replace("{=minMoney}", $money, $metaDescription);
    }


    $this->registerMetaTag([
        "name"    => 'og:description',
        "content" => $metaDescription,
    ], 'og:description');

    $this->registerMetaTag([
        "name"    => 'description',
        "content" => $metaDescription,
    ], 'description');
}

if (!$model->meta_keywords && $cmsTreeType->meta_keywords_template) {
    $metaKeywords = $cmsTreeType->meta_keywords_template;
    if (strpos($metaKeywords, "{=section.seoName}") !== false) {
        $metaKeywords = str_replace("{=section.seoName}", $model->seoName, $metaKeywords);
    }
    if (strpos($metaKeywords, "{=siteName}") !== false) {
        $metaKeywords = str_replace("{=siteName}", \Yii::$app->skeeks->site->name, $metaKeywords);
    }
    if (strpos($metaKeywords, "{=minMoney}") !== false) {
        $lowPrice = \yii\helpers\ArrayHelper::getValue($data, 'lowPrice', 0);
        $money = new \skeeks\cms\money\Money((string)$lowPrice, \Yii::$app->money->currencyCode);
        $metaKeywords = str_replace("{=minMoney}", $money, $metaKeywords);
    }

    $this->registerMetaTag([
        "name"    => 'keywords',
        "content" => $metaKeywords,
    ], 'keywords');
}

//print_r($eavFiltersHandler->toArray());die;

?>
<span itemprop="product" itemscope itemtype="https://schema.org/Product">
<meta itemprop="name" content="<?php echo $model->seoName; ?>"/>
    
<?php if ($data) : ?>
    <span itemprop="aggregateRating" itemscope itemtype="https://schema.org/AggregateRating">
        <meta itemprop="reviewCount" content="<?php echo \yii\helpers\ArrayHelper::getValue($data, 'reviewCount', 0); ?>"/>
        <meta itemprop="ratingValue" content="<?php echo \yii\helpers\ArrayHelper::getValue($data, 'ratingValue', 0); ?>"/>
        <meta itemprop="bestRating" content="<?php echo \yii\helpers\ArrayHelper::getValue($data, 'bestRating', 0); ?>"/>
        <meta itemprop="worsRating" content="<?php echo \yii\helpers\ArrayHelper::getValue($data, 'worsRating', 0); ?>"/>
    </span>
<?php endif; ?>

<div itemprop="offers" itemscope itemtype="https://schema.org/AggregateOffer">
    <meta itemprop="priceCurrency" content="<?php echo \Yii::$app->money->currency_code; ?>"/>
    <?php if ($data) : ?>
        <meta itemprop="offerCount" content="<?php echo \yii\helpers\ArrayHelper::getValue($data, 'offerCount', 0); ?>"/>
        <meta itemprop="highPrice" content="<?php echo \yii\helpers\ArrayHelper::getValue($data, 'highPrice', 0); ?>"/>
        <meta itemprop="lowPrice" content="<?php echo \yii\helpers\ArrayHelper::getValue($data, 'lowPrice', 0); ?>"/>

    <?php endif; ?>
    <?
    echo $this->render("@app/views/modules/cms/tree/catalogs/".\Yii::$app->view->theme->product_list_view_file, [
        'model'                => $model,
        'description_short'    => $model->description_short,
        'description'          => $model->description_full,
        'dataProvider'         => $dataProvider,
        'filtersWidget'        => $filtersWidget,
        'savedFilter'          => @$savedFilter,
        'agregateCategoryData' => $data,
    ]);
    ?>
</div>
</span>