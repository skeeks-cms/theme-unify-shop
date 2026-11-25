<?php
// php -d short_open_tag=1 tests/product-description.php <vendor/autoload.php> [template]
// description в микроразметке товара не бывает пустым: описание товара, затем коллекции,
// иначе строка из названия, бренда, коллекции и страны (Яндекс Вебмастер требует его у товара).
require $argv[1] ?? '/app/vendor/autoload.php';
require dirname($argv[1] ?? '/app/vendor/autoload.php').'/yiisoft/yii2/Yii.php';
new \yii\console\Application(['id' => 'product-description-test', 'basePath' => __DIR__]);
$source = file_get_contents($argv[2] ?? dirname(__DIR__).'/src/views/modules/cms/content-element/product.php');
$start = strpos($source, '<?php', strpos($source, '<meta itemprop="name"'));
$marker = '<meta itemprop="description"';
$end = strpos($source, '<?php endif; ?>', strpos($source, $marker));
if ($start === false || $end === false) {
    throw new RuntimeException('Не найден блок description');
}
$template = substr($source, $start, $end + strlen('<?php endif; ?>') - $start);
$collection = static function ($name, $short = '', $full = '') {
    return (object)['name' => $name, 'description_short' => $short, 'description_full' => $full];
};
$cases = [
    'своё краткое' => [['short' => 'Краткое <b>описание</b> товара'], [], 'Краткое описание товара'],
    'своё полное' => [['full' => '<p>Полное &mdash; описание</p>'], [$collection('Bohemia', 'Коллекция')], 'Полное — описание'],
    'коллекция' => [[], [$collection('Bohemia', 'Bohemia от Gracia Ceramica')], 'Bohemia от Gracia Ceramica'],
    'полное коллекции' => [[], [$collection('Bohemia', '', '<p>Богемия &mdash; королевство</p>')], 'Богемия — королевство'],
    'коллекция у общего товара' => [['offer' => [$collection('Prado', 'Прадо')]], [], 'Прадо'],
    'из данных товара' => [['brand' => 'Gracia Ceramica', 'country' => 'Россия', 'measure' => 'м2'], [$collection('Bohemia')], 'Декор Bohemia brown 03 25х60 см. Бренд: Gracia Ceramica. Коллекция: Bohemia. Страна: Россия. Цена указана за 1 м2'],
    'только название' => [[], [], 'Декор Bohemia brown 03 25х60 см'],
];
foreach ($cases as $title => [$data, $collections, $expected]) {
    $model = (object)[
        'productDescriptionShort' => $data['short'] ?? '',
        'productDescriptionFull'  => $data['full'] ?? '',
        'seoName'                 => 'Декор Bohemia brown 03 25х60 см',
        'name'                    => 'Декор',
    ];
    $shopProduct = (object)[
        'collections'            => $collections,
        'isOfferProduct'         => isset($data['offer']),
        'shopProductWhithOffers' => isset($data['offer']) ? (object)['collections' => $data['offer']] : null,
        'brand'                  => isset($data['brand']) ? (object)['name' => $data['brand']] : null,
        'country'                => isset($data['country']) ? (object)['name' => $data['country']] : null,
        'measure_code'           => isset($data['measure']) ? '055' : null,
        'measure'                => isset($data['measure']) ? (object)['symbol' => $data['measure']] : null,
    ];
    ob_start();
    eval('?>'.$template);
    $html = ob_get_clean();
    if (!preg_match('/itemprop="description" content="([^"]*)"/', $html, $match)) {
        throw new RuntimeException("{$title}: нет description");
    }
    $actual = html_entity_decode($match[1], ENT_QUOTES | ENT_HTML5, 'UTF-8');
    if ($actual !== $expected) {
        throw new RuntimeException("{$title}: «{$actual}» вместо «{$expected}»");
    }
}
echo "OK: описание товара, коллекции (и общего товара), строка из данных товара; description не пустой.\n";
