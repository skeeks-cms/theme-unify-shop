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
$this->registerJs(<<<JS
$("body").on("click", ".sx-not-select-offer", function() {
    var hasError = false;
    $("#sx-select-offer input").each(function() {
        if (!$(this).val()) {
            hasError = true;
            $(this).closest(".sx-choose-property-group").addClass("sx-need-select");
        }
    });
    if (hasError) {
        $(".sx-not-select-offer-message").show();
    } else {
        $(".sx-not-select-offer-message").hide();
    }
    return false;
});
JS
);
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

                <span class="current ss-price sx-new-price h1 g-color-primary" title="Ваша цена, по которой вы можете купить товар" data-toggle="tooltip">
                    <?= $priceHelper->minMoney; ?>
                    <? if ($shopProduct->measure_ratio != 1) : ?>
                        / <?= $shopProduct->measure->symbol; ?>
                    <? endif; ?>
                </span>
                <span class="current ss-price sx-old-price h3" title="Базовая цена, доступная для всех" data-toggle="tooltip"><?= $priceHelper->basePrice->money; ?></span>
                <?
                $info = [];
                foreach ($priceHelper->applyedDiscounts as $shopDiscount) {
                    $info[] = $shopDiscount->notes;
                }

                if ($canViewTypePrices = \Yii::$app->shop->canViewTypePrices) {
                    foreach ($canViewTypePrices as $canViewTypePrice) {
                        $p = $shopProduct->getShopProductPrices()->andWhere(['type_price_id' => $canViewTypePrice->id])->one();
                        $info[] = $p->money." — ".$canViewTypePrice->name;
                    }
                }
                $infoDisocount = implode("<br />", $info);
                ?>
                <span class="sx-price-info h4">
                            <i class="far fa-question-circle" title="<?php echo $infoDisocount; ?>" data-toggle="tooltip" data-html="true"></i>
                        </span>
            <? else: ?>
                <? if ((float)$priceHelper->minPrice->money->amount > 0) : ?>
                    <span class="current ss-price sx-new-price h1 g-color-primary" title="Ваша цена, по которой вы можете купить товар" data-toggle="tooltip">
                        <?= $priceHelper->minMoney; ?>
                        <? if ($shopProduct->measure_ratio != 1) : ?>
                            / <?= $shopProduct->measure->symbol; ?>
                        <? endif; ?>
                    </span>

                    <?php if ($canViewTypePrices = \Yii::$app->shop->canViewTypePrices) : ?>
                        <?php foreach ($canViewTypePrices as $canViewTypePrice) : ?>
                            <?php
                            $p = $shopProduct->getShopProductPrices()->andWhere(['type_price_id' => $canViewTypePrice->id])->one();
                            ?>
                            <?php if ($p && $canViewTypePrice->id != $priceHelper->minPrice->type_price_id) : ?>
                                <div class="current ss-price h3 sx-new-price sx-second-price"><span title="<b><?php echo $canViewTypePrice->name; ?></b><br><?php echo $canViewTypePrice->description; ?>" data-html="true"
                                                                                                    data-toggle="tooltip"><?= $p->money; ?></span>
                                    <span class="sx-price-info h4" title="<b><?php echo $canViewTypePrice->name; ?></b><br><?php echo $canViewTypePrice->description; ?>" data-html="true" data-toggle="tooltip">
                                        <i class="far fa-question-circle"></i>
                                    </span>
                                </div>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    <?php endif; ?>
                <? endif; ?>
            <? endif; ?>
        <? endif; ?>
    </div>

    <div class="sx-quantity-wrapper">
        <div class="d-flex flex-row">
        <span class="d-flex flex-row sx-quantity-group sx-main-quantity-group">
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
            <div class="my-auto sx-measure-symbol">
                <?= $shopProduct->measure->symbol; ?>
            </div>
        </div>

        <? if ($shopProduct->measure_matches_jsondata) : ?>
            <? foreach ($shopProduct->measureMatches as $code => $count) : ?>
                <? $measure = \skeeks\cms\measure\models\CmsMeasure::find()->where(['code' => $code])->one(); ?>
                <? if ($shopProduct->measure_ratio >= $count) : ?>
                    <div class="row">
                    <!--<div class="my-auto g-ml-10">
                        =
                    </div>-->
                    <div class="my-auto g-ml-10 d-flex flex-row">
                        <span class="d-flex flex-row sx-quantity-group sx-secondary-quantity-group">
                            <div class="my-auto sx-minus">-</div>
                            <div class="my-auto">
                                <input
                                        value="<?
                                        if ($count / $shopProduct->measure_ratio >= 1) {
                                            echo $count / $shopProduct->measure_ratio;
                                        } else {
                                            echo round($shopProduct->measure_ratio / $count);
                                        }
                                        ?>"
                                        class="form-control sx-quantity-input"
                                        data-measure_ratio="<?
                                        if ($count / $shopProduct->measure_ratio >= 1) {
                                            echo $count / $shopProduct->measure_ratio;
                                        } else {
                                            echo round($shopProduct->measure_ratio / $count);
                                        }
                                        ?>"
                                />
                            </div>
                            <div class="my-auto sx-plus">+</div>
                        </span>
                        <div class="my-auto g-ml-10">
                            <?= $measure->symbol; ?>
                        </div>
                    </div>
                <? else: ?>
                    <div class="my-auto g-ml-10" style="color: gray; font-size: 14px;">
                        в 1 <?= $measure->symbol; ?> <?= $count; ?> <?= $shopProduct->measure->symbol; ?>
                    </div>
                <? endif; ?>
                </div>


            <? endforeach; ?>
        <? endif; ?>
    </div>

    <? if ($shopProduct->quantity > 0) : ?>
        <div class="g-mt-10">
            <div class="control-group group-submit g-mb-15">
                <div class="buttons-row ">
                    <? if ($shopProduct->minProductPrice && $shopProduct->minProductPrice->price == 0) : ?>
                        <? if (\Yii::$app->skeeks->site->shopSite->is_show_button_no_price) : ?>
                            <?= \yii\helpers\Html::tag('button', '<i class="icon-cart"></i> '.\Yii::t('skeeks/unify-shop', 'Add to cart'), [
                                'class'   => 'btn btn-block btn-xxl btn-primary js-to-cart to-cart-fly-btn g-font-size-18',
                                'type'    => 'button',
                                'onclick' => new \yii\web\JsExpression("sx.Shop.addProduct({$shopProduct->id}, $('.sx-quantity-input').val()); return false;"),
                            ]); ?>
                        <? else : ?>
                            <a class="btn btn-block btn-xxl btn-primary g-font-size-18" href="#sx-order" data-toggle="modal">Оставить заявку</a>
                        <? endif; ?>
                    <? else : ?>
                        <?= \yii\helpers\Html::tag('button', '<i class="icon-cart"></i> '.\Yii::t('skeeks/unify-shop', 'Add to cart'), [
                            'class'   => 'btn btn-xxl btn-block btn-primary js-to-cart to-cart-fly-btn g-font-size-18',
                            'type'    => 'button',
                            'onclick' => new \yii\web\JsExpression("sx.Shop.addProduct({$shopProduct->id}, $('.sx-quantity-input').val()); return false;"),
                        ]); ?>
                    <? endif; ?>
                </div>

                <?
                echo $this->render("@app/views/modules/cms/content-element/_product-quantity", [
                    'shopProduct'           => $shopProduct,
                ]); ?>
            </div>
        </div>
    <? else : ?>
        <div class="g-mt-10">
            <div class="control-group group-submit g-mb-15">
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
                            'class' => 'btn btn-primary btn-block btn-grey-white btn-52 js-out-click-btn btn-xxl g-font-size-18',
                        ],
                    ]); ?>
                </div>
                <div class="availability-row available" style="">
                    <span class="row-value">Товара нет</span>
                </div>
            </div>
        </div>
    <? endif; ?>

