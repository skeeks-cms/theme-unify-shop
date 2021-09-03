<?php
/**
 * @author Semenov Alexander <semenov@skeeks.com>
 * @link http://skeeks.com/
 * @copyright 2010 SkeekS (СкикС)
 * @date 06.03.2015
 *
 * @var \skeeks\cms\models\CmsContentElement $model
 *
 */
?>
<? if ($model->image) : ?>
<a href="<?php echo $model->url; ?>">
    <img class="img-fluid mx-auto" src="<?= \Yii::$app->imaging->thumbnailUrlOnRequest($model->image->src,
        new \skeeks\cms\components\imaging\filters\Thumbnail([
            'w' => 200,
            'h' => 200,
            'm' => \Imagine\Image\ImageInterface::THUMBNAIL_INSET,
        ]), $model->code
    ) ?>" alt="<?= $model->name; ?>">
</a>
<? endif; ?>
