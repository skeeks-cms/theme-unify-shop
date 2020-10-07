<?php
/**
 * @link https://cms.skeeks.com/
 * @copyright Copyright (c) 2010 SkeekS
 * @license https://cms.skeeks.com/license/
 * @author Semenov Alexander <semenov@skeeks.com>
 */
/* @var $this yii\web\View */
?>
<? if ($this->theme->is_show_home_slider && !\Yii::$app->mobileDetect->isMobile) : ?>
    <?
    $content = \skeeks\cms\models\CmsContent::find()->where(['code' => 'slide'])->one();
    ?>
    <?= \skeeks\cms\cmsWidgets\contentElements\ContentElementsCmsWidget::widget([
        'namespace'          => 'home-slider',
        'enabledCurrentTree' => 'N',
        'orderBy'            => 'priority',
        'order'              => SORT_ASC,
        'enabledRunCache'    => \skeeks\cms\components\Cms::BOOL_N,
        'content_ids'        => [
            $content ? $content->id : "",
        ],
        'viewFile'           => '@app/views/widgets/ContentElementsCmsWidget/slider-revo-no-full',
    ]); ?>

<? endif; ?>


<?

if (\Yii::$app->mobileDetect->isMobile) {

    $catalogTree = \skeeks\cms\models\CmsTree::find()->cmsSite()->joinWith('treeType as treeType')->andWhere(['treeType.code' => 'catalog'])->orderBy(['level' => SORT_ASC])->limit(1)->one();
    $config = [];
    if ($catalogTree) {
        $config['parent_tree_id'] = $catalogTree->id;
    }
    $widget = \skeeks\cms\cmsWidgets\tree\TreeCmsWidget::beginWidget('mobile-home-catalog-small', $config);
    $widget->descriptor->name = 'Разделы каталога (мобильная версия)';
    $widget->viewFile = '@app/views/widgets/TreeMenuCmsWidget/sub-catalog-small';
    $widget->activeQuery->with('image');
    $widget::end();
    
} else {

    $catalogTree = \skeeks\cms\models\CmsTree::find()->cmsSite()->joinWith('treeType as treeType')->andWhere(['treeType.code' => 'catalog'])->orderBy(['level' => SORT_ASC])->limit(1)->one();
    $config = [];
    if ($catalogTree) {
        $config['parent_tree_id'] = $catalogTree->id;
    }
    $widget = \skeeks\cms\cmsWidgets\tree\TreeCmsWidget::beginWidget('home-tree-slider', $config);
    $widget->descriptor->name = 'Слайдер разделов';
    $widget->viewFile = '@app/views/widgets/TreeCmsWidget/revolution-slider';
    $widget->is_has_image_only = true;
    $widget->activeQuery->with('image');
    $widget::end();

}


?>


<? if (\Yii::$app->shop->shopContents && \Yii::$app->mobileDetect->isDesktop) : ?>
    <div class="container sx-container g-mt-40 g-mb-40">
        <?
        $widgetElements = \skeeks\cms\cmsWidgets\contentElements\ContentElementsCmsWidget::beginWidget("home-poupular-products", [
            'viewFile'             => '@app/views/widgets/ContentElementsCmsWidget/products-stick',
            'label'                => "Популярные товары",
            'enabledPaging'        => "N",
            'enabledRunCache'      => "N",
            'orderBy'           =>  'show_counter',
            'active'               => "Y",
            'content_ids'          => \yii\helpers\ArrayHelper::map(\Yii::$app->shop->shopContents, 'id', 'id'),
            'limit'                => 15,
            'contentElementClass'  => \skeeks\cms\shop\models\ShopCmsContentElement::class,
            'dataProviderCallback' => function (\yii\data\ActiveDataProvider $activeDataProvider) //use ($filterWidget)
            {
                $activeDataProvider->query->with('shopProduct');
                $activeDataProvider->query->with('shopProduct.baseProductPrice');
                $activeDataProvider->query->with('shopProduct.minProductPrice');
                $activeDataProvider->query->with('image');
                //$activeDataProvider->query->joinWith('shopProduct.baseProductPrice as basePrice');
                $activeDataProvider->query->orderBy(['show_counter' => SORT_DESC]);

                \Yii::$app->shop
                    ->filterBaseContentElementQuery($activeDataProvider->query)
                    ->filterByPriceContentElementQuery($activeDataProvider->query)
                ;
            },
        ]);
        $widgetElements::end();
        ?>
    </div>
    <div class="container sx-container g-mt-40 g-mb-40">
        <?
        $widgetElements = \skeeks\cms\cmsWidgets\contentElements\ContentElementsCmsWidget::beginWidget("home-new-products", [
            'viewFile'             => '@app/views/widgets/ContentElementsCmsWidget/products-stick',
            'label'                => "Новые поступления",
            'enabledPaging'        => "N",
            'enabledRunCache'      => "N",
            'active'               => "Y",
            'content_ids'          => \yii\helpers\ArrayHelper::map(\Yii::$app->shop->shopContents, 'id', 'id'),
            'limit'                => 15,
            'contentElementClass'  => \skeeks\cms\shop\models\ShopCmsContentElement::class,
            'dataProviderCallback' => function (\yii\data\ActiveDataProvider $activeDataProvider) //use ($filterWidget)
            {
                $activeDataProvider->query->with('shopProduct');
                $activeDataProvider->query->with('shopProduct.baseProductPrice');
                $activeDataProvider->query->with('shopProduct.minProductPrice');
                $activeDataProvider->query->with('image');

                \Yii::$app->shop
                    ->filterBaseContentElementQuery($activeDataProvider->query)
                    ->filterByPriceContentElementQuery($activeDataProvider->query)
                ;

                //$activeDataProvider->query->joinWith('shopProduct.baseProductPrice as basePrice');
                $activeDataProvider->query->orderBy(['published_at' => SORT_DESC]);
            },
        ]);
        $widgetElements::end();
        ?>
    </div>

<? endif; ?>
<? if ($model->description_full) : ?>
    <!-- What People Say -->
    <section class="container sx-container g-pt-10 g-pb-10">
        <div class="row justify-content-between">
            <div class="col-lg-12 flex-lg-unordered g-mt-20--lg g-mb-20">
                <div class="mb-2">
                    <div class="d-inline-block g-width-20 g-height-2 g-pos-rel g-top-minus-4 g-bg-primary mr-2"></div>
                    <span class="g-color-gray-dark-v3 g-font-weight-600 g-font-size-12 text-uppercase">О компании11</span>
                </div>

                <?= $model->description_full; ?>

            </div>
        </div>
    </section>
    <!-- End What People Say -->
<? endif; ?>
<!-- Blog News -->
<section class="container sx-container g-pt-10 g-pb-10">
    <?
    $widgetElements = \skeeks\cms\cmsWidgets\contentElements\ContentElementsCmsWidget::beginWidget("home-news", [
        'viewFile'                   => '@app/views/widgets/ContentElementsCmsWidget/news-masonry',
        'label'                      => "Новости компании",
        'enabledRunCache'            => "Y",
        'content_ids'                => [1],
        'enabledPaging'              => 'N',
        'enabledCurrentTree'         => \skeeks\cms\components\Cms::BOOL_N,
        'enabledCurrentTreeChild'    => skeeks\cms\components\Cms::BOOL_N,
        'enabledCurrentTreeChildAll' => skeeks\cms\components\Cms::BOOL_N,
    ]);
    $widgetElements::end();
    ?>

</section>
<!-- End Blog News -->

