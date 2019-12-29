<?php
/**
 * @link https://cms.skeeks.com/
 * @copyright Copyright (c) 2010 SkeekS
 * @license https://cms.skeeks.com/license/
 * @author Semenov Alexander <semenov@skeeks.com>
 */
/* @var $this yii\web\View */
?>
<? if ($this->theme->is_show_home_slider) : ?>
<?
$content = \skeeks\cms\models\CmsContent::find()->where(['code' => 'slide'])->one();
?>
<?= \skeeks\cms\cmsWidgets\contentElements\ContentElementsCmsWidget::widget([
    'namespace'          => 'home-slider',
    'enabledCurrentTree' => 'N',
    'orderBy' => 'priority',
    'order' => SORT_ASC,
    'enabledRunCache'    => \skeeks\cms\components\Cms::BOOL_N,
    'content_ids'        => [
        $content ? $content->id : "",
    ],
    'viewFile'           => '@app/views/widgets/ContentElementsCmsWidget/slider-revo-no-full',
]); ?>

<? endif; ?>


<?


$catalog = \skeeks\cms\models\CmsTree::find()->where([
    'dir' => 'catalog',
])->andWhere([
    '>',
    'image_id',
    0,
])->one();
if (\Yii::$app->mobileDetect->isMobile) {

    $widget = \skeeks\cms\cmsWidgets\treeMenu\TreeMenuCmsWidget::begin([
        'namespace'       => 'mobile-home-catalog-small',
        'limit'           => 30,
        'viewFile'        => '@app/views/widgets/TreeMenuCmsWidget/sub-catalog-small',
        'treeParentCode'  => "catalog",
        'enabledRunCache' => \skeeks\cms\components\Cms::BOOL_N,
    ]);
    $widget->activeQuery->with('image');
    \skeeks\cms\cmsWidgets\treeMenu\TreeMenuCmsWidget::end();
} else {

    echo \skeeks\cms\cmsWidgets\treeMenu\TreeMenuCmsWidget::widget([
        'namespace'       => 'home-tree-slider',
        'enabledRunCache' => "N",
        'limit'           => 30,
        'viewFile'        => '@app/views/widgets/TreeMenuCmsWidget/revolution-slider',
        'treeParentCode'  => "catalog",
        //'enabledRunCache' => \skeeks\cms\components\Cms::BOOL_N,
    ]);
}


?>


<? if (\Yii::$app->shop->shopContents && \Yii::$app->mobileDetect->isDesktop) : ?>
    <div class="container g-mt-40 g-mb-40">
        <?
        $widgetElements = \skeeks\cms\cmsWidgets\contentElements\ContentElementsCmsWidget::beginWidget("home-poupular-products", [
            'viewFile'             => '@app/views/widgets/ContentElementsCmsWidget/products-stick',
            'label'                => "Популярные товары",
            'enabledPaging'        => "N",
            'enabledRunCache'      => "Y",
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
            },
        ]);
        $widgetElements::end();
        ?>
    </div>
    <div class="container g-mt-40 g-mb-40">
        <?
        $widgetElements = \skeeks\cms\cmsWidgets\contentElements\ContentElementsCmsWidget::beginWidget("home-new-products", [
            'viewFile'             => '@app/views/widgets/ContentElementsCmsWidget/products-stick',
            'label'                => "Новые поступления",
            'enabledPaging'        => "N",
            'enabledRunCache'      => "Y",
            'content_ids'          => \yii\helpers\ArrayHelper::map(\Yii::$app->shop->shopContents, 'id', 'id'),
            'limit'                => 15,
            'contentElementClass'  => \skeeks\cms\shop\models\ShopCmsContentElement::class,
            'dataProviderCallback' => function (\yii\data\ActiveDataProvider $activeDataProvider) //use ($filterWidget)
            {
                $activeDataProvider->query->with('shopProduct');
                $activeDataProvider->query->with('shopProduct.baseProductPrice');
                $activeDataProvider->query->with('shopProduct.minProductPrice');
                $activeDataProvider->query->with('image');
                if (!\Yii::$app->shop->is_show_product_no_price) {
                    $activeDataProvider->query->joinWith('shopProduct.shopProductPrices as pricesFilter');
                    $activeDataProvider->query->andWhere(['>', '`pricesFilter`.price', 0]);
                }

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
    <section class="container g-pt-10 g-pb-10">
        <div class="row justify-content-between">
            <div class="col-lg-12 flex-lg-unordered g-mt-20--lg g-mb-20">
                <div class="mb-2">
                    <div class="d-inline-block g-width-20 g-height-2 g-pos-rel g-top-minus-4 g-bg-primary mr-2"></div>
                    <span class="g-color-gray-dark-v3 g-font-weight-600 g-font-size-12 text-uppercase">О компании</span>
                </div>

                <?= $model->description_full; ?>

            </div>
        </div>
    </section>
    <!-- End What People Say -->
<? endif; ?>
<!-- Blog News -->
<section class="container g-pt-10 g-pb-10">
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

