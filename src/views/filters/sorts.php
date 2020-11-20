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
?>
<div class="sorting sx-filters-form row">
    <div class="col-12">
        <? if (@$sortFiltersHandler) : ?>
            <?php echo $sortFiltersHandler->renderVisible(); ?>
        <? endif; ?>
        <? if (@$availabilityFiltersHandler) : ?>
            <?= $availabilityFiltersHandler->renderVisible(); ?>
        <? endif; ?>
    </div>
</div>
