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
\skeeks\cms\themes\unify\assets\VanillaLazyLoadAsset::register($this);

$isShowMobileThumbnails = \Yii::$app->mobileDetect->isDesktop ? "true" : "false";
$this->registerJs(<<<JS
$('[data-fancybox="images"]').fancybox({
    thumbs: {
        autoStart: {$isShowMobileThumbnails}, // Display thumbnails on opening
        hideOnClose: true, // Hide thumbnail grid when closing animation starts
        parentEl: ".fancybox-container", // Container is injected into this element
        axis: "y", // Vertical (y) or horizontal (x) scrolling
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
.sx-main-product-container .sx-product-images
{
    padding-left: 10px;
}
.js-carousel.slick-initialized .js-slide {
    margin-bottom: 10px;
}
CSS
);
?>
<?

$images = @$images;

if ($images !== false && !$images) {
    if ($model->mainProductImage) {
        $images[] = $model->mainProductImage;
    }

    if ($productImages = $model->productImages) {
        $images = \yii\helpers\ArrayHelper::merge((array) $images, (array) $productImages);
    }
}

?>
<? if ($images) : ?>
    <div class="d-flex flex-row">
        <? if (count($images) > 1) : ?>
            <div id="carouselCus2" class="js-carousel text-center g-mx-minus-5 sx-stick sx-stick-navigation" style="width: 80px;"
                 data-infinite="true"
                 data-center-mode="true"
                 data-slides-show="6"
                 data-is-thumbs="true"
                 data-vertical="true"
                 data-focus-on-select="false"
                 data-nav-for="#carouselCus1"
                 data-arrows-classes="sx-arrows g-color-primary--hover sx-color-silver"
                 data-arrow-left-classes="hs-icon hs-icon-arrow-top sx-left"
                 data-arrow-right-classes="hs-icon hs-icon-arrow-bottom sx-right"
            >
                <? foreach ($images as $image) : ?>
                    
                    <?
                    $preview = \Yii::$app->imaging->getPreview($image,
                        new \skeeks\cms\components\imaging\filters\Thumbnail([
                            'w' => 100,
                            'h' => 100,
                            'm' => \Imagine\Image\ImageInterface::THUMBNAIL_OUTBOUND,
                            'sx_preview' => \skeeks\cms\components\storage\SkeeksSuppliersCluster::IMAGE_PREVIEW_MICRO,
                        ]), $model->code
                    );
                    ?>
                    
                    <div class="js-slide">
                        <img class="img-fluid lazy" 
                             style="aspect-ratio: <?php echo $preview->cssAspectRatio; ?>; width: 100%;"
                             src="<?php echo \Yii::$app->cms->image1px; ?>"
                             data-src="<?= $preview->src; ?>" alt="<?= $model->name; ?>">
                    </div>
                <? endforeach; ?>
            </div>

        <? endif; ?>

        <div id="carouselCus1" class="js-carousel sx-stick sx-stick-slider" style="<? echo (count($images) > 1) ? "width: calc(100% - 90px);" : "width: 100%;"; ?>"
             data-infinite="true"
             data-fade="true"
             data-arrows-classes="g-color-primary--hover sx-arrows sx-images-carousel-arrows sx-color-silver"
             data-arrow-left-classes="hs-icon hs-icon-arrow-left sx-left sx-plus-20"
             data-arrow-right-classes="hs-icon hs-icon-arrow-right sx-right sx-plus-20"
             data-nav-for="#carouselCus2">

            <?
            /**
             * @var $image \skeeks\cms\models\CmsStorageFile
             */
            foreach ($images as $image) : ?>
                <?
                $preview = \Yii::$app->imaging->getPreview($image,
                    new \skeeks\cms\components\imaging\filters\Thumbnail([
                        'w' => $this->theme->product_card_img_preview_width,
                        'h' => $this->theme->product_card_img_preview_height,
                        'm' => $this->theme->product_card_img_preview_crop,
                        'sx_preview' => \skeeks\cms\components\storage\SkeeksSuppliersCluster::IMAGE_PREVIEW_BIG,
                    ]), $model->code
                );
                ?>

                <div class="js-slide">
                    <!--w-100-->
                    <a class="sx-fancybox-gallary" data-fancybox="images" href="<?= $image->src; ?>">
                        <img class="img-fluid lazy" 
                             style="aspect-ratio: <?php echo $preview->cssAspectRatio; ?>; height: 100%; width: 100%; max-width: 500px; max-height: <?php echo $preview->height; ?>px;"
                             src="<?php echo \Yii::$app->cms->image1px; ?>"
                             data-src="<?= $preview->src; ?>" alt="<?= $model->name; ?>">
                    </a>
                </div>
            <? endforeach; ?>
        </div>
    </div>


<? else: ?>
    <div id="carouselCus1" class="js-carousel sx-stick sx-stick-slider"
         data-infinite="true"
         data-fade="true"
         data-arrows-classes="sx-arrows g-color-primary--hover sx-color-silver"
         data-arrow-left-classes="hs-icon hs-icon-arrow-left sx-left"
         data-arrow-right-classes="hs-icon hs-icon-arrow-right sx-right"
         data-nav-for="#carouselCus2">
        <div class="js-slide g-bg-cover">
            <!--w-100-->
            <img class="img-fluid" src="<?= \skeeks\cms\helpers\Image::getCapSrc(); ?>" alt="<?= $model->name; ?>">
        </div>
    </div>
<? endif; ?>
