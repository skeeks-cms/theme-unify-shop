<?php
/**
 * @author Semenov Alexander <semenov@skeeks.com>
 * @link http://skeeks.com/
 * @copyright 2010 SkeekS (СкикС)
 * @date 06.03.2015
 *
 */
/* @var $this yii\web\View */
/**
 * @var \skeeks\cms\shop\models\ShopCollection $model
 */
\skeeks\cms\themes\unify\assets\VanillaLazyLoadAsset::register($this);
?>
<div class="g-brd-gray-light-v4 g-color-gray-dark-v2 g-brd-around g-bg-white sx-collection-list-item">
    <a class="d-block text-center" href="<?= $model->url; ?>">
        <? if ($model->image) : ?>


            <?
                \skeeks\cms\themes\unifyshop\assets\ProductListImagesAsset::register($this);

                $images = [];
                $images[] = $model->image;
                if ($model->images) {
                    $images = \yii\helpers\ArrayHelper::merge($images, $model->images);
                }
                ?>
                <div class="sx-list-images">
                    
                    <?
                    $counter = 0;
                    foreach ($images as $image) : ?>
                        <? $counter ++; 
                        $preview = \Yii::$app->imaging->getPreview($image,
                            new \skeeks\cms\components\imaging\filters\Thumbnail([
                                'w'          => $this->theme->catalog_img_preview_width,
                                'h'          => $this->theme->catalog_img_preview_width,
                                'm'          => \Yii::$app->view->theme->catalog_img_preview_crop ? \Yii::$app->view->theme->catalog_img_preview_crop : \Imagine\Image\ManipulatorInterface::THUMBNAIL_INSET,
                                'sx_preview' => \skeeks\cms\components\storage\SkeeksSuppliersCluster::IMAGE_PREVIEW_MEDIUM,
                            ]), $model->code
                        );
                        ?>
                    
                            
                        <? if ($counter < 6) : ?>
                        <img class="sx-list-image lazy"
                             style="aspect-ratio: <?= $preview->cssAspectRatio?>; width: 100%;"
                             src="<?php echo \Yii::$app->cms->image1px; ?>"
                             data-src="<?= $preview->src; ?>"
                             title="<?= \yii\helpers\Html::encode($model->code); ?>"
                             alt="<?= \yii\helpers\Html::encode($model->code); ?>"/>
                        <? endif; ?>
                    <? endforeach; ?>
                </div>
        <? else : ?>
            <img class="img-fluid g-transform-scale-1_1--parent-hover g-transition-0_5 g-transition--ease-in-out" src="<?= \skeeks\cms\helpers\Image::getCapSrc(); ?>" alt="<?= $model->name; ?>">
        <? endif; ?>
    </a>
    <div class="g-pt-15 g-px-15">
        <a class="h5 g-font-weight-600" href="<?= $model->url; ?>"><?= $model->name; ?></a>

        <div class="g-nowrap" style="overflow: hidden; margin-bottom: 20px;">
            <? if ($model->brand && $model->brand->country_alpha2) : ?>
                <div class="pull-left" href="#"><?= $model->brand->country->name; ?></div>
            <? endif; ?>

            <? if ($model->brand) : ?>
                <div class="pull-left" href="#"><?= $model->brand->name; ?></div>
            <? endif; ?>
        </div>
        <!--<hr class="g-brd-gray-light-v4 g-my-10">
        <div class="text-center h6 g-mb-10">Товаров: <?/*= $model->getProducts()->count(); */?> шт.</div>-->
    </div>
</div>
