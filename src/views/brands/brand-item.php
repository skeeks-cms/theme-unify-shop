<?php
/**
 * @link https://cms.skeeks.com/
 * @copyright Copyright (c) 2010 SkeekS
 * @license https://cms.skeeks.com/license/
 * @author Semenov Alexander <semenov@skeeks.com>
 */
/**
 * @var $this yii\web\View
 * @var $model \skeeks\cms\shop\models\ShopBrand
 */
\skeeks\cms\themes\unify\assets\VanillaLazyLoadAsset::register($this);
$image = null;
if ($model->logo) {
    $image = $model->logo;
}
?>

<div class="sx-brand-item-wrapper col-lg-2 col-md-3 col-6">
    <a href="<?php echo $model->url; ?>" class="sx-brand-item">
        <div class="sx-image">
            <img
                class="lazy img-responsive"
                style="spect-ratio: 1; width: 90%;"
                src="<?php echo \Yii::$app->cms->image1px; ?>"
                data-src="<?= \Yii::$app->imaging->thumbnailUrlOnRequest($image ? $image->src : \skeeks\cms\helpers\Image::getCapSrc(),
                    new \skeeks\cms\components\imaging\filters\Thumbnail([
                        'w' => 230,
                        'h' => 230,
                    ]), $model->logo ? $model->code : ''
                ); ?>"
            />
        </div>
        <div class="sx-caption">
            <div class="sx-title">
                <?php echo $model->name; ?>
            </div>
            <?php
            if($model->country) : ?>
                <div class="sx-country">
                    <?php echo $model->country->name; ?>
                </div>
            <?php endif; ?>
            <div class="sx-total-products">
                Товаров: <?php echo \Yii::$app->formatter->asInteger($model->raw_row['total_products']); ?>
            </div>



        </div>
    </a>
</div>