<?php
/**
 * @link https://cms.skeeks.com/
 * @copyright Copyright (c) 2010 SkeekS
 * @license https://cms.skeeks.com/license/
 * @author Semenov Alexander <semenov@skeeks.com>
 */
/* @var $this yii\web\View */
/* @var $model \skeeks\cms\shop\models\ShopCmsContentElement */
skeeks\assets\unify\base\UnifyHsRatingAsset::register($this);
$this->registerJs(<<<JS
$.HSCore.components.HSRating.init($('.js-rating-show'), {
  spacing: 2
});
JS
);

$shopProduct = $model->shopProduct;
$priceHelper = \Yii::$app->shop->cart->getProductPriceHelper($model);

//Если этот товар привязан к главному
$infoModel = $model;
if ($shopProduct->main_pid) {
    if ($shopProduct->shopMainProduct->isOfferProduct) {
        $element = $shopProduct->shopMainProduct->cmsContentElement;
        $infoModel = $element->parentContentElement;
        $infoModel->name = $element->name;
    } else {
        $infoModel = $shopProduct->shopMainProduct->cmsContentElement;
    }
}


$shopOfferChooseHelper = null;
if ($shopProduct->isOffersProduct) {
    $shopOfferChooseHelper = new \skeeks\cms\shop\helpers\ShopOfferChooseHelper([
        'shopProduct' => $shopProduct,
    ]);
}
$singlPage = \skeeks\cms\themes\unifyshop\cmsWidgets\product\ShopProductSinglPage::beginWidget('product-page');
$singlPage::end();
?>
<section class="sx-product-card-wrapper g-mt-0 g-pb-0 to-cart-fly-wrapper" itemscope itemtype="http://schema.org/Product">
    <meta itemprop="name" content="<?= \yii\helpers\Html::encode($infoModel->name); ?><?= $priceHelper->basePrice->money; ?>"/>
    <link itemprop="url" href="<?= $model->absoluteUrl; ?>"/>
    <meta itemprop="description" content="<?= $infoModel->description_short ? $infoModel->description_short : '-'; ?>"/>
    <meta itemprop="sku" content="<?= $model->id; ?>"/>
    
    <? if ($infoModel->image) : ?>
        <link itemprop="image" href="<?= $infoModel->image->absoluteSrc; ?>">
    <? endif; ?>

    <div class="container sx-container g-py-20">
        <div class="row">
            <div class="col-md-12">
                <?= $this->render('@app/views/breadcrumbs', [
                    'model' => $infoModel,
                    'isShowH1' => $singlPage->is_show_title_in_breadcrumbs
                    /*'isShowLast' => true,
                    'isShowH1'   => false,*/
                ]); ?>
            </div>
        </div>

        <? $pjax = \skeeks\cms\widgets\Pjax::begin(); ?>
        <div class="row">
            <div class="col-md-<?= $singlPage->width_col_images; ?>">
                <div class="sx-product-images g-ml-40 g-mr-40">
                    <?= $this->render("_product-images", [
                        'model'                 => $infoModel,
                        'shopOfferChooseHelper' => $shopOfferChooseHelper,

                    ]); ?>
                </div>
            </div>

            <div class="col-md-<?= $singlPage->width_col_short_info; ?> sx-col-product-info">
                <div class="product-info ss-product-info">
                    <? if ($singlPage->is_show_title_in_short_description) : ?>
                        <h1 class="h4 g-font-weight-600"><?= $model->seoName; ?></h1>
                    <? endif; ?>
                    <div class="product-info-header">


                        <?= $this->render("@app/views/modules/cms/content-element/_product-right-top-info", [
                            'model'                 => $model,
                            'shopProduct'           => $shopProduct,
                            'priceHelper'           => $priceHelper,
                            'shopOfferChooseHelper' => $shopOfferChooseHelper,
                        ]); ?>

                        <?= $this->render("@app/views/modules/cms/content-element/_product-price", [
                            'model'                 => $model,
                            'shopProduct'           => $shopProduct,
                            'priceHelper'           => $priceHelper,
                            'shopOfferChooseHelper' => $shopOfferChooseHelper,
                        ]); ?>


                        <? if ($infoModel->description_short) : ?>
                            <div class="sx-description-short">
                                <?= $infoModel->description_short; ?>
                                <p>
                                    <a href="#sx-description" class="sx-scroll-to g-font-size-13 sx-dashed g-brd-primary--hover g-color-primary--hover">
                                        Подробнее
                                    </a>
                                </p>
                            </div>
                        <? endif; ?>

                        <?= $this->render("@app/views/modules/cms/content-element/_product-right-bottom-info", [
                            'model'                 => $infoModel,
                            'shopProduct'           => $shopProduct,
                            'priceHelper'           => $priceHelper,
                            'shopOfferChooseHelper' => $shopOfferChooseHelper,
                        ]); ?>


                    </div>


                </div>
            </div>
        </div>
        <? $pjax::end(); ?>

    </div>


    <div class="container">

        <div class="row">
            <div class="col-md-12">
                <h2>Характеристики</h2>
                <?

                $widget = \skeeks\cms\rpViewWidget\RpViewWidget::beginWidget('product-properties', [
                    'model'                   => $infoModel,
                    'visible_properties'      => @$visible_items,
                    'visible_only_has_values' => true,
                    'viewFile'                => '@app/views/widgets/RpWidget/default',
                ]); ?>
                <? /* $widget->viewFile = '@app/views/modules/cms/content-element/_product-properties';*/ ?>
                <? \skeeks\cms\rpViewWidget\RpViewWidget::end(); ?>

            </div>
        </div>
        <? if ($infoModel->description_full) : ?>
            <div class="row">
                <div class="col-md-12 sx-content" id="sx-description">
                    <h2>Описание</h2>
                    <?= $infoModel->description_full; ?>
                </div>
            </div>
        <? endif; ?>

    </div>
