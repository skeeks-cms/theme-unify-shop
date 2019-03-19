<?php
/**
 * @author Semenov Alexander <semenov@skeeks.com>
 * @link http://skeeks.com/
 * @copyright 2010 SkeekS (СкикС)
 * @date 28.07.2016
 */
/* @var $this yii\web\View */
/* @var $availabilityFiltersHandler \skeeks\cms\shop\queryFilter\AvailabilityFiltersHandler */
/* @var $sortFiltersHandler \skeeks\cms\shop\queryFilter\sortFiltersHandler */
$availabilityFiltersHandler->viewFileVisible = '@app/views/filters/availability-filter';
$sortFiltersHandler->viewFileVisible = '@app/views/filters/sort-filter';
?>


<div class="sorting sx-filters-form">

    <?= $this->registerJs(<<<JS
(function(sx, $, _)
{
sx.classes.Filters = sx.classes.Component.extend({
    _onDomReady: function()
    {
        /*$('.sx-filter-action').on('click', function()
        {
            var jForm = $('#sx-filters');
            $('#' + $(this).data('filter')).val($(this).data('filter-value'));
            jForm.submit();
            return false;
        });*/
        /*$('.sx-filter-action-checkbox').on('click', function()
        {
            var checkboxValue;
            if ($(this).attr("checked") != 'checked')
            {
                checkboxValue = 1;
            }
            else
                checkboxValue = 1;
            var jForm = $('#sx-filters');
            $('#' + $(this).data('filter')).val($(this).data('filter-value'));
            jForm.submit();
            return false;
        });*/
    },
});

new sx.classes.Filters();
})(sx, sx.$, sx._);
JS
); ?>
        <div class="sort">
            <div class="lbl">
                Сортировать:
            </div>
            <div class="vals">
                <?= $sortFiltersHandler->renderVisible(); ?>
            </div>

        </div>

        <?= $availabilityFiltersHandler->renderVisible(); ?>


</div>

<div class="row">
    <div class="col-md-12 sx-filters-selected-wrapper">

    </div>
</div>