<?php
/**
 * @author Semenov Alexander <semenov@skeeks.com>
 * @link http://skeeks.com/
 * @copyright 2010 SkeekS (СкикС)
 * @date 09.10.2015
 */
/* @var $this yii\web\View */
\skeeks\assets\unify\base\UnifyHsStickyBlockAsset::register($this);
?>
<div
        id="stickyblock-start"
        class="js-sticky-block"
        data-start-point="#stickyblock-start"
        data-has-sticky-header="true"
        data-end-point=".sx-footer"
>
    <div class="g-bg-secondary g-pa-20 g-pb-20 mb-4">
        <div class="toggle active" style="display: block;">

            <?php if ((float)\Yii::$app->shop->shopUser->shopOrder->money->amount > 0) : ?>


            <div class="toggle-content" style="display: block;">


                <?php if (\skeeks\cms\shop\models\ShopDiscountCoupon::find()
                    ->andWhere(['shop_discount_id' => \skeeks\cms\shop\models\ShopDiscount::find()->cmsSite()->select(['id'])])
                    ->active()->count()) : ?>
                    <div class="sx-discount-coupons-wrapper">
                        <?= \skeeks\cms\shopDiscountCoupon\ShopDiscountCouponWidget::widget([
                            'btnSubmitName' => '✓',
                        ]); ?>
                    </div>
                <?php endif; ?>


                <span class="clearfix">
                <span class="float-right"><?= \Yii::$app->shop->shopUser->shopOrder->moneyOriginal; ?></span>
                <strong class="pull-left">Товаров:</strong>
            </span>
                <? if (\Yii::$app->shop->shopUser->shopOrder->moneyDiscount->amount > 0) : ?>
                    <span class="clearfix">
                    <span class="float-right"><?= \Yii::$app->shop->shopUser->shopOrder->moneyDiscount; ?></span>
                    <span class="pull-left">Скидка:</span>
                </span>
                <? endif; ?>

                <? if (\Yii::$app->shop->shopUser->shopOrder->moneyDelivery->amount > 0) : ?>
                    <span class="clearfix">
                    <span class="float-right"><?= \Yii::$app->shop->shopUser->shopOrder->moneyDelivery; ?></span>
                    <span class="pull-left">Доставка:</span>
                </span>
                <? endif; ?>

                <? if (\Yii::$app->shop->shopUser->shopOrder->moneyVat->amount > 0) : ?>
                    <span class="clearfix">
                    <span class="float-right"><?= \Yii::$app->shop->shopUser->shopOrder->moneyVat; ?></span>
                    <span class="pull-left">Налог:</span>
                </span>
                <? endif; ?>

                <? if (\Yii::$app->shop->shopUser->shopOrder->weight > 0) : ?>
                    <span class="clearfix">
                    <span class="float-right"><?= \Yii::$app->shop->shopUser->shopOrder->weightFormatted; ?></span>
                    <span class="pull-left">Вес:</span>
                </span>
                <? endif; ?>
                <hr/>
                <span class="clearfix">
                <span class="float-right size-20"><?= \Yii::$app->shop->shopUser->shopOrder->money; ?></span>
                <strong class="pull-left">ИТОГ:</strong>
            </span>
                <hr/>
                <?php endif; ?>

                <?= $submit; ?>
            </div>
        </div>
    </div>
</div>
