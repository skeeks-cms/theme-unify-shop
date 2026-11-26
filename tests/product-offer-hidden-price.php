<?php
// php -d short_open_tag=1 tests/product-offer-hidden-price.php [template]
// Offer в карточке товара выводится только при видимой цене больше нуля: скрытую цену
// («только при наличии») и нулевую («по запросу») поисковикам не передаём.
$source = file_get_contents($argv[1] ?? dirname(__DIR__).'/src/views/modules/cms/content-element/_product-price.php');
$check = static function ($condition, $message) {
    if (!$condition) {
        throw new RuntimeException($message);
    }
};

$rule = '$isOfferMarkup = $isShowPrice && (float)$priceHelper->minMoney->amount > 0;';
$check(substr_count($source, $rule) === 2, 'Правило Offer должно быть в простом товаре и в модификации');
$check(substr_count($source, 'itemprop="offers"') === 2, 'Offer только у простого товара и выбранной модификации: без выбора модификации предложения нет');
$check(substr_count($source, '<div class="product-price"<? if ($isOfferMarkup) : ?> itemprop="offers" itemscope="" itemtype="http://schema.org/Offer"<? endif; ?>>') === 2, 'Блок Offer должен зависеть от $isOfferMarkup');
$check(substr_count($source, "'markup'       => \$isOfferMarkup") === 2, 'Наличие без Offer не должно выводить разметку');

// Цена и валюта Offer — только внутри условия
if (!preg_match_all('/<\? if \(\$isOfferMarkup\) : \?>(.*?)<\? endif; \?>/s', $source, $blocks)) {
    throw new RuntimeException('Нет условных блоков Offer');
}
$priceBlocks = array_filter($blocks[1], static function ($b) { return strpos($b, 'itemprop="price"') !== false; });
$check(count($priceBlocks) === 2 && substr_count($source, 'itemprop="price"') === 2, 'Цена Offer выводится вне условия');

$money = static function ($amount) { return (object)['amount' => $amount]; };
foreach ([
    [true, '1247.00', true],
    [true, '0', false],
    [true, '0.00', false],
    [false, '1247.00', false],
    [true, '0.01', true],
] as [$isShowPrice, $amount, $expected]) {
    $priceHelper = (object)['minMoney' => $money($amount)];
    eval($rule);
    $check($isOfferMarkup === $expected, "Offer при показе цены ".var_export($isShowPrice, true)." и цене {$amount}");
}
echo "OK: Offer только при видимой цене больше нуля, в обоих блоках; без Offer наличие не размечается.\n";
