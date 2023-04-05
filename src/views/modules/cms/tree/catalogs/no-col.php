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
 * @var array                                                                $agregateCategoryData
 */
$totalOffers = (int)\yii\helpers\ArrayHelper::getValue($agregateCategoryData, 'offerCount', 0);
$catalogSettings = \skeeks\cms\themes\unifyshop\cmsWidgets\catalog\ShopCatalogNoColPage::beginWidget("catalog-no-col");
$catalogSettings::end();

if ($filtersWidget->getPriceHandler()) {
    $filtersWidget->getPriceHandler()->viewFile = \Yii::$app->mobileDetect->isMobile ? '@app/views/filters/price-filter' : '@app/views/filters/price-filter-inline';
}

if ($filtersWidget->getEavHandler()) {
    $filtersWidget->getEavHandler()->viewFile = \Yii::$app->mobileDetect->isMobile ? '@app/views/filters/eav-filters' : '@app/views/filters/eav-filters-inline';
}

$priceFilter = $filtersWidget->getPriceHandler();
$eavFilter = $filtersWidget->getEavHandler();
$appliedValues = [];
if ($eavFilter) {
    $appliedValues = $eavFilter->getApplied();
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
                    'isShowH1'   => false,
                    'isShowLast' => true,
                ]) ?>
                <div class="sx-catalog-h1-wrapper" style="display: flex; margin-bottom: 10px;">
                    <div><h1 class="sx-breadcrumbs-h1 sx-catalog-h1" style="margin-bottom: 0px;"><?php echo $model->seoName; ?></h1></div>
                    <div class="sx-catalog-total-offers" style="color: #979797;
    margin-top: auto;
    margin-left: 12px;
    font-size: 15px;">(<?php echo \Yii::t('app', '{n, plural, =0{нет товаров} =1{# товар} one{# товар} few{# товара} many{# товаров} other{# товаров}}', ['n' => $totalOffers],
                            'ru_RU'); ?>)
                    </div>
                </div>


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
                        <div class="h5 sx-sub-title">Другие товары с опцией «<?php echo $savedFilter->propertyValueName; ?>»:</div>

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
                                           title="<?php echo $sf->seoName; ?>">


                                            <?php if ($sf->image) : ?>
                                                <div class="sx-img-wrapper">
                                                    <img src="<?= \skeeks\cms\helpers\Image::getSrc(\Yii::$app->imaging->thumbnailUrlOnRequest($sf->image ? $sf->image->src : null,
                                                        new \skeeks\cms\components\imaging\filters\Thumbnail([
                                                            'w' => 50,
                                                            'h' => 50,
                                                            'm' => \Imagine\Image\ManipulatorInterface::THUMBNAIL_INSET,
                                                        ]), $sf->cmsTree->code
                                                    )); ?>
                                                    " alt="<?php echo $sf->seoName; ?>"/>
                                                </div>
                                            <?php endif; ?>

                                            <div class="my-auto sx-info-wrapper">
                                                <div class="sx-title"><?php echo $sf->cmsTree->name; ?></div>
                                                <div><?php echo $sf->propertyValueName; ?></div>
                                            </div>
                                        </a>
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

                <? if (\Yii::$app->cms->currentTree && \Yii::$app->view->theme->is_show_catalog_subtree_before_products && !$savedFilter) : ?>
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
                            <? if (\Yii::$app->view->theme->is_allow_filters) : ?>
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

                        <?
                        \skeeks\cms\themes\unify\widgets\filters\assets\FiltersWidgetAsset::register($this);
                        $pjax = \skeeks\cms\widgets\PjaxLazyLoad::begin(); ?>
                        <?php if ($pjax->isPjax) : ?>
                            <?php
                            if (!\Yii::$app->mobileDetect->isMobile) {
                                $filtersWidget->getSortHandler()->viewFile = '@app/views/filters/sort-filter-inline';
                                $filtersWidget->getAvailabilityHandler()->viewFile = '@app/views/filters/availability-filter-inline';
                            }
    
                            echo $filtersWidget->run();
                            ?>
                        <?php else : ?>
                            Загрузка фильтров...
                        <?php endif; ?>
                        <? $pjax::end(); ?>
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
                            ->with("cmsTree")
                            ->with("cmsContentProperty")
                            ->with("valueContentElement")
                            ->with("valueContentPropertyEnum")
                            ->orderBy(['cmsContentProperty.priority' => SORT_ASC])
                            //->groupBy(['cmsContentProperty.id'])
                            ->limit(200)
                            ->all();
                    }

                    ?>
                    <?php if ($savedFilters) : ?>

                    <?php
/**
 * @link https://cms.skeeks.com/
 * @copyright Copyright (c) 2010 SkeekS
 * @license https://cms.skeeks.com/license/
 * @author Semenov Alexander <semenov@skeeks.com>
 */
/* @var $this yii\web\View */
$this->registerCss(<<<CSS
.sx-small::before {
    content: " ";
    background: linear-gradient(to bottom, #403d3d00, #ffffffd4);
    height: 73px;
    width: 100%;
    position: absolute;
    bottom: 0;
    z-index: 11;
}

.sx-small {
    max-height: 145px;
    position: relative;
    overflow: hidden;
}
.sx-more .btn {
    /*padding: 20px;
    font-size: 20px;*/
    margin-top: 0.5rem;
}

CSS
);

$this->registerJs(<<<JS
if ($(".sx-spoiler").height() > 200) {
    $(".sx-spoiler").addClass("sx-small");
    $(".sx-more").show();
}

$(".sx-more .btn").on("click", function() {
     $(".sx-spoiler").removeClass("sx-small");
     $(".sx-more").hide();
     return false;
});
JS
);
?>
                    
                        <div class="sx-saved-filters-list sx-saved-filters-list--after sx-spoiler" style="margin-top: 1.5rem;">
                            <?php
                            $savedFiltersData = [];
                            foreach ($savedFilters as $sf) {
                                $savedFiltersData[$sf->cms_content_property_id]['savedFilters'][$sf->id] = $sf;
                                $savedFiltersData[$sf->cms_content_property_id]['name'] = $sf->cmsContentProperty->name;
                            }
                            ?>
                            <div class="h3 sx-title">Быстрый подбор товаров из раздела «<?php echo $savedFilter ? $savedFilter->getCmsTree()->one()->name : $model->name; ?>»</div>
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
                        <div class="sx-more">
                            <button class="btn btn-default btn-block">
                                Показать еще
                            </button>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
</section>

