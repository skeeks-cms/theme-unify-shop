<?php
/**
 * @author Semenov Alexander <semenov@skeeks.com>
 * @link http://skeeks.com/
 * @copyright 2010 SkeekS (СкикС)
 * @date 25.05.2015
 */
/* @var $this   yii\web\View */
/* @var $widget \skeeks\cms\cmsWidgets\contentElements\ContentElementsCmsWidget */
if (!\Yii::$app->shop->is_show_product_no_price) {
    $widget->dataProvider->query->joinWith('shopProduct.shopProductPrices as pricesFilter');
    $widget->dataProvider->query->andWhere(['>', '`pricesFilter`.price', 0]);
}
?>
<? if ($widget->label) : ?>
    <h1 class="size-17 margin-bottom-20"><?= $widget->label; ?></h1>
<? endif; ?>

<? echo \yii\widgets\ListView::widget([
    'dataProvider' => $widget->dataProvider,
    'itemView'     => '@app/views/widgets/ContentElementsCmsWidget/product-item',
    'emptyText'    => '',
    'options'      => [
        'class' => '',
        'tag'   => 'div',
    ],
    'itemOptions'  => [
        'tag'   => 'div',
        'class' => 'col-lg-4 col-sm-6 item',
    ],
    'pager'        => [
        'class' => \skeeks\cms\themes\unify\widgets\ScrollAndSpPager::class
    ],
    //"\n{items}<div class=\"box-paging\">{pager}</div>{summary}<div class='sx-js-pagination'></div>",
    'layout'       => '<div class="row"><div class="col-md-12">{summary}</div></div>
<div class="no-gutters row list-view">{items}</div>
<div class="row"><div class="col-md-12">{pager}</div></div>',
]) ?>
