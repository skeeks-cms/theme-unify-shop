<?php
/**
 * @var $this yii\web\View
 * @var $isActive bool Фильтр который нужно отрисовать
 * @var $value_id int  Фильтр который нужно отрисовать
 * @var $property_id int Фильтр который нужно отрисовать
 * @var $seoName string Фильтр который нужно отрисовать
 * @var $displayName string Фильтр который нужно отрисовать
 * @var $url string Фильтр который нужно отрисовать
 */
?>
<?php if ($isActive) : ?>
    <li class="list-inline-item sx-active" style="margin-bottom: 5px;" data-value_id="<?php echo $value_id; ?>" data-property_id="<?php echo $property_id; ?>">
        <div class="btn btn-primary"
        >
            <span data-toggle="tooltip" data-html="true" title="У вас применен фильтр «<?php echo $seoName; ?>»">
                <?php echo $displayName; ?>
            </span>
            <i class="hs-icon hs-icon-close sx-close-btn" data-toggle="tooltip" title="Отменить выбранный фильтр"></i>
        </div>
    </li>
<?php else : ?>
    <li class="list-inline-item" style="margin-bottom: 5px;">
        <a class="sx-main-text-color btn btn-default"
           href="<?php echo $url; ?>"
           data-toggle="tooltip"
           title="Часто ищут на нашем сайте! Применить фильтр «<?php echo $seoName; ?>»"
        >
            <?php echo $displayName; ?>
        </a>
    </li>
<?php endif; ?>
