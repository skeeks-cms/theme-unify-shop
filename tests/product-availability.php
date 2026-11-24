<?php
// php -d short_open_tag=1 tests/product-availability.php <vendor/autoload.php>
// Наличие и рейтинг в карточке товара: разметка и видимый покупателю статус из одного решения.
require $argv[1] ?? '/app/vendor/autoload.php';
require dirname($argv[1] ?? '/app/vendor/autoload.php').'/yiisoft/yii2/Yii.php';
require_once dirname(__DIR__).'/src/helpers/ProductAvailability.php';
new \yii\console\Application(['id' => 'product-availability-test', 'basePath' => __DIR__, 'language' => 'ru-RU']);

use skeeks\cms\themes\unifyshop\helpers\ProductAvailability;

$check = static function ($condition, $message) {
    if (!$condition) {
        throw new RuntimeException($message);
    }
};
$stock = static function ($storeId, $quantity) {
    return (object)['shop_store_id' => $storeId, 'quantity' => $quantity];
};
$suppliers = [20, 21];

// Склады не ведутся — наличие не подтверждено
$check(ProductAvailability::resolve([], false, false, $suppliers) === null, 'Без складов наличие не должно выводиться');
// Свой склад
$r = ProductAvailability::resolve([$stock(1, '5.0000')], true, false, $suppliers);
$check($r['schema'] === 'InStock' && $r['label'] === 'В наличии' && $r['note'] === null, 'Остаток на своём складе — InStock');
// Только поставщик (как у k-mix)
$r = ProductAvailability::resolve([$stock(1, 0), $stock('20', '779.7600')], true, false, $suppliers);
$check($r['schema'] === 'InStock' && $r['note'] === 'на центральном складе', 'Остаток у поставщика — InStock с пометкой');
// Нет остатка, заказ без остатка разрешён
$r = ProductAvailability::resolve([$stock(1, 0)], true, true, $suppliers);
$check($r['schema'] === 'BackOrder' && $r['label'] === 'Под заказ', 'Без остатка с разрешённым заказом — BackOrder');
// Нет остатка
$r = ProductAvailability::resolve([$stock(1, 0), $stock(20, -2)], true, false, $suppliers);
$check($r['schema'] === 'OutOfStock' && $r['label'] === 'Нет в наличии', 'Без остатка — OutOfStock');
$r = ProductAvailability::resolve([], true, false, $suppliers);
$check($r['schema'] === 'OutOfStock', 'Нет записей остатков — OutOfStock');

$views = dirname(__DIR__).'/src/views/modules/cms/content-element/';
$view = new \yii\web\View();

// Разметка и видимый статус совпадают
$html = $view->renderFile($views.'_product-availability.php', ['availability' => ProductAvailability::resolve([$stock(20, 3)], true, false, $suppliers)]);
$check(strpos($html, '<link itemprop="availability" href="https://schema.org/InStock">') !== false, 'Нет разметки InStock');
$check(strpos($html, 'sx-product-availability--InStock') !== false && strpos($html, 'В наличии') !== false, 'Нет видимого статуса «В наличии»');
$html = $view->renderFile($views.'_product-availability.php', ['availability' => ProductAvailability::resolve([], true, false, $suppliers)]);
$check(strpos($html, 'schema.org/OutOfStock') !== false && strpos($html, 'Нет в наличии') !== false, 'Нет разметки и статуса OutOfStock');
$html = $view->renderFile($views.'_product-availability.php', ['availability' => null]);
$check(trim($html) === '', 'Без подтверждённого наличия ничего не выводится');

// Видимый рейтинг: те же значения, что в AggregateRating; без оценок — пусто
\Yii::$app->set('skeeks', (object)['site' => (object)['shopSite' => (object)['max_product_rating_value' => 5]]]);
$html = $view->renderFile($views.'_product-rating.php', ['shopProduct' => (object)['rating_value' => '4.7015', 'rating_count' => 93]]);
$check(strpos($html, 'Рейтинг 4,7') !== false && strpos($html, '93 оценки') !== false, 'Видимый рейтинг не совпадает с данными: '.$html);
$check(strpos($html, 'width: 94%') !== false, 'Заполнение звёзд не соответствует рейтингу');
foreach ([[1, 'оценка'], [11, 'оценок'], [22, 'оценки'], [58, 'оценок'], [101, 'оценка']] as [$n, $word]) {
    $html = $view->renderFile($views.'_product-rating.php', ['shopProduct' => (object)['rating_value' => 4.5, 'rating_count' => $n]]);
    $check(strpos($html, $n.' '.$word) !== false, 'Неверное склонение для '.$n);
}
foreach ([[0, 0], [4.5, 0], [0, 10]] as [$value, $count]) {
    $html = $view->renderFile($views.'_product-rating.php', ['shopProduct' => (object)['rating_value' => $value, 'rating_count' => $count]]);
    $check(trim($html) === '', 'Рейтинг без значения или без оценок не должен выводиться');
}

// Шаблоны карточки: жёсткого InStock нет, AggregateRating при тех же условиях
$price = file_get_contents($views.'_product-price.php');
$check(strpos($price, 'schema.org/InStock') === false, 'В _product-price.php остался жёсткий InStock');
$check(substr_count($price, '_product-availability') === 2, 'Наличие должно выводиться в простом товаре и модификации');
$check(strpos($price, '_product-rating') !== false, 'Видимый рейтинг не подключён');
$product = file_get_contents($views.'product.php');
$check(strpos($product, '(float)$shopProduct->rating_value > 0 && (int)$shopProduct->rating_count > 0') !== false, 'Условие AggregateRating расходится с видимым рейтингом');

echo "OK: наличие (без складов, свой склад, поставщик, под заказ, нет), разметка = видимый статус, рейтинг и склонение, шаблоны карточки.\n";
