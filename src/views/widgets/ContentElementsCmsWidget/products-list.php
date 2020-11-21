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
<? echo \yii\widgets\ListView::widget([
    'dataProvider' => $widget->dataProvider,
    'itemView'     => '@app/views/widgets/ContentElementsCmsWidget/product-item',
    'emptyText'    => '',
    'options'      => [
        'class' => '',
        'tag'   => 'div',
    ],
    'itemOptions'  => \yii\helpers\ArrayHelper::merge([
        'tag'   => 'div',
        'class' => 'col-lg-4 col-6 sx-product-card-wrapper',
    ], (array)@$itemOptions),
    'pager'        => [
        'container' => '.sx-product-list',
        'item'      => '.sx-product-card-wrapper',
        'class'     => \skeeks\cms\themes\unify\widgets\ScrollAndSpPager::class,
    ],
    'summary'      => "Всего товаров: {totalCount}",
    //"\n{items}<div class=\"box-paging\">{pager}</div>{summary}<div class='sx-js-pagination'></div>",
    'layout'       => '<div class="row"><div class="col-md-12 sx-product-list-summary">{summary}</div></div>
    <div class="no-gutters row sx-product-list">{items}</div>
    <div class="row"><div class="col-md-12">{pager}</div></div>',
])
?>
