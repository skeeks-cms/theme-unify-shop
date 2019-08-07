<?php
/**
 * @author Semenov Alexander <semenov@skeeks.com>
 * @link http://skeeks.com/
 * @copyright 2010 SkeekS (СкикС)
 * @date 07.07.2015
 */
/* @var $this yii\web\View */
/* @var $widget \skeeks\cms\reviews2\widgets\reviews2\Reviews2Widget */

$model = $widget->modelMessage;
$pjaxId = $widget->id."-pjax";
$this->registerJs(<<<JS
// initialization of rating
 $.HSCore.helpers.HSRating.init();

(function(sx, $, _)
{
    sx.classes.AddRview2 = sx.classes.Component.extend({

        _onDomReady: function()
        {
            $(".sx-toggle-add-review2").on("click", function()
            {
                $("#sx-add-review2").toggle();
                return false;
            });
        },
    });

    new sx.classes.AddRview2();
})(sx, sx.$, sx._);
$('.rating-active').click(function(){
    $('#reviews_rating').val($('.rating-active').attr('data-rate-value'));
});
$('#reviewForm').on('submit', function() {
    var count = $('.u-rating-v1 .click').size();
    $('#reviews2message-rating').val(count);
});
JS
);
?>

<section class="reviews-section--left">
<? if ($widget->dataProvider->query->count()>0) : ?>
    <? if ($widget->enabledPjaxPagination == \skeeks\cms\components\Cms::BOOL_Y) : ?>
        <? \skeeks\cms\modules\admin\widgets\Pjax::begin([
            'id' => $pjaxId,
        ]); ?>
    <? endif; ?>
    <!-- Panel Body -->
    <div class="card-block g-pa-0">

    <? echo \yii\widgets\ListView::widget([
        'dataProvider' => $widget->dataProvider,
        'itemView'     => 'review-item',
        'emptyText'    => '',
        'options'      =>
            [
                'tag'   => 'div',
                'class' => 'reviews-list',
            ],
        'itemOptions'  => [
            'tag'   => 'article',
            'class' => 'review-item',
        ],
        'pager'        => [
            //'class'   => \v3project\themes\mega\widgets\LinkPagerWithBlock::className(),
            'options'       =>
                [
                    'class' => '',
                ],
            //'activePageCssClass' => 'current',
            'nextPageLabel' => '<i class="fa fa-angle-right"></i>',
            'prevPageLabel' => '<i class="fa fa-angle-left"></i>',

        ],
        'layout'       => "{items}{pager}",
    ]) ?>
    </div>

    <? if ($widget->enabledPjaxPagination == \skeeks\cms\components\Cms::BOOL_Y) : ?>
        <? \skeeks\cms\modules\admin\widgets\Pjax::end(); ?>
    <? endif; ?>
<? else : ?>
    <div class="container">
        <div class="row">
           <div class="col-sm-12">
               <p>Ваш отзыв может стать первым.</p>
           </div>
        </div>
    </div>
<? endif; ?>
</section><!--.reviews-section--left-->


<?
$modal = \yii\bootstrap\Modal::begin([
    'header'       => 'Оставить свой отзыв',
    'id'           => 'showReviewFormBlock',
    'toggleButton' => false,
    'size'         => \yii\bootstrap\Modal::SIZE_DEFAULT,
]);
?>
<? $form = \skeeks\cms\base\widgets\ActiveFormAjaxSubmit::begin([
    'id'                    =>  'reviewForm',
    'action'                => \skeeks\cms\helpers\UrlHelper::construct('/reviews2/backend/submit')->toString(),
    'options'               => [
        'class' => 'reviews-section--right',
    ],
    'validationUrl'         => \skeeks\cms\helpers\UrlHelper::construct('/reviews2/backend/submit')->enableAjaxValidateForm()->toString(),
    'afterValidateCallback' => new \yii\web\JsExpression(<<<JS
    function(jQueryForm, AjaxQuery)
    {
        var handler = new sx.classes.AjaxHandlerStandartRespose(AjaxQuery, {
            'blockerSelector' : '#' + jQueryForm.attr('id'),
            'enableBlocker' : true,
        });

        handler.bind('success', function(e, response)
        {
            jQueryForm.empty().append(response.message);
            $.pjax.reload('#{$pjaxId}');
        });

        handler.bind('error', function(e, response)
        {
            $('.sx-captcha-wrapper img', jQueryForm).click();
        });


    }
JS
    ),
]); ?>

