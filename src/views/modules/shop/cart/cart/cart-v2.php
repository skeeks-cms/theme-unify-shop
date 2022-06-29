<?php
/**
 * @link https://cms.skeeks.com/
 * @copyright Copyright (c) 2010 SkeekS
 * @license https://cms.skeeks.com/license/
 * @author Semenov Alexander <semenov@skeeks.com>
 */
/* @var $this yii\web\View */
/* @var $deliveries \skeeks\cms\shop\models\ShopDelivery[] */

\skeeks\cms\shop\widgets\ShopGlobalWidget::widget();

\skeeks\cms\themes\unifyshop\assets\components\ShopUnifyCartV2PageAsset::register($this);

$jsData = \yii\helpers\Json::encode([
    'checkout_backend'               => \yii\helpers\Url::to(['/shop/cart/order-checkout']),
    'order_free_shipping_from_price' => \Yii::$app->skeeks->site->shopSite->order_free_shipping_from_price,
]);

$this->registerJs(<<<JS
    (function(sx, $, _)
    {
        sx.Checkout = new sx.classes.cartv2.Checkout({$jsData});
    })(sx, sx.$, sx._);
JS
);
$shopOrder = \Yii::$app->shop->shopUser->shopOrder;

//Установка недостающих данных по умолчанию
if (!$shopOrder->cms_user_id && !\Yii::$app->user->isGuest) {
    $shopOrder->cms_user_id = \Yii::$app->user->id;
    $shopOrder->save(true, ['cms_user_id']);
}

