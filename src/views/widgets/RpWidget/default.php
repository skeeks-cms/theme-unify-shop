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
            if ($value) : ?>
                <tr>
                    <td>
                        <? $property = $widget->model->relatedPropertiesModel->getRelatedProperty($code); ?>
                        <?= $property->name; ?>
                        <? if ($property->hint) : ?>
                            <i class="far fa-question-circle" title="<?= $property->hint; ?>"></i>
                        <? endif; ?>
                    </td>
                    <td class="rtd">
                        <?php if($url = $widget->getUrl($code)) : ?>
                            <a href="<?php echo $url; ?>" data-pjax="0">
                                <?= $value; ?>
                                <? if ($property->cms_measure_code) : ?>
                                    <?= $property->cmsMeasure->asShortText; ?>
                                <? endif; ?>
                            </a>
                        <?php else : ?>
                            <?= $value; ?>
                            <? if ($property->cms_measure_code) : ?>
                                <?= $property->cmsMeasure->asShortText; ?>
                            <? endif; ?>
                        <?php endif; ?>
                    </td>
                </tr>
            <? endif; ?>
        <? endforeach; ?>
    </table>
<? endif;  ?>