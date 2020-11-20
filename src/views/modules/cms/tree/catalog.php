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
$catalogSettings = \skeeks\cms\themes\unifyshop\cmsWidgets\catalog\ShopCatalogPage::beginWidget("catalog");
$catalogSettings::end();

?>
<section class="">
    <div class="container sx-container">
        <? /* $pjax = \skeeks\cms\widgets\Pjax::begin(); */ ?>
        <div class="row">
            <div class="order-md-2 sx-content-col-main">
                <?= $this->render('@app/views/breadcrumbs', [
                    'model'      => $model,
                    'isShowLast' => true,
                ]) ?>

                <div class="row">
                    <div class="col-md-12 sx-filters-selected-wrapper">
                    </div>
                </div>

                <div class="sx-content">
                    <?= $model->description_full; ?>
                </div>

                <? if ($catalogSettings->is_show_subtree_before_products) : ?>
                    <?php
                    $widget = \skeeks\cms\cmsWidgets\tree\TreeCmsWidget::beginWidget('sub-catalog');
                    $widget->descriptor->name = 'Подразделы каталога';
                    $widget->viewFile = '@app/views/widgets/TreeMenuCmsWidget/sub-catalog-small';
                    $widget->parent_tree_id = $model->id;
                    $widget->activeQuery->with('image');
                    $widget::end();
                    ?>
                <? endif; ?>

                <div class="row">
                    <? if ($catalogSettings->is_allow_filters) : ?>
                        <div class="col-6"><a href="#" class="sx-btn-filter btn btn-block g-valign-middle text-left">Фильтры <i class="fa fa-angle-down pull-right g-pt-5" aria-hidden="true"></i></a></div>
                    <? endif; ?>
                    <div class="col-6 sx-sorting-block"><a href="#" class="sx-btn-sort btn btn-block g-valign-middle text-left">Сортировка <i class="fa fa-angle-down pull-right g-pt-5" aria-hidden="true"></i></a></div>
                </div>
                <?
                $isShowFilters = (bool)$catalogSettings->is_allow_filters;
                /**
                 * @var $model \skeeks\cms\models\Tree
                 */
                /*if ($maxLevelTree = $model->getDescendants()->limit(1)->orderBy(['level' => SORT_DESC])->one()) {
                    if (($maxLevelTree->level - $model->level) > 1) {
                        $isShowFilters = false;
                    }
                }*/
                if ($model->activeChildren) {
                    $isShowFilters = false;
                }

                $filtersWidget = \skeeks\cms\themes\unify\widgets\filters\FiltersWidget::begin();
                $availabilityFiltersHandler = new \skeeks\cms\shop\queryFilter\AvailabilityFiltersHandler();
                $availabilityFiltersHandler->value = (int)\Yii::$app->skeeks->site->shopSite->is_show_product_no_price;

                $sortFiltersHandler = new \skeeks\cms\shop\queryFilter\SortFiltersHandler();

                $availabilityFiltersHandler->viewFileVisible = '@app/views/filters/availability-filter';
                $sortFiltersHandler->viewFileVisible = '@app/views/filters/sort-filter';

                $filtersWidget
                    ->registerHandler($availabilityFiltersHandler)
                    ->registerHandler($sortFiltersHandler);


                $widgetElements = \skeeks\cms\cmsWidgets\contentElements\ContentElementsCmsWidget::beginWidget("shop-product-list", [
                    'pageSize'            => 15,
                    'active'              => "Y",
                    'contentElementClass' => \skeeks\cms\shop\models\ShopCmsContentElement::className(),
                ]);

                $widgetElements->viewFile = '@app/views/widgets/ContentElementsCmsWidget/products-list';
                $widgetElements->descriptor->name = 'Список товаров';
                $widgetElements->dataProvider->query->with('shopProduct');
                $widgetElements->dataProvider->query->with('shopProduct.baseProductPrice');
                $widgetElements->dataProvider->query->with('shopProduct.minProductPrice');
                $widgetElements->dataProvider->query->with('image');
                $widgetElements->dataProvider->query->joinWith('shopProduct');
                \Yii::$app->shop->filterBaseContentElementQuery($widgetElements->dataProvider->query);

                $query = $widgetElements->dataProvider->query;
                $baseQuery = clone $query;


                if ($isShowFilters) {
                    $eavFiltersHandler = new \skeeks\cms\shop\queryFilter\ShopEavQueryFilterHandler([
                        'baseQuery' => $baseQuery,
                    ]);

                    $eavFiltersHandler->openedPropertyIds = \Yii::$app->skeeks->site->shopSite->open_filter_property_ids;
                    $eavFiltersHandler->viewFile = '@app/views/filters/eav-filters';
                    $rpQuery = $eavFiltersHandler->getRPQuery();

                    if ($show_filter_property_ids = \Yii::$app->skeeks->site->shopSite->show_filter_property_ids) {
                        $rpQuery->andWhere([\skeeks\cms\models\CmsContentProperty::tableName().'.id' => $show_filter_property_ids]);
                    }

                    /*$rpQuery->andWhere([
                        'cmap.cms_content_id' => $model->tree_id,
                    ]);*/
                    /*$rpQuery->andWhere(
                        ['map.cms_tree_id' => $model->id]
                    );*/
                    $eavFiltersHandler->initRPByQuery($rpQuery);

                    $priceFiltersHandler = new \skeeks\cms\shop\queryFilter\PriceFiltersHandler([
                        'baseQuery' => $baseQuery,
                        'viewFile'  => '@app/views/filters/price-filter',
                    ]);

                    $filtersWidget
                        ->registerHandler($priceFiltersHandler);

                    $filtersWidget
                        ->registerHandler($eavFiltersHandler);
                }

                ?>



                <?
                $filtersWidget->loadFromRequest();
                $filtersWidget->applyToQuery($query);
                ?>


                <?= $this->render('@app/views/filters/sorts', [
                    'filtersWidget'              => $filtersWidget,
                    'sortFiltersHandler'         => $sortFiltersHandler,
                    'availabilityFiltersHandler' => $availabilityFiltersHandler,
                ]); ?>

                <? $widgetElements::end(); ?>

            </div>

            <div class="order-md-1 g-bg-secondary sx-content-col-left" style="">
                <?= $this->render('@app/views/modules/cms/tree/_catalog-left-col', [
                    'catalogSettings' => $catalogSettings,
                    'filtersWidget'   => $filtersWidget,
                    'isShowFilters'   => $isShowFilters,
                ]); ?>
            </div>
        </div>

        <? /* $pjax::end(); */ ?>
    </div>
</section>