<? elseif ($shopProduct->isOffersProduct || $shopProduct->isOfferProduct) : ?>

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
                    <span class="current ss-price sx-new-price h1 g-color-primary" title="Ваша цена, по которой вы можете купить товар" data-toggle="tooltip"><?= $priceHelper->minMoney; ?></span>
                    <span class="current ss-price sx-old-price h3" title="Базовая цена, доступная для всех" data-toggle="tooltip"><?= $priceHelper->basePrice->money; ?></span>
                    <?
                    $info = [];
                    foreach ($priceHelper->applyedDiscounts as $shopDiscount) {
                        $info[] = $shopDiscount->notes;
                    }

                    if ($canViewTypePrices = \Yii::$app->shop->canViewTypePrices) {
                        foreach ($canViewTypePrices as $canViewTypePrice) {
                            $p = $shopProduct->getShopProductPrices()->andWhere(['type_price_id' => $canViewTypePrice->id])->one();
                            if ($p) {
                                $info[] = $p->money." — ".$canViewTypePrice->name;
                            }

                        }
                    }
                    $infoDisocount = implode("<br />", $info);
                    ?>
                    <span class="sx-price-info h4">
                            <i class="far fa-question-circle" title="<?php echo $infoDisocount; ?>" data-toggle="tooltip" data-html="true"></i>
                        </span>
                <? else: ?>
                    <? if ((float)$priceHelper->minPrice->money->amount > 0) : ?>
                        <span class="current ss-price sx-new-price h1 g-color-primary" title="Ваша цена, по которой вы можете купить товар" data-toggle="tooltip"><?= $priceHelper->minMoney; ?></span>

                        <?php if ($canViewTypePrices = \Yii::$app->shop->canViewTypePrices) : ?>
                            <?php foreach ($canViewTypePrices as $canViewTypePrice) : ?>
                                <?php
                                $p = $offerShopProduct->getShopProductPrices()->andWhere(['type_price_id' => $canViewTypePrice->id])->one();
                                ?>
                                <?php if ($p && $canViewTypePrice->id != $priceHelper->minPrice->type_price_id) : ?>
                                    <div class="current ss-price h3 sx-new-price sx-second-price"><span title="<b><?php echo $canViewTypePrice->name; ?></b><br><?php echo $canViewTypePrice->description; ?>"
                                                                                                        data-html="true" data-toggle="tooltip"><?= $p->money; ?></span>
                                        <span class="sx-price-info h4" title="<b><?php echo $canViewTypePrice->name; ?></b><br><?php echo $canViewTypePrice->description; ?>" data-html="true" data-toggle="tooltip">
                                            <i class="far fa-question-circle"></i>
                                        </span>
                                    </div>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        <?php endif; ?>

                    <? endif; ?>
                <? endif; ?>
            <? endif; ?>
        </div>

        <?= $shopOfferChooseHelper->render(); ?>


        <? if ($offerShopProduct->quantity > 0) : ?>


            <div class="sx-quantity-wrapper">
                <div class="d-flex flex-row">
                <span class="d-flex flex-row sx-quantity-group">
                    <div class="my-auto sx-minus">-</div>
                    <div class="my-auto">
                        <input
                                value="<?= $offerShopProduct->measure_ratio; ?>"
                                class="form-control sx-quantity-input"
                                data-measure_ratio="<?= $offerShopProduct->measure_ratio; ?>"
                        />
                    </div>
                    <div class="my-auto sx-plus">+</div>
                </span>
                    <div class="my-auto sx-measure-symbol">
                        <?= $offerShopProduct->measure->symbol; ?>
                    </div>
                </div>

                <? if ($shopProduct->measure_matches_jsondata) : ?>
                    <? foreach ($shopProduct->measureMatches as $code => $count) : ?>
                        <? $measure = \skeeks\cms\measure\models\CmsMeasure::find()->where(['code' => $code])->one(); ?>
                        <? if ($shopProduct->measure_ratio >= $count) : ?>
                            <div class="my-auto g-ml-10">
                                =
                            </div>
                            <div class="my-auto g-ml-10 d-flex flex-row">
                        <span class="d-flex flex-row sx-quantity-group sx-secondary-quantity-group">
                            <div class="my-auto sx-minus">-</div>
                            <div class="my-auto">
                                <input
                                        value="<?
                                        if ($count / $shopProduct->measure_ratio >= 1) {
                                            echo $count / $shopProduct->measure_ratio;
                                        } else {
                                            echo round($shopProduct->measure_ratio / $count);
                                        }
                                        ?>"
                                        class="form-control sx-quantity-input"
                                        data-measure_ratio="<?
                                        if ($count / $shopProduct->measure_ratio >= 1) {
                                            echo $count / $shopProduct->measure_ratio;
                                        } else {
                                            echo round($shopProduct->measure_ratio / $count);
                                        }
                                        ?>"
                                />
                            </div>
                            <div class="my-auto sx-plus">+</div>
                        </span>
                                <div class="my-auto g-ml-10">
                                    <?= $measure->symbol; ?>
                                </div>
                            </div>
                        <? else: ?>
                            <div class="my-auto g-ml-10" style="color: gray; font-size: 12px;">
                                в 1 <?= $measure->symbol; ?> <?= $count; ?> <?= $shopProduct->measure->symbol; ?>
                            </div>
                        <? endif; ?>


                    <? endforeach; ?>
                <? endif; ?>
            </div>


            <div class="g-mt-10">
                <div class="control-group group-submit g-mb-15">
                    <div class="buttons-row ">
                        <? if ($offerShopProduct->minProductPrice && $offerShopProduct->minProductPrice->price == 0) : ?>
                            <? if (\Yii::$app->skeeks->site->shopSite->is_show_button_no_price) : ?>
                                <?= \yii\helpers\Html::tag('button', '<i class="icon-cart"></i> '.\Yii::t('skeeks/unify-shop', 'Add to cart'), [
                                    'class'   => 'btn btn-xxl btn-block btn-primary js-to-cart to-cart-fly-btn g-font-size-18',
                                    'type'    => 'button',
                                    'onclick' => new \yii\web\JsExpression("sx.Shop.addProduct({$offerShopProduct->id}, $('.sx-quantity-input').val()); return false;"),
                                ]); ?>
                            <? else : ?>
                                <a class="btn btn-xxl btn-block btn-block btn-primary g-font-size-18" href="#sx-order" data-toggle="modal">Оставить заявку</a>
                            <? endif; ?>
                        <? else : ?>
                            <?= \yii\helpers\Html::tag('button', '<i class="icon-cart"></i> '.\Yii::t('skeeks/unify-shop', 'Add to cart'), [
                                'class'   => 'btn btn-xxl btn-block btn-primary js-to-cart to-cart-fly-btn g-font-size-18',
                                'type'    => 'button',
                                'onclick' => new \yii\web\JsExpression("sx.Shop.addProduct({$offerShopProduct->id}, $('.sx-quantity-input').val()); return false;"),
                            ]); ?>
                        <? endif; ?>
                    </div>

                    <?
                    echo $this->render("@app/views/modules/cms/content-element/_product-quantity", [
                        'shopProduct'           => $offerShopProduct,
                    ]); ?>
                </div>
            </div>
        <? else : ?>
            <div class="g-mt-10">
                <div class="control-group group-submit g-mb-15">
                    <div class="buttons-row ">
                        <? /*= \skeeks\cms\shop\widgets\notice\NotifyProductEmailModalWidget::widget([
                            'view_file'        => '@app/views/widgets/NotifyProductEmailModalWidget/modalForm',
                            'product_id'       => $offerShopProduct->id,
                            'size'             => "modal-dialog-350",
                            'success_modal_id' => 'readySubscribeModal',
                            'id'               => 'modalWait',
                            'class'            => 'b-modal b-modal-wait',
                            'toggleButton'     => [
                                'label' => 'Уведомить о поступлении',
                                'style' => '',
                                'class' => 'btn btn-primary btn-block btn-xxl btn-grey-white btn-52 js-out-click-btn g-font-size-18',
                            ],
                        ]); */ ?>
                    </div>
                    <div class="availability-row available" style="">
                        <span class="row-value">Товара нет</span>
                    </div>
                </div>
            </div>
        <? endif; ?>


    <? else : ?>
        <div class="product-price g-mb-10" itemprop="offers" itemscope="" itemtype="http://schema.org/Offer">
            <? if ($priceHelper) : ?>
                <div class="">
                    <? if ($priceHelper->hasDiscount) : ?>
                        <span class="current ss-price h1 sx-new-price g-color-primary" title="Ваша цена, по которой вы можете купить товар" data-toggle="tooltip"><?= \Yii::t('skeeks/unify-shop',
                                'from'); ?> <?= $priceHelper->minMoney; ?></span>
                        <span class="current ss-price h3 sx-old-price" title="Базовая цена, доступная для всех" data-toggle="tooltip"><?= \Yii::t('skeeks/unify-shop',
                                'from'); ?> <?= $priceHelper->basePrice->money; ?></span>
                        <?
                        $info = [];
                        foreach ($priceHelper->applyedDiscounts as $shopDiscount) {
                            $info[] = $shopDiscount->notes;
                        }

                        if ($canViewTypePrices = \Yii::$app->shop->canViewTypePrices) {
                            foreach ($canViewTypePrices as $canViewTypePrice) {
                                $p = $shopProduct->getShopProductPrices()->andWhere(['type_price_id' => $canViewTypePrice->id])->one();
                                $info[] = $p->money." — ".$canViewTypePrice->name;
                            }
                        }
                        $infoDisocount = implode("<br />", $info);
                        ?>
                        <span class="sx-price-info h4">
                            <i class="far fa-question-circle" title="<?php echo $infoDisocount; ?>" data-toggle="tooltip" data-html="true"></i>
                        </span>
                    <? else: ?>
                        <? if ((float)$priceHelper->minPrice->money->amount > 0) : ?>
                            <span class="current ss-price h1 sx-new-price g-color-primary" title="Ваша цена, по которой вы можете купить товар" data-toggle="tooltip"><?= \Yii::t('skeeks/unify-shop',
                                    'from'); ?> <?= $priceHelper->minMoney; ?></span>

                            <?php if ($canViewTypePrices = \Yii::$app->shop->canViewTypePrices) : ?>
                                <?php foreach ($canViewTypePrices as $canViewTypePrice) : ?>
                                    <?php
                                    $p = $shopProduct->getShopProductPrices()->andWhere(['type_price_id' => $canViewTypePrice->id])->one();
                                    ?>
                                    <?php if ($p && $canViewTypePrice->id != $priceHelper->minPrice->type_price_id) : ?>
                                        <div class="current ss-price h3 sx-new-price sx-second-price"><span title="<b><?php echo $canViewTypePrice->name; ?></b><br><?php echo $canViewTypePrice->description; ?>"
                                                                                                            data-html="true" data-toggle="tooltip"><?= \Yii::t('skeeks/unify-shop', 'from'); ?> <?= $p->money; ?></span>
                                            <span class="sx-price-info h4" title="<b><?php echo $canViewTypePrice->name; ?></b><br><?php echo $canViewTypePrice->description; ?>" data-html="true" data-toggle="tooltip">
                                                <i class="far fa-question-circle"></i>
                                            </span>
                                        </div>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            <?php endif; ?>

                        <? endif; ?>
                    <? endif; ?>
                </div>
            <? endif; ?>
        </div>
        <?= $shopOfferChooseHelper->render(); ?>

        <div class="g-mt-10">
            <div class="sx-not-select-offer-message" style="display: none; color: red; text-align: center; padding-bottom: 5px;">Уточните ваш выбор</div>
            <div class="control-group group-submit g-mb-15">
                <div class="buttons-row ">
                    <?= \yii\helpers\Html::tag('button', '<i class="icon-cart"></i> '.\Yii::t('skeeks/unify-shop', 'Add to cart'), [
                        'class' => 'btn btn-xxl btn-block btn-primary g-font-size-18 disabled sx-not-select-offer',
                        'type'  => 'button',
                    ]); ?>
                </div>
            </div>
        </div>
    <? endif; ?>


<? endif; ?>
