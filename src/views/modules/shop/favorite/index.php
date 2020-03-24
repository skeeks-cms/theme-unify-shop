<?php
/**
 * @link https://cms.skeeks.com/
 * @copyright Copyright (c) 2010 SkeekS
 * @license https://cms.skeeks.com/license/
 * @author Semenov Alexander <semenov@skeeks.com>
 */
/* @var $this yii\web\View */
/* @var $model \skeeks\cms\models\CmsTree */
?>
<section class="g-mt-0 g-pb-0">
    <div class="container sx-container">
        <? /* $pjax = \skeeks\cms\widgets\Pjax::begin(); */ ?>
        <div class="row" style="background: #d3d3cd;">
            <div class="col-md-6">

            </div>
            <div class="col-md-6">

            </div>
        </div>
        <div class="row">
            <div class="order-md-2 g-py-20 g-px-15 sx-content-col-main">
                <?= $this->render('@app/views/breadcrumbs', [
                    'title' => "Избранные товары",
                    'isShowLast' => true,
                ]) ?>


                <div class="row">
                    <div class="col-6"><a href="#" class="sx-btn-filter btn btn-block g-valign-middle text-left">Фильтры <i class="fa fa-angle-down pull-right g-pt-5" aria-hidden="true"></i></a></div>
                    <div class="col-6 sx-sorting-block"><a href="#" class="sx-btn-sort btn btn-block g-valign-middle text-left">Сортировка <i class="fa fa-angle-down pull-right g-pt-5" aria-hidden="true"></i></a></div>
                </div>


                <?

                //Есть подразделы
                $isShowFilters = true;


                $filtersWidget = \skeeks\cms\themes\unify\widgets\filters\FiltersWidget::begin();
                $availabilityFiltersHandler = new \skeeks\cms\shop\queryFilter\AvailabilityFiltersHandler();
                $availabilityFiltersHandler->value = (int)\Yii::$app->shop->is_show_product_only_quantity;

                $sortFiltersHandler = new \skeeks\cms\shop\queryFilter\SortFiltersHandler();

                $availabilityFiltersHandler->viewFileVisible = '@app/views/filters/availability-filter';
                $sortFiltersHandler->viewFileVisible = '@app/views/filters/sort-filter';

                $filtersWidget
                    ->registerHandler($availabilityFiltersHandler)
                    ->registerHandler($sortFiltersHandler);


                $widgetElements = \skeeks\cms\cmsWidgets\contentElements\ContentElementsCmsWidget::beginWidget("shop-favorite-list", [
                    'viewFile'             => '@app/views/widgets/ContentElementsCmsWidget/products-list',
                    'pageSize'             => 60,
                    'contentElementClass'  => \skeeks\cms\shop\models\ShopCmsContentElement::className(),
                    'dataProviderCallback' => function (\yii\data\ActiveDataProvider $activeDataProvider)
                    use ($filtersWidget) {
                        //$activeDataProvider->query->with('relatedProperties');

                        $activeDataProvider->query->with('shopProduct');
                        $activeDataProvider->query->with('shopProduct.baseProductPrice');
                        $activeDataProvider->query->with('shopProduct.minProductPrice');
                        $activeDataProvider->query->with('image');

                        $activeDataProvider->query->joinWith('shopProduct');

                        \Yii::$app->shop->filterBaseContentElementQuery($activeDataProvider->query);
                        $activeDataProvider->query->joinWith("shopProduct.shopFavoriteProducts as fav");
                        $activeDataProvider->query->andWhere(['is not', "fav.id", null]);
                        $activeDataProvider->query->andWhere(["fav.shop_cart_id" => \Yii::$app->shop->cart->id]);

                    },

                ]);

                $query = $widgetElements->dataProvider->query;
                $baseQuery = clone $query;


                if ($isShowFilters) {
                    $eavFiltersHandler = new \skeeks\cms\eavqueryfilter\CmsEavQueryFilterHandler([
                        'baseQuery' => $baseQuery,
                    ]);
                    $eavFiltersHandler->openedPropertyIds = \Yii::$app->shop->open_filter_property_ids;
                    $eavFiltersHandler->viewFile = '@app/views/filters/eav-filters';
                    $rpQuery = $eavFiltersHandler->getRPQuery();

                    if (\Yii::$app->shop->show_filter_property_ids) {
                        $rpQuery->andWhere([\skeeks\cms\models\CmsContentProperty::tableName().'.id' => \Yii::$app->shop->show_filter_property_ids]);
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
            <div class="order-md-1 g-py-20 g-px-15 g-bg-secondary sx-content-col-left">

                <div class="g-mb-20">
                    <? if (!$isShowFilters) : ?>
                    <div style="display: none;">
                        <? endif; ?>
                        <? $filtersWidget::end(); ?>
                        <? if (!$isShowFilters) : ?>
                    </div>
                <? endif; ?>
                </div>

                <!--<div id="stickyblock-start" class="g-pa-5 js-sticky-block" data-start-point="#stickyblock-start" data-end-point=".sx-footer">

                </div>-->


            </div>
        </div>

        <? /* $pjax::end(); */ ?>
    </div>
</section>