</section>


<? if (\Yii::$app->unifyShopTheme->is_allow_product_review) : ?>
    <section class="g-brd-gray-light-v4 g-brd-top g-mt-20 g-mb-20">
        <div class="container">

            <div class="col-md-12 g-mt-20" id="sx-reviews">
                <div class="float-right"><a href="#showReviewFormBlock" data-toggle="modal" class="btn btn-primary showReviewFormBtn">Оставить отзыв</a></div>
                <h2>Отзывы</h2>
            </div>

            <?
            $widgetReviews = \skeeks\cms\reviews2\widgets\reviews2\Reviews2Widget::begin([
                'namespace'         => 'Reviews2Widget',
                'viewFile'          => '@app/views/widgets/Reviews2Widget/reviews',
                'cmsContentElement' => $model,
            ]);
            $widgetReviews::end();
            ?>
        </div>
    </section>
<? endif; ?>


<section class="g-brd-gray-light-v4 g-brd-top g-mt-20">

    <? if (\Yii::$app->shop->shopContents) : ?>
        <?
        $treeIds = [];
        if ($model->cmsTree && $model->cmsTree->parent) {
            $treeIds = \yii\helpers\ArrayHelper::map($model->cmsTree->parent->children, 'id', 'id');
        }
        ?>
        <div class="container g-mt-20 g-mb-40 ">
            <?
            $widgetElements = \skeeks\cms\cmsWidgets\contentElements\ContentElementsCmsWidget::beginWidget("product-similar-products", [
                'viewFile'             => '@app/views/widgets/ContentElementsCmsWidget/products-stick',
                'label'                => "Рекомендуем также",
                'enabledPaging'        => "N",
                'content_ids'          => \yii\helpers\ArrayHelper::map(\Yii::$app->shop->shopContents, 'id', 'id'),
                'tree_ids'             => $treeIds,
                'limit'                => 15,
                'contentElementClass'  => \skeeks\cms\shop\models\ShopCmsContentElement::class,
                'dataProviderCallback' => function (\yii\data\ActiveDataProvider $activeDataProvider) use ($model) {
                    $activeDataProvider->query->with('shopProduct');
                    $activeDataProvider->query->with('shopProduct.baseProductPrice');
                    $activeDataProvider->query->with('shopProduct.minProductPrice');
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

                },
            ]);
            $widgetElements::end();
            ?>
        </div>
    <? endif; ?>
</section>


<section class="g-brd-gray-light-v4 g-brd-top g-mt-20">

    <? if (\Yii::$app->shop->shopContents) : ?>
        <?
        $treeIds = [];
        if ($model->cmsTree && $model->cmsTree->parent) {
            $treeIds = \yii\helpers\ArrayHelper::map($model->cmsTree->parent->children, 'id', 'id');
        }
        ?>
        <div class="container g-mt-20 g-mb-40 ">
            <?
            $widgetElements = \skeeks\cms\cmsWidgets\contentElements\ContentElementsCmsWidget::beginWidget("product-viewed-products", [
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
                    $query->leftJoin('shop_product', '`shop_product`.`id` = `cms_content_element`.`id`');
                    $query->leftJoin('shop_viewed_product', '`shop_viewed_product`.`shop_product_id` = `shop_product`.`id`');
                    $query->andWhere(['shop_user_id' => \Yii::$app->shop->shopUser->id]);
                    //$query->orderBy(['shop_viewed_product.created_at' => SORT_DESC]);

                    \Yii::$app->shop->filterBaseContentElementQuery($query);
                },
            ]);
            $widgetElements::end();
            ?>
        </div>
    <? endif; ?>
</section>


<?
$modal = \yii\bootstrap\Modal::begin([
    'header'       => 'Оставить заявку',
    'id'           => 'sx-order',
    'toggleButton' => false,
    'size'         => \yii\bootstrap\Modal::SIZE_DEFAULT,
]);
?>
<?= \skeeks\modules\cms\form2\cmsWidgets\form2\FormWidget::widget([
    'form_code' => 'feedback',
    'namespace' => 'FormWidget-feedback',
    'viewFile'  => 'with-messages'
    //'viewFile' => '@app/views/widgets/FormWidget/fiz-connect'
]); ?>

<?
$modal::end();
?>
