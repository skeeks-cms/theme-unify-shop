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
    <div class="g-mb-20">
        <div
                id="stickyblock-start"
                class="g-pa-5 js-sticky-block"
                data-start-point="#stickyblock-start" data-end-point=".sx-footer"
        >

            <? if ($parent && $parent->activeChildren) : ?>

                <div class="u-heading-v3-1 g-mb-10">
                    <h2 class="h5 u-heading-v3__title sx-col-left-title g-brd-primary ">
                        <?= \Yii::t('skeeks/unify', 'Categories'); ?>
                    </h2>
                </div>

                <ul class="list-unstyled mb-0 sx-col-menu">

                    <? foreach ($parent->activeChildren as $child) : ?>

                        <li class="g-brd-bottom g-brd-gray-light-v4 g-pb-10 g-mb-12">
                            <i class="fas fa-angle-right g-color-gray-dark-v5 g-mr-5"></i>
                            <a class="<?= $child->id == $model->id ? "active g-color-primary" : "sx-main-text-color"; ?> u-link-v5 g-color-primary--hover"
                               href="<?= $child->url; ?>">
                                <?= $child->name; ?>
                                <!--<i class="fas fa-leaf float-right align-middle" style="color: gray;"></i>-->
                            </a>
                        </li>
                    <? endforeach; ?>

                </ul>

            <? endif; ?>

        </div>
    </div>


<? endif; ?>

<? if (!$isShowFilters) : ?>
<div class="g-mb-20">
    <div style="display: none;">
        <? endif; ?>
        <? $filtersWidget::end(); ?>
        <? if (!$isShowFilters) : ?>
    </div>
</div>
<? endif; ?>

<!--<div id="stickyblock-start" class="g-pa-5 js-sticky-block" data-start-point="#stickyblock-start" data-end-point=".sx-footer">

</div>-->

<div class="g-mb-20">
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
</div>