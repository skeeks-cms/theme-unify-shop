<?php
/**
 * @author Semenov Alexander <semenov@skeeks.com>
 * @link https://skeeks.com/
 * @copyright (c) 2010 SkeekS
 * @date 13.11.2017
 */
/* @var $this yii\web\View */
/* @var $handler \skeeks\cms\shop\queryFilter\PriceFiltersHandler */
/* @var $form \yii\widgets\ActiveForm */
/* @var $code string */
$widget = $this->context;
?>

<?
$min = $handler->minValue;
$max = $handler->maxValue;

$val1 = $handler->f ? $handler->f : $min;
$val2 = $handler->t ? $handler->t : $max;

$fromId = \yii\helpers\Html::getInputId($handler, 'f');
$toId = \yii\helpers\Html::getInputId($handler, 't');

$selectedValue = "";
if ($val1 != $min) {
    $selectedValue .= "от ".\Yii::$app->formatter->asInteger($val1);
}
if ($val2 != $max) {
    $selectedValue .= " до ".\Yii::$app->formatter->asInteger($val2);
}
if ($selectedValue) {
    $selectedValue .= " р.";
}

?>
<? if ($min != $max
    //&& $max > 0
) : ?>
<!--<meta itemprop="lowPrice" content="<?php /*echo $val1; */?>">
<meta itemprop="highPrice" content="<?php /*echo $val2; */?>">-->

    <div class="dropdown sx-filter sx-inline-filter <?php echo $selectedValue ? "sx-filter-selected" : ""; ?>">
        <button class="dropdown-toggle btn btn-default sx-inline-btn" data-toggle="dropdown">
            <?= \Yii::t('app', 'Цена'); ?>
            <? if ($val1 != $min || $val2 != $max) : ?>
                <small>(<?= $selectedValue; ?>)</small>
            <? endif; ?>
        </button>
        <div class="dropdown-menu sort-slider sx-project-slider-skin keep-open" aria-labelledby="dropdownMenuButton">
            <div class="filter--group">
                <div class="filter--group--body sort-slider sx-project-slider-skin">
                    <div class="sort-slider__row">
                        <div class="sort-slider__input my-auto">
                            <?= $form->field($handler, "f")->textInput([
                                'placeholder' => \Yii::$app->money->currencyCode,
                                'id'          => 'sx-filter-price-from',
                                'value'       => $val1 == $min ? "" : $val1,
                                'placeholder' => $val1 == $min ? $val1 : "",
                                'class'       => 'sx-from form-control',
                            ])->label(false); ?>
                        </div>
                        <span class="sort-slider__devide my-auto">—</span>
                        <div class="sort-slider__input my-auto">
                            <?= $form->field($handler, "t")->textInput([
                                'placeholder' => \Yii::$app->money->currencyCode,
                                'id'          => 'sx-filter-price-to',
                                'value'       => $val2 == $max ? "" : $val2,
                                'placeholder' => $val2 == $max ? $val2 : "",
                                'class'       => 'sx-to form-control',
                            ])->label(false); ?>
                        </div>
                        <span class="sort-slider__measure my-auto">руб.</span>
                    </div>


                    <input type="text"
                           id="sx-filter-price"
                           class="slider-range"
                           data-no-submit="true"
                           data-type="double"
                           data-min="<?= $min ?>"
                           data-max="<?= $max ?>"
                           data-from="<?= $val1; ?>"
                           data-to="<?= $val2; ?>"
                           data-postfix=" р."/>


                </div>

                <div class="sx-btn-apply-wrapper">
                    <button type="submit" class="btn btn-primary">Применить</button>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>



