<?php
/**
 * @author Semenov Alexander <semenov@skeeks.com>
 * @link http://skeeks.com/
 * @copyright 2010 SkeekS (СкикС)
 * @date 25.05.2015
 */
/* @var $this   yii\web\View */
/* @var $widget \skeeks\cms\cmsWidgets\treeMenu\TreeMenuCmsWidget */
/* @var $model   \skeeks\cms\models\Tree */
?>

<div class="col-sm-4 col-6 sx-item-wrapper">
    <!-- Article -->
    <div class="sx-item">
        <div class="sx-img-wrapper">
            <img src="<?= \skeeks\cms\helpers\Image::getSrc(\Yii::$app->imaging->thumbnailUrlOnRequest($model->mainImage ? $model->mainImage->src : null,
                 new \skeeks\cms\components\imaging\filters\Thumbnail([
                     'w' => 900,
                     'h' => 500,
                     'm' => \Imagine\Image\ManipulatorInterface::THUMBNAIL_OUTBOUND,
                 ]), $model->code
             )); ?>" class="img-fluid">
        </div>
        <div class="sx-info">
            <div class="sx-desc-wrapper">
            <div class="h4 sx-title"><?= $model->name; ?></div>
            <?php if($model->description_short) : ?>
                <div class="sx-desc"><?= $model->description_short; ?></div>
            <?php endif; ?>
            </div>
            <div class="sx-btn-wrapper">
            <a class="btn btn-md btn-primary" href="<?= $model->url; ?>">Смотреть</a>
            </div>
        </div>
    </div>
</div>
