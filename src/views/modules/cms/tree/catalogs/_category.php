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
$image_width = @$image_width;
if (!$image_width) {
    $image_width = 50;
}
$image_height = @$image_height;
if (!$image_height) {
    $image_height = 50;
}
?>
<li class="list-inline-item <?php echo @$adult_css_class; ?> <?php echo $isActive ? "sx-active" : ""; ?>" style="margin-bottom: 0.5rem;">

    <a class="<?php echo $isActive ? "" : "sx-main-text-color"; ?> btn
        <?php echo $isActive ? "btn-primary" : "btn-default"; ?>
        "
       href="<?php echo $url; ?>"
       data-toggle="tooltip"
       title="<?php echo $seoName; ?>">
        <?php if ($image) : ?>


            <div class="sx-img-wrapper" style="position: relative;">
                <?php echo @$adult_blocked_html; ?>
                <img src="<?= \skeeks\cms\helpers\Image::getSrc(\Yii::$app->imaging->thumbnailUrlOnRequest($image->src,
                    new \skeeks\cms\components\imaging\filters\Thumbnail([
                        'w' => $image_width,
                        'h' => $image_height,
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
