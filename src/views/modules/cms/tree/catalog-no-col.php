<?php
/**
 * @link https://cms.skeeks.com/
 * @copyright Copyright (c) 2010 SkeekS
 * @license https://cms.skeeks.com/license/
 * @author Semenov Alexander <semenov@skeeks.com>
 */
/**
 * @var $this yii\web\View
 * @var $model \skeeks\cms\models\CmsTree
 */
if (!\Yii::$app->mobileDetect->isMobile) {
    $this->registerCss(<<<CSS
.sx-filters-block-header {
    display: none;
}
CSS
    );
}
?>
<section class="">
    <div class="container sx-container">
        <? /* $pjax = \skeeks\cms\widgets\Pjax::begin(); */ ?>
        <div class="row">
            <div class="col-12 sx-catalog-wrapper" style="padding-bottom: 20px; padding-top: 20px;">
                <?= $this->render('@app/views/breadcrumbs', [
                    'model'      => $model,
                    'isShowLast' => true,
                ]) ?>
                <div class="sx-content">
                    <?= $model->description_full; ?>
                </div>

                <? if (\Yii::$app->unifyShopTheme->is_show_catalog_subtree_before_products) : ?>
                    <?php
                    $widget = \skeeks\cms\cmsWidgets\tree\TreeCmsWidget::beginWidget('sub-catalog');
                    $widget->descriptor->name = 'Подразделы каталога';
                    $widget->viewFile = '@app/views/widgets/TreeMenuCmsWidget/sub-catalog-small';
                    $widget->parent_tree_id = $model->id;
                    $widget->activeQuery->with('image');
                    $widget::end();
                    ?>
                <? endif; ?>

                <?
                $isShowFilters = (bool)\Yii::$app->unifyShopTheme->is_allow_filters;
                $filtersWidget = \skeeks\cms\themes\unifyshop\filters\StandartShopFiltersWidget::begin();

                $widgetElements = \skeeks\cms\cmsWidgets\contentElements\ContentElementsCmsWidget::beginWidget("shop-product-list", [
                    'pageSize'            => \Yii::$app->unifyShopTheme->productListPerPageSize,
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
                \Yii::$app->shop->filterByPriceContentElementQuery($widgetElements->dataProvider->query);

                $query = $widgetElements->dataProvider->query;
                $baseQuery = clone $query;

                if ($isShowFilters) {
                    $eavFiltersHandler = new \skeeks\cms\shop\queryFilter\ShopEavQueryFilterHandler([
                        'baseQuery' => $baseQuery,
                    ]);

                    $eavFiltersHandler->openedPropertyIds = \Yii::$app->skeeks->site->shopSite->open_filter_property_ids;
                    $eavFiltersHandler->viewFile = \Yii::$app->mobileDetect->isMobile ? '@app/views/filters/eav-filters' : '@app/views/filters/eav-filters-inline';
                    $rpQuery = $eavFiltersHandler->getRPQuery();

                    if ($show_filter_property_ids = \Yii::$app->skeeks->site->shopSite->show_filter_property_ids) {
                        $rpQuery->andWhere([\skeeks\cms\models\CmsContentProperty::tableName().'.id' => $show_filter_property_ids]);
                    }

                    if ($model->activeChildren) {
                        $rpQuery->andWhere([
                            'or',
                            ['map.cms_tree_id' => $model->id],
                            ['map.cms_tree_id' => null],
                        ]);
                    }

                    $eavFiltersHandler->initRPByQuery($rpQuery);

                    $priceFiltersHandler = new \skeeks\cms\shop\queryFilter\PriceFiltersHandler([
                        'baseQuery' => $baseQuery,
                        'viewFile'  => \Yii::$app->mobileDetect->isMobile ? '@app/views/filters/price-filter' : '@app/views/filters/price-filter-inline',
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

                <?php if (\Yii::$app->mobileDetect->isMobile) {
                    \skeeks\assets\unify\base\UnifyHsStickyBlockAsset::register($this);
                }; ?>
                <div class="row sx-mobile-filters-block js-sticky-block" id="sx-mobile-filters-block" data-has-sticky-header="true" data-start-point="#sx-mobile-filters-block" data-end-point=".sx-footer">
                    <div class="col-12 sx-mobile-filters-block--inner">
                        <div class="btn-group" style="width: 100%;">
                            <? if ($isShowFilters) : ?>
                                <a href="#" class="sx-btn-filter btn sx-btn-white sx-icon-arrow-down--after">Фильтры</a>
                            <? endif; ?>
                            <!--<a href="#" class="sx-btn-sort btn sx-btn-white text-left sx-icon-arrow-down--after">Сортировка</a>-->
                            <a href="#" class="btn dropdown-toggle sx-btn-white sx-btn-sort-select sx-icon-arrow-down--after" data-toggle="dropdown" style="">
                                <?php echo $filtersWidget->getSortHandler()->valueAsText; ?>
                            </a>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                <? foreach ($filtersWidget->getSortHandler()->getSortOptions() as $code => $name) : ?>
                                    <a class="dropdown-item sx-select-sort sx-filter-action" href="#" data-filter="productfilters-sort" data-filter-value="<?php echo $code; ?>"><?php echo $name; ?></a>
                                <? endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="sx-filters-wrapper-inline">
                    <?php
                    if (!\Yii::$app->mobileDetect->isMobile) {
                        $filtersWidget->getSortHandler()->viewFile = '@app/views/filters/sort-filter-inline';
                        $filtersWidget->getAvailabilityHandler()->viewFile = '@app/views/filters/availability-filter-inline';
                    }

                    $filtersWidget::end(); ?>
                </div>

                <div class="row sx-fast-filters">
                    <div class="col-12">
                        <span class="sx-filters-selected-wrapper">
                        </span>
                    </div>
                </div>

                <? $widgetElements::end(); ?>
            </div>

        </div>
        <? /* $pjax::end(); */ ?>
    </div>
</section>

