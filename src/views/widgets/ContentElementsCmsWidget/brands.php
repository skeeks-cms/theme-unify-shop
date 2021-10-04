<?php
/**
 * @author Semenov Alexander <semenov@skeeks.com>
 * @link http://skeeks.com/
 * @copyright 2010 SkeekS (СкикС)
 * @date 25.05.2015
 */
/* @var $this   yii\web\View */
/* @var $widget \skeeks\cms\cmsWidgets\contentElements\ContentElementsCmsWidget */
\skeeks\cms\themes\unify\assets\components\UnifyThemeStickAsset::register($this);

$query = $widget->dataProvider->query;
?>
<? if ($query->count()) : ?>

    <div class="sx-products-slider-wrapper sx-brand-slider-wrapper">
        <? if ($widget->label) : ?>
            <div class="sx-products-slider--title sx-products-slider--title">
                <div class="h2"><?= $widget->label; ?></div>
                <!--<p class="lead">We want to create a range of beautiful, practical and modern outerwear that doesn't cost the earth – but let's you still live life in style.</p>-->
            </div>
        <? endif; ?>


        <? echo \yii\widgets\ListView::widget([
            'dataProvider' => $widget->dataProvider,
            'itemView'     => '_brand-item',
            'emptyText'    => '',
            'itemOptions'  => \yii\helpers\ArrayHelper::merge([
                'tag'   => 'div',
                'class' => 'sx-brand-card-wrapper my-auto',
            ], (array)@$itemOptions),
            'options'      => [
                'class' => 'js-carousel sx-stick sx-products-stick',
                'tag'   => 'div',
                'data'  => [
                    'slidesToShow' => (int)\Yii::$app->unifyShopTheme->product_slider_items,
                    'autoplay'   => "1",
                    'infinite'   => "1",
                    'responsive'   => [
                        [
                            'breakpoint' => 2600,
                            'settings'   => [
                                'slidesToShow' => 5,
                            ],
                        ],
                        [
                            'breakpoint' => 1025,
                            'settings'   => [
                                'slidesToShow' => 5,
                            ],
                        ],
                        [
                            'breakpoint' => 480,
                            'settings'   => [
                                'slidesToShow' => 2,
                            ],
                        ],
                        [
                            'breakpoint' => 376,
                            'settings'   => [
                                'slidesToShow' => 2,
                            ],
                        ],

                    ],

                    'arrows-classes'      => "g-color-primary--hover sx-arrows sx-color-silver",
                    'arrow-left-classes'  => "hs-icon hs-icon-arrow-left sx-left sx-minus-20 d-none d-sm-block",
                    'arrow-right-classes' => "hs-icon hs-icon-arrow-right sx-right sx-minus-20 d-none d-sm-block",
                ],
            ],
            'layout'       => '{items}',


        ]) ?>
    </div>
<? endif; ?>
