<?php
/**
 * @author Semenov Alexander <semenov@skeeks.com>
 * @link http://skeeks.com/
 * @copyright 2010 SkeekS (СкикС)
 * @date 25.05.2015
 */
/* @var $this   yii\web\View */
/* @var $widget \skeeks\cms\cmsWidgets\treeMenu\TreeMenuCmsWidget */
/* @var $trees  \skeeks\cms\models\Tree[] */


$this->registerCss(<<<CSS
/**
 * Кастомизация слайдера
 */

.tp-caption.FoodCarousel-Button,
.FoodCarousel-Button,
.erinyen .tp-thumb-title
{
    font-family: "Open Sans", Helvetica, Arial, sans-serif;
}
CSS
);

$url = \Yii::$app->assetManager->getAssetUrl(\Yii::$app->assetManager->getBundle(\skeeks\assets\unify\base\UnifyAsset::class), "assets/vendor/revolution-slider/revolution/js/extensions/");

\skeeks\assets\unify\base\UnifyRevolutionAsset::register($this);
\skeeks\assets\unify\base\revolution\UnifyRevolutionActionsAsset::register($this);
\skeeks\assets\unify\base\revolution\UnifyRevolutionNavigationAsset::register($this);
\skeeks\assets\unify\base\revolution\UnifyRevolutionLayeranimationAsset::register($this);
\skeeks\assets\unify\base\revolution\UnifyRevolutionCarouselAsset::register($this);
$this->registerJs(<<<JS
var tpj = jQuery;

    var revapi41;
    tpj(document).ready(function () {
      if (tpj('#rev_slider_41_1').revolution == undefined) {
        revslider_showDoubleJqueryError('#rev_slider_41_1');
      } else {
        revapi41 = tpj('#rev_slider_41_1').show().revolution({
          sliderType: 'carousel',
          jsFileLocation: '{$url}',
          sliderLayout: 'fullwidth',
          dottedOverlay: 'none',
          delay: 9000,
          navigation: {
            keyboardNavigation: 'off',
            keyboard_direction: 'horizontal',
            mouseScrollNavigation: 'off',
            mouseScrollReverse: 'default',
            onHoverStop: 'off',
            arrows: {
              style: 'metis',
              enable: true,
              hide_onmobile: true,
              hide_under: 768,
              hide_onleave: false,
              tmp: '',
              left: {
                h_align: 'left',
                v_align: 'center',
                h_offset: 0,
                v_offset: 0
              },
              right: {
                h_align: 'right',
                v_align: 'center',
                h_offset: 0,
                v_offset: 0
              }
            },
            thumbnails: {
              style: 'erinyen',
              enable: true,
              width: 150,
              height: 100,
              min_width: 150,
              wrapper_padding: 20,
              wrapper_color: 'rgba(0,0,0,0.05)',
              tmp: '<span class="tp-thumb-over"></span>' +
              '<span class="tp-thumb-image"></span>' +
              '<span class="tp-thumb-title">{{title}}</span>' +
              '<span class="tp-thumb-more"></span>',
              visibleAmount: 9,
              hide_onmobile: false,
              hide_onleave: false,
              direction: 'horizontal',
              span: true,
              position: 'outer-bottom',
              space: 10,
              h_align: 'center',
              v_align: 'bottom',
              h_offset: 0,
              v_offset: 0
            }
          },
          carousel: {
            maxRotation: 65,
            vary_rotation: 'on',
            minScale: 55,
            vary_scale: 'off',
            horizontal_align: 'center',
            vertical_align: 'center',
            fadeout: 'on',
            vary_fade: 'on',
            maxVisibleItems: 5,
            infinity: 'on',
            space: -150,
            stretch: 'off',
            showLayersAllTime: 'off',
            easing: 'Power3.easeInOut',
            speed: '800'
          },
          visibilityLevels: [1240, 1024, 778, 480],
          gridwidth: 600,
          gridheight: 600,
          lazyType: 'none',
          shadow: 0,
          spinner: 'off',
          stopLoop: 'on',
          stopAfterLoops: 0,
          stopAtSlide: 1,
          shuffle: 'off',
          autoHeight: 'off',
          disableProgressBar: 'on',
          hideThumbsOnMobile: 'off',
          hideSliderAtLimit: 0,
          hideCaptionAtLimit: 0,
          hideAllCaptionAtLilmit: 0,
          debugMode: false,
          fallbacks: {
            simplifyAll: 'off',
            nextSlideOnWindowFocus: 'off',
            disableFocusListener: false
          }
        });
        }
    });
JS
);
?>

