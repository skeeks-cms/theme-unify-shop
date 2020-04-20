<?php
/**
 * @link https://cms.skeeks.com/
 * @copyright Copyright (c) 2010 SkeekS
 * @license https://cms.skeeks.com/license/
 * @author Semenov Alexander <semenov@skeeks.com>
 */
/* @var $this yii\web\View */
/* @var $model \skeeks\cms\shop\models\ShopCmsContentElement */
/* @var $shopOfferChooseHelper \skeeks\cms\shop\helpers\ShopOfferChooseHelper */
/* @var $shopProduct \skeeks\cms\shop\models\ShopProduct */
/* @var $priceHelper \skeeks\cms\shop\helpers\ProductPriceHelper */

?>
<? if ($shopProduct->isSimpleProduct) : ?>

    <div class="product-price g-mt-10 g-mb-10" itemprop="offers" itemscope="" itemtype="http://schema.org/Offer">
        <link itemprop="url" href="<?= $model->absoluteUrl; ?>"/>
        <meta itemprop="price" content="<?= $priceHelper->basePrice->money->amount; ?>">
        <meta itemprop="priceCurrency" content="<?= $priceHelper->basePrice->money->currency->code; ?>">
        <meta itemprop="priceValidUntil" content="<?= date('Y-m-d', strtotime('+1 week')); ?>">
        <link itemprop="availability" href="http://schema.org/InStock">

        <? if ($priceHelper) : ?>
            <? if ($priceHelper->hasDiscount) : ?>
                <span class="current ss-price sx-old-price h3"><?= $priceHelper->basePrice->money; ?></span>
                <span class="current ss-price sx-new-price h1 g-color-primary">
                    <?= $priceHelper->minPrice->money; ?>
                    <? if ($shopProduct->measure_ratio != 1) : ?>
                        / <?= $shopProduct->measure->symbol; ?>
                    <? endif; ?>
                </span>
            <? else: ?>
                <? if ((float)$priceHelper->minPrice->money->amount > 0) : ?>
                    <span class="current ss-price sx-new-price h1 g-color-primary">
                        <?= $priceHelper->minPrice->money; ?>
                        <? if ($shopProduct->measure_ratio != 1) : ?>
                            / <?= $shopProduct->measure->symbol; ?>
                        <? endif; ?>
                    </span>
                <? endif; ?>
            <? endif; ?>
        <? endif; ?>
    </div>

    <div class="d-flex flex-row">
        <span class="d-flex flex-row sx-quantity-group">
            <div class="my-auto sx-minus">-</div>
            <div class="my-auto">
                <input
                        value="<?= $shopProduct->measure_ratio; ?>"
                        class="form-control sx-quantity-input"
                        data-measure_ratio="<?= $shopProduct->measure_ratio; ?>"
                />
            </div>
            <div class="my-auto sx-plus">+</div>
        </span>
        <div class="my-auto g-ml-10">
            <?= $shopProduct->measure->symbol; ?>
        </div>

        <? if ($shopProduct->measure_matches_jsondata) : ?>
            <? foreach ($shopProduct->measureMatches as $code => $count) : ?>
                <? $measure = \skeeks\cms\measure\models\CmsMeasure::find()->where(['code' => $code])->one(); ?>
                <? if ($shopProduct->measure_ratio >= $count) : ?>
                    <div class="my-auto g-ml-10">
                        =
                    </div>
                    <div class="my-auto g-ml-10">
                        <?
                        if ($count / $shopProduct->measure_ratio >= 1) {
                            echo $count / $shopProduct->measure_ratio;
                        } else {
                            echo round($shopProduct->measure_ratio / $count);
                        }
                        ?>
                        <?= $measure->symbol; ?>
                    </div>
                <? else: ?>
                    <div class="my-auto g-ml-10" style="color: gray; font-size: 12px;">
                        (1<?= $measure->symbol; ?> = <?= $count; ?><?= $shopProduct->measure->symbol; ?>)
                    </div>
                <? endif; ?>


            <? endforeach; ?>
        <? endif; ?>
    </div>

    <? if ($shopProduct->quantity > 0) : ?>
        <div class="g-mt-10">
            <div class="control-group group-submit g-mb-15">
                <div class="buttons-row ">
                    <? if ($shopProduct->minProductPrice && $shopProduct->minProductPrice->price == 0) : ?>
                        <? if (\Yii::$app->shop->is_show_button_no_price) : ?>
                            <?= \yii\helpers\Html::tag('button', '<i class="icon-cart"></i> '.\Yii::t('skeeks/unify-shop', 'Add to cart'), [
                                'class'   => 'btn btn-block btn-xxl u-btn-primary js-to-cart to-cart-fly-btn g-font-size-18',
                                'type'    => 'button',
                                'onclick' => new \yii\web\JsExpression("sx.Shop.addProduct({$shopProduct->id}, $('.sx-quantity-input').val()); return false;"),
                            ]); ?>
                        <? else : ?>
                            <a class="btn btn-block btn-xxl u-btn-primary g-font-size-18" href="#sx-order" data-toggle="modal">Оставить заявку</a>
                        <? endif; ?>
                    <? else : ?>
                        <?= \yii\helpers\Html::tag('button', '<i class="icon-cart"></i> '.\Yii::t('skeeks/unify-shop', 'Add to cart'), [
                            'class'   => 'btn btn-xxl btn-block u-btn-primary js-to-cart to-cart-fly-btn g-font-size-18',
                            'type'    => 'button',
                            'onclick' => new \yii\web\JsExpression("sx.Shop.addProduct({$shopProduct->id}, 1); return false;"),
                        ]); ?>
                    <? endif; ?>
                </div>
                <? if (\Yii::$app->shop->is_show_quantity_product) : ?>
                    <div class="availability-row available" style=""><!-- 'available' || 'not-available' || '' -->
                        <? if ($shopProduct->quantity > 10) : ?>
                            <span class="row-label"><?= \Yii::t("skeeks/unify-shop", "In stock over 10"); ?> <?= $shopProduct->measure->symbol; ?></span>
                        <? else : ?>
                            <span class="row-label"><?= \Yii::t("skeeks/unify-shop", "In stock"); ?>:</span> <span class="row-value"><?= $shopProduct->quantity; ?> <?= $shopProduct->measure->symbol; ?></span>
                        <? endif; ?>
                    </div>
                <? endif; ?>
            </div>
        </div>
    <? else : ?>
        <div class="product-control g-mt-10">
            <div class="control-group group-submit g-mr-10 g-mb-15">
                <div class="buttons-row ">
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
                        'toggleButton'     => [
                            'label' => 'Уведомить о поступлении',
                            'style' => '',
                            'class' => 'btn btn-primary btn-grey-white btn-52 js-out-click-btn btn-xxl g-font-size-18',
                        ],
                    ]); ?>
                </div>
                <div class="availability-row available" style="">
                    <span class="row-value">Товара нет</span>
                </div>
            </div>
        </div>
    <? endif; ?>

