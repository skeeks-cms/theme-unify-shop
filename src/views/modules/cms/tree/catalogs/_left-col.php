<?php
/**
 * @link https://cms.skeeks.com/
 * @copyright Copyright (c) 2010 SkeekS
 * @license https://cms.skeeks.com/license/
 * @author Semenov Alexander <semenov@skeeks.com>
 */
/* @var $this yii\web\View */
/* @var $catalogSettings \skeeks\cms\themes\unifyshop\cmsWidgets\catalog\ShopCatalogPage */
?>

<? if ($catalogSettings->is_show_subtree_col_left) : ?>
    <?
    $model = \Yii::$app->cms->currentTree;
    $menuName = $model ? $model->name : "Меню";
    $parent = $model;
    if ($model) {

        if ($model->activeChildren) {
            $parent = $model;
        } elseif ($model->parent) {
            $parent = $model->parent;
        } elseif (isset($model->parents[1])) {
            $parent = $model->parents[1];
            $menuName = $parent->name;

            if (!$parent->activeChildren) {
                $parent = $model->parents[0];
            }
        } else {
            $parent = $model->parents[0];
        }
    }
    ?>
    <div class="sx-col-left-block">


        <? if ($parent && $parent->activeChildren) : ?>

            <div class="g-mb-10">
                <div class="h5 sx-col-left-title">
                    <?= \Yii::t('skeeks/unify', 'Categories'); ?>
                </div>
            </div>

            <ul class="list-unstyled mb-0 sx-col-menu">
                <? foreach ($parent->activeChildren as $child) : ?>
                    <li class="">
                        <a class="<?= $child->id == $model->id ? "active g-color-primary" : "sx-main-text-color"; ?> u-link-v5 g-color-primary--hover g-text-underline--none--hover"
                           href="<?= $child->url; ?>">
                            <?= $child->name; ?>
                        </a>
                    </li>
                <? endforeach; ?>
            </ul>

        <? endif; ?>

    </div>


<? endif; ?>

<? if (!\Yii::$app->unifyShopTheme->is_allow_filters) : ?>
<div class="sx-col-left-block">
    <div style="display: none;">
        <? endif; ?>
        <?php /*\skeeks\assets\unify\base\UnifyHsStickyBlockAsset::register($this); */ ?><!--
        <div id="stickyblock-start"
             class="js-sticky-block"
             data-has-sticky-header="true" 
             data-start-point="#stickyblock-start" data-end-point=".sx-footer">-->

        <? echo $filtersWidget->run(); ?>
        <!--</div>-->
        <? if (!\Yii::$app->unifyShopTheme->is_allow_filters) : ?>
    </div>
</div>
<? endif; ?>

<!--<div id="stickyblock-start" class="g-pa-5 js-sticky-block" data-start-point="#stickyblock-start" data-end-point=".sx-footer">

</div>-->

<? $content = \skeeks\cms\models\CmsContent::find()->where(['code' => 'news'])->one(); ?>
<?= \skeeks\cms\cmsWidgets\contentElements\ContentElementsCmsWidget::widget([
    'namespace'          => 'ContentElementsCmsWidget-left-news',
    'viewFile'           => '@app/views/widgets/ContentElementsCmsWidget/left-news',
    'label'              => 'Новости',
    'content_ids'        => [
        $content ? $content->id : "",
    ],
    'enabledCurrentTree' => \skeeks\cms\components\Cms::BOOL_N,
    'enabledRunCache'    => \skeeks\cms\components\Cms::BOOL_N,
]); ?>
