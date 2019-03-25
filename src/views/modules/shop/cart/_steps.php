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
<ul id="stepFormProgress" class="js-step-progress row justify-content-center list-inline text-center g-font-size-17 mb-0">
    <li class="col-3 list-inline-item g-mb-20 g-mb-0--sm active" onclick="location.href='<?= \yii\helpers\Url::to(['/shop/cart']); ?>'; return false;">
        <span class="d-block u-icon-v2 u-icon-size--sm g-rounded-50x g-brd-primary g-color-primary g-color-white--parent-active g-bg-primary--active g-color-white--checked g-bg-primary--checked mx-auto mb-3">
          <i class="g-font-style-normal g-font-weight-700 g-hide-check">1</i>
          <i class="fa fa-check g-show-check"></i>
        </span>
        <h4 class="g-font-size-16 mb-0"><?= \Yii::t('skeeks/shop-cart-steps-widget', 'Cart'); ?></h4>
    </li>

    <li class="col-3 list-inline-item g-mb-20 g-mb-0--sm <?= in_array(\Yii::$app->controller->action->getUniqueId(), ['shop/cart/checkout', 'shop/order/finish']) ? "active" : ""; ?>"
    onclick="location.href='<?= \yii\helpers\Url::to(['/shop/cart/checkout']); ?>'; return false;"
    >
                <span class="d-block u-icon-v2 u-icon-size--sm g-rounded-50x g-brd-gray-light-v2 g-color-gray-dark-v5 g-brd-primary--active g-color-white--parent-active g-bg-primary--active g-color-white--checked g-bg-primary--checked mx-auto mb-3">
                  <i class="g-font-style-normal g-font-weight-700 g-hide-check">2</i>
                  <i class="fa fa-check g-show-check"></i>
                </span>
        <h4 class="g-font-size-16 mb-0"><?= \Yii::t('skeeks/shop-cart-steps-widget', 'Ordering'); ?></h4>
    </li>

    <li class="col-3 list-inline-item <?= \Yii::$app->controller->action->getUniqueId() == 'shop/order/finish' ? "active" : ""; ?>">
                <span class="d-block u-icon-v2 u-icon-size--sm g-rounded-50x g-brd-gray-light-v2 g-color-gray-dark-v5 g-brd-primary--active g-color-white--parent-active g-bg-primary--active g-color-white--checked g-bg-primary--checked mx-auto mb-3">
                  <i class="g-font-style-normal g-font-weight-700 g-hide-check">3</i>
                  <i class="fa fa-check g-show-check"></i>
                </span>
        <h4 class="g-font-size-16 mb-0"><?= \Yii::t('skeeks/shop-cart-steps-widget', 'Ready order'); ?></h4>
    </li>
</ul>
<?= \yii\helpers\Html::endTag('div'); ?>
