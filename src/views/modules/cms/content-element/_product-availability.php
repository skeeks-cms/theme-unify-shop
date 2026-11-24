<?php
/**
 * @link https://cms.skeeks.com/
 * @copyright Copyright (c) 2010 SkeekS
 * @license https://cms.skeeks.com/license/
 * @author Semenov Alexander <semenov@skeeks.com>
 */
/* @var $this yii\web\View */
/* @var $availability array|null результат \skeeks\cms\themes\unifyshop\helpers\ProductAvailability */
/**
 * Наличие внутри блока Offer: разметка availability и тот же статус для покупателя.
 * Если склады на сайте не ведутся, наличие не подтверждено — не выводится ничего.
 */
if (!$availability) {
    return;
}
$this->registerCss(<<<CSS
.sx-product-availability {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    gap: 0 .4em;
    margin-bottom: .5rem;
    font-size: .95rem;
    line-height: 1.4;
}
.sx-product-availability::before {
    content: "";
    width: .55em;
    height: .55em;
    border-radius: 50%;
    background: currentColor;
}
.sx-product-availability--InStock { color: #2e7d32; }
.sx-product-availability--BackOrder { color: #b26a00; }
.sx-product-availability--OutOfStock { color: #8a8a8a; }
.sx-product-availability__note { color: #8a8a8a; }
CSS
);
?>
<link itemprop="availability" href="https://schema.org/<?= $availability['schema']; ?>">
<div class="sx-product-availability sx-product-availability--<?= $availability['schema']; ?>">
    <span class="sx-product-availability__label"><?= \yii\helpers\Html::encode($availability['label']); ?></span>
    <?php if ($availability['note']) : ?>
        <span class="sx-product-availability__note"><?= \yii\helpers\Html::encode($availability['note']); ?></span>
    <?php endif; ?>
</div>
