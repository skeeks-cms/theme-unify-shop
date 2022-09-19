<?php
/**
 * @link https://cms.skeeks.com/
 * @copyright Copyright (c) 2010 SkeekS
 * @license https://cms.skeeks.com/license/
 * @author Semenov Alexander <semenov@skeeks.com>
 */
/**
 * @var $this yii\web\View
 * @var $model \skeeks\cms\models\CmsContentElement
 */
\skeeks\cms\themes\unify\assets\VanillaLazyLoadAsset::register($this);

?>

<div class="sx-brand-item-wrapper col-lg-2 col-md-3 col-6">
    <a href="<?php echo $model->url; ?>" class="sx-brand-item">
        <div class="sx-image">
            <img
                class="lazy img-responsive"
                style="aspect-ratio: 1;"
                src="<?php echo \Yii::$app->cms->image1px; ?>"
                data-src="<?= \Yii::$app->imaging->thumbnailUrlOnRequest($model->image ? $model->image->src : \skeeks\cms\helpers\Image::getCapSrc(),
                    new \skeeks\cms\components\imaging\filters\Thumbnail([
                        'w' => 180,
                        'h' => 180,
                    ]), $model->image ? $model->code : ''
                ); ?>"
            />
        </div>
        <div class="sx-caption">
            <div class="sx-title">
                <?php echo $model->name; ?>
            </div>
            <?php
            $model->relatedPropertiesModel->initAllProperties();
            if($model->relatedPropertiesModel->getAttribute("country")) : ?>
                <div class="sx-country">
                    <?php echo $model->relatedPropertiesModel->getAttributeAsText("country"); ?>
                </div>
            <?php endif; ?>

        </div>
    </a>
</div>