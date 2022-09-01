<?php
/**
 * @link https://cms.skeeks.com/
 * @copyright Copyright (c) 2010 SkeekS
 * @license https://cms.skeeks.com/license/
 * @author Semenov Alexander <semenov@skeeks.com>
 */
/* @var $this yii\web\View */
/*echo \skeeks\cms\base\Widget::widget('home', [
    'allow' => [
        ''
    ]
]);*/
//\Yii::$app->view->theme->bodyCssClass = 'sx-transparent-header';
//\skeeks\cms\themes\unify\assets\VanillaLazyLoadAsset::register($this);
?>

<?php if ($this->beginCache('_main-catalog_v1_' . \Yii::$app->cms->currentTree->id, [
    'duration' => 0,
    'dependency' => new \yii\caching\TagDependency([
        'tags' => [
            (new \skeeks\cms\models\CmsTree())->getTableCacheTagCmsSite(),
            \Yii::$app->cms->cmsSite->getTableCacheTag()
        ],
    ]),
])) : ?>
    <?php
    $catalogTree = \skeeks\cms\models\CmsTree::find()
        ->cmsSite()
        ->joinWith('treeType as treeType')
        //->andWhere(['treeType.code' => 'katalog-na-glavnuyu'])
        ->andWhere([\skeeks\cms\models\CmsTree::tableName().".id" => \Yii::$app->cms->currentTree->id])
        ->orderBy(['level' => SORT_ASC])->limit(1)->one();
    ?>
    <? if ($catalogTree && $catalogTree->activeChildren) : ?>
        <section class="sx-main-catalog" id="sx-main-catalog" style="padding-top: 40px;">
            <div class="container sx-container">
                <div class="row no-gutters">
                    <? foreach ($catalogTree->activeChildren as $tree) : ?>
                        <div class="col-lg-3 col-md-6 col-xs-12" style="margin-bottom: 40px;">

                            <div class="d-flex flex-row" style="line-height: 1.1; margin-bottom: 10px;">
                                <div class="my-auto">
                                    <img style="width: 50px; height: 50px; border-radius: 25%;"
                                         src="<?= \skeeks\cms\helpers\Image::getSrc(\Yii::$app->imaging->thumbnailUrlOnRequest($tree->mainImage ? $tree->mainImage->src : null,
                                             new \skeeks\cms\components\imaging\filters\Thumbnail([
                                                 'w' => 50,
                                                 'h' => 50,
                                                 'm' => \Imagine\Image\ManipulatorInterface::THUMBNAIL_INSET,
                                             ]), $tree->code
                                         )); ?>
                        " alt="<?= $tree->name; ?>">
                                </div>
                                <div class="my-auto" style="margin-left: 10px; ">
                                    <div class="h5" style="font-size: 20px; margin-bottom: 0; line-height: 1;">
                                        <a href="<?php echo $tree->url; ?>" style="color: black; font-weight: 600;" class="g-color-primary--hover g-text-underline--none--hover">
                                            <?php echo $tree->name; ?>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <?php if ($tree->activeChildren) : ?>
                                <?
                                $counter = 0;
                                foreach ($tree->activeChildren as $subTree) : ?>
                                    <?php
                                    $counter++;
                                    if ($counter <= 20) : ?>
                                        <div style="padding-left: 60px; line-height: 1.1; margin-bottom: 10px;">
                                            <a href="<?php echo $subTree->url; ?>" style="color: rgb(51, 51, 51); font-size: 15px;" class="g-color-primary--hover g-text-underline--none--hover">
                                                <?php echo $subTree->name; ?>
                                            </a>
                                        </div>
                                    <?php endif; ?>
                                <?php endforeach; ?>

                                <?php
                                if ($counter > 20) : ?>
                                    <div style="padding-left: 60px;">
                                        <a href="<?php echo $tree->url; ?>" style="color: rgb(51, 51, 51); font-weight: 600; font-size: 15px;" class="g-color-primary--hover g-text-underline--none--hover">
                                            И ещё <?php echo $counter - 3; ?>
                                        </a>
                                    </div>
                                <?php endif; ?>

                            <?php endif; ?>

                        </div>
                    <? endforeach; ?>
                </div>
            </div>
        </section>
    <? endif; ?>


    <?php $this->endCache(); ?>

<?php endif; ?>





