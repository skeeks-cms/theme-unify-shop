<?php
/**
 * @link https://cms.skeeks.com/
 * @copyright Copyright (c) 2010 SkeekS
 * @license https://cms.skeeks.com/license/
 * @author Semenov Alexander <semenov@skeeks.com>
 */
/* @var $this yii\web\View */
/* @var $model \skeeks\cms\models\CmsTree */


$dataProvider = new \yii\data\ActiveDataProvider([
    'query' => \skeeks\cms\shop\models\ShopCmsContentElement::find()->active(),
]);
$dataProvider->query->cmsTree();

$dataProvider->pagination->pageSize = \Yii::$app->unifyShopTheme->productListPerPageSize;
$dataProvider->query->with('shopProduct');
$dataProvider->query->with('shopProduct.baseProductPrice');
$dataProvider->query->with('image');
$dataProvider->query->joinWith('shopProduct');

\Yii::$app->shop->filterBaseContentElementQuery($dataProvider->query);

$dataProvider->query->joinWith("shopProduct.shopFavoriteProducts as fav");
$dataProvider->query->andWhere(['is not', "fav.id", null]);
$dataProvider->query->andWhere(["fav.shop_user_id" => \Yii::$app->shop->shopUser->id]);

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

/*\Yii::$app->breadcrumbs->createBase()->append(\Yii::t('skeeks/shop/app', 'Favorite products'));*/

echo $this->render("@app/views/modules/cms/tree/catalogs/".\Yii::$app->unifyShopTheme->product_list_view_file, [
    'dataProvider'  => $dataProvider,
    'filtersWidget' => $filtersWidget,
    'title'         => \Yii::t('skeeks/shop/app', 'Favorite products'),
]);
?>
