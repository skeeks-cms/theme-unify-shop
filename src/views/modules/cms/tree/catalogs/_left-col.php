<?php
/**
 * @link https://cms.skeeks.com/
 * @copyright Copyright (c) 2010 SkeekS
 * @license https://cms.skeeks.com/license/
 * @author Semenov Alexander <semenov@skeeks.com>
 */
/* @var $this yii\web\View */
/* @var $catalogSettings \skeeks\cms\themes\unifyshop\cmsWidgets\catalog\ShopCatalogPage */
/* @var $model \skeeks\cms\models\CmsTree */
/* @var $savedFilter \skeeks\cms\models\CmsSavedFilter */
/* @var $appliedValues array */
?>

<? if ($catalogSettings->is_show_subtree_col_left) : ?>
    <?
    $this->registerCss(<<<CSS
.sx-col-menu i {
    font-size: 12px;
}

.sx-col-menu .sx-children-cat {
    margin-left: 20px;
}
.sx-col-menu .btn {
    text-align: left;
    line-height: 1.1;
}
CSS
    );

    ?>

    <div class="sx-col-left-block">

        <?
        //Если есть дочерние разделы или есть родительские больше чем главная
        if ($model->activeChildren || count($model->parents) > 1) : ?>

        <?php
        $model = clone \Yii::$app->cms->currentTree;
        $model->refresh();

        $data = [];

        foreach ($model->parents as $parent)
        {
            if($parent->level > 0) {
                $data[] = [
                    'tree' => $parent,
                    'isParent' => $parent
                ];
            }
        }

        if ($savedFilter) {
            $data[] = [
                'tree' => $savedFilter->cmsTree,
                'isParent' => $parent
            ];
            $data[] = [
                'savedFilter' => $savedFilter,
                'isCurrent' => true,
            ];

            /*if ($model->activeChildren) {
                foreach ($model->activeChildren  as $child)
                {
                    $data[] = [
                        'tree' => $child
                    ];
                }
            }*/
        } else {


            if ($appliedValues) {
                $data[] = [
                    'tree' => $model,
                    'isParent' => true,
                ];
                $data[] = [
                    'name' => "Применены фильтры",
                    'isCurrent' => true,
                ];
            } else {
                $data[] = [
                    'tree' => $model,
                    'isCurrent' => true,
                ];

                if ($model->activeChildren) {
                    foreach ($model->activeChildren  as $child)
                    {
                        $data[] = [
                            'tree' => $child,
                            'isChildren' => true
                        ];
                    }
                }
            }



        }



        ?>




        <?php if($data) : ?>
            <!--<div class="g-mb-10">
                <div class="h5 sx-col-left-title">
                    <?/*= \Yii::t('skeeks/unify', 'Categories'); */?>
                </div>
            </div>-->

            <ul class="list-unstyled mb-0 sx-col-menu">
            <? foreach ($data as $counter => $row) : ?>
                <?php
                /**
                 * @var $savedFilter \skeeks\cms\models\CmsSavedFilter
                 */
                    $name = \yii\helpers\ArrayHelper::getValue($row, "name");
                    $cmsTree = \yii\helpers\ArrayHelper::getValue($row, "tree");
                    $isCurrent = \yii\helpers\ArrayHelper::getValue($row, "isCurrent");
                    $isParent = \yii\helpers\ArrayHelper::getValue($row, "isParent");
                    $isChildren = \yii\helpers\ArrayHelper::getValue($row, "isChildren");
                    $savedFilter = \yii\helpers\ArrayHelper::getValue($row, "savedFilter");
                    $title = '';
                    if ($counter == 0) {
                        $title = "Смотреть все товары";
                    } elseif ($isParent) {
                        $title = "Смотреть все товары из раздела «{$cmsTree->seoName}»";
                    }
                ?>
                <?php if($cmsTree) : ?>
                    <li class="<?php echo $isChildren ? "sx-children-cat" : ""; ?>">
                        <? if($isCurrent) : ?>
                            <div class="sx-main-text-color g-text-underline--none--hover btn btn-primary btn-block"
                               data-toggle="tooltip" title="<?php echo $title; ?>"
                            >
                                <?php if($isParent) : ?>
                                    <i class="hs-icon hs-icon-arrow-left"></i>
                                <?php endif; ?>
                                <?= $cmsTree->name; ?>
                            </div>
                        <? else : ?>
                            <a class="sx-main-text-color g-text-underline--none--hover"
                               data-toggle="tooltip" title="<?php echo $title; ?>"
                               href="<?= $cmsTree->url; ?>">
                                <?php if($isParent) : ?>
                                    <i class="hs-icon hs-icon-arrow-left"></i>
                                <?php endif; ?>
                                <?= $cmsTree->name; ?>
                            </a>
                        <? endif; ?>

                    </li>
                <?php endif; ?>
                <?php if($savedFilter) : ?>
                    <li class="<?php echo $isChildren ? "sx-children-cat" : ""; ?>">
                        <div class="sx-main-text-color g-text-underline--none--hover <?php echo $isCurrent ? "btn btn-primary btn-block" : ""; ?>"
                           data-toggle="tooltip" title="<?php echo $title; ?>"
                         >
                            <?= $savedFilter->shortSeoName; ?>
                        </div>
                    </li>
                <?php endif; ?>

                <?php if($name) : ?>
                    <li class="<?php echo $isChildren ? "sx-children-cat" : ""; ?>">
                        <div class="sx-main-text-color g-text-underline--none--hover <?php echo $isCurrent ? "btn btn-primary btn-block" : ""; ?>"
                           data-toggle="tooltip" title="<?= $name; ?>"
                         >
                            <?= $name; ?>
                        </div>
                    </li>
                <?php endif; ?>
            <?php endforeach; ?>
            </ul>
        <?php endif; ?>





        <? endif; ?>

    </div>


<? endif; ?>

<? if (!\Yii::$app->view->theme->is_allow_filters) : ?>
<div class="sx-col-left-block">
    <div style="display: none;">
        <? endif; ?>
        <?
        \skeeks\cms\themes\unify\widgets\filters\assets\FiltersWidgetAsset::register($this);
        $pjax = \skeeks\cms\widgets\PjaxLazyLoad::begin(); ?>
        <?php if ($pjax->isPjax) : ?>
            <? echo $filtersWidget->run(); ?>
        <?php else : ?>

        <?php endif; ?>
        <? $pjax::end(); ?>

        <? if (!\Yii::$app->view->theme->is_allow_filters) : ?>
    </div>
</div>
<? endif; ?>
