<?php
/**
 * @author Semenov Alexander <semenov@skeeks.com>
 * @link http://skeeks.com/
 * @copyright 2010 SkeekS (СкикС)
 * @date 10.07.2015
 *
 * @var \skeeks\cms\reviews2\models\Reviews2Message $model
 */
/* @var $this yii\web\View */
$message = '';
if ($model->rating == 1)
{
    $message = 'Очень плохой товар';
} else if ($model->rating == 2)
{
    $message = 'Плохой товар';
}else if ($model->rating == 3)
{
    $message = 'Средний товар';
}else if ($model->rating == 4)
{
    $message = 'Хороший товар';
}else if ($model->rating == 5)
{
    $message = 'Очень хороший товар';
}
?>
<div class="media g-brd-around g-brd-gray-light-v4 g-pa-20 g-mb-20">
    <div class="media-body">
        <div class="d-sm-flex justify-content-sm-between align-items-sm-center g-mb-15 g-mb-10--sm">
            <header class="g-mb-5 g-mb-0--sm">
                <h5 class="h4 g-font-weight-300 g-mr-10 g-mb-5"><? if ($model->createdBy) : ?>
                        <?= $model->createdBy->displayName; ?>
                    <? else : ?>
                        Гость
                    <? endif; ?></h5>
                <div class="js-rating g-color-yellow" data-rating="<?=$model->rating; ?>"></div>
            </header>
            <div class="text-nowrap g-font-size-12">
                <span class="text-muted"><?=\Yii::$app->formatter->asDatetime($model->published_at);?></span>
            </div>
        </div>
        <?= $model->comments; ?>
        <? if ($model->dignity) : ?>
            <h5 class="g-mr-10 g-mb-5">Достоинства: </h5>
            <?=$model->dignity; ?>
        <? endif; ?>

        <? if ($model->disadvantages) : ?>
            <h5 class="g-mr-10 g-mb-5">Недостатки: </h5>
            <?=$model->disadvantages; ?>
        <? endif; ?>
    </div>
</div>