<?= $form->field($model, 'element_id')->hiddenInput([
    'value' => $widget->cmsContentElement->id,
])->label(false); ?>

    <div class="form-grid">
    <!--<input type="hidden" id="reviews_rating" name="Reviews2Message[rating]" value="">
    <div class="form-grid--row">
        <div class="form-grid--cell">
            <label class="form-label">Оценка:</label>
        </div>
        <div class="form-grid--cell rate">
            <div class="rating-active" data-rate-value="0"></div>
        </div>
    </div>-->
<?
$this->registerCss(<<<CSS

.sx-from-required {
    display: none;
}
CSS
);

?>

<?= $form->field($model, 'rating')->hiddenInput()->label(false); ?>
    <div class="form-group d-flex align-items-center justify-content-between mb-0">
        <label class="mb-0">Ретинг: </label>
        <ul class="js-rating u-rating-v1 g-font-size-17 g-color-gray-light-v3 mb-0" data-hover-classes="g-color-yellow">
            <li>
                <i class="fa fa-star"></i>
            </li>
            <li>
                <i class="fa fa-star"></i>
            </li>
            <li>
                <i class="fa fa-star"></i>
            </li>
            <li>
                <i class="fa fa-star"></i>
            </li>
            <li>
                <i class="fa fa-star"></i>
            </li>
        </ul>
    </div>
<? if (\Yii::$app->user->isGuest) : ?>
    <? if (in_array('user_name', \Yii::$app->reviews2->enabledFieldsOnGuest)): ?>
        <?= $form->field($model, 'user_name', [
            'options'  => [
                'class' => 'form-grid--row',
            ],
            'template' => "<div class=\"form-grid--cell\">{label}</div>\n<div class='form-grid--cell'>{input}{hint}{error}</div>",

        ])->label('Ваше имя: *', [
            'class' => 'form-label',
        ])->textInput([
            'class' => 'form-control form-control-rounded',
        ]); ?>
    <? endif; ?>
    <? if (in_array('user_email', \Yii::$app->reviews2->enabledFieldsOnGuest)): ?>
        <?= $form->field($model, 'user_email', [
            'options'      => [
                'class' => 'form-grid--row',
            ],
            'template'     => "<div class=\"form-grid--cell\">{label}</div>\n<div class='form-grid--cell'>{input}{hint}{error}</div>",
            'labelOptions' => ['class' => 'form-label form-label-inline'],
        ])->label('Email: *')->textInput([
            'class' => 'form-control form-control-rounded',
        ]); ?>
    <? endif; ?>

    <? if (in_array('dignity', \Yii::$app->reviews2->enabledFieldsOnGuest)): ?>
        <?= $form->field($model, 'dignity', [
            'options'  => [
                'class' => 'form-grid--row',
            ],
            'template' => "<div class=\"form-grid--cell\">{label}</div>\n<div class='form-grid--cell'>{input}{hint}{error}</div>",

            'labelOptions' => ['class' => 'form-label form-label-inline'],
        ])->textarea([
            'rows'  => 6,
            'class' => 'form-control form-control-rounded',
        ]); ?>
    <? endif; ?>
    <? if (in_array('disadvantages', \Yii::$app->reviews2->enabledFieldsOnGuest)): ?>
        <?= $form->field($model, 'disadvantages', [
            'options'  => [
                'class' => 'form-grid--row',
            ],
            'template' => "<div class=\"form-grid--cell\">{label}</div>\n<div class='form-grid--cell'>{input}{hint}{error}</div>",

            'labelOptions' => ['class' => 'form-label form-label-inline'],
        ])->textarea([
            'rows'  => 6,
            'class' => 'form-control form-control-rounded',
        ]); ?>
    <? endif; ?>
    <? if (in_array('comments', \Yii::$app->reviews2->enabledFieldsOnGuest)): ?>
        <?= $form->field($model, 'comments', [
            'options'      => [
                'class' => 'form-grid--row',
            ],
            'template'     => "<div class=\"form-grid--cell\">{label}</div>\n<div class='form-grid--cell'>{input}{hint}{error}</div>",
            'labelOptions' => ['class' => 'form-label form-label-inline'],
        ])->label('Текст отзыва:<br><small>(минимальное количество символов&nbsp;100)</small>')->textarea([
            'rows'      => 6,
            'minlength' => 100,
            'class'     => 'form-control form-control-rounded',
        ]); ?>
    <? endif; ?>
    <? if (in_array('verifyCode', \Yii::$app->reviews2->enabledFieldsOnGuest)): ?>
        <?= $form->field($model, 'verifyCode')->widget(\skeeks\cms\captcha\Captcha::className()) ?>
    <? endif; ?>
