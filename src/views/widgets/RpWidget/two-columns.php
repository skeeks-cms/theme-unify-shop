<?php
/**
 * @author Semenov Alexander <semenov@skeeks.com>
 * @link http://skeeks.com/
 * @copyright 2010 SkeekS (СкикС)
 * @date 08.12.2016
 */
/* @var $this yii\web\View */
/* @var $widget \skeeks\cms\rpViewWidget\RpViewWidget */
?>
<? if ($attributes = $widget->rpAttributes) :  ?>
    <ul class="sx-properties">
        <? $counter = 0; ?>
        <? foreach ($attributes as $code => $value ) :
            if ($value) : ?>
                <li>
                    <span class="sx-properties--name">
                        <? $property = $widget->model->relatedPropertiesModel->getRelatedProperty($code); ?>
                        <?= $property->name; ?>
                        <? if ($property->hint) : ?>
                            <i class="far fa-question-circle" title="<?= $property->hint; ?>"></i>
                        <? endif; ?>
                    </span>
                    <span class="sx-properties--value">
                        <?= $value; ?>
                        <? if ($property->cms_measure_code) : ?>
                            <?= $property->cmsMeasure->asShortText; ?>
                        <? endif; ?>
                    </span>
                </li>
            <? endif; ?>
        <? endforeach; ?>
    </ul>
<? endif;  ?>