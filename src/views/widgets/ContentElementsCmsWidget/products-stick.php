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
if (!\Yii::$app->skeeks->site->shopSite->is_show_product_no_price) {
    $query->joinWith('shopProduct.shopProductPrices as pricesFilter');
    $query->andWhere(['>', '`pricesFilter`.price', 0]);
}

?>
<? if ($query->count()) : ?>

    <? if ($widget->label) : ?>
        <div class="text-center mx-auto g-max-width-600 g-mb-20">
            <div class="h2 mb-4"><?= $widget->label; ?></div>
            <!--<p class="lead">We want to create a range of beautiful, practical and modern outerwear that doesn't cost the earth – but let's you still live life in style.</p>-->
        </div>
    <? endif; ?>

    <? echo \yii\widgets\ListView::widget([
        'dataProvider' => $widget->dataProvider,
        'itemView'     => 'product-item',
        'emptyText'    => '',
        'itemOptions'  => \yii\helpers\ArrayHelper::merge([
            'tag'   => 'div',
            'class' => 'sx-product-card-wrapper',
        ], (array)@$itemOptions),
        'options'      => [
            'class' => 'js-carousel sx-stick sx-products-stick',
            'tag'   => 'div',
            'data'  => [
                'slidesToShow' => (int)\Yii::$app->unifyShopTheme->product_slider_items,
                'responsive'   => [
                    [
                        'breakpoint' => 2600,
                        'settings'   => [
                            'slidesToShow' => (int)\Yii::$app->unifyShopTheme->product_slider_items,
                        ],
                    ],
                    [
                        'breakpoint' => 1025,
                        'settings'   => [
                            'slidesToShow' => 4,
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

<? endif; ?>