$deliveries = \skeeks\cms\shop\models\ShopDelivery::find()->cmsSite()->active()->sort()->all();

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
        <div class="sx-hidden">
            <div id="sx-money-zero"><?php echo new \skeeks\cms\money\Money("0", $shopOrder->currency_code); ?></div>
        </div>
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-6 sx-order-col-left">
                    <div class="sx-inner-col-wrapper sx-project-form-wrapper">


                        <div id="sx-client-block" class="sx-checkout-block sx-client-block">
                            <div class="h5 sx-checkout-block-title">
                                1. Покупатель

                                <?php if ($this->theme->cart_contact_text) : ?>
                                    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@48,400,0,0"/>
                                    <div class="material-symbols-outlined" data-toggle="tooltip" data-html="true" title="<?php echo $this->theme->cart_contact_text; ?>">
                                        help
                                    </div>
                                <?php endif; ?>

                            </div>

                            <?php if (\Yii::$app->user->isGuest) : ?>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group js-float-label-wrapper">
                                            <label>Имя</label>
                                            <input type="text" class="form-control sx-save-after-change" data-field="contact_first_name" value="<?php echo $shopOrder->contact_first_name; ?>"/>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group js-float-label-wrapper">
                                            <label>Фамилия</label>
                                            <input type="text" class="form-control sx-save-after-change" data-field="contact_last_name" value="<?php echo $shopOrder->contact_last_name; ?>"/>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group js-float-label-wrapper">
                                            <label>Телефон</label>
                                            <input type="text" id='sx-phone' class="form-control sx-save-after-change" data-field="contact_phone" value="<?php echo $shopOrder->contact_phone; ?>"/>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group js-float-label-wrapper">
                                            <label>Email</label>
                                            <input type="text" class="form-control sx-save-after-change" data-field="contact_email" value="<?php echo $shopOrder->contact_email; ?>"/>
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
                                        <div class="form-group js-float-label-wrapper">
                                            <label>Имя</label>
                                            <input type="text" <?php echo \Yii::$app->user->identity->first_name ? "disabled" : ""; ?> class="form-control" value="<?php echo \Yii::$app->user->identity->first_name; ?>"/>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group js-float-label-wrapper">
                                            <label>Фамилия</label>
                                            <input type="text" <?php echo \Yii::$app->user->identity->last_name ? "disabled" : ""; ?> class="form-control" value="<?php echo \Yii::$app->user->identity->last_name; ?>"/>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group js-float-label-wrapper">
                                            <label>Телефон</label>
                                            <input type="text" <?php echo \Yii::$app->user->identity->phone ? "disabled" : ""; ?> id='sx-phone' class="form-control"
                                                   value="<?php echo \Yii::$app->user->identity->phone; ?>"/>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group js-float-label-wrapper">
                                            <label>Email</label>
                                            <input type="text" <?php echo \Yii::$app->user->identity->email ? "disabled" : ""; ?> class="form-control" value="<?php echo \Yii::$app->user->identity->email; ?>"/>
                                        </div>
                                    </div>
                                </div>

                            <?php endif; ?>

                            <div class="sx-receiver">
                                <div class="sx-receiver-triggger-wrapper">
                                    <span class="sx-receiver-triggger" data-value="<?php echo (int)$shopOrder->hasReceiver; ?>" data-text-me="Я буду получать товар!" data-text-other="Получать будет другой человек?">
                                        Получать будет другой человек?
                                    </span>
                                </div>
                                <div class="sx-receiver-data sx-hidden">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group js-float-label-wrapper">
                                                <label>Имя получателя</label>
                                                <input type="text" class="form-control sx-save-after-change" data-field="receiver_first_name" value="<?php echo $shopOrder->receiver_first_name; ?>"/>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group js-float-label-wrapper">
                                                <label>Фамилия получателя</label>
                                                <input type="text" class="form-control sx-save-after-change" data-field="receiver_last_name" value="<?php echo $shopOrder->receiver_last_name; ?>"/>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group js-float-label-wrapper">
                                                <label>Телефон получателя</label>
                                                <input type="text" id='sx-phone-receiver' class="form-control sx-save-after-change" data-field="receiver_phone" value="<?php echo $shopOrder->receiver_phone; ?>"/>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group js-float-label-wrapper">
                                                <label>Email получателя</label>
                                                <input type="text" class="form-control sx-save-after-change" data-field="receiver_email" value="<?php echo $shopOrder->receiver_email; ?>"/>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>


                        </div>

                        <div class="sx-checkout-block sx-delivery-block" id="sx-delivery-block">
                            <div class="h5 sx-checkout-block-title">
                                2. Способ получения
                                <?php if ($this->theme->cart_delivery_text) : ?>
                                    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@48,400,0,0"/>
                                    <div class="material-symbols-outlined" data-toggle="tooltip" data-html="true" title="<?php echo $this->theme->cart_delivery_text; ?>">
                                        help
                                    </div>
                                <?php endif; ?>
                            </div>

                            <div class="row">
                                <?php if ($deliveries) : ?>
                                    <?php foreach ($deliveries as $delivery) : ?>
                                        <div class="col-md-6 col-12">
                                            <div class="btn btn-block btn-check sx-delivery <?php echo $shopOrder->shopDelivery && $shopOrder->shopDelivery->id == $delivery->id ? "sx-checked" : ""; ?>"
                                                 data-id="<?php echo $delivery->id; ?>">
                                                <div>
                                                <span class="sx-checked-icon" data-icon="✓">
                                                    <?php echo $shopOrder->shopDelivery && $shopOrder->shopDelivery->id == $delivery->id ? "✓" : ""; ?>
                                                </span>
                                                    <span class="sx-delivery-name">
                                                    <?php echo $delivery->name; ?>
                                                </span>
                                                </div>
                                                <?php if ($this->theme->cart_is_show_delivery_btn_price) : ?>
                                                    <div class="sx-delivery-btn-price" 
                                                         data-money-current-amount="<?php echo (float) $delivery->getMoneyForOrder($shopOrder)->amount; ?>" 
                                                         data-money="<?php echo $delivery->money; ?>" 
                                                         data-money-amount="<?php echo (float) $delivery->money->amount; ?>" 
                                                         data-free-money-amount="<?php echo $delivery->freeOrderPriceFrom; ?>"
                                                    >
                                                        <?php echo $delivery->getMoneyForOrder($shopOrder); ?>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                <?php else : ?>
                                    <div class="col-12">
                                        <p>Магазин не настроен!</p>
                                    </div>
                                <?php endif; ?>

                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <?php if ($deliveries) : ?>
                                        <?php foreach ($deliveries as $delivery) : ?>
                                            <div class="sx-delivery-tab <?php echo $shopOrder->shopDelivery && $shopOrder->shopDelivery->id == $delivery->id ? "" : "sx-hidden"; ?>" data-id="<?php echo $delivery->id; ?>">
                                                <?php if ($delivery->description) : ?>
                                                    <div class="sx-delivery-description"><?php echo $delivery->description; ?></div>
                                                <?php endif; ?>

                                                <?php if ($delivery->handler) : ?>
                                                    <?php echo $delivery->handler->renderWidget($shopOrder); ?>
                                                <?php endif; ?>
                                            </div>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <div class="sx-checkout-block sx-paysystem-block" id="sx-paysystem-block" data-toggle="tooltip" data-placement="right" title="">
                            <div class="h5 sx-checkout-block-title">
                                3. Способ оплаты

                                <?php if ($this->theme->cart_paysystem_text) : ?>
                                    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@48,400,0,0"/>
                                    <div class="material-symbols-outlined" data-toggle="tooltip" data-html="true" title="<?php echo $this->theme->cart_paysystem_text; ?>">
                                        help
                                    </div>
                                <?php endif; ?>

                            </div>
                            <div class="row">
                                <?php
                                /**
                                 * @var $paySystems \skeeks\cms\shop\models\ShopPaySystem[]
                                 */
                                $paySystems = \skeeks\cms\shop\models\ShopPaySystem::find()->cmsSite()->active()->sort()->all();

                                if ($paySystems) : ?>
                                    <?php foreach ($paySystems as $paySystem) : ?>
                                        <div class="col-md-6 col-12">
                                            <div class="btn btn-block btn-check sx-paysystem 
                                            <?php echo $shopOrder->shopPaySystem && $shopOrder->shopPaySystem->id == $paySystem->id ? "sx-checked" : ""; ?>
                                            <?php echo $paySystem->isAllowForOrder($shopOrder) ? "" : "sx-disabled"; ?>
