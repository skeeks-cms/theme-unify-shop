<?php
/**
 * @link https://cms.skeeks.com/
 * @copyright Copyright (c) 2010 SkeekS
 * @license https://cms.skeeks.com/license/
 * @author Semenov Alexander <semenov@skeeks.com>
 */
/* @var $this yii\web\View */
/* @var $shopOfferChooseHelper \skeeks\cms\shop\helpers\ShopOfferChooseHelper */
$this->registerJs(<<<JS

_.delay(function() {
    $(".slick-slide").on("click", function() {
    var jElement = $(this).find(".sx-fancybox-gallary");
    jElement.trigger("click");
});
}, 500);

$('[data-fancybox="images"]').fancybox({
    
    thumbs: {
    autoStart: true, // Display thumbnails on opening
    hideOnClose: true, // Hide thumbnail grid when closing animation starts
    parentEl: ".fancybox-container", // Container is injected into this element
    axis: "x", // Vertical (y) or horizontal (x) scrolling
    
      clickContent: function(current, event) {
        return current.type === "image" ? "zoom" : false;
      },
  },
});

JS
);

$this->registerCss(<<<CSS
    .slick-current {
        cursor: zoom-in;
    }

    .js-carousel .sx-images-carousel-arrows {
        color: #999;
        z-index: 10;
        width: 45px;
        height: 45px;
        position: absolute;
        top: 50%;
        -webkit-transform: translateY(-50%);
        -ms-transform: translateY(-50%);
        transform: translateY(-50%);
        /*border: 1px solid #999;*/
        border-radius: 50%;
        cursor: pointer;
        font-size: 25px;
        font-family: hs-icons;
    }

    .js-carousel .sx-images-carousel-arrows.sx-left {
        left: -20px;
    }
    .js-carousel .sx-images-carousel-arrows.sx-right {
        right: -20px;
    }

    .js-carousel .sx-images-carousel-arrows::before {
        display: inline-block;
        top: 50%;
        left: 50%;
        vertical-align: top;
        -webkit-transform: translateX(-50%) translateY(-50%);
        -ms-transform: translateX(-50%) translateY(-50%);
        transform: translateX(-50%) translateY(-50%);
        position: absolute;
    }

    .js-carousel .sx-images-carousel-arrows.sx-left::before {
        left: 45%;
    }
    .js-carousel .sx-images-carousel-arrows.sx-right::before {
        left: 55%;
    }
    
    
    .slick-transform-off .slick-track {
        -webkit-transform: none !important;
        -ms-transform: none !important;
        transform: none !important;
    }

CSS
);
?>
<?

$images = [];

if ($model->mainProductImage) {
    $images[] = $model->mainProductImage;
}

if ($productImages = $model->productImages) {
    $images = \yii\helpers\ArrayHelper::merge($images, $productImages);
}


?>
<? if ($images) : ?>
    <div id="carouselCus1" class="js-carousel g-pt-10 g-mb-10 sx-stick-slider"
         data-infinite="true"
         data-fade="true"
         data-arrows-classes="u-arrow-v1 g-color-primary--hover sx-images-carousel-arrows"
         data-arrow-left-classes="hs-icon hs-icon-arrow-left sx-left g-left-minus-20"
         data-arrow-right-classes="hs-icon hs-icon-arrow-right sx-right g-right-minus-20"
         data-nav-for="#carouselCus2">

        <? foreach ($images as $image) : ?>
            <div class="js-slide g-bg-cover">
                <!--w-100-->
                <a class="sx-fancybox-gallary" data-fancybox="images" href="<?= $image->src; ?>">
                    <img class="img-fluid" src="<?= \Yii::$app->imaging->thumbnailUrlOnRequest($image->src,
                        new \skeeks\cms\components\imaging\filters\Thumbnail([
                            'w' => 700,
                            'h' => 500,
                            'm' => \Imagine\Image\ImageInterface::THUMBNAIL_INSET,
                        ]), $model->code
                    ); ?>" alt="<?= $model->name; ?>">
                </a>
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
                            'm' => \Imagine\Image\ImageInterface::THUMBNAIL_OUTBOUND,
                        ]), $model->code
                    ); ?>" alt="<?= $model->name; ?>">
                </div>
            <? endforeach; ?>
        </div>
    <? endif; ?>
<? else: ?>
    <div id="carouselCus1" class="js-carousel g-pt-10 g-mb-10 sx-stick-slider"
         data-infinite="true"
         data-fade="true"
         data-arrows-classes="u-arrow-v1 g-brd-around g-brd-gray-dark-v5 g-absolute-centered--y g-width-45 g-height-45 g-font-size-25 g-color-gray-dark-v5 g-color-primary--hover rounded-circle"
         data-arrow-left-classes="fa fa-angle-left g-left-minus-20"
         data-arrow-right-classes="fa fa-angle-right g-right-minus-20"
         data-nav-for="#carouselCus2">
        <div class="js-slide g-bg-cover">
            <!--w-100-->
            <img class="img-fluid" src="<?= \skeeks\cms\helpers\Image::getCapSrc(); ?>" alt="<?= $model->name; ?>">
        </div>
    </div>
<? endif; ?>
