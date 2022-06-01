<?php
/**
 * @link https://cms.skeeks.com/
 * @copyright Copyright (c) 2010 SkeekS
 * @license https://cms.skeeks.com/license/
 * @author Semenov Alexander <semenov@skeeks.com>
 */
/* @var $this yii\web\View */

\skeeks\cms\shop\widgets\ShopGlobalWidget::widget();

\skeeks\cms\themes\unifyshop\assets\components\ShopUnifyCartV2PageAsset::register($this);

$this->registerJs(<<<JS
    (function(sx, $, _)
    {
        new sx.classes.cartv2.Checkout();
    })(sx, sx.$, sx._);
JS
);
$shopOrder = \Yii::$app->shop->shopUser->shopOrder;

//Установка недостающих данных по умолчанию
if (!$shopOrder->cms_user_id && !\Yii::$app->user->isGuest) {
    $shopOrder->cms_user_id = \Yii::$app->user->id;
    $shopOrder->save(true, ['cms_user_id']);
}

$deliveries = \skeeks\cms\shop\models\ShopDelivery::getAllowForOrder();
/*if (!$shopOrder->shop_delivery_id && $deliveries) {
    $firstDelivery = $deliveries[0];
    $shopOrder->shop_delivery_id = $firstDelivery->id;
    $shopOrder->save(true, ['shop_delivery_id']);
}

if (!$shopOrder->shop_pay_system_id && $shopOrder->paySystems) {
    $firstPaySystem = $shopOrder->paySystems[0];
    $shopOrder->shop_pay_system_id = $firstPaySystem->id;
    $shopOrder->save(true, ['shop_pay_system_id']);
}*/
$this->registerJs(<<<JS
$("body").on("click", ".sx-remove-order-item", function() {
    var jItem = $(this).closest(".sx-order-item");
    var ID = jItem.data("id");

    sx.block(jItem);

    var ajaxQuery = sx.Shop.createAjaxRemoveBasket(ID);
    ajaxQuery.onSuccess(function (e, data) {
        jItem.slideUp();
    });
    ajaxQuery.execute();
});
JS
);

