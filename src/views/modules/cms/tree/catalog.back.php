<?php
/**
 * @link https://cms.skeeks.com/
 * @copyright Copyright (c) 2010 SkeekS
 * @license https://cms.skeeks.com/license/
 * @author Semenov Alexander <semenov@skeeks.com>
 */
/* @var $this yii\web\View */
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
                    $filterWidget = new \skeeks\yii2\queryfilter\QueryFilterShortUrlWidget();
                    $filterWidget->viewFile = '@app/views/filters/product-filter';

                    $availabilityFiltersHandler = new \skeeks\cms\shop\queryFilter\AvailabilityFiltersHandler();
                    $sortFiltersHandler = new \skeeks\cms\shop\queryFilter\SortFiltersHandler();

                    $filterWidget
                        ->registerHandler($availabilityFiltersHandler)
                        ->registerHandler($sortFiltersHandler)
                    ;
                ?>



                <?/* $filters = new \skeeks\cms\themes\unifyshop\models\ProductFilters(); */?><!--
                --><?/* $filters->load(\Yii::$app->request->get()); */?>

                <?
                $widgetElements = \skeeks\cms\cmsWidgets\contentElements\ContentElementsCmsWidget::beginWidget("shop-product-list", [
                    'viewFile'             => '@app/views/widgets/ContentElementsCmsWidget/products-list',
                    'contentElementClass'  => \skeeks\cms\shop\models\ShopCmsContentElement::className(),
                    'dataProviderCallback' => function (\yii\data\ActiveDataProvider $activeDataProvider)
                    use ($filters) {
                        //$activeDataProvider->query->with('relatedProperties');

                        $activeDataProvider->query->with('shopProduct');
                        $activeDataProvider->query->with('shopProduct.baseProductPrice');
                        $activeDataProvider->query->with('shopProduct.minProductPrice');
                        $activeDataProvider->query->with('image');
                        /*
                        if ($shopFilters) {
                            $shopFilters->search($activeDataProvider);
                        }*/
                        $filters->search($activeDataProvider);

                        $activeDataProvider->query->joinWith('shopProduct');
                        $activeDataProvider->query->andWhere([
                            '!=',
                            'shopProduct.product_type',
                            \skeeks\cms\shop\models\ShopProduct::TYPE_OFFER,
                        ]);
                        //$activeDataProvider->query->joinWith('shopProduct.baseProductPrice as basePrice');
                        //$activeDataProvider->query->orderBy(['basePrice' => SORT_ASC]);
                    },

                ]); ?>

                <?= $this->render('@app/views/include/filters', ['filters' => $filters]) ?>

                <? $widgetElements::end(); ?>

            </div>
            <?= $this->render("@app/views/include/col-left"); ?>
        </div>
    </div>
</section>