<div id="rev_slider_41_1_wrapper" class="rev_slider_wrapper fullwidthbanner-container" style="background: #eef0f1; padding: 0; margin: 0 auto;"
     data-alias="food-carousel26"
     data-source="gallery">
    <div id="rev_slider_41_1" class="rev_slider fullwidthabanner" style="display: none;"
         data-version="5.4.1">
        <ul>
            <? if ($trees = $widget->activeQuery->all()) : ?>
                <? foreach ($trees as $key => $tree) : ?>
                    <? if ($tree->image) : ?>

                        <li data-index="rs-<?= $tree->id; ?>"
                            data-transition="fade"
                            data-slotamount="7"
                            data-hideafterloop="0"
                            data-hideslideonmobile="off"
                            data-easein="default"
                            data-easeout="default"
                            data-masterspeed="300"
                            data-thumb="<?= \Yii::$app->imaging->thumbnailUrlOnRequest($tree->image ? $tree->image->src : null,
                                new \skeeks\cms\components\imaging\filters\Thumbnail([
                                    'w' => 600,
                                    'h' => 400,
                                    'm' => \Imagine\Image\ImageInterface::THUMBNAIL_INSET,
                                ]), $tree->code
                            ); ?>"
                            data-rotate="0"
                            data-saveperformance="off"
                            data-title="<?= $tree->name; ?>">

                            <img class="rev-slidebg" src="<?= \Yii::$app->imaging->thumbnailUrlOnRequest($tree->image ? $tree->image->src : null,
                                new \skeeks\cms\components\imaging\filters\Thumbnail([
                                    'w' => 600,
                                    'h' => 400,
                                    'm' => \Imagine\Image\ImageInterface::THUMBNAIL_INSET,
                                ]), $tree->code
                            ); ?>" alt="<?= $tree->name; ?>"
                                 data-bgposition="center center"
                                 data-bgfit="contain"
                                 data-bgrepeat="no-repeat"
                                 style="cursor: pointer;"
                                 onclick="location.href='<?= $tree->url; ?>'; return false;"
                            />

                            <!-- LAYER NR. 2 -->
                            <a href="<?= $tree->url; ?>" id="slide-<?= $tree->id; ?>-layer-1" class="tp-caption FoodCarousel-Button rev-btn" style="z-index: 6; white-space: nowrap; text-transform: uppercase; outline: none; box-shadow: none; box-sizing: border-box; -moz-box-sizing: border-box; -webkit-box-sizing: border-box; cursor: pointer;"
                               data-x="center"
                               data-y="bottom"
                               data-voffset="50"
                               data-width="['auto']"
                               data-height="['auto']"
                               data-type="button"

                               data-responsive_offset="on"
                               data-responsive="off"
                               data-frames='[
                     {"from":"opacity:0;","speed":300,"to":"o:1;","delay":0,"ease":"Power3.easeInOut"},
                     {"delay":"wait","speed":300,"to":"opacity:0;","ease":"nothing"},
                     {"frame":"hover","speed":"300","ease":"Power1.easeInOut","to":"o:1;rX:0;rY:0;rZ:0;z:0;","style":"c:rgba(255,255,255,1);bg:rgba(41,46,49,1);bw:1px 1px 1px 1px;"}
                   ]'
                               data-textAlign="['left','left','left','left']"
                               data-paddingtop="[15,15,15,15]"
                               data-paddingright="[70,70,70,70]"
                               data-paddingbottom="[15,15,15,15]"
                               data-paddingleft="[50,50,50,50]"
                               data-lasttriggerstate="reset">
                                <i class="pe-7s-note2" style="font-size: 21px; float: left; margin-top: -6px; margin-right: 10px;"></i>
                                <?= $tree->name; ?>
                            </a>
                        </li>
                    <? endif; ?>


                <? endforeach; ?>
            <? endif; ?>

        </ul>
        <div class="tp-bannertimer tp-bottom" style="visibility: hidden !important;"></div>
    </div>
</div>