<? elseif ($shopProduct->isOffersProduct) : ?>
    <? if ($shopOfferChooseHelper->offerCmsContentElement) : ?>
        <?
        $offerShopProduct = $shopOfferChooseHelper->offerCmsContentElement->shopProduct;
        $priceHelper = \Yii::$app->shop->cart->getProductPriceHelper($shopOfferChooseHelper->offerCmsContentElement);
        ?>


        <div class="product-price g-mt-10 g-mb-10" itemprop="offers" itemscope="" itemtype="http://schema.org/Offer">
            <link itemprop="url" href="<?= $model->absoluteUrl; ?>"/>
            <meta itemprop="price" content="<?= $priceHelper->basePrice->money->amount; ?>">
            <meta itemprop="priceCurrency" content="<?= $priceHelper->basePrice->money->currency->code; ?>">
            <meta itemprop="priceValidUntil" content="<?= date('Y-m-d', strtotime('+1 week')); ?>">
            <link itemprop="availability" href="http://schema.org/InStock">
            <? if ($priceHelper) : ?>
                <? if ($priceHelper->hasDiscount) : ?>
                    <span class="current ss-price sx-old-price h3"><?= $priceHelper->basePrice->money; ?></span>
                    <span class="current ss-price sx-new-price h1 g-color-primary"><?= $priceHelper->minPrice->money; ?></span>
                <? else: ?>
                    <? if ((float)$priceHelper->minPrice->money->amount > 0) : ?>
                        <span class="current ss-price sx-new-price h1 g-color-primary"><?= $priceHelper->minPrice->money; ?></span>
                    <? endif; ?>
                <? endif; ?>
            <? endif; ?>
        </div>

        <?= $shopOfferChooseHelper->render(); ?>


        <? if ($offerShopProduct->quantity > 0) : ?>
            <div class="product-control g-mt-10">
                <div class="control-group group-submit g-mr-10 g-mb-15">
                    <div class="buttons-row ">
                        <? if ($offerShopProduct->minProductPrice && $offerShopProduct->minProductPrice->price == 0) : ?>
                            <? if (\Yii::$app->shop->is_show_button_no_price) : ?>
                                <?= \yii\helpers\Html::tag('button', '<i class="icon-cart"></i> '.\Yii::t('skeeks/unify-shop', 'Add to cart'), [
                                    'class'   => 'btn btn-xxl u-btn-primary js-to-cart to-cart-fly-btn g-font-size-18',
                                    'type'    => 'button',
                                    'onclick' => new \yii\web\JsExpression("sx.Shop.addProduct({$offerShopProduct->id}, 1); return false;"),
                                ]); ?>
                            <? else : ?>
                                <a class="btn btn-xxl u-btn-primary g-font-size-18" href="#sx-order" data-toggle="modal">Оставить заявку</a>

                            <? endif; ?>
                        <? else : ?>
                            <?= \yii\helpers\Html::tag('button', '<i class="icon-cart"></i> '.\Yii::t('skeeks/unify-shop', 'Add to cart'), [
                                'class'   => 'btn btn-xxl u-btn-primary js-to-cart to-cart-fly-btn g-font-size-18',
                                'type'    => 'button',
                                'onclick' => new \yii\web\JsExpression("sx.Shop.addProduct({$offerShopProduct->id}, 1); return false;"),
                            ]); ?>
                        <? endif; ?>
                    </div>
                    <? if (\Yii::$app->shop->is_show_quantity_product) : ?>
                        <div class="availability-row available" style=""><!-- 'available' || 'not-available' || '' -->
                            <? if ($offerShopProduct->quantity > 10) : ?>
                                <span class="row-label"><?= \Yii::t("skeeks/unify-shop", "In stock over 10"); ?> <?= $offerShopProduct->measure->symbol; ?></span>
                            <? else : ?>
                                <span class="row-label"><?= \Yii::t("skeeks/unify-shop", "In stock"); ?>:</span> <span class="row-value"><?= $offerShopProduct->quantity; ?> <?= $offerShopProduct->measure->symbol; ?></span>
                            <? endif; ?>
                        </div>
                    <? endif; ?>
                </div>
            </div>
        <? else : ?>
            <div class="product-control g-mt-10">
                <div class="control-group group-submit g-mr-10 g-mb-15">
                    <div class="buttons-row ">
                        <?= \skeeks\cms\shop\widgets\notice\NotifyProductEmailModalWidget::widget([
                            'view_file'        => '@app/views/widgets/NotifyProductEmailModalWidget/modalForm',
                            'product_id'       => $shopOfferChooseWidget->offerCmsContentElement->id,
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
                            'toggleButton'     => [
                                'label' => 'Уведомить о поступлении',
                                'style' => '',
                                'class' => 'btn btn-primary btn-xxl btn-grey-white btn-52 js-out-click-btn g-font-size-18',
                            ],
                        ]); ?>
                    </div>
                    <div class="availability-row available" style="">
                        <span class="row-value">Товара нет</span>
                    </div>
                </div>
            </div>
        <? endif; ?>


    <? endif; ?>


<? endif; ?>
