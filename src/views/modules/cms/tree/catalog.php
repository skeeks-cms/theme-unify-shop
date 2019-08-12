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
    <div class="container">
        <?/* $pjax = \skeeks\cms\widgets\Pjax::begin(); */?>
        <div class="row">
            <div class="col-md-9 order-md-2 g-py-20">
                <?= $this->render('@app/views/breadcrumbs', [
                    'model' => $model,
                ]) ?>
                <div class="g-color-gray-dark-v1 g-font-size-16 sx-content">
                    <?= $model->description_full; ?>
                </div>

                <? $widget = \skeeks\cms\cmsWidgets\treeMenu\TreeMenuCmsWidget::begin([
                    'namespace'       => 'sub-catalog-small',
                    'viewFile'        => '@app/views/widgets/TreeMenuCmsWidget/sub-catalog-small',
                    'treePid'         => $model->id,
                    'enabledRunCache' => \skeeks\cms\components\Cms::BOOL_N,
                ]); ?>
                <?
                $widget->activeQuery->with('image');
                ?>
                <? \skeeks\cms\cmsWidgets\treeMenu\TreeMenuCmsWidget::end(); ?>




                <?
                //$filterWidget = \skeeks\cms\themes\unifyshop\widgets\filter\ProductFiterWidget::begin();
                $filtersWidget = \skeeks\cms\themes\unify\widgets\filters\FiltersWidget::begin();
               /* \Yii::$app->seo->canurl->ADDimportant_pname($filterWidget->filtersParamName);
                $filterWidget->viewFile = '@app/views/filters/product-filter';*/

                $availabilityFiltersHandler = new \skeeks\cms\shop\queryFilter\AvailabilityFiltersHandler();
                $sortFiltersHandler = new \skeeks\cms\shop\queryFilter\SortFiltersHandler();

                $availabilityFiltersHandler->viewFileVisible = '@app/views/filters/availability-filter';
                $sortFiltersHandler->viewFileVisible = '@app/views/filters/sort-filter';

                $filtersWidget
                    ->registerHandler($availabilityFiltersHandler)
                    ->registerHandler($sortFiltersHandler);


                $widgetElements = \skeeks\cms\cmsWidgets\contentElements\ContentElementsCmsWidget::beginWidget("shop-product-list", [
                    'viewFile'             => '@app/views/widgets/ContentElementsCmsWidget/products-list',
                    'pageSize'             => 15,
                    'contentElementClass'  => \skeeks\cms\shop\models\ShopCmsContentElement::className(),
                    'dataProviderCallback' => function (\yii\data\ActiveDataProvider $activeDataProvider)
                    use ($filtersWidget) {
                        //$activeDataProvider->query->with('relatedProperties');

                        $activeDataProvider->query->with('shopProduct');
                        $activeDataProvider->query->with('shopProduct.baseProductPrice');
                        $activeDataProvider->query->with('shopProduct.minProductPrice');
                        $activeDataProvider->query->with('image');

                        //$activeDataProvider->query->joinWith('shopProduct.baseProductPrice as basePrice');
                        //$activeDataProvider->query->orderBy(['basePrice' => SORT_ASC]);
                    },

                ]);


                $query = $widgetElements->dataProvider->query;
                $baseQuery = clone $query;





                $eavFiltersHandler = new \skeeks\cms\eavqueryfilter\CmsEavQueryFilterHandler([
                    'baseQuery' => $baseQuery
                ]);
                $eavFiltersHandler->viewFile = '@app/views/filters/eav-filters';
                $rpQuery = $eavFiltersHandler->getRPQuery();
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
                ?>



                <?
                $filtersWidget->loadFromRequest();
                $filtersWidget->applyToQuery($query);
                ?>


                <?= $this->render('@app/views/filters/sorts', [
                    'filtersWidget'               => $filtersWidget,
                    'sortFiltersHandler'         => $sortFiltersHandler,
                    'availabilityFiltersHandler' => $availabilityFiltersHandler,
                ]); ?>

                <? $widgetElements::end(); ?>

            </div>
            <div class="col-md-3 order-md-1 g-py-20 g-bg-secondary">
                <div class="g-mb-20">
                    <? $filtersWidget::end(); ?>
                    <div id="stickyblock-start" class="g-pa-5 js-sticky-block" data-start-point="#stickyblock-start" data-end-point=".sx-footer">

                    </div>
                </div>
            </div>
        </div>

        <?/* $pjax::end(); */?>
    </div>
</section>

