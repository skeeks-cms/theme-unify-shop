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
                            'm' => \Imagine\Image\ImageInterface::THUMBNAIL_INSET,
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
