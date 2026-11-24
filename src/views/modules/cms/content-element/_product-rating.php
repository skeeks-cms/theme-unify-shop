<?php
/**
 * @link https://cms.skeeks.com/
 * @copyright Copyright (c) 2010 SkeekS
 * @license https://cms.skeeks.com/license/
 * @author Semenov Alexander <semenov@skeeks.com>
 */
/* @var $this yii\web\View */
/* @var $shopProduct \skeeks\cms\shop\models\ShopProduct */
/**
 * Видимый рейтинг товара: те же значения и то же условие, что у AggregateRating
 * в product.php (рейтинг и число оценок больше нуля). Число оценок не называем отзывами.
 */
$ratingValue = (float)$shopProduct->rating_value;
$ratingCount = (int)$shopProduct->rating_count;
if ($ratingValue <= 0 || $ratingCount <= 0) {
    return;
}
$bestRating = (float)\Yii::$app->skeeks->site->shopSite->max_product_rating_value ?: 5;
$percent = max(0, min(100, round($ratingValue / $bestRating * 100, 1)));
$ratingText = \Yii::$app->formatter->asDecimal($ratingValue, 1);
$mod10 = $ratingCount % 10;
$mod100 = $ratingCount % 100;
if ($mod10 == 1 && $mod100 != 11) {
    $countWord = 'оценка';
} elseif ($mod10 >= 2 && $mod10 <= 4 && ($mod100 < 12 || $mod100 > 14)) {
    $countWord = 'оценки';
} else {
    $countWord = 'оценок';
}
$countText = \Yii::$app->formatter->asInteger($ratingCount).' '.$countWord;

$this->registerCss(<<<CSS
.sx-product-rating {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    gap: 0 .5em;
    margin-bottom: .5rem;
    font-size: .95rem;
    line-height: 1.4;
}
.sx-product-rating__stars {
    position: relative;
    display: inline-block;
    font-size: 1.05em;
    letter-spacing: .1em;
    line-height: 1;
    color: #d6d6d6;
}
.sx-product-rating__stars-fill {
    position: absolute;
    top: 0;
    left: 0;
    overflow: hidden;
    white-space: nowrap;
    color: #f5b301;
}
.sx-product-rating__count { color: #8a8a8a; }
CSS
);
?>
<div class="sx-product-rating" title="Рейтинг <?= $ratingText; ?> из <?= \Yii::$app->formatter->asDecimal($bestRating, 0); ?>">
    <span class="sx-product-rating__stars" aria-hidden="true">★★★★★<span class="sx-product-rating__stars-fill" style="width: <?= $percent; ?>%">★★★★★</span></span>
    <span class="sx-product-rating__value">Рейтинг <?= $ratingText; ?></span>
    <span class="sx-product-rating__count"><?= $countText; ?></span>
</div>