?>
<? /* \skeeks\cms\widgets\Pjax::begin([
    'id' => 'sx-cart-full',
]); */ ?>
<div id="sx-cart-v2">
    <!--=== Content Part ===-->
    <section class="sx-cart-layout">
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-6 sx-order-col-left">
                    <div class="sx-inner-col-wrapper sx-project-form-wrapper">


                        <div class="sx-checkout-block">
                            <div class="h5 sx-checkout-block-title">1. Данные покупателя</div>

                            <?php if (\Yii::$app->user->isGuest) : ?>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group field-relatedpropertiesmodel-name js-float-label-wrapper">
                                            <label>Имя</label>
                                            <input type="text" class="form-control sx-save-after-change" data-field="contact_first_name" value="<?php echo $shopOrder->contact_first_name ; ?>" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group field-relatedpropertiesmodel-name js-float-label-wrapper">
                                            <label>Фамилия</label>
                                            <input type="text" class="form-control sx-save-after-change" data-field="contact_last_name" value="<?php echo $shopOrder->contact_last_name ; ?>"/>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group field-relatedpropertiesmodel-name js-float-label-wrapper">
                                            <label>Телефон</label>
                                            <input type="text" id='sx-phone' class="form-control sx-save-after-change" data-field="contact_phone" value="<?php echo $shopOrder->contact_phone ; ?>"/>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group field-relatedpropertiesmodel-name js-float-label-wrapper">
                                            <label>Email</label>
                                            <input type="text" class="form-control sx-save-after-change" data-field="contact_email" value="<?php echo $shopOrder->contact_email ; ?>"/>
                                        </div>
                                    </div>
                                </div>

                            <?php else : ?>

                                <div class="row">
                                    <div class="col-12">
                                        <div class="sx-small-info">
                                            Вы авторизованы на сайте, данные покупателя подставлены из вашего профиля и редактируются в личном кабинете!
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group field-relatedpropertiesmodel-name js-float-label-wrapper">
                                            <label>Имя</label>
                                            <input type="text" <?php echo \Yii::$app->user->identity->first_name ? "disabled" : ""; ?> class="form-control" value="<?php echo \Yii::$app->user->identity->first_name; ?>"/>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group field-relatedpropertiesmodel-name js-float-label-wrapper">
                                            <label>Фамилия</label>
                                            <input type="text" <?php echo \Yii::$app->user->identity->last_name ? "disabled" : ""; ?> class="form-control" value="<?php echo \Yii::$app->user->identity->last_name; ?>"/>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group field-relatedpropertiesmodel-name js-float-label-wrapper">
                                            <label>Телефон</label>
                                            <input type="text" <?php echo \Yii::$app->user->identity->phone ? "disabled" : ""; ?> id='sx-phone' class="form-control"
                                                   value="<?php echo \Yii::$app->user->identity->phone; ?>"/>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group field-relatedpropertiesmodel-name js-float-label-wrapper">
                                            <label>Email</label>
                                            <input type="text" <?php echo \Yii::$app->user->identity->email ? "disabled" : ""; ?> class="form-control" value="<?php echo \Yii::$app->user->identity->email; ?>"/>
                                        </div>
                                    </div>
                                </div>

                            <?php endif; ?>
                        </div>

                        <div class="sx-checkout-block sx-delivery-block" id="sx-delivery-block">
                            <div class="h5 sx-checkout-block-title">2. Способ получения</div>

                            <div class="row">
                                <?php if ($deliveries) : ?>
                                    <?php foreach ($deliveries as $delivery) : ?>
                                        <div class="col-md-6 col-12">
                                            <div class="btn btn-block btn-check sx-delivery <?php echo $shopOrder->shopDelivery->id == $delivery->id ? "sx-checked" : ""; ?>" data-id="<?php echo $delivery->id; ?>">
                                                <span class="sx-checked-icon" data-icon="✓">
                                                    <?php echo $shopOrder->shopDelivery->id == $delivery->id ? "✓" : ""; ?>
                                                </span>
                                                <span class="sx-delivery-name">
                                            <?php echo $delivery->name; ?>
                                        </span>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                <?php else : ?>
                                    <p>Магазин не настроен!</p>
                                <?php endif; ?>

                            </div>
                        </div>

                        <div class="sx-checkout-block sx-paysystem-block" id="sx-paysystem-block">
                            <div class="h5 sx-checkout-block-title">3. Способ оплаты</div>
                            <div class="row">
                                <?php if ($shopOrder->paySystems) : ?>
                                    <?php foreach ($shopOrder->paySystems as $paySystem) : ?>
                                        <div class="col-md-6 col-12">
                                            <div class="btn btn-block btn-check sx-paysystem <?php echo $shopOrder->shopPaySystem->id == $paySystem->id ? "sx-checked" : ""; ?>" data-id="<?php echo $paySystem->id; ?>">
                                                <span class="sx-checked-icon" data-icon="✓">
                                                    <?php echo $shopOrder->shopPaySystem->id == $paySystem->id ? "✓" : ""; ?>
                                                </span>
                                                <span class="sx-paysystem-name">
                                            <?php echo $paySystem->name; ?>
                                        </span>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                <?php else : ?>
                                    <p>Магазин не настроен!</p>
                                <?php endif; ?>
                            </div>

                        </div>

                        <div class="sx-checkout-block sx-paysystem-block" id="sx-comment-block">
                            <div class="h5 sx-checkout-block-title">4. Комментарий к заказу</div>
                            <div class="row">
                                <div class="col-12">
                                    <!--<div class="form-group field-relatedpropertiesmodel-name js-float-label-wrapper">
                                        <label>Комментарий к заказу</label>-->
                                    <textarea class="form-control sx-save-after-change"
                                              data-field="comment"
                                              placeholder="В этом поле вы можете указать комментарий к заказу в свободной форме. Поле необязательно к заполнению."
                                              rows="4"
                                    ><?php echo $shopOrder->comment; ?></textarea>
                                    <!--</div>-->
                                </div>
                            </div>

                        </div>


                        <? /* $checkout = \skeeks\cms\shopCheckout\ShopCheckoutWidget::begin([
                        'isAutoUserRegister'      => true,
                        //'viewFile'      => "@app/views/widgets/cms-shop-checkout-widget/default",
                        'btnSubmitWrapperOptions' => [
                            'style' => 'display: none;',
                        ],
                    ]); */ ?><!--
                    --><? /* $checkout::end(); */ ?>

                    </div>
                </div>
                <div class="col-md-6 sx-order-col-right">
                    <div class="sx-inner-col-wrapper">
                        <? if (!\Yii::$app->shop->shopUser->shopOrder->shopOrderItems) : ?>
                            <div class="col-12 my-auto">
                                <!-- EMPTY CART -->
                                <div class="my-auto">
                                    <div class="">
                                        <div class="h2">Ваша корзина пуста!</div>
                                        В вашей корзине нет товаров.<br/>
                                    </div>
                                </div>
                                <!-- /EMPTY CART -->
                            </div>
                        <? else: ?>
                            <!-- LEFT -->
                            <div class="col-12">

                                <div class="h5">Товары <small style="color: silver; font-size: 12px;">(Заказ №<?php echo \Yii::$app->shop->shopUser->shopOrder->id; ?>)</small></div>


                                <div class="sx-order-items">
                                    <?php echo $this->render("@app/views/modules/shop/cart/_cart-order-items-v2"); ?>
                                </div>

                            </div>

                            <div class="col-12" style="margin-bottom: 20px;">
                                <?= \skeeks\cms\shopDiscountCoupon\ShopDiscountCouponWidget::widget([
                                    'couponInputOptions' => [
                                        'placeholder' => "У вас есть промокод?",
                                    ],
                                    'btnSubmitName'      => 'Применить',
                                ]); ?>
                            </div>

                            <div class="sx-order-result">
                                <div class="col-12 sx-order-result-block <?php echo \Yii::$app->shop->shopUser->shopOrder->moneyItems->amount > 0 ? "" : "sx-hidden"; ?>">
                                    <div class="float-right sx-money-items" data-value="<?= (float)\Yii::$app->shop->shopUser->shopOrder->moneyItems->amount; ?>">
                                        <?= \Yii::$app->shop->shopUser->shopOrder->moneyItems; ?>
                                    </div>
                                    <div class="pull-left">Сумма</div>
                                </div>
                                <div class="col-12 sx-order-result-block <?php echo \Yii::$app->shop->shopUser->moneyDelivery->amount > 0 ? "" : "sx-hidden"; ?>">
                                    <div class="float-right sx-money-delivery" data-value="<?= (float)\Yii::$app->shop->shopUser->shopOrder->moneyDelivery->amount; ?>">
                                        <?= \Yii::$app->shop->shopUser->shopOrder->moneyDelivery; ?>
                                    </div>
                                    <div class="pull-left">Доставка</div>
                                </div>
                                <div class="col-12 sx-order-result-block <?php echo \Yii::$app->shop->shopUser->moneyVat->amount > 0 ? "" : "sx-hidden"; ?>">
                                    <div class="float-right sx-money-vat" data-value="<?= (float)\Yii::$app->shop->shopUser->shopOrder->moneyVat->amount; ?>">
                                        <?= \Yii::$app->shop->shopUser->shopOrder->moneyVat; ?>
                                    </div>
                                    <div class="pull-left">Налог</div>
                                </div>
                                <div class="col-12 sx-order-result-block <?php echo \Yii::$app->shop->shopUser->moneyDiscount->amount > 0 ? "" : "sx-hidden"; ?>">
                                    <div class="float-right sx-money-discount" data-value="<?= (float)\Yii::$app->shop->shopUser->shopOrder->moneyDiscount->amount; ?>">
                                        <?= \Yii::$app->shop->shopUser->shopOrder->moneyDiscount; ?>
                                    </div>
                                    <div class="pull-left">Скидка</div>
                                </div>
                                <div class="col-12 sx-order-result-block <?php echo \Yii::$app->shop->shopUser->shopOrder->weight > 0 ? "" : "sx-hidden"; ?>">
                                    <div class="float-right sx-weight" data-value="<?= (float)\Yii::$app->shop->shopUser->shopOrder->weight; ?>">
                                        <?= \Yii::$app->shop->shopUser->shopOrder->weightFormatted; ?>
                                    </div>
                                    <div class="pull-left">Вес</div>
                                </div>
                                <div class="g-my-10 col-12 sx-order-result-itogo">
                                    <div class="float-right size-20 sx-money" data-value="<?= (float)\Yii::$app->shop->shopUser->shopOrder->money->amount; ?>">
                                        <?= \Yii::$app->shop->shopUser->shopOrder->money; ?>
                                    </div>
                                    <div class="pull-left">Итого</div>
                                </div>
                            </div>

                            <div class="col-12">
                                <a href="#" class="btn btn-xxl btn-block btn-primary btn-submit-order" data-pjax="0">
                                    Оформить заказ
                                </a>
                            </div>

                        <? endif; ?>

                    </div>
                </div>
            </div>
        </div>
    </section>
    <? /* \skeeks\cms\widgets\Pjax::end(); */ ?>
</div>
