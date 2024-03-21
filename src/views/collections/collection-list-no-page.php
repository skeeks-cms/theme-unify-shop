<?php
/**
 * @author Semenov Alexander <semenov@skeeks.com>
 * @link http://skeeks.com/
 * @copyright 2010 SkeekS (СкикС)
 * @date 25.05.2015
 */
/* @var $this   yii\web\View */
/* @var $dataProvider \yii\data\ActiveDataProvider */
if (!@$itemClasses) {
    $itemClasses = 'col-sm-6 col-lg-4';
}
$this->registerCss(<<<CSS
.sx-collection-list-item-wrapper {
    margin-top: 5px;
    margin-bottom: 5px;
}
.sx-collection-list .sx-collection-list-item-wrapper {
    padding-right: 7px !important;
    padding-left: 7px !important;
}
.sx-collection-list {
    margin-right: -7px !important;
    margin-left: -7px !important;
}
.sx-collection-list-item {
    border-radius: var(--base-radius);
    overflow: hidden;
}
CSS
);
?>
<div class="sx-products-slider-wrapper">
        <? if ($label) : ?>
            <div class="sx-products-slider--title">
                <div class="h2"><?= $label; ?></div>
                <!--<p class="lead">We want to create a range of beautiful, practical and modern outerwear that doesn't cost the earth – but let's you still live life in style.</p>-->
            </div>
        <? endif; ?>
<!--<meta itemprop="offerCount" content="<?php /*echo $total; */?>">-->
<? echo \yii\widgets\ListView::widget([
    'dataProvider' => $dataProvider,
    'itemView'     => '@app/views/collections/collection-list-item',
    'emptyText'    => '',
    'options'      => [
        'class' => '',
        'tag'   => false,
    ],
    /*'itemOptions'  => [
        'tag'   => 'div',
        'class' => $itemClasses . ' item sx-collection-list-item-wrapper',
    ],*/
    'itemOptions'  => \yii\helpers\ArrayHelper::merge([
        'tag'   => 'div',
        'class' => \Yii::$app->view->theme->prooductListItemCssClasses . ' item sx-collection-list-item-wrapper',
    ], (array)@$itemOptions),
    'pager'        => [
        'container' => '.sx-product-list',
        'item'      => '.sx-product-card-wrapper',
        'class'     => \skeeks\cms\themes\unify\widgets\ScrollAndSpPager::class,
    ],
    //'summary'      => "Всего коллекций: {totalCount}",
    'summary'      => false,
    //"\n{items}<div class=\"box-paging\">{pager}</div>{summary}<div class='sx-js-pagination'></div>",
    'layout'       => '<div class="row"><div class="col-md-12 sx-product-list-summary">{summary}</div></div>
    <div class="no-gutters row list-view sx-collection-list">{items}</div>
    ',
])
?>
</div>
