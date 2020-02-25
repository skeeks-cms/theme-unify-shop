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
<div class="sorting sx-filters-form">
    <? if ($sortFiltersHandler) : ?>
    <div class="sort">
        <div class="lbl">
            <?= \Yii::t("skeeks/unify", "Sorting"); ?>:
        </div>
        <div class="vals">
            <?= $sortFiltersHandler->renderVisible(); ?>
        </div>
    </div>
    <? endif; ?>
    <? if (@$availabilityFiltersHandler) : ?>
        <?= $availabilityFiltersHandler->renderVisible(); ?>
    <? endif; ?>
</div>
<div class="row g-mt-10">
    <div class="col-md-12 sx-filters-selected-wrapper">
    </div>
</div>