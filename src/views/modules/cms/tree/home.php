<?php
/**
 * @link https://cms.skeeks.com/
 * @copyright Copyright (c) 2010 SkeekS
 * @license https://cms.skeeks.com/license/
 * @author Semenov Alexander <semenov@skeeks.com>
 */
/* @var $this yii\web\View */
?>
<?
$catalog = \skeeks\cms\models\CmsTree::find()->where([
    'dir' => 'catalog'
])->andWhere([
    '>', 'image_id', 0
])->one();
echo \skeeks\cms\cmsWidgets\treeMenu\TreeMenuCmsWidget::widget([
    'namespace'       => 'home-tree-slider',
    'enabledRunCache'                => "Y",
    'viewFile'        => '@app/views/widgets/TreeMenuCmsWidget/revolution-slider',
    'treePid'         => $catalog ? $catalog->id : null,
    //'enabledRunCache' => \skeeks\cms\components\Cms::BOOL_N,
]);
?>


<? if (\Yii::$app->shop->shopContents) : ?>
    <div class="container g-mt-40 g-mb-40">
        <?
        $widgetElements = \skeeks\cms\cmsWidgets\contentElements\ContentElementsCmsWidget::beginWidget("home-poupular-products", [
            'viewFile'             => '@app/views/widgets/ContentElementsCmsWidget/products-stick',
            'label'                => "Популярные товары",
            'enabledPaging'        => "N",
            'enabledRunCache'                => "Y",
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
            'enabledRunCache'                => "Y",
            'content_ids'          => \yii\helpers\ArrayHelper::map(\Yii::$app->shop->shopContents, 'id', 'id'),
            'limit'                => 15,
            'contentElementClass'  => \skeeks\cms\shop\models\ShopCmsContentElement::class,
            'dataProviderCallback' => function (\yii\data\ActiveDataProvider $activeDataProvider) //use ($filterWidget)
            {
                $activeDataProvider->query->with('shopProduct');
                $activeDataProvider->query->with('shopProduct.baseProductPrice');
                $activeDataProvider->query->with('shopProduct.minProductPrice');
                $activeDataProvider->query->with('image');
                if (!\Yii::$app->shop->is_show_product_no_price)   {
                    $activeDataProvider->query->joinWith('shopProduct.shopProductPrices as pricesFilter');
                    $activeDataProvider->query->andWhere(['>','`pricesFilter`.price',0]);
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
        <div class="col-lg-12 flex-lg-unordered g-mt-50--lg g-mb-60">
            <div class="mb-2">
                <div class="d-inline-block g-width-20 g-height-2 g-pos-rel g-top-minus-4 g-bg-primary mr-2"></div>
                <span class="g-color-gray-dark-v3 g-font-weight-600 g-font-size-12 text-uppercase">О компании</span>
            </div>

            <?=$model->description_full; ?>

        </div>
    </div>
</section>
<!-- End What People Say -->
<? endif; ?>
<!-- Blog News -->
<section class="container-fluid g-pt-10 g-pb-10">
    <?
    $widgetElements = \skeeks\cms\cmsWidgets\contentElements\ContentElementsCmsWidget::beginWidget("home-news", [
        'viewFile'             => '@app/views/widgets/ContentElementsCmsWidget/news-masonry-nopagination',
        'label'                => "Новости компании",
        'enabledRunCache'                => "Y",
        'content_ids'          => [1],
        'enabledCurrentTree'   => \skeeks\cms\components\Cms::BOOL_N,
        'enabledCurrentTreeChild' => skeeks\cms\components\Cms::BOOL_N,
        'enabledCurrentTreeChildAll' => skeeks\cms\components\Cms::BOOL_N
    ]);
    $widgetElements::end();
    ?>

</section>
<!-- End Blog News -->

