<?php
/**
 * @var $this yii\web\View
 *
 * @var $image \skeeks\cms\models\StorageFile Картинка
 * @var $displayName string Отображаемое название
 * @var $seoName string Полное название отображается при наведении
 * @var $isActive bool Активный пункт
 * @var $url string Ссылка
 * @var $description string Короткое серое название
 * @var $code string Название картинки
 */

?>
<li class="list-inline-item <?php echo $isActive ? "sx-active" : ""; ?>" style="margin-bottom: 5px;">
    <a class="<?php echo $isActive ? "" : "sx-main-text-color"; ?> btn
        <?php echo $isActive ? "btn-primary" : "btn-default"; ?>
        "
       href="<?php echo $url; ?>"
       data-toggle="tooltip"
       title="<?php echo $seoName; ?>">
        <?php if ($image) : ?>
            <div class="sx-img-wrapper">
                <img src="<?= \skeeks\cms\helpers\Image::getSrc(\Yii::$app->imaging->thumbnailUrlOnRequest($image->src,
                    new \skeeks\cms\components\imaging\filters\Thumbnail([
                        'w' => 50,
                        'h' => 50,
                        'm' => \Imagine\Image\ManipulatorInterface::THUMBNAIL_INSET,
                    ]), $code
                )); ?>
                " alt="<?php echo $seoName; ?>"/>
            </div>
        <?php endif; ?>

        <div class="my-auto sx-info-wrapper">
            <div class="sx-title"><?php echo $displayName; ?></div>
            <div><?php echo $description; ?></div>
        </div>
    </a>
</li>