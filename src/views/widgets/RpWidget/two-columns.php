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
<?
    /*print_r($attributes);die;*/
    ?>
    <ul class="sx-properties">
        <? $counter = 0; ?>
        <? foreach ($attributes as $code => $value ) :
            if ($value) : ?>
                <li>
                    <span class="sx-properties--name">
                        <? $property = $widget->model->relatedPropertiesModel->getRelatedProperty($code); ?>
                        <?= $property->name; ?>
                        
                        <? if ($property->hint) : ?>
                            <i class="far fa-question-circle" title="<?= $property->hint; ?>" data-toggle="tooltip" style="margin-left: 5px;"></i>
                        <? endif; ?>
                    </span>
                    <span class="sx-properties--value">
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


                    </span>
                </li>
            <? endif; ?>
        <? endforeach; ?>
    </ul>
<? endif;  ?>