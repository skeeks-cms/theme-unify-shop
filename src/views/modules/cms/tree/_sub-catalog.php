<?php
/**
 * @link https://cms.skeeks.com/
 * @copyright Copyright (c) 2010 SkeekS
 * @license https://cms.skeeks.com/license/
 * @author Semenov Alexander <semenov@skeeks.com>
 */
/* @var $this yii\web\View */
$this->registerCss(<<<CSS
.sx-sub-catalog .sx-item-wrapper {
    padding-bottom: 2rem;
}
.sx-sub-catalog .sx-item {
    border: 1px solid #1079AC29;
    border-radius: var(--base-radius);
    text-align: center;
    overflow: hidden;
    height: 100%;
}
.sx-sub-catalog .sx-item .sx-info-wrapper {
    padding: 1rem;
}

.sx-sub-catalog .sx-item .sx-img-wrapper {
}
.sx-sub-catalog .sx-item img {
    width: 100%;
}
CSS
);
?>

<?php if ($this->beginCache('_main-sub_catalog_v3_'.\Yii::$app->cms->currentTree->id, [
    'duration'   => 0,
    'dependency' => new \yii\caching\TagDependency([
        'tags' => [
            (new \skeeks\cms\models\CmsTree())->getTableCacheTagCmsSite(),
            \Yii::$app->cms->cmsSite->getTableCacheTag(),
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
        <section class="sx-sub-catalog" id="sx-sub-catalog">
            <div class="container sx-container">
                <div class="row">
                    <? foreach ($catalogTree->activeChildren as $tree) : ?>
                        <div class="col-lg-3 col-md-6 col-6 sx-item-wrapper">

                            <div class="sx-item">
                                <div class="sx-img-wrapper">
                                    <a href="<?php echo $tree->url; ?>">
                                    <img
                                         src="<?= \skeeks\cms\helpers\Image::getSrc(\Yii::$app->imaging->thumbnailUrlOnRequest($tree->mainImage ? $tree->mainImage->src : null,
                                             new \skeeks\cms\components\imaging\filters\Thumbnail([
                                                 'w' => 300,
                                                 'h' => 300,
                                                 'm' => \Imagine\Image\ManipulatorInterface::THUMBNAIL_INSET,
                                             ]), $tree->code
                                         )); ?>
                                        " alt="<?= $tree->name; ?>">
                                    </a>
                                </div>
                                <div class="sx-info-wrapper">
                                    <div class="h5">
                                        <a href="<?php echo $tree->url; ?>">
                                            <?php echo $tree->name; ?>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <? endforeach; ?>
                </div>
            </div>
        </section>
    <? endif; ?>


    <?php $this->endCache(); ?>

<?php endif; ?>





