<?
/**
 * @author Semenov Alexander <semenov@skeeks.com>
 * @link http://skeeks.com/
 * @copyright 2010 SkeekS (СкикС)
 * @date 06.03.2015
 */
/* @var $this \yii\web\View */
?>

<? /*= $this->render('@template/include/breadcrumbs', [
    'title' => "Результаты поиска: " . \Yii::$app->cmsSearch->searchQuery
])*/ ?>
<!--<section style="padding: 40px 0;">
    <div class="container sx-content">
        <div class="row">
            <div class="col-md-12">
                <div class="alert g-bg-secondary" role="alert">
                    <?/*= Yii::t("skeeks/unify", "You searched"); */?>: <strong><?/*= \Yii::$app->cmsSearch->searchQuery; */?></strong>
                </div>->
                <div class="row">
                    <div class="col-md-12">
                        <?/*= \skeeks\cms\cmsWidgets\contentElements\ContentElementsCmsWidget::widget([
                            'namespace' => 'ContentElementsCmsWidget-search-result',
                            'contentElementClass'  => \skeeks\cms\shop\models\ShopCmsContentElement::class,
                            'viewFile' => '@app/views/widgets/ContentElementsCmsWidget/products-list',
                            'enabledCurrentTree' => \skeeks\cms\components\Cms::BOOL_N,
                            'pageSize' => 12,
                            'active' => "Y",
                            'dataProviderCallback' => function (\yii\data\ActiveDataProvider $dataProvider) {
                                \Yii::$app->cmsSearch->buildElementsQuery($dataProvider->query);
                                \Yii::$app->cmsSearch->logResult($dataProvider);
                                \Yii::$app->shop->filterBaseContentElementQuery($dataProvider->query);
                            },
                            'params' => [
                                'itemOptions' => [
                                    'class' => 'col-lg-3 col-sm-6 col-6 sx-product-card-wrapper'
                                ]
                            ]
                        ]) */?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>-->



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
    'query' => \skeeks\cms\shop\models\ShopCmsContentElement::find()->cmsSite()->active(),
]);
$dataProvider->query->cmsTree();

$dataProvider->pagination->pageSize = \Yii::$app->unifyShopTheme->productListPerPageSize;
$dataProvider->query->with('shopProduct');
$dataProvider->query->with('shopProduct.baseProductPrice');
$dataProvider->query->with('image');
$dataProvider->query->joinWith('shopProduct');

\Yii::$app->shop->filterBaseContentElementQuery($dataProvider->query);

\Yii::$app->cmsSearch->buildElementsQuery($dataProvider->query);
\Yii::$app->cmsSearch->logResult($dataProvider);

$dataProvider->query->groupBy([\skeeks\cms\models\CmsContentElement::tableName() . ".id"]);

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
    'title'         => \Yii::t('skeeks/shop/app', 'Результаты поиска'),
]);
?>
