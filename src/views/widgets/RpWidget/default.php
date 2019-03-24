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
    <table class="table table-striped">
        <? $counter = 0; ?>
        <? foreach ($attributes as $code => $value ) :
            if (!in_array($code, ['propertySameProducts','video', 'simillar'])) : ?>
                <tr>
                    <td><?= $widget->model->relatedPropertiesModel->getRelatedProperty($code)->name; ?></td>
                    <td class="rtd"><?= $value; ?></td>
                </tr>
            <? endif; ?>
        <? endforeach; ?>
    </table>
<? endif;  ?>