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

JS
);
?><!--
<div class="row">
    <div class="col-md-12 col-lg-12">
        <a href="#" class="btn btn-primary sx-toggle-add-review2 pull-right"><i class="glyphicon glyphicon-plus"></i>
            Добавить отзыв</a>
    </div>
</div>-->
<section class="reviews-section--left">

    <? /* if ($widget->dataProvider->count > 0) :*/ ?><!--
            <header class="title-subsection">
                <h3>Отзывы</h3>
            </header>
        <? /* else : */ ?>
            <header class="title-subsection text-center">
                <h3 class="no-reviews">Ты можешь<br/>быть первым… <span class="arrow hidden-xs hidden-sm">&rarr;</span></h3>
            </header>
        --><? /* endif; */ ?>

    <? if ($widget->enabledPjaxPagination == \skeeks\cms\components\Cms::BOOL_Y) : ?>
        <? \skeeks\cms\modules\admin\widgets\Pjax::begin([
            'id' => $pjaxId,
        ]); ?>
    <? endif; ?>

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

    <? if ($widget->enabledPjaxPagination == \skeeks\cms\components\Cms::BOOL_Y) : ?>
        <? \skeeks\cms\modules\admin\widgets\Pjax::end(); ?>
    <? endif; ?>
</section><!--.reviews-section--left-->


<div class="col-md-8 offset-md-2" style="    background: #fafafa;
    padding: 20px;">

    <? $form = \skeeks\cms\base\widgets\ActiveFormAjaxSubmit::begin([
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

    <header class="title-subsection">
        <h3>Оставить свой отзыв</h3>
    </header>

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
        <?= $form->field($model, 'rating')->radioList(\Yii::$app->reviews2->ratings); ?>
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
    </div>
</div>
