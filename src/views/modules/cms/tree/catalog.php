<?php
/**
 * @link https://cms.skeeks.com/
 * @copyright Copyright (c) 2010 SkeekS
 * @license https://cms.skeeks.com/license/
 * @author Semenov Alexander <semenov@skeeks.com>
 */
/* @var $this yii\web\View */
\skeeks\cms\themes\unifyshop\assets\UnifyShopCatalogAsset::register($this);
?>
<section class="g-mt-0 g-pb-0">
    <div class="container g-py-20">
        <div class="row">
            <div class="col-md-9 order-md-2">
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
                $filterWidget = \skeeks\cms\themes\unifyshop\widgets\filter\ProductFiterWidget::begin();
                $filterWidget->viewFile = '@app/views/filters/product-filter';

                $availabilityFiltersHandler = new \skeeks\cms\shop\queryFilter\AvailabilityFiltersHandler();
                $sortFiltersHandler = new \skeeks\cms\shop\queryFilter\SortFiltersHandler();

                $filterWidget
                    ->registerHandler($availabilityFiltersHandler)
                    ->registerHandler($sortFiltersHandler);

                $widgetElements = \skeeks\cms\cmsWidgets\contentElements\ContentElementsCmsWidget::beginWidget("shop-product-list", [
                    'viewFile'             => '@app/views/widgets/ContentElementsCmsWidget/products-list',
                    'contentElementClass'  => \skeeks\cms\shop\models\ShopCmsContentElement::className(),
                    'dataProviderCallback' => function (\yii\data\ActiveDataProvider $activeDataProvider)
                    use ($filterWidget) {
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

                $priceFiltersHandler = new \skeeks\cms\shop\queryFilter\PriceFiltersHandler([
                    'baseQuery' => $baseQuery,
                    'viewFile'  => '@app/views/filters/price-filter',
                ]);

                $filterWidget
                    ->registerHandler($priceFiltersHandler);

                ?>



                <?
                $filterWidget->loadFromRequest();
                $filterWidget->applyToQuery($query);
                ?>

                <?= $this->render('@app/views/filters/filters', [
                    'filterWidget'               => $filterWidget,
                    'sortFiltersHandler'         => $sortFiltersHandler,
                    'availabilityFiltersHandler' => $availabilityFiltersHandler,
                ]) ?>

                <? $widgetElements::end(); ?>

            </div>
            <div class="col-md-3 order-md-1  g-pt-0">
                <div class="g-mb-20">
                    <? $filterWidget::end(); ?>
                    <div id="stickyblock-start" class="g-bg-white g-pa-5 js-sticky-block" data-start-point="#stickyblock-start" data-end-point=".sx-footer">

                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

