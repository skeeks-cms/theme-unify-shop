<?php
/**
 * @link https://cms.skeeks.com/
 * @copyright Copyright (c) 2010 SkeekS
 * @license https://cms.skeeks.com/license/
 * @author Semenov Alexander <semenov@skeeks.com>
 */
/* @var $this yii\web\View */
?>

<?
\skeeks\cms\themes\unifyshop\assets\components\ShopUnifyCartPageAsset::register($this);
\skeeks\cms\themes\unifyshop\assets\ShopUnifyCartAsset::register($this);
\skeeks\cms\shop\widgets\ShopGlobalWidget::widget();

$this->registerCss(<<<CSS
    .sx-shop-checkout-widget .custom-radio:hover {
        background: #efefef;
    }
CSS
);
$this->registerJs(<<<JS
    (function(sx, $, _)
    {
        new sx.classes.shop.FullCart(sx.Shop, 'sx-cart-full');
    })(sx, sx.$, sx._);
JS
);
?>

<? \skeeks\cms\widgets\Pjax::begin([
    'id' => 'sx-cart-full',
]) ?>
    <!--=== Content Part ===-->
    <section class="sx-cart-layout g-mt-0 g-pb-0">
        <div class="container sx-border-block">
            <div class="row">


                <? if (\Yii::$app->shop->shopFuser->isEmpty()) : ?>
                    <!-- EMPTY CART -->
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <strong>Ваша корзина пуста!</strong><br/>
                            В вашей корзине нет покупок.<br/>
                            Кликните <a href="/" data-pjax="0">сюда</a> для продолжения покупок. <br/>
                            <!--<span class="label label-warning">this is just an empty cart example</span>-->
                        </div>
                    </div>
                    <!-- /EMPTY CART -->
                <? else: ?>

                    <div class="col-md-12 g-my-50 sx-steps">
                        <?= \skeeks\cms\shopCartStepsWidget\ShopCartStepsWidget::widget([
                            'viewFile' => '@app/views/modules/shop/cart/_steps',
                        ]); ?>
                    </div>

                    <!-- LEFT -->
                    <div class="col-lg-9 col-sm-8">

                        <!-- CART -->

                        <!-- cart content -->
                        <div id="cartContent" class="g-bg-secondary g-pa-20 g-pb-50 mb-4 sx-project-form-wrapper">


                            <? $checkout = \skeeks\cms\shopCheckout\ShopCheckoutWidget::begin([
                                'btnSubmitWrapperOptions' =>
                                    [
                                        'style' => 'display: none;',
                                    ],
                            ]); ?>
                            <? \skeeks\cms\shopCheckout\ShopCheckoutWidget::end(); ?>

                            <div class="clearfix"></div>
                        </div>
                        <!-- /cart content -->

                        <!-- /CART -->

                    </div>


                    <!-- RIGHT -->
                    <div class="col-lg-3 col-sm-4">

                        <? $url = \yii\helpers\Url::to(['/shop/cart/payment']); ?>
                        <?= $this->render("_result", [
                            'submit' => <<<HTML
    <a href="#" onclick="$('#{$checkout->formId}').submit(); return false;" class="btn btn-primary btn-lg btn-block size-15" data-pjax="0">
        <i class="fa fa-mail-forward"></i> Оформить
    </a>
HTML
                            ,

                        ]); ?>

                    </div>
                <? endif; ?>


            </div>

        </div>
    </section>
<? \skeeks\cms\widgets\Pjax::end() ?>