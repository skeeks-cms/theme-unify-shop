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
    'query' => \skeeks\cms\shop\models\ShopCmsContentElement::find()->active(),
]);
//Если нужно учитывать второстепенную привязку разделов, нужно доработать.
$dataProvider->query->cmsTree($model, true, \Yii::$app->view->theme->is_join_second_trees ? true : false);
$dataProvider->pagination->defaultPageSize = \Yii::$app->view->theme->productListPerPageSize;
//$dataProvider->pagination->pageSize = \Yii::$app->view->theme->productListPerPageSize;
$dataProvider->query->with('shopProduct');
$dataProvider->query->with('shopProduct.baseProductPrice');
$dataProvider->query->with('image');
$dataProvider->query->joinWith('shopProduct');
$dataProvider->query->groupBy(\skeeks\cms\shop\models\ShopCmsContentElement::tableName().".id");

/*print_r($dataProvider->query);die;*/
\Yii::$app->shop->filterByTypeContentElementQuery($dataProvider->query);
//\Yii::$app->shop->filterByMainPidContentElementQuery($dataProvider->query);


$filtersWidget = new \skeeks\cms\themes\unifyshop\filters\StandartShopFiltersWidget([
    'activeFormConfig' => [
        'action' => $model->url,
    ],
]);
$baseQuery = clone $dataProvider->query;

$eavFiltersHandler = null;
if (\Yii::$app->view->theme->is_allow_filters) {
    $eavFiltersHandler = new \skeeks\cms\shop\queryFilter\ShopEavQueryFilterHandler([
        'baseQuery' => $baseQuery,
    ]);

    $eavFiltersHandler->openedPropertyIds = \Yii::$app->skeeks->site->shopSite->open_filter_property_ids;
    $eavFiltersHandler->viewFile = '@app/views/filters/eav-filters';
    $rpQuery = $eavFiltersHandler->getRPQuery();

    if ($show_filter_property_ids = \Yii::$app->skeeks->site->shopSite->show_filter_property_ids) {
        $rpQuery->andWhere([\skeeks\cms\models\CmsContentProperty::tableName().'.id' => $show_filter_property_ids]);
    }

    /*if ($model->activeChildren) {
        $rpQuery->andWhere([
            'or',
            ['map.cms_tree_id' => $model->id],
            ['map.cms_tree_id' => null],
        ]);
    } */

    $treeIds = [$model->id];
    /*if ($model->main_cms_tree_id) {
        $treeIds[] = $model->main_cms_tree_id;
    }*/
    //print_r($treeIds);die;
    /*print_r($treeIds);die;*/
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
        ->registerHandler($eavFiltersHandler, 'eav');
}
$filtersWidget->loadFromRequest();
if ($eavFiltersHandler) {
    if ($savedFilter) {
        $eavFiltersHandler->loadFromSavedFilter($savedFilter);
    }
    $savedFilterFromRequest = $eavFiltersHandler->savedFilter;
    if ($savedFilterFromRequest && !$savedFilter) {
        \Yii::$app->response->redirect($savedFilterFromRequest->url);
        \Yii::$app->end();
    }
}
$filtersWidget->applyToQuery($dataProvider->query);

?>
<!--Тут кэш и построение микроразметки-->
<?php

$q = clone $dataProvider->query;
$total = $q->limit(-1)->offset(-1)->orderBy([])->count('*');
$dataProvider->setTotalCount($total);

$data = \skeeks\cms\shop\components\ShopComponent::getAgregateCategoryData($dataProvider->query, @$savedFilter ? $savedFilter : $model);
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
        'model'             => $model,
        'description_short' => $model->description_short,
        'description'       => $model->description_full,
        'dataProvider'      => $dataProvider,
        'filtersWidget'     => $filtersWidget,
        'savedFilter'       => @$savedFilter,
    ]);
    ?>
</div>
</span>