"
                                                 data-id="<?php echo $paySystem->id; ?>"
                                                 data-is_allow="<?php echo $paySystem->isAllowForOrder($shopOrder); ?>"
                                                 data-is_allow_message="<?php echo $paySystem->getNotAllowMessage($shopOrder); ?>"

                                            >
                                                <span class="sx-checked-icon" data-icon="✓">
                                                    <?php echo $shopOrder->shopPaySystem && $shopOrder->shopPaySystem->id == $paySystem->id ? "✓" : ""; ?>
                                                </span>
                                                <span class="sx-paysystem-name">
                                                    <?php echo $paySystem->name; ?>
                                                </span>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                <?php else : ?>
                                    <div class="col-12">
                                        <p>Магазин не настроен!</p>
                                    </div>
                                <?php endif; ?>
                            </div>
                            
                            <div class="row">
                                <div class="col-12">
                                    <?php if ($paySystems) : ?>
                                        <?php foreach ($paySystems as $paySystem) : ?>
                                            <div class="sx-paysystem-tab <?php echo $shopOrder->shopPaySystem && $shopOrder->shopPaySystem->id == $paySystem->id ? "" : "sx-hidden"; ?>" data-id="<?php echo $paySystem->id; ?>">
                                                <?php if ($paySystem->description) : ?>
                                                    <div class="sx-paysystem-description"><?php echo $paySystem->description; ?></div>
                                                <?php endif; ?>

                                                <?php /*if ($paysystem->handler) : */?><!--
                                                    <?php /*echo $delivery->handler->renderWidget($shopOrder); */?>
                                                --><?php /*endif; */?>
                                            </div>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </div>
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

                        <?php if ($this->theme->cart_after_comment_text) : ?>
                            <div class="row">
                                <div class="col-12">
                                    <div class="sx-cart_after_comment_text">
                                        <?php echo $this->theme->cart_after_comment_text; ?>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>


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

                                <?php
                                    $order_free_shipping_from_price = $shopOrder->shopDelivery ? $shopOrder->shopDelivery->freeOrderPriceFrom : \Yii::$app->skeeks->site->shopSite->order_free_shipping_from_price;
                                ?>

                                <?/* if ($order_free_shipping_from_price = \Yii::$app->skeeks->site->shopSite->order_free_shipping_from_price) : */?>

                                    <div class="sx-free-delivery <?php echo (float)$shopOrder->moneyItems->amount > $order_free_shipping_from_price ? "sx-hidden" : ""; ?>">
                                        Добавьте товаров на <span class="sx-need-price">
                                            <?php
                                            $m = new \skeeks\cms\money\Money((string)$order_free_shipping_from_price, $shopOrder->currency_code);
                                            echo $m->sub($shopOrder->moneyItems); ?>
                                        </span> для бесплатной доставки
                                    </div>
                                    <div class="sx-free-delivery-success <?php echo (float)$shopOrder->moneyItems->amount > $order_free_shipping_from_price && $order_free_shipping_from_price > 0 ? "" : "sx-hidden"; ?>">
                                        Доставим товар бесплатно!
                                    </div>


                                <?/* endif; */?>

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
                                <div class="sx-order-error">

                                </div>
                            </div>
                            <div class="col-12">
                                <a href="#" class="btn btn-xxl btn-block btn-primary btn-submit-order" data-pjax="0" data-value="Оформить заказ" data-process="Подождите...">
                                    Оформить заказ
                                </a>
                            </div>

                            <?php if ($this->theme->cart_after_btn_text) : ?>
                                <div class="col-12">
                                    <div class="sx-after-order-btn-text">
                                        <?php echo $this->theme->cart_after_btn_text; ?>
                                    </div>
                                </div>
                            <?php endif; ?>


                        <? endif; ?>

                    </div>
                </div>
            </div>
        </div>
    </section>
    <? /* \skeeks\cms\widgets\Pjax::end(); */ ?>
</div>
