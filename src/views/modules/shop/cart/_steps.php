<?php
/**
 * @author Semenov Alexander <semenov@skeeks.com>
 * @link http://skeeks.com/
 * @copyright 2010 SkeekS (СкикС)
 * @date 16.10.2016
 */
/* @var $this yii\web\View */
/* @var $widget \skeeks\cms\shop\widgets\cart\ShopCartStepsWidget */
\skeeks\cms\shopCartStepsWidget\assets\ShopCartStepsWidgetAsset::register($this);
$widget = $this->context;
?>
<?= \yii\helpers\Html::beginTag('div', $widget->options); ?>
<!-- Step Titles -->
<ul id="stepFormProgress" class="js-step-progress row justify-content-center list-inline text-center mb-0">
    <li class="col-3 list-inline-item active" onclick="location.href='<?= \yii\helpers\Url::to(['/shop/cart']); ?>'; return false;">
        <span class="d-block sx-step-item g-brd-primary--active g-color-white--parent-active g-bg-primary--active mx-auto mb-3">
          <i class="g-hide-check">1</i>
        </span>
        <h4 class="sx-step-name"><?= \Yii::t('skeeks/shop-cart-steps-widget', 'Cart'); ?></h4>
    </li>

    <li class="col-3 list-inline-item <?= in_array(\Yii::$app->controller->action->getUniqueId(), ['shop/cart/checkout', 'shop/order/finish']) ? "active" : ""; ?>"
        onclick="location.href='<?= \yii\helpers\Url::to(['/shop/cart/checkout']); ?>'; return false;"
    >
                <span class="d-block sx-step-item g-brd-primary--active g-color-white--parent-active g-bg-primary--active mx-auto mb-3">
                  <i class="g-hide-check">2</i>
                </span>
        <h4 class="sx-step-name"><?= \Yii::t('skeeks/shop-cart-steps-widget', 'Ordering'); ?></h4>
    </li>

    <li class="col-3 list-inline-item <?= \Yii::$app->controller->action->getUniqueId() == 'shop/order/finish' ? "active" : ""; ?>">
                <span class="d-block sx-step-item g-brd-primary--active g-color-white--parent-active g-bg-primary--active mx-auto mb-3">
                  <i class="g-hide-check">3</i>
                </span>
        <h4 class="sx-step-name"><?= \Yii::t('skeeks/shop-cart-steps-widget', 'Ready order'); ?></h4>
    </li>
</ul>
<?= \yii\helpers\Html::endTag('div'); ?>
