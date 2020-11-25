<?php
/**
 * @link https://cms.skeeks.com/
 * @copyright Copyright (c) 2010 SkeekS
 * @license https://cms.skeeks.com/license/
 * @author Semenov Alexander <semenov@skeeks.com>
 */
/* @var $model \skeeks\cms\shop\models\ShopCmsContentElement */
/* @var $shopOfferChooseHelper \skeeks\cms\shop\helpers\ShopOfferChooseHelper */
/* @var $shopProduct \skeeks\cms\shop\models\ShopProduct */
/* @var $priceHelper \skeeks\cms\shop\helpers\ProductPriceHelper */
/* @var $this yii\web\View */


?>

<?
$q = \skeeks\cms\shop\models\ShopCmsContentElement::find();
$q->joinWith('shopProduct as sp');
$q->joinWith('cmsTree as cmsTree');
$q->andWhere([
    'in',
    'sp.product_type',
    [
        \skeeks\cms\shop\models\ShopProduct::TYPE_SIMPLE,
        \skeeks\cms\shop\models\ShopProduct::TYPE_OFFERS,
    ],
]);
$q
    ->joinWith("shopProduct.shopProductRelations1 as shopProductRelations1")
    ->joinWith("shopProduct.shopProductRelations2 as shopProductRelations2")
    ->andWhere([
        '!=',
        'sp.id',
        $model->id,
    ])
    ->andWhere([
        'or',
        ["shopProductRelations1.shop_product1_id" => $model->id],
        ["shopProductRelations1.shop_product2_id" => $model->id],
        ["shopProductRelations2.shop_product1_id" => $model->id],
        ["shopProductRelations2.shop_product2_id" => $model->id],
    ]);
$q->groupBy(['cmsTree.id']);


?>


<? if (\Yii::$app->shop->shopContents) : ?>

    <?
    $treeIds = [];
    if ($model->cmsTree && $model->cmsTree->parent) {
        $treeIds = \yii\helpers\ArrayHelper::map($model->cmsTree->parent->children, 'id', 'id');
    }
    $widgetElements = \skeeks\cms\cmsWidgets\contentElements\ContentElementsCmsWidget::beginWidget("product-similar-products", [
        'viewFile'             => '@app/views/widgets/ContentElementsCmsWidget/products-stick',
        'label'                => "Рекомендуем также",
        'enabledPaging'        => "N",
        'content_ids'          => \yii\helpers\ArrayHelper::map(\Yii::$app->shop->shopContents, 'id', 'id'),
        'tree_ids'             => $treeIds,
        'limit'                => 15,
        'contentElementClass'  => \skeeks\cms\shop\models\ShopCmsContentElement::class,
        'dataProviderCallback' => function (\yii\data\ActiveDataProvider $activeDataProvider) use ($model) {
            //$activeDataProvider->query->with('shopProduct');
            //$activeDataProvider->query->with('shopProduct.baseProductPrice');
            //$activeDataProvider->query->with('shopProduct.minProductPrice');
            $activeDataProvider->query->with('image');
            //$activeDataProvider->query->joinWith('shopProduct.baseProductPrice as basePrice');
            //$activeDataProvider->query->orderBy(['show_counter' => SORT_DESC]);

            $activeDataProvider->query->andWhere(['!=', \skeeks\cms\models\CmsContentElement::tableName().".id", $model->id]);

            /*$activeDataProvider->query->andWhere([
                '!=',
                'shopProduct.product_type',
                \skeeks\cms\shop\models\ShopProduct::TYPE_OFFER,
            ]);*/

            \Yii::$app->shop->filterBaseContentElementQuery($activeDataProvider->query);
            \Yii::$app->shop->filterByPriceContentElementQuery($activeDataProvider->query);

        },
    ]);

    ?>

    <? if ($widgetElements->dataProvider->query->count()) : ?>
        <section class="sx-products-slider-section">
            <div class="container sx-container">
                <? $widgetElements::end(); ?>
            </div>
        </section>
    <? endif; ?>


    <?php if (\Yii::$app->shop->shopUser) : ?>

        <?
        $widgetElements2 = \skeeks\cms\cmsWidgets\contentElements\ContentElementsCmsWidget::beginWidget("product-viewed-products", [
            'viewFile'            => '@app/views/widgets/ContentElementsCmsWidget/products-stick',
            'label'               => "Просмотренные товары",
            'enabledPaging'       => "N",
            'content_ids'         => \yii\helpers\ArrayHelper::map(\Yii::$app->shop->shopContents, 'id', 'id'),
            //'tree_ids'             => $treeIds,
            'enabledSearchParams' => "N",
            'enabledCurrentTree'  => "N",
            'limit'               => 15,
            'contentElementClass' => \skeeks\cms\shop\models\ShopCmsContentElement::class,
            'activeQueryCallback' => function (\yii\db\ActiveQuery $query) use ($model) {
                $query->andWhere(['!=', \skeeks\cms\models\CmsContentElement::tableName().".id", $model->id]);
                $query->innerJoin('shop_product', '`shop_product`.`id` = `cms_content_element`.`id`');
                $query->innerJoin('shop_viewed_product', '`shop_viewed_product`.`shop_product_id` = `shop_product`.`id`');
                $query->andWhere(['shop_user_id' => \Yii::$app->shop->shopUser->id]);
                //$query->orderBy(['shop_viewed_product.created_at' => SORT_DESC]);

                \Yii::$app->shop->filterBaseContentElementQuery($query);
            },
        ]);
        ?>

        <? if ($widgetElements2->dataProvider->query->count()) : ?>
            <section class="sx-products-slider-section">
                <div class="container sx-container">
                    <? $widgetElements2::end(); ?>
                </div>
            </section>
        <? endif; ?>
    <? endif; ?>

<? endif; ?>