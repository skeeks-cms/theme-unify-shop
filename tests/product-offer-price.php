<?php
// php -d short_open_tag=1 tests/product-offer-price.php [template]
// Цена в микроразметке Offer должна совпадать с «Вашей ценой» на странице и в товарном фиде:
// иначе Яндекс Вебмастер (Товары) показывает ошибку несовпадения цены.
$source = file_get_contents($argv[1] ?? dirname(__DIR__).'/src/views/modules/cms/content-element/_product-price.php');
if (!preg_match_all('/^[ \t]*<meta itemprop="price"[^\n]*\n[ \t]*<meta itemprop="priceCurrency"[^\n]*$/m', $source, $blocks) || count($blocks[0]) !== 2) {
    throw new RuntimeException('Ожидалось два блока цены Offer: простой товар и товар с вариантами');
}
$money = static function ($amount, $code = 'RUB') {
    return (object)['amount' => $amount, 'currency' => (object)['code' => $code]];
};
$cases = [
    // базовая, со скидкой, ожидаемое значение price
    ['236.00', '200.6', '200.60'],
    ['255.00', '216.75000000', '216.75'],
    ['3301.00', '3301.00', '3301.00'],
    ['1000', '799.995', '800.00'],
];
foreach ($blocks[0] as $template) {
    foreach ($cases as [$base, $effective, $expected]) {
        $priceHelper = (object)[
            'basePrice' => (object)['money' => $money($base, 'XXX')],
            'minMoney'  => $money($effective),
        ];
        ob_start();
        eval('?>'.$template);
        $html = ob_get_clean();
        if (!preg_match('/itemprop="price" content="([^"]+)"/', $html, $price) || $price[1] !== $expected) {
            throw new RuntimeException("Цена Offer {$price[1]} вместо {$expected} (базовая {$base}, со скидкой {$effective})");
        }
        if (!preg_match('/itemprop="priceCurrency" content="RUB"/', $html)) {
            throw new RuntimeException('Валюта Offer должна быть валютой итоговой цены');
        }
    }
}
echo "OK: цена Offer со скидкой в обоих блоках, два знака после запятой, валюта итоговой цены.\n";
