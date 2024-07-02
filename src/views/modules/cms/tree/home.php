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
        'viewFile'           => '@app/views/widgets/ContentElementsCmsWidget/stock-carousel',
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
    $widget->is_cache = false;
    $widget->viewFile = '@app/views/widgets/TreeCmsWidget/revolution-slider';
    $widget->is_has_image_only = true;
    $widget->activeQuery->with('image');
    $widget::end();

}


?>


<?php
$collectionsQuery = \skeeks\cms\shop\models\ShopCollection::find()->andWhere(['is not', 'cms_image_id', null])->orderBy(['created_at' => SORT_DESC])->limit(4);
if ($collectionsQuery->count()) :
?>

    <div id="sx-home-collection-1">
        <div class="container sx-container sx-popular-product" style="margin: 40px auto;">
    <?php echo $this->render("@app/views/collections/collection-list-no-page", [
        'label' => "Новые коллекции",
        'itemClasses' => "col-sm-6 col-lg-3",
        'dataProvider' => new \yii\data\ActiveDataProvider([
            'query'      => $collectionsQuery,
            'pagination' => [
                'pageSize' => 4,
            ],
        ]),
    ]); ?>
    </div>
    </div>
    <div id="sx-home-collection-2">
        <?
        $collectionsQuery = \skeeks\cms\shop\models\ShopCollection::find()->andWhere(['is not', 'cms_image_id', null])->orderBy(['priority' => SORT_DESC])->limit(4);
        ?>
        <div class="container sx-container sx-popular-product" style="margin: 40px auto;">
            <?php echo $this->render("@app/views/collections/collection-list-no-page", [
            'label' => "Популярные коллекции",
            'itemClasses' => "col-sm-6 col-lg-3",
            'dataProvider' => new \yii\data\ActiveDataProvider([
                'query'      => $collectionsQuery,
                'pagination' => [
                    'pageSize' => 4,
                ],
            ]),
        ]); ?>
    </div>
    </div>

<?php endif; ?>


<? if (\Yii::$app->shop->shopContents && \Yii::$app->mobileDetect->isDesktop) : ?>
    <div class="container sx-container sx-popular-product" style="margin: 40px auto;">
        <?
        \skeeks\cms\themes\unify\assets\components\UnifyThemeStickAsset::register($this);
        \skeeks\cms\themes\unifyshop\assets\components\ShopUnifyProductCardAsset::register($this);
        $widgetElements = \skeeks\cms\cmsWidgets\contentElements\ContentElementsCmsWidget::beginWidget("home-poupular-products", [
            'viewFile'             => '@app/views/widgets/ContentElementsCmsWidget/products-stick',
            'label'                => "Популярные товары",
            'enabledPaging'        => "N",
            'enabledRunCache'      => "Y",
            'enabledCurrentTree'   => "N",
            'orderBy'              => 'show_counter',
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
                //$activeDataProvider->query->orderBy(['show_counter' => SORT_DESC]);

                \Yii::$app->shop
                    ->filterBaseContentElementQuery($activeDataProvider->query)
                    ->filterByQuantityQuery($activeDataProvider->query);
            },
        ]);
        $widgetElements::end();
        ?>
    </div>
    <div class="container sx-container sx-new-product" style="margin: 40px auto;">
        <?
        $widgetElements = \skeeks\cms\cmsWidgets\contentElements\ContentElementsCmsWidget::beginWidget("home-new-products", [
            'viewFile'             => '@app/views/widgets/ContentElementsCmsWidget/products-stick',
            'label'                => "Новые поступления",
            'enabledPaging'        => "N",
            'enabledRunCache'      => "Y",
            'enabledCurrentTree'   => "N",
            'active'               => "Y",
            'orderBy'                    => "published_at",
            'order'                      => SORT_DESC,
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
                    ->filterByQuantityQuery($activeDataProvider->query);

                //$activeDataProvider->query->joinWith('shopProduct.baseProductPrice as basePrice');
                /*$activeDataProvider->query->orderBy(['published_at' => SORT_DESC]);*/
            },
        ]);
        $widgetElements::end();
        ?>
    </div>

<? endif; ?>



<section style="margin: 50px 0;">
    <!--<header class="text-center g-mb-60">
        <div class="h2">Бренды</div>
    </header>-->
    <div class="container sx-container">
        <?
        $contentClients = \skeeks\cms\models\CmsContent::find()->where(['code' => 'brand'])->one();

        ?>
        <? $widget = \skeeks\cms\cmsWidgets\contentElements\ContentElementsCmsWidget::begin([
            'namespace'                  => 'brands',
            'content_ids'                => [
                $contentClients ? $contentClients->id : "",
            ],
            'label'                      => "Товары по брендам",
            'limit'                      => 100,
            'orderBy'                    => "priority",
            'order'                      => SORT_ASC,
            'pageSize'                   => 100,
            'enabledCurrentTree'         => \skeeks\cms\components\Cms::BOOL_N,
            'enabledCurrentTreeChild'    => \skeeks\cms\components\Cms::BOOL_N,
            'enabledCurrentTreeChildAll' => \skeeks\cms\components\Cms::BOOL_N,
            'viewFile'                   => '@app/views/widgets/ContentElementsCmsWidget/brands',
        ]); ?>
        <?php
                $widget->dataProvider->query->joinWith("mainCmsContentElement as mainCmsContentElement");

        $widget->dataProvider->query->andWhere([
            'or',
            ['is not', \skeeks\cms\models\CmsContentElement::tableName() . '.image_id', null],
            ['is not', 'mainCmsContentElement.image_id', null],
        ]);
        ?>
        <?php $widget::end(); ?>
    </div>

</section>



<? if ($model->description_full) : ?>
    <!-- What People Say -->
    <section class="container sx-container" style="margin: 40px auto;">
        <div class="row justify-content-between">
            <div class="col-12">
                <?php if ($model->seo_h1) : ?>
                    <h1><?php echo $model->seo_h1; ?></h1>
                <?php endif; ?>

                <?= $model->description_full; ?>

            </div>
        </div>
    </section>
    <!-- End What People Say -->
<? endif; ?>



<!-- Blog News -->
<section class="container sx-container" style="padding-top: 10px; padding-bottom: 10px;">
    <?
    $widgetElements = \skeeks\cms\cmsWidgets\contentElements\ContentElementsCmsWidget::beginWidget("home-news-v2", [
        'viewFile'                   => '@app/views/widgets/ContentElementsCmsWidget/news-grid',
        'label'                      => "Новости компании",
        'enabledRunCache'            => "Y",
        'content_ids'                => [1],
        'limit'                      => 4,
        'pageSize'                   => 4,
        'enabledPaging'              => 'N',
        'enabledCurrentTree'         => \skeeks\cms\components\Cms::BOOL_N,
        'enabledCurrentTreeChild'    => skeeks\cms\components\Cms::BOOL_N,
        'enabledCurrentTreeChildAll' => skeeks\cms\components\Cms::BOOL_N,
    ]);
    $widgetElements::end();
    ?>

</section>
<!-- End Blog News -->

