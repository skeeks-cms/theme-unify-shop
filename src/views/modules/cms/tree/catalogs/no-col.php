<?php
/**
 * @link https://cms.skeeks.com/
 * @copyright Copyright (c) 2010 SkeekS
 * @license https://cms.skeeks.com/license/
 * @author Semenov Alexander <semenov@skeeks.com>
 */
/**
 * @var                                                                      $this yii\web\View
 * @var                                                                      $model \skeeks\cms\models\CmsTree
 * @var \yii\data\ActiveDataProvider                                         $dataProvider
 * @var \skeeks\cms\themes\unifyshop\filters\StandartShopFiltersWidget       $filtersWidget
 * @var \skeeks\cms\themes\unifyshop\cmsWidgets\catalog\ShopCatalogNoColPage $catalogSettings
 */

$catalogSettings = \skeeks\cms\themes\unifyshop\cmsWidgets\catalog\ShopCatalogNoColPage::beginWidget("catalog-no-col");
$catalogSettings::end();

if ($filtersWidget->getPriceHandler()) {
    $filtersWidget->getPriceHandler()->viewFile = \Yii::$app->mobileDetect->isMobile ? '@app/views/filters/price-filter' : '@app/views/filters/price-filter-inline';
}

if ($filtersWidget->getEavHandler()) {
    $filtersWidget->getEavHandler()->viewFile = \Yii::$app->mobileDetect->isMobile ? '@app/views/filters/eav-filters' : '@app/views/filters/eav-filters-inline';
}

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
        <div class="row">
            <div class="col-12 sx-catalog-wrapper" style="padding-bottom: 20px; padding-top: 20px;">
                <?= $this->render('@app/views/breadcrumbs', [
                    'model'      => @$model,
                    'title'      => @$title,
                    'isShowLast' => true,
                ]) ?>


                <?
                $savedFilters = [];
                if (@$model && $savedFilter) {
                    //todo: вынести в шаблон
                    $savedFilters = \skeeks\cms\models\CmsSavedFilter::find()
                        ->cmsSite()
                        //->joinWith("cmsContentProperty as cmsContentProperty")
                        ->joinWith("cmsTree as cmsTree")
                        ->with("cmsContentProperty")
                        ->with("cmsTree")
                        ->orderBy([
                            'cmsTree.level'    => SORT_ASC,
                            'cmsTree.priority' => SORT_ASC,
                        ])
                        //->groupBy(['cmsContentProperty.id'])
                        ->limit(200);

                    if ($savedFilter->value_content_element_id) {
                        $savedFilters->andWhere(['value_content_element_id' => $savedFilter->value_content_element_id]);
                    } elseif ($savedFilter->value_content_property_enum_id) {
                        $savedFilters->andWhere(['value_content_property_enum_id' => $savedFilter->value_content_property_enum_id]);
                    }

                    $savedFilters = $savedFilters->all();
                }

                ?>
                <?php if (count($savedFilters) > 1) : ?>
                    <div class="sx-saved-filters-list sx-saved-filters-list--before">
                        <div class="h5 sx-sub-title"><?php echo $savedFilter->propertyValueName; ?> встречается в разделах</div>

                        <?php
                        $savedFiltersData = [];
                        foreach ($savedFilters as $sf) {
                            /**
                             * @var $sf \skeeks\cms\models\CmsSavedFilter
                             */
                            $savedFiltersData[$sf->cms_content_property_id]['savedFilters'][$sf->id] = $sf;
                            $savedFiltersData[$sf->cms_content_property_id]['name'] = $sf->cmsContentProperty->name;
                        }
                        ?>
                        <? foreach ($savedFiltersData as $savedFiltersRow) : ?>
                            <!--<div class="h4 sx-sub-title"><?php /*echo \yii\helpers\ArrayHelper::getValue($savedFiltersRow, "name"); */ ?></div>-->
                            <ul class="list-unstyled list-inline" style="margin-bottom: 10px;">
                                <? foreach (\yii\helpers\ArrayHelper::getValue($savedFiltersRow, "savedFilters") as $sf) : ?>
                                    <li class="list-inline-item <?php echo (@$savedFilter && $sf->id == $savedFilter->id) ? "sx-active" : ""; ?>" style="margin-bottom: 5px;">
                                        <a class="<?php echo (@$savedFilter && $sf->id == $savedFilter->id) ? "" : "sx-main-text-color"; ?> btn 
                                            <?php echo (@$savedFilter && $sf->id == $savedFilter->id) ? "btn-primary" : "btn-default"; ?>
                                            "
                                           href="<?php echo $sf->url; ?>"
                                           data-toggle="tooltip"
                                           title="<?php echo $sf->seoName; ?>"><?php echo $sf->cmsTree->name; ?></a>
                                    </li>
                                <? endforeach; ?>
                            </ul>
                        <? endforeach; ?>
                    </div>
                <?php endif; ?>
                
                <?php if (@$description_short) : ?>
                    <div class="sx-content sx-description-short">
                        <?= @$description_short; ?>
                    </div>
                <?php endif; ?>

                <? if (\Yii::$app->cms->currentTree && \Yii::$app->unifyShopTheme->is_show_catalog_subtree_before_products && !$savedFilter) : ?>
                    <?php
                    $widget = \skeeks\cms\cmsWidgets\tree\TreeCmsWidget::beginWidget('sub-catalog');
                    $widget->descriptor->name = 'Подразделы каталога';
                    $widget->viewFile = '@app/views/widgets/TreeMenuCmsWidget/sub-catalog-small';
                    $widget->parent_tree_id = $model->id;
                    $widget->activeQuery->with('image');
                    $widget::end();
                    ?>
                <? endif; ?>


                <?php if (\Yii::$app->mobileDetect->isMobile) {
                    \skeeks\assets\unify\base\UnifyHsStickyBlockAsset::register($this);
                }; ?>
                <div class="row sx-mobile-filters-block js-sticky-block" id="sx-mobile-filters-block" data-has-sticky-header="true" data-start-point="#sx-mobile-filters-block" data-end-point=".sx-footer">
                    <div class="col-12 sx-mobile-filters-block--inner">
                        <div class="btn-group" style="width: 100%;">
                            <? if (\Yii::$app->unifyShopTheme->is_allow_filters) : ?>
                                <a href="#" class="sx-btn-filter btn sx-btn-white sx-icon-arrow-down--after">Фильтры</a>
                            <? endif; ?>
                            <!--<a href="#" class="sx-btn-sort btn sx-btn-white text-left sx-icon-arrow-down--after">Сортировка</a>-->
                            <a href="#" class="btn dropdown-toggle sx-btn-white sx-btn-sort-select sx-icon-arrow-down--after" data-toggle="dropdown" style="">
                                <?php echo $filtersWidget->getSortHandler()->valueAsText; ?>
                            </a>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                <? foreach ($filtersWidget->getSortHandler()->getSortOptions() as $code => $name) : ?>
                                    <a class="dropdown-item sx-select-sort sx-filter-action" href="#" data-filter="#s-value" data-filter-value="<?php echo $code; ?>"><?php echo $name; ?></a>
                                <? endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>


                <?php if ($catalogSettings->is_fix_filters_on_scroll) : ?>
                <!--Зафиксировать фильтры на верху страницы-->
                <div class="sx-filters-wrapper-inline js-sticky-block "
                     id="sx-filters-wrapper-inline"
                     data-has-sticky-header="true"
                     data-start-point="#sx-filters-wrapper-inline"
                     data-end-point=".sx-footer"
                >
                    <?php \skeeks\assets\unify\base\UnifyHsStickyBlockAsset::register($this); ?>
                    <?php else: ?>
                    <div class="sx-filters-wrapper-inline" id="sx-filters-wrapper-inline">
                        <?php endif; ?>

                        <?php

                        if (!\Yii::$app->mobileDetect->isMobile) {
                            $filtersWidget->getSortHandler()->viewFile = '@app/views/filters/sort-filter-inline';
                            $filtersWidget->getAvailabilityHandler()->viewFile = '@app/views/filters/availability-filter-inline';
                        }
                        echo $filtersWidget->run(); ?>
                    </div>
                    <div class="row sx-fast-filters">
                        <div class="col-12">
                        <span class="sx-filters-selected-wrapper">
                        </span>
                        </div>
                    </div>


                    <?php echo $this->render("@app/views/products/product-list", [
                        'dataProvider' => $dataProvider,
                    ]); ?>

                    <?php if (@$description) : ?>
                        <div class="sx-content sx-description-full" style="margin-top: 20px;">
                            <?= @$description; ?>
                        </div>
                    <?php endif; ?>

                    <?
                    $savedFilters = [];
                    if (@$model) {
                        //todo: вынести в шаблон
                        $savedFilters = $model->getCmsSavedFilters()
                            ->joinWith("cmsContentProperty as cmsContentProperty")
                            ->with("cmsContentProperty")
                            ->orderBy(['cmsContentProperty.priority' => SORT_ASC])
                            //->groupBy(['cmsContentProperty.id'])
                            ->limit(200)
                            ->all();
                    }

                    ?>
                    <?php if ($savedFilters) : ?>
                        <div class="sx-saved-filters-list sx-saved-filters-list--after" style="margin-top: 20px;">
                            <?php
                            $savedFiltersData = [];
                            foreach ($savedFilters as $sf) {
                                $savedFiltersData[$sf->cms_content_property_id]['savedFilters'][$sf->id] = $sf;
                                $savedFiltersData[$sf->cms_content_property_id]['name'] = $sf->cmsContentProperty->name;
                            }
                            ?>
                            <div class="h3 sx-title">Быстрый подбор товаров</div>
                            <? foreach ($savedFiltersData as $savedFiltersRow) : ?>
                                <div class="h5 sx-sub-title"><?php echo \yii\helpers\ArrayHelper::getValue($savedFiltersRow, "name"); ?></div>
                                <ul class="list-unstyled list-inline" style="margin-bottom: 10px;">
                                    <? foreach (\yii\helpers\ArrayHelper::getValue($savedFiltersRow, "savedFilters") as $sf) : ?>
                                        <li class="list-inline-item <?php echo (@$savedFilter && $sf->id == $savedFilter->id) ? "sx-active" : ""; ?>" style="margin-bottom: 5px;">
                                            <a class="<?php echo (@$savedFilter && $sf->id == $savedFilter->id) ? "" : "sx-main-text-color"; ?> btn 
                                            <?php echo (@$savedFilter && $sf->id == $savedFilter->id) ? "btn-primary" : "btn-default"; ?>
                                            "
                                               href="<?php echo $sf->url; ?>"
                                               data-toggle="tooltip"
                                               title="<?php echo $sf->seoName; ?>"><?php echo $sf->propertyValueName; ?></a>
                                        </li>
                                    <? endforeach; ?>
                                </ul>
                            <? endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
</section>

