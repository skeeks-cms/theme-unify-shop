<?php
/**
 * @link https://cms.skeeks.com/
 * @copyright Copyright (c) 2010 SkeekS
 * @license https://cms.skeeks.com/license/
 * @author Semenov Alexander <semenov@skeeks.com>
 */
/* @var $this yii\web\View */
skeeks\assets\unify\base\UnifyHsRatingAsset::register($this);
$this->registerJs(<<<JS
$.HSCore.components.HSRating.init($('.js-rating-show'), {
  spacing: 2
});
JS
);
$shopProduct = \skeeks\cms\shop\models\ShopProduct::getInstanceByContentElement($model);
$model = $shopProduct->cmsContentElement;
$priceHelper = \Yii::$app->shop->cart->getProductPriceHelper($model);

$reviews2Count = $model->relatedPropertiesModel->getSmartAttribute('reviews2Count');
$rating = $model->relatedPropertiesModel->getSmartAttribute('reviews2Rating');
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
    <div class="container g-py-20">

        <div class="row">
            <div class="col-md-12">
                <?= $this->render('@app/views/breadcrumbs', [
                    'model' => $model,
                    /*'isShowLast' => true,
                    'isShowH1'   => false,*/
                ]); ?>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-8">
                <div class="sx-product-images g-ml-40 g-mr-40">
                    <?
                    $images = [];
                    if ($model->image) {
                        $images[] = $model->image;
                    }
                    if ($model->images) {
                        $images = \yii\helpers\ArrayHelper::merge($images, $model->images);
                    }
                    ?>
                    <? if ($images) : ?>

                        <div id="carouselCus1" class="js-carousel g-pt-10 g-mb-10 sx-stick-slider"
                             data-infinite="true"
                             data-fade="true"
                             data-arrows-classes="u-arrow-v1 g-brd-around g-brd-gray-dark-v5 g-absolute-centered--y g-width-45 g-height-45 g-font-size-25 g-color-gray-dark-v5 g-color-primary--hover rounded-circle"
                             data-arrow-left-classes="fa fa-angle-left g-left-minus-20"
                             data-arrow-right-classes="fa fa-angle-right g-right-minus-20"
                             data-nav-for="#carouselCus2">

                            <? foreach ($images as $image) : ?>
                                <div class="js-slide g-bg-cover">
                                    <!--w-100-->
                                    <img class="img-fluid" src="<?= \Yii::$app->imaging->thumbnailUrlOnRequest($image->src,
                                        new \skeeks\cms\components\imaging\filters\Thumbnail([
                                            'w' => 700,
                                            'h' => 500,
                                            'm' => \Imagine\Image\ImageInterface::THUMBNAIL_INSET,
                                        ]), $model->code
                                    ); ?>" alt="<?= $model->name; ?>">
                                </div>
                            <? endforeach; ?>

                        </div>

                        <? if (count($images) > 1) : ?>
                            <div id="carouselCus2" class="js-carousel text-center u-carousel-v3 g-mx-minus-5 sx-stick-navigation"
                                 data-infinite="true"
                                 data-center-mode="true"
                                 data-slides-show="8"
                                 data-is-thumbs="true"
                                 data-focus-on-select="false"
                                 data-nav-for="#carouselCus1"
                                 data-arrows-classes="u-arrow-v1 g-absolute-centered--y g-width-45 g-height-45 g-font-size-30 g-color-gray-dark-v5 g-color-primary--hover rounded-circle"
                                 data-arrow-left-classes="fa fa-angle-left g-left-minus-40"
                                 data-arrow-right-classes="fa fa-angle-right g-right-minus-40"
                            >

                                <? foreach ($images as $image) : ?>
                                    <div class="js-slide g-cursor-pointer g-px-5">
                                        <img class="img-fluid" src="<?= \Yii::$app->imaging->thumbnailUrlOnRequest($image->src,
                                            new \skeeks\cms\components\imaging\filters\Thumbnail([
                                                'w' => 75,
                                                'h' => 75,
                                                'm' => \Imagine\Image\ImageInterface::THUMBNAIL_INSET,
                                            ]), $model->code
                                        ); ?>" alt="<?= $model->name; ?>">
                                    </div>
                                <? endforeach; ?>
                            </div>
                        <? endif; ?>


                    <? else: ?>

                    <? endif; ?>
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
                                    <div class="feedback-review cf pull-right">
                                        <? if ($rating > 0) : ?>
                                            <div class="product-rating pull-right" itemprop="aggregateRating" itemscope="" itemtype="http://schema.org/AggregateRating">
                                                <div class="js-rating-show g-color-yellow" data-rating="<?= $rating; ?>"></div>
                                                <meta itemprop="ratingValue" content="<?= $rating ? $rating : 0; ?>">
                                                <meta itemprop="reviewCount" content="<?= $reviews2Count ? $reviews2Count : 0; ?>">
                                            </div>
                                        <? else : ?>
                                            <div class="product-rating pull-right">
                                                <div class="js-rating-show g-color-yellow" data-rating="<?= $rating; ?>"></div>
                                            </div>
                                        <? endif; ?>

                                        <div class="sx-feedback-links pull-right g-mr-10">
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


                        <!--<h1 class="h3 g-color-gray-dark-v2" itemprop="name">
                            <? /*= $model->name; */ ?>
                        </h1>-->


                        <div class="product-price g-mt-10 g-mb-10" itemprop="offers" itemscope="" itemtype="http://schema.org/Offer">
                            <link itemprop="url" href="<?= $model->absoluteUrl; ?>"/>
                            <meta itemprop="price" content="<?= $priceHelper->basePrice->money->amount; ?>">
                            <meta itemprop="priceCurrency" content="<?= $priceHelper->basePrice->money->currency->code; ?>">
                            <meta itemprop="priceValidUntil" content="<?= date('Y-m-d', strtotime('+1 week')); ?>">
                            <link itemprop="availability" href="http://schema.org/InStock">

                            <? if ($priceHelper) : ?>
                                <?
                                $prefix = "";
                                if ($shopProduct->isTradeOffers()) {
                                    $prefix = "от ";
                                }
                                ?>

                                <? if ($priceHelper->hasDiscount) : ?>
                                    <span class="current ss-price sx-old-price h1"><?= $prefix; ?><?= $priceHelper->basePrice->money; ?></span>
                                    <span class="current ss-price sx-new-price h1 g-font-weight-600 g-color-primary"><?= $prefix; ?><?= $priceHelper->minPrice->money; ?></span>
                                <? else: ?>
                                    <span class="current ss-price sx-new-price h1 g-font-weight-600 g-color-primary"><?= $prefix; ?><?= $priceHelper->minPrice->money; ?></span>
                                <? endif; ?>
                            <? endif; ?>

                        </div>


                        <? if ($shopProduct->quantity > 0) : ?>
                            <div class="product-control g-mt-10">
                                <div class="control-group group-submit g-mr-10 g-mb-15">
                                    <div class="buttons-row ">
                                        <? if ($shopProduct->minProductPrice && $shopProduct->minProductPrice->price == 0) : ?>
                                            <? if (\Yii::$app->shop->is_show_button_no_price) : ?>
                                                <?= \yii\helpers\Html::tag('button', '<i class="icon-cart"></i> Добавить в корзину', [
                                                    'class'   => 'btn btn-xxl u-btn-primary g-rounded-50 js-to-cart to-cart-fly-btn g-font-size-18',
                                                    'type'    => 'button',
                                                    'onclick' => new \yii\web\JsExpression("sx.Shop.addProduct({$shopProduct->id}, 1); return false;"),
                                                ]); ?>
                                            <? else : ?>
                                                <a class="btn btn-xxl u-btn-primary g-rounded-50 g-font-size-18" href="#sx-order" data-toggle="modal">Оставить заявку</a>

                                            <? endif; ?>
                                        <? else : ?>
                                            <?= \yii\helpers\Html::tag('button', '<i class="icon-cart"></i> Добавить в корзину', [
                                                'class'   => 'btn btn-xxl u-btn-primary g-rounded-50 js-to-cart to-cart-fly-btn g-font-size-18',
                                                'type'    => 'button',
                                                'onclick' => new \yii\web\JsExpression("sx.Shop.addProduct({$shopProduct->id}, 1); return false;"),
                                            ]); ?>
                                        <? endif; ?>
                                    </div>
                                    <? if (\Yii::$app->shop->is_show_quantity_product) : ?>
                                    <div class="availability-row available" style=""><!-- 'available' || 'not-available' || '' -->
                                        <? if ($shopProduct->quantity > 10) : ?>
                                            <span class="row-label">В наличии более 10 шт.</span>
                                        <? else : ?>
                                            <span class="row-label">В наличии:</span> <span class="row-value"><?= $shopProduct->quantity; ?> шт.</span>
                                        <? endif; ?>

                                    </div>
                                    <? endif; ?>
                                </div>
                            </div>
                        <? else : ?>

                            <?= \skeeks\cms\shop\widgets\notice\NotifyProductEmailModalWidget::widget([
                                'view_file'        => '@app/views/widgets/NotifyProductEmailModalWidget/modalForm',
                                'product_id'       => $model->id,
                                'size'             => "modal-dialog-350",
                                'success_modal_id' => 'readySubscribeModal',
                                'id'               => 'modalWait',
                                'class'            => 'b-modal b-modal-wait',
                                //'header' => '<div class="b-modal__title h2">Жду товар</div>',

                                /*'closeButton' => [
                                        'tag'   => 'button',
                                        'class' => 'close',
                                        'label' => '1111111',
                                    ],*/

                                'toggleButton' => [
                                    'label' => 'Уведомить о поступлении',
                                    'style' => '',
                                    'class' => 'btn btn-grey-white btn-52 js-out-click-btn',
                                ],
                            ]); ?>

                        <? endif; ?>

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
                            <!--<ul class="nav d-flex justify-content-between g-font-size-12 text-uppercase" role="tablist" data-target="nav-1-1-default-hor-left">
                                <li class="nav-item">
                                    <a class="nav-link g-color-primary--parent-active g-pa-0 g-pb-1 active show" data-toggle="tab" href="#nav-1-1-default-hor-left--3" role="tab" aria-selected="true">
                                        <span class="u-icon-v3 g-rounded-50x">
                                            <i class="icon-christmas-005 u-line-icon-pro"></i>
                                        </span>
                                        <span>Условия доставки</span>
                                    </a>
                                </li>
                                <li class="nav-item g-brd-bottom g-brd-gray-dark-v4">
                                    <a class="nav-link g-color-primary--parent-active g-pa-0 g-pb-1 show" data-toggle="tab" href="#nav-1-1-default-hor-left--1" role="tab" aria-selected="false">View Size Guide</a>
                                </li>
                            </ul>-->

                            <!-- Nav tabs -->
                            <!--u-nav-v1-1-->
                            <ul class="nav nav-justified  u-nav-v5-1" role="tablist" data-target="nav-1-1-accordion-default-hor-left-icons" data-tabs-mobile-type="accordion" data-btn-classes="btn btn-md btn-block rounded-0 u-btn-outline-lightgray g-mb-20">
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
    </div>

    <? if ($shopProduct->isTradeOffers()) : ?>
        <div class="container" style="background: #f1f1f1;">

            <div class="row">
                <div class="col-md-12">
                    <h2>Предложения</h2>
                </div>
            </div>

            <div class="row">
                <div class="col-md-3">

                    <?
                    $query = $shopProduct->getTradeOffers();
                    $baseQuery = clone $query;

                    $filtersWidget = \skeeks\cms\themes\unify\widgets\filters\FiltersWidget::begin();

                    $eavFiltersHandler = new \skeeks\cms\eavqueryfilter\CmsEavQueryFilterHandler([
                        'baseQuery' => $baseQuery,
                    ]);
                    $eavFiltersHandler->viewFile = '@app/views/filters/eav-filters';
                    $rpQuery = $eavFiltersHandler->getRPQuery();
                    $eavFiltersHandler->initRPByQuery($rpQuery);

                    $filtersWidget
                        ->registerHandler($eavFiltersHandler);
                    $filtersWidget->loadFromRequest();
                    $filtersWidget->applyToQuery($query);

                    $filtersWidget::end();

                    /**
                     * @var \skeeks\cms\shop\models\ShopCmsContentElement $tOffer
                     */
                    //$tradeOffers = $shopCmsContentElement->getTradeOffers()->with("relatedProperties")->with("relatedElementProperties")->all();
                    $tradeOffers = $query->all();
                    ?>
                </div>
                <div class="col-md-9">
                    <? foreach ($tradeOffers as $tOffer) : ?>
                        <div class="row sx-price-block">
                            <div class="col-md-2 col-sm-12">
                                <img src="<?= \skeeks\cms\helpers\Image::getSrc(
                                    \Yii::$app->imaging->thumbnailUrlOnRequest($tOffer->image ? $tOffer->image->src : null,
                                        new \skeeks\cms\components\imaging\filters\Thumbnail([
                                            'w' => 50,
                                            'h' => 50,
                                            'm' => \Imagine\Image\ManipulatorInterface::THUMBNAIL_INSET,
                                        ]), $model->code
                                    )); ?>" title="<?= $model->name; ?>" alt="<?= $model->name; ?>" class="img_list_catalog" style="max-height: 50px; max-width: 50px;"/>
                                Код: <?= $tOffer->id; ?>
                            </div>
                            <div class="col-md-2 col-sm-12">
                                <b style="line-height: 20px;">
                                    <?
                                    $color = trim($tOffer->relatedPropertiesModel->getSmartAttribute('color'));
                                    $title = $tOffer->relatedPropertiesModel->getSmartAttribute('packing')." ".$tOffer->relatedPropertiesModel->getSmartAttribute('measure').($color ? " - ".$color : "");
                                    echo(trim($title) ? $title : $tOffer->name);
                                    ?>
                                </b>
                                <!--
                                      <img class="img-responsive" src="<? /*= \skeeks\cms\helpers\Image::getSrc($tOffer->image ? $tOffer->image->src : ""); */ ?>" />-->
                            </div>

                            <div class="col-md-2 col-sm-12">
                                <!--<b><? /*= $tOffer->name; */ ?></b>-->
                            </div>
                            <div class="col-md-2 col-sm-12">
                                      <span class="price">
                                            <? if ($tOffer->shopProduct->minProductPrice->id == $tOffer->shopProduct->baseProductPrice->id) : ?>
                                                <span class="old"></span>
                                                <span class="new"><?= \Yii::$app->money->convertAndFormat($tOffer->shopProduct->minProductPrice->money); ?></span>
                                            <? else : ?>
                                                <span class="old"><?= \Yii::$app->money->convertAndFormat($tOffer->shopProduct->baseProductPrice->money); ?></span>
                                                <span class="new"><?= \Yii::$app->money->convertAndFormat($tOffer->shopProduct->minProductPrice->money); ?></span>
                                            <? endif; ?>
                                        </span>
                            </div>
                            <div class="col-md-4 col-sm-12" style="text-align: right;">

                                <input type="number" id="sx-number-<?= $tOffer->id; ?>" value="1" name="qty" class=" sx-basket-quantity" style="width: 50px;
                                        border: #ddd 1px solid;
                                        border-radius: 0px;" maxlength="3" max="999" min="1">
                                <a class="btn btn-default btn-primary product-add-cart noradius" href="#"
                                   onclick="sx.Shop.addProduct(<?= $tOffer->id; ?>, $('#sx-number-<?= $tOffer->id; ?>').val()); return false;"><i
                                            class="fa fa-cart-plus"></i> В корзину</a>

                                <? /*= \yii\helpers\Html::tag('a', '<i class="fa fa-shopping-cart"></i> <strong>В корзину</strong>', [
                                        'class' => 'btn btn-primary js-to-cart',
                                        'type' => 'button',
                                        'onclick' => new \yii\web\JsExpression("sx.Shop.addProduct({$tOffer->shopProduct->id}, 1); return false;"),
                                    ]); */ ?>
                            </div>
                        </div>
                    <? endforeach; ?>

                </div>


            </div>
        </div>
    <? endif; ?>


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
            <div class="pull-right"><a href="#showReviewFormBlock" data-toggle="modal" class="btn btn-primary showReviewFormBtn">Оставить отзыв</a></div>
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