<? else: ?>
    <? if (in_array('user_name', \Yii::$app->reviews2->enabledFieldsOnUser)): ?>
        <?= $form->field($model, 'user_name', [
            'options'  => [
                'class' => 'form-grid--row',
            ],
            'template' => "<div class=\"form-grid--cell\">{label}</div>\n<div class='form-grid--cell'>{input}{hint}{error}</div>",
        ])->label('Ваше имя: *', [
            'class' => 'form-label',
        ])->textInput([
            'class' => 'form-control form-control-rounded',
        ]); ?>
    <? endif; ?>
    <? if (in_array('user_email', \Yii::$app->reviews2->enabledFieldsOnUser)): ?>
        <?= $form->field($model, 'user_email', [
            'options'  => [
                'class' => 'form-grid--row',
            ],
            'template' => "<div class=\"form-grid--cell\">{label}</div>\n<div class='form-grid--cell'>{input}{hint}{error}</div>",

        ])->label('Email: *')->textInput([
            'class' => 'form-control form-control-rounded',
        ]); ?>
    <? endif; ?>

    <? if (in_array('dignity', \Yii::$app->reviews2->enabledFieldsOnUser)): ?>
        <?= $form->field($model, 'dignity', [
            'options'  => [
                'class' => 'form-grid--row',
            ],
            'template' => "<div class=\"form-grid--cell\">{label}</div>\n<div class='form-grid--cell'>{input}{hint}{error}</div>",

        ])->textarea([
            'rows'  => 6,
            'class' => 'form-control form-control-rounded',
        ]); ?>
    <? endif; ?>
    <? if (in_array('disadvantages', \Yii::$app->reviews2->enabledFieldsOnUser)): ?>
        <?= $form->field($model, 'disadvantages', [
            'options'  => [
                'class' => 'form-grid--row',
            ],
            'template' => "<div class=\"form-grid--cell\">{label}</div>\n<div class='form-grid--cell'>{input}{hint}{error}</div>",

        ])->textarea([
            'rows'  => 6,
            'class' => 'form-control form-control-rounded',
        ]); ?>
    <? endif; ?>
    <? if (in_array('comments', \Yii::$app->reviews2->enabledFieldsOnUser)): ?>

        <?= $form->field($model, 'comments', [
            'options'      => [
                'class' => 'form-grid--row',
            ],
            'template'     => "<div class=\"form-grid--cell\">{label}</div>\n<div class='form-grid--cell'>{input}{hint}{error}</div>",
            'labelOptions' => ['class' => 'form-label form-label-inline'],
        ])->label('Текст отзыва:<br><small>(минимальное количество символов&nbsp;100)</small>')->textarea([
            'rows'      => 6,
            'minlength' => 100,
            'class'     => 'form-control form-control-rounded',
        ]); ?>

    <? endif; ?>
    <? if (in_array('verifyCode', \Yii::$app->reviews2->enabledFieldsOnUser)): ?>
        <?= $form->field($model, 'verifyCode')->widget(\skeeks\cms\captcha\Captcha::className()) ?>
    <? endif; ?>
<? endif; ?>

    <div class="form-grid--row g-mt-20">
        <div class="form-grid--cell"></div>
        <div class="form-grid--cell submit">
            <?= \yii\helpers\Html::submitButton("".\Yii::t('app', $widget->btnSubmit), [
                'class' => 'btn btn-primary',
            ]); ?>
        </div>
    </div>


<? \skeeks\cms\base\widgets\ActiveFormAjaxSubmit::end(); ?>

<?
$modal::end();
?>