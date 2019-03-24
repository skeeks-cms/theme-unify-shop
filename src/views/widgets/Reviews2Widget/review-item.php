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
    <div class="review-item--header">
        <span class="review-item--author">
            <? if ($model->createdBy) : ?>
                <?= $model->createdBy->displayName; ?>
            <? else : ?>
                Гость
            <? endif; ?></span>
        <span class="review-item--date"><?= $model->user_city ? $model->user_city . '&nbsp;|&nbsp;' : '' ?> <?= $model->user_name; ?><?=\Yii::$app->formatter->asDatetime($model->published_at);?></span>
        <div class="rating">
            <div class="star <?= $model->rating >1 ? 'active' : ''?>"></div>
            <div class="star <?= $model->rating >2 ? 'active' : ''?>"></div>
            <div class="star <?= $model->rating >3 ? 'active' : ''?>"></div>
            <div class="star <?= $model->rating >4 ? 'active' : ''?>"></div>
            <div class="star"<?= $model->rating >=5 ? 'active' : ''?>></div>
        </div>
    </div>
    <div class="review-item--prod">
        <a href="<?= $model->element->url; ?>"><?= $model->element->name; ?></a>
    </div>
    <div class="review-item--txt"><?= $model->comments; ?></div>

