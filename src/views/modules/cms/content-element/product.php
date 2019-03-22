<?php
/**
 * @link https://cms.skeeks.com/
 * @copyright Copyright (c) 2010 SkeekS
 * @license https://cms.skeeks.com/license/
 * @author Semenov Alexander <semenov@skeeks.com>
 */
/* @var $this yii\web\View */
$shopProduct = \skeeks\cms\shop\models\ShopProduct::getInstanceByContentElement($model);
$model = $shopProduct->cmsContentElement;
$priceHelper = \Yii::$app->shop->cart->getProductPriceHelper($model);
?>
<section class="sx-product-card-wrapper g-mt-0 g-pb-0 to-cart-fly-wrapper" itemscope itemtype="http://schema.org/Product">
    <meta itemprop="name" content="<?= \yii\helpers\Html::encode($model->name); ?><?= $priceHelper->basePrice->money; ?>"/>
    <meta itemprop="url" content="<?= $model->absoluteUrl; ?>"/>
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
                                        <div class="product-rating pull-right" itemprop="aggregateRating" itemscope="" itemtype="http://schema.org/AggregateRating">
                                            <div class="rating-container rating-custom-size rating-animate">
                                                <div class="rating">
                                                    <span class="empty-stars">
                                                        <span class="star"><i class="star-empty"></i></span>
                                                        <span class="star"><i class="star-empty"></i></span>
                                                        <span class="star"><i class="star-empty"></i></span>
                                                        <span class="star"><i class="star-empty"></i></span>
                                                        <span class="star"><i class="star-empty"></i></span>
                                                    </span>
                                                    <span class="filled-stars" style="width: 0%;">
                                                        <span class="star"><i class="star-fill"></i></span>
                                                        <span class="star"><i class="star-fill"></i></span>
                                                        <span class="star"><i class="star-fill"></i></span>
                                                        <span class="star"><i class="star-fill"></i></span>
                                                        <span class="star"><i class="star-fill"></i></span>
                                                    </span>
                                                </div>
                                            </div>
                                            <meta itemprop="ratingValue" content="5">
                                            <meta itemprop="bestRating" content="5">
                                            <meta itemprop="ratingCount" content="1">
                                        </div>

                                        <div class="sx-feedback-links pull-right g-mr-10">
                                            <a href="#" class="sx-scroll-to g-color-gray-dark-v2 g-font-size-13 sx-dashed  g-brd-primary--hover g-color-primary--hover">
                                                <?
                                                echo \Yii::t(
                                                    'app',
                                                    '{n, plural, =0{нет отзывов} =1{один отзыв} one{# отзыв} few{# отзыва} many{# отзывов} other{# отзыва}}',
                                                    ['n' => 0]
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
                            <meta itemprop="price" content="<?= $priceHelper->basePrice->money->amount; ?>">
                            <meta itemprop="priceCurrency" content="<?= $priceHelper->basePrice->money->currency->code; ?>">
                            <link itemprop="availability" href="http://schema.org/InStock">

                            <span class="current ss-price h1 g-font-weight-600 g-color-primary"><?= $priceHelper->basePrice->money; ?></span>
                        </div>


                        <? if ($shopProduct->quantity > 0) : ?>
                            <div class="product-control g-mt-10">
                                <div class="control-group group-submit">

                                    <?= \yii\helpers\Html::tag('button', '<i class="icon-cart"></i> Добавить в корзину', [
                                        'class'   => 'btn btn-xxl u-btn-primary g-rounded-50 g-mr-10 g-mb-15 js-to-cart to-cart-fly-btn g-font-size-18',
                                        'type'    => 'button',
                                        'onclick' => new \yii\web\JsExpression("sx.Shop.addProduct({$shopProduct->id}, 1); return false;"),
                                    ]); ?>

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
                                <a href="#" class="sx-scroll-to g-color-gray-dark-v2 g-font-size-13 sx-dashed g-brd-primary--hover g-color-primary--hover">
                                    Подробнее
                                </a>
                                </p>
                            </div>
                        <? endif; ?>
                    </div>

                </div>
            </div>
        </div>

</section>