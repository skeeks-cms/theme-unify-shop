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
<!--<a href="#" class="sx-filter-action g-color-primary--hover <?/*= $handler->value == '-popular' ? "active g-color-primary g-brd-primary" : "" ; */?>" data-filter="productfilters-sort" data-filter-value="-popular"><?/*= \Yii::t("skeeks/unify", "Popular"); */?></a>
<a href="#" class="sx-filter-action g-color-primary--hover <?/*= $handler->value == 'price' ? "active g-color-primary g-brd-primary" : "" ; */?>" data-filter="productfilters-sort" data-filter-value="price"><?/*= \Yii::t("skeeks/unify-shop", "Cheap first"); */?></a>
<a href="#" class="sx-filter-action g-color-primary--hover <?/*= $handler->value == '-price' ? "active g-color-primary g-brd-primary" : "" ; */?>" data-filter="productfilters-sort" data-filter-value="-price"><?/*= \Yii::t("skeeks/unify-shop", "Dear first"); */?></a>
<a href="#" class="sx-filter-action g-color-primary--hover <?/*= $handler->value == '-new' ? "active g-color-primary g-brd-primary" : "" ; */?>" data-filter="productfilters-sort" data-filter-value="-new"><?/*= \Yii::t("skeeks/unify", "New"); */?></a>
-->
<div class="dropdown sx-inline-filter" style="display: inline-block;">
    <a href="#" class="btn btn-secondary dropdown-toggle g-text-underline--none--hover" data-toggle="dropdown" style="color: #555;
background-color: #fff;">
        <span class="Select-arrow-zone"></span> <?php echo $handler->valueAsText; ?>
    </a>
    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
        <? foreach ($handler->getSortOptions() as $code => $name) : ?>
            <a class="dropdown-item sx-select-sort sx-filter-action" href="#" data-filter="productfilters-sort" data-filter-value="<?php echo $code; ?>"><?php echo $name; ?></a>
        <? endforeach; ?>
    </div>
</div>