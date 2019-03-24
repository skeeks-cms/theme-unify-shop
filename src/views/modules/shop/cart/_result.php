<?php
/**
 * @author Semenov Alexander <semenov@skeeks.com>
 * @link http://skeeks.com/
 * @copyright 2010 SkeekS (СкикС)
 * @date 09.10.2015
 */
/* @var $this yii\web\View */
?>
<div class="toggle-transparent toggle-bordered-full clearfix">
    <div class="toggle active" style="display: block;">
        <div class="toggle-content" style="display: block;">

            <span class="clearfix">
                <span class="pull-right"><?= \Yii::$app->shop->cart->moneyOriginal; ?></span>
                <strong class="pull-left">Товаров:</strong>
            </span>
            <? if (\Yii::$app->shop->cart->moneyDiscount->amount > 0) : ?>
                <span class="clearfix">
                    <span class="pull-right"><?= \Yii::$app->shop->cart->moneyDiscount; ?></span>
                    <span class="pull-left">Скидка:</span>
                </span>
            <? endif; ?>

            <? if (\Yii::$app->shop->cart->moneyDelivery->amount > 0) : ?>
                <span class="clearfix">
                    <span class="pull-right"><?= \Yii::$app->shop->cart->moneyDelivery; ?></span>
                    <span class="pull-left">Доставка:</span>
                </span>
            <? endif; ?>

            <? if (\Yii::$app->shop->cart->moneyVat->amount > 0) : ?>
                <span class="clearfix">
                    <span class="pull-right"><?= \Yii::$app->shop->cart->moneyVat; ?></span>
                    <span class="pull-left">Налог:</span>
                </span>
            <? endif; ?>

            <? if (\Yii::$app->shop->cart->weight > 0) : ?>
                <span class="clearfix">
                    <span class="pull-right"><?= \Yii::$app->shop->cart->weight; ?> г.</span>
                    <span class="pull-left">Вес:</span>
                </span>
            <? endif; ?>
            <hr />
            <span class="clearfix">
                <span class="pull-right size-20"><?= \Yii::$app->shop->cart->money; ?></span>
                <strong class="pull-left">ИТОГ:</strong>
            </span>
            <hr />
            <?= $submit; ?>
        </div>
    </div>
</div>
