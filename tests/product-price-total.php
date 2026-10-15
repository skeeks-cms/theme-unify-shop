<?php
// php -d short_open_tag=1 tests/product-price-total.php <vendor/autoload.php> [template]
require $argv[1] ?? '/app/vendor/autoload.php';
require dirname($argv[1] ?? '/app/vendor/autoload.php').'/yiisoft/yii2/Yii.php';
new \yii\console\Application(['id' => 'product-price-total-test', 'basePath' => __DIR__]);
$source = file_get_contents($argv[2] ?? dirname(__DIR__).'/src/views/modules/cms/content-element/_product-price.php');
$start = strpos($source, '<?php if ($isShowPrice && $shopProduct->measure_ratio_min != 1 && $shopProduct->minProductPrice) : ?>');
$end = strpos($source, '<?php endif; ?>', $start);
if ($start === false || $end === false) {
    throw new RuntimeException('Не найден блок суммы за выбранное количество');
}
$template = substr($source, $start, $end + strlen('<?php endif; ?>') - $start);
$view = new class extends \yii\web\View {
    public function registerJs($js, $position = self::POS_READY, $key = null) {
        if (strpos($js, 'var total = base * qty;') === false) {
            throw new RuntimeException('Не найден пересчёт суммы');
        }
        return $this;
    }
};
\Yii::$app->set('money', (object)['currency_symbol' => '₽']);
foreach ([[1000, 800, 1.44], [1000, 1000, 1.44], [1000, 799.99, 2.88], [1000, 0, 1.44]] as [$regular, $discounted, $quantity]) {
    $createMoney = static function ($amount) {
        $money = (new ReflectionClass(\skeeks\cms\money\Money::class))->newInstanceWithoutConstructor();
        $money->amount = $amount;
        return $money;
    };
    $original = $createMoney($regular);
    $effective = $createMoney($discounted);
    $shopProduct = (object)['measure_ratio_min' => $quantity, 'minProductPrice' => (object)['money' => $original]];
    $priceHelper = (object)['minMoney' => $effective];
    foreach ([true, false] as $isShowPrice) {
        $render = function () use ($template, $shopProduct, $priceHelper, $isShowPrice) {
            ob_start();
            eval('?>'.$template);
            return ob_get_clean();
        };
        $html = $render->call($view);
        if (!$isShowPrice) {
            if (trim($html) !== '') {
                throw new RuntimeException('Скрытая цена попала в сумму или данные пересчёта');
            }
            continue;
        }
        if (!preg_match('/class="sx-money-total" data-amount="([^"]+)">([^<]+)</', $html, $match)) {
            throw new RuntimeException('Не найдена сумма');
        }
        $expected = \Yii::$app->formatter->asDecimal($discounted * $quantity);
        if ((float)$match[1] !== (float)$discounted || $match[2] !== $expected) {
            throw new RuntimeException('Сумма или цена для браузерного пересчёта не учитывает скидку');
        }
        if ((float)$effective->amount !== (float)$discounted || (float)$original->amount !== (float)$regular) {
            throw new RuntimeException('Расчёт суммы изменил цену единицы товара');
        }
    }
}
echo "OK: скрытая цена, скидка, обычная и нулевая цена, дробное количество, данные пересчёта, неизменность цен.\n";
