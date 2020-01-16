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

$val1 = $handler->from ? $handler->from : $min;
$val2 = $handler->to ? $handler->to : $max;

$fromId = \yii\helpers\Html::getInputId($handler, 'from');
$toId = \yii\helpers\Html::getInputId($handler, 'to');
?>
<? if ($min != $max
    //&& $max > 0
) : ?>
    <section class="filter--group opened <?= ($val1 != $min || $val2 != $max) ? "sx-filter-selected" : "" ?>">
        <header class="filter--group--header">Цена</header>
        <div class="filter--group--body sort-slider sx-project-slider-skin">
            <div class="filter--group--inner">
                <div class="sort-slider__row">
                    <div class="sort-slider__input">
                        <?= $form->field($handler, "from")->textInput([
                            'placeholder' => \Yii::$app->money->currencyCode,
                            'id'          => 'sx-filter-price-from',
                            'value' => $val1,
                            'class' => 'sx-from form-control',
                        ])->label(false); ?>
                    </div>
                    <span class="sort-slider__devide">—</span>
                    <div class="sort-slider__input">
                        <?= $form->field($handler, "to")->textInput([
                            'placeholder' => \Yii::$app->money->currencyCode,
                            'id'          => 'sx-filter-price-to',
                            'value' => $val2,
                            'class' => 'sx-to form-control',
                        ])->label(false); ?>
                    </div>
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


                <!--<div class="" style="display: block;">
                    <div class="col-md-6">
                        <?/*= $form->field($handler, "from")->textInput([
                            'placeholder' => \Yii::$app->money->currencyCode,
                            'id'          => 'sx-filter-price-from',
                        ])->label("От"); */?>
                    </div>
                    <div class="col-md-6">
                        <?/*= $form->field($handler, "to")->textInput([
                            'placeholder' => \Yii::$app->money->currencyCode,
                            'id'          => 'sx-filter-price-to',
                        ])->label("До"); */?>
                    </div>
                </div>-->

                <? $this->registerJs(<<<JS
/*
$('#sx-filter-price').ionRangeSlider({
    onFinish: function (data) {
        $("#sx-filter-price-from").val(data.from);
        $("#sx-filter-price-to").val(data.to);
        $("#sx-filter-price-to").change();
    },
  
});*/

JS
                ); ?>
            </div>
        </div>
    </section>


<? endif; ?>

