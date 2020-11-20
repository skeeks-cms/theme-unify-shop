<?php
/**
 * @author Semenov Alexander <semenov@skeeks.com>
 * @link https://skeeks.com/
 * @copyright (c) 2010 SkeekS
 * @date 13.11.2017
 */
/* @var $this yii\web\View */
/* @var $handler \skeeks\cms\shop\queryFilter\SortFiltersHandler */
/* @var $form \yii\widgets\ActiveForm */
/* @var $code string */
$widget = $this->context;
$id = \yii\helpers\Html::getInputId($handler, 'value');

$this->registerJs(<<<JS
            
$('.sx-filter-action').on('click', function()
{
    $("[data-value=sx-sort]").val($(this).data('filter-value'));
    $("[data-value=sx-sort]").change();
    return false;
});

JS
);
?>
<div class="dropdown sx-inline-filter" style="display: inline-block;">
    <a href="#" class="btn dropdown-toggle sx-btn-white sx-btn-sort-select sx-icon-arrow-down--after" data-toggle="dropdown" style="">
        <?php echo $handler->valueAsText; ?>
    </a>
    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
        <? foreach ($handler->getSortOptions() as $code => $name) : ?>
            <a class="dropdown-item sx-select-sort sx-filter-action" href="#" data-filter="productfilters-sort" data-filter-value="<?php echo $code; ?>"><?php echo $name; ?></a>
        <? endforeach; ?>
    </div>
</div>