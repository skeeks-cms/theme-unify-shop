<?php
/**
 * @link https://cms.skeeks.com/
 * @copyright Copyright (c) 2010 SkeekS
 * @license https://cms.skeeks.com/license/
 * @author Semenov Alexander <semenov@skeeks.com>
 */
/* @var $this yii\web\View */
/* @var $shopOfferChooseHelper \skeeks\cms\shop\helpers\ShopOfferChooseHelper */
\skeeks\cms\themes\unify\assets\components\UnifyThemeStickAsset::register($this);
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
    <div id="carouselCus1" class="js-carousel sx-stick sx-stick-slider"
         data-infinite="true"
         data-fade="true"
         data-arrows-classes="g-color-primary--hover sx-arrows sx-images-carousel-arrows sx-color-silver"
         data-arrow-left-classes="hs-icon hs-icon-arrow-left sx-left sx-minus-20"
         data-arrow-right-classes="hs-icon hs-icon-arrow-right sx-right sx-minus-20"
         data-nav-for="#carouselCus2">

        <? foreach ($images as $image) : ?>
            <div class="js-slide">
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
        <div id="carouselCus2" class="js-carousel text-center g-mx-minus-5 sx-stick sx-stick-navigation"
             data-infinite="true"
             data-center-mode="true"
             data-slides-show="8"
             data-is-thumbs="true"
             data-vertical="false"
             data-focus-on-select="false"
             data-nav-for="#carouselCus1"
             data-arrows-classes="sx-arrows g-color-primary--hover sx-color-silver"
             data-arrow-left-classes="hs-icon hs-icon-arrow-left sx-left"
             data-arrow-right-classes="hs-icon hs-icon-arrow-right sx-right"
        >
            <? foreach ($images as $image) : ?>
                <div class="js-slide">
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
    <div id="carouselCus1" class="js-carousel sx-stick sx-stick-slider"
         data-infinite="true"
         data-fade="true"
         data-arrows-classes="u-arrow-v1 g-brd-around g-brd-gray-dark-v5 g-absolute-centered--y g-width-45 g-height-45 g-font-size-25 g-color-gray-dark-v5 g-color-primary--hover rounded-circle"
         data-arrow-left-classes="hs-icon hs-icon-arrow-left sx-left"
         data-arrow-right-classes="hs-icon hs-icon-arrow-right sx-right"
         data-nav-for="#carouselCus2">
        <div class="js-slide g-bg-cover">
            <!--w-100-->
            <img class="img-fluid" src="<?= \skeeks\cms\helpers\Image::getCapSrc(); ?>" alt="<?= $model->name; ?>">
        </div>
    </div>
<? endif; ?>
