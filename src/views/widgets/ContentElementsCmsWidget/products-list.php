<?php
/**
 * @author Semenov Alexander <semenov@skeeks.com>
 * @link http://skeeks.com/
 * @copyright 2010 SkeekS (СкикС)
 * @date 25.05.2015
 */
/* @var $this   yii\web\View */
/* @var $widget \skeeks\cms\cmsWidgets\contentElements\ContentElementsCmsWidget */
?>
<? if ($widget->label) : ?>
    <h1 class="size-17 margin-bottom-20"><?= $widget->label; ?></h1>
<? endif; ?>

<? echo \yii\widgets\ListView::widget([
    'dataProvider' => $widget->dataProvider,
    'itemView'     => 'product-item',
    'emptyText'    => '',
    'options'      => [
        'class' => '',
        'tag'   => 'div',
    ],
    'itemOptions' => [
        'tag' => 'div',
        'class' => 'col-lg-4 col-sm-6 item'
    ],
    'layout'       => '<div class="no-gutters row list-view">{items}</div><div class="row"><div class="col-md-12">{summary}</div><div class="col-md-12">{pager}</div></div>',
]) ?>
