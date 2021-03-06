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
 */
$dataProvider = new \yii\data\ActiveDataProvider([
    'query' => \skeeks\cms\shop\models\ShopCmsContentElement::find()->active(),
]);
//Если нужно учитывать второстепенную привязку разделов, нужно доработать.
$dataProvider->query->cmsTree();

$dataProvider->pagination->pageSize = \Yii::$app->unifyShopTheme->productListPerPageSize;
$dataProvider->query->with('shopProduct');
$dataProvider->query->with('shopProduct.baseProductPrice');
$dataProvider->query->with('image');
$dataProvider->query->joinWith('shopProduct');


\Yii::$app->shop->filterByTypeContentElementQuery($dataProvider->query);
//\Yii::$app->shop->filterByMainPidContentElementQuery($dataProvider->query);


$filtersWidget = new \skeeks\cms\themes\unifyshop\filters\StandartShopFiltersWidget();
$baseQuery = clone $dataProvider->query;

if (\Yii::$app->unifyShopTheme->is_allow_filters) {
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
    if ($model->main_cms_tree_id) {
        $treeIds[] = $model->main_cms_tree_id;
    }
    
    $rpQuery->andWhere([
        'or',
        ['map.cms_tree_id' => $treeIds],
        ['map.cms_tree_id' => null],
    ]);

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
$filtersWidget->applyToQuery($dataProvider->query);

?>
<div itemprop="offers" itemscope="" itemtype="http://schema.org/AggregateOffer">
<meta itemprop="priceCurrency" content="<?php echo \Yii::$app->money->currency_code; ?>" />
<?
echo $this->render("@app/views/modules/cms/tree/catalogs/".\Yii::$app->unifyShopTheme->product_list_view_file, [
    'model'         => $model,
    'description'   => $model->description_full,
    'dataProvider'  => $dataProvider,
    'filtersWidget' => $filtersWidget,
]);
?>
</div>