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

$rating = $model->relatedPropertiesModel->getSmartAttribute('reviews2Rating');
$reviews2Count = $model->relatedPropertiesModel->getSmartAttribute('reviews2Count');

$shopOfferChooseHelper = null;
if ($shopProduct->isOffersProduct) {
    $shopOfferChooseHelper = new \skeeks\cms\shop\helpers\ShopOfferChooseHelper([
        'shopProduct' => $shopProduct,
    ]);

}


?>
<section class="sx-product-card-wrapper g-mt-0 g-pb-0 to-cart-fly-wrapper" itemscope itemtype="http://schema.org/Product">
    <meta itemprop="name" content="<?= \yii\helpers\Html::encode($model->name); ?><?= $priceHelper->basePrice->money; ?>"/>
    <link itemprop="url" href="<?= $model->absoluteUrl; ?>"/>
    <meta itemprop="description" content="<?= $model->description_short ? $model->description_short : '-'; ?>"/>
    <meta itemprop="sku" content="<?= $model->id; ?>"/>

    <? if ($model->relatedPropertiesModel->getAttribute('brand')) : ?>
        <meta itemprop="brand" content="<?= $model->relatedPropertiesModel->getSmartAttribute('brand'); ?>"/>
    <? else : ?>
        <meta itemprop="brand" content="<?= \Yii::$app->view->theme->title; ?>"/>
    <? endif; ?>
    <? if ($model->image) : ?>
        <link itemprop="image" href="<?= $model->image->absoluteSrc; ?>">
    <? endif; ?>
    <div class="container sx-container g-py-20">

        <div class="row">
            <div class="col-md-12">
                <?= $this->render('@app/views/breadcrumbs', [
                    'model' => $model,
                    /*'isShowLast' => true,
                    'isShowH1'   => false,*/
                ]); ?>
            </div>
        </div>

        <? $pjax = \skeeks\cms\widgets\Pjax::begin(); ?>
        <div class="row">
            <div class="col-lg-8">
                <div class="sx-product-images g-ml-40 g-mr-40">
                    <?= $this->render("_product-images", [
                        'model' => $model,
                    ]); ?>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="product-info ss-product-info">
                    <div class="product-info-header">
                        <div class="topmost-row">
                            <div class="row no-gutters">
                                <div class="col-5">
                                    <div data-product-id="<?= $model->id; ?>" class="item-lot">Код:&nbsp;<?= $model->id; ?></div>
                                </div>
                                <div class="col-7">
                                    <div class="feedback-review cf float-right">
                                        <? if ($rating > 0) : ?>
                                            <div class="product-rating float-right" itemprop="aggregateRating" itemscope="" itemtype="http://schema.org/AggregateRating">
                                                <div class="js-rating-show g-color-yellow" data-rating="<?= $rating; ?>"></div>
                                                <meta itemprop="ratingValue" content="<?= $rating ? $rating : 0; ?>">
                                                <meta itemprop="reviewCount" content="<?= $reviews2Count ? $reviews2Count : 0; ?>">
                                            </div>
                                        <? else : ?>
                                            <div class="product-rating float-right">
                                                <div class="js-rating-show g-color-yellow" data-rating="<?= $rating; ?>"></div>
                                            </div>
                                        <? endif; ?>

                                        <div class="sx-feedback-links float-right g-mr-10">
                                            <a href="#sx-reviews" class="sx-scroll-to g-color-gray-dark-v2 g-font-size-13 sx-dashed  g-brd-primary--hover g-color-primary--hover">
                                                <?
                                                echo \Yii::t(
                                                    'app',
                                                    '{n, plural, =0{нет отзывов} =1{один отзыв} one{# отзыв} few{# отзыва} many{# отзывов} other{# отзыва}}',
                                                    ['n' => $reviews2Count]
                                                );
                                                ?>
                                            </a>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>


                        <?= $this->render("_product-price", [
                            'model'                 => $model,
                            'shopProduct'           => $shopProduct,
                            'priceHelper'           => $priceHelper,
                            'shopOfferChooseHelper' => $shopOfferChooseHelper,
                        ]); ?>


                        <? if ($model->description_short) : ?>
                            <div class="sx-description-short g-color-gray-dark-v4">
                                <?= $model->description_short; ?>
                                <p>
                                    <a href="#sx-description" class="sx-scroll-to g-color-gray-dark-v2 g-font-size-13 sx-dashed g-brd-primary--hover g-color-primary--hover">
                                        Подробнее
                                    </a>
                                </p>
                            </div>
                        <? endif; ?>
                        <div class="sx-product-delivery-info g-mt-20">
                            <!-- Nav tabs -->
                            <!--u-nav-v1-1-->
                            <ul class="nav nav-justified  u-nav-v5-1" role="tablist" data-target="nav-1-1-accordion-default-hor-left-icons" data-tabs-mobile-type="accordion"
                                data-btn-classes="btn btn-md btn-block rounded-0 u-btn-outline-lightgray g-mb-20">
                                <li class="nav-item">
                                    <a class="nav-link active" data-toggle="tab" href="#nav-1-1-accordion-default-hor-left-icons--1" role="tab">
                                        <!--<i class="icon-christmas-037 u-tab-line-icon-pro "></i>-->
                                        <i class="fas fa-truck g-mr-3"></i>
                                        Условия доставки
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#nav-1-1-accordion-default-hor-left-icons--2" role="tab">
                                        <!--<i class="icon-communication-025 u-tab-line-icon-pro g-mr-3"></i>-->
                                        <i class="far fa-question-circle g-mr-3"></i>
                                        Помощь
                                    </a>
                                </li>

                            </ul>

                            <!-- Tab panes -->
                            <div id="nav-1-1-accordion-default-hor-left-icons" class="tab-content">
                                <div class="tab-pane fade show active" id="nav-1-1-accordion-default-hor-left-icons--1" role="tabpanel">
                                    <? \skeeks\cms\cmsWidgets\text\TextCmsWidget::beginWidget('product-delivery-short'); ?>
                                    <p>Ближайшая дата доставки: 31 мар. 2019 г.</p>
                                    <p>Способы доставки: курьер, Почта России</p>
                                    <p>Регионы доставки: вся Россия</p>
                                    <? \skeeks\cms\cmsWidgets\text\TextCmsWidget::end(); ?>
                                </div>

                                <div class="tab-pane fade" id="nav-1-1-accordion-default-hor-left-icons--2" role="tabpanel">
                                    <? \skeeks\cms\cmsWidgets\text\TextCmsWidget::beginWidget('product-help-short'); ?>
                                    <p class="g-font-weight-600">Проблема с добавлением товара в корзину?</p>
                                    <p>Если у вас появилась сложность с добавлением товара в корзину, вы можете позвонить по номеру
                                        <a href="tel:<?= $this->theme->phone; ?>"><?= $this->theme->phone; ?></a> и оформить заказ по телефону.</p>
                                    <p>Пожалуйста, сообщите, какие проблемы с добавлением товара в корзину вы испытываете:</p>
                                    <? \skeeks\cms\cmsWidgets\text\TextCmsWidget::end(); ?>
                                </div>
                            </div>

                            <!-- End Nav tabs -->

                        </div>
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
                    'model'                   => $model,
                    'visible_properties'      => @$visible_items,
                    'visible_only_has_values' => true,
                    'viewFile'                => '@app/views/widgets/RpWidget/default',
                ]); ?>
                <? /* $widget->viewFile = '@app/views/modules/cms/content-element/_product-properties';*/ ?>
                <? \skeeks\cms\rpViewWidget\RpViewWidget::end(); ?>

            </div>
        </div>
        <? if ($model->description_full) : ?>
            <div class="row">
                <div class="col-md-12 sx-content" id="sx-description">
                    <h2>Описание</h2>
                    <?= $model->description_full; ?>
                </div>
            </div>
        <? endif; ?>

    </div>
</section>

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

                    $activeDataProvider->query->joinWith('shopProduct');
                    $activeDataProvider->query->andWhere([
                        '!=',
                        'shopProduct.product_type',
                        \skeeks\cms\shop\models\ShopProduct::TYPE_OFFER,
                    ]);

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
                    $query->andWhere(['shop_fuser_id' => \Yii::$app->shop->shopFuser->id]);
                    //$query->orderBy(['shop_viewed_product.created_at' => SORT_DESC]);

                    $query->joinWith('shopProduct');
                    $query->andWhere([
                        '!=',
                        'shopProduct.product_type',
                        \skeeks\cms\shop\models\ShopProduct::TYPE_OFFER,
                    ]);

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
