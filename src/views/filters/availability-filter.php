<?php
/**
 * @author Semenov Alexander <semenov@skeeks.com>
 * @link https://skeeks.com/
 * @copyright (c) 2010 SkeekS
 * @date 13.11.2017
 */
/* @var $this yii\web\View */
/* @var $handler \skeeks\cms\shop\queryFilter\AvailabilityFiltersHandler */
/* @var $form \yii\widgets\ActiveForm */
/* @var $code string */
$widget = $this->context;
$id = \yii\helpers\Html::getInputId($handler, 'value');

$this->registerJs(<<<JS
            
$("#check-in-stock").on('change', function() {
    if ($(this).is(":checked")) {
        $("[data-value=sx-availability]").val(1);
    } else {
        $("[data-value=sx-availability]").val(0);
    }
    
    $("[data-value=sx-availability]").change();
});
JS
);
?>
<div class="checkbox in-stock">
    <input type="checkbox" <?= $handler->value == 1 ? "checked" : "" ; ?> id="check-in-stock" />
    <label for="check-in-stock" class="sx-label">В наличии</label>
</div>
