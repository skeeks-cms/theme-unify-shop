<?php
// php tests/product-meta.php
// Title/description карточки товара: шаблоны контента товаров и строка по умолчанию без пустых частей
// («✔ .», «р..»), без нераскрытых переменных; необязательные части [..] пропадают при пустых значениях.
require dirname(__DIR__).'/src/helpers/ProductMeta.php';

use skeeks\cms\themes\unifyshop\helpers\ProductMeta;

$name = 'Керамогранит LCM Каскад Натурал / Cascade Natural полированный серый 6060CAS55P 60х60 см';
$values = [
    'model.seoName' => $name,
    'brand'         => 'LCM',
    'sku'           => '6060CAS55P',
    'price'         => '3 290 р.',
    'measure'       => 'м²',
    'siteName'      => 'АртКерамика',
];
$withoutPrice = ['price' => ''] + $values;
$properties = function ($var) {
    return ['property.Поверхность' => 'полированная', 'property.abc' => ''][$var] ?? '';
};

$cases = [
    'по умолчанию, пустое описание' => [ProductMeta::defaultDescription($name, '', '3 290 р.'), "✔ {$name}. Цена 3 290 р."],
    'по умолчанию, с описанием' => [ProductMeta::defaultDescription($name, '<p>Для пола и стен.</p>', '3 290 р.'), "✔ {$name}. ✔ Для пола и стен. Цена 3 290 р."],
    'по умолчанию, без цены' => [ProductMeta::defaultDescription($name, '', ''), "✔ {$name}."],
    'по умолчанию, ничего' => [ProductMeta::defaultDescription('', ' ', ''), ''],
    'title по шаблону' => [ProductMeta::render('{=model.seoName} — Новокузнецк', $values), "{$name} — Новокузнецк"],
    'description с ценой' => [ProductMeta::render('{=model.seoName}, {=property.Поверхность}. [Цена {=price} за {=measure}.] Подбор в {=siteName}, Новокузнецк.', $values, $properties),
        "{$name}, полированная. Цена 3 290 р. за м². Подбор в АртКерамика, Новокузнецк."],
    'description без цены и характеристики' => [ProductMeta::render('{=model.seoName}, {=property.abc}. [Цена {=price} за {=measure}.] Уточните наличие и цену.', $withoutPrice, $properties),
        "{$name}. Уточните наличие и цену."],
    'неизвестная переменная' => [ProductMeta::render('{=nope} {=model.seoName} ({=nope2})', $values), $name],
    'пустое начало и тире' => [ProductMeta::render('{=model.name} — Новокузнецк', $values), 'Новокузнецк'],
    'старый мусор' => [ProductMeta::clean('✔ Плитка. ✔ . Цена 3 290 р..'), '✔ Плитка. Цена 3 290 р.'],
    'дробные числа не трогаем' => [ProductMeta::clean('Толщина 0.8 см, , вес 1,44 кг'), 'Толщина 0.8 см, вес 1,44 кг'],
];
foreach ($cases as $title => [$actual, $expected]) {
    if ($actual !== $expected) {
        throw new RuntimeException("{$title}: «{$actual}» вместо «{$expected}»");
    }
    if (strpos($actual, '{=') !== false) {
        throw new RuntimeException("{$title}: нераскрытая переменная");
    }
}
echo "OK: метатеги товара по шаблону и по умолчанию без пустых частей.\n";
