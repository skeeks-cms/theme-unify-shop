<?php
/**
 * @link https://cms.skeeks.com/
 * @copyright Copyright (c) 2010 SkeekS
 * @license https://cms.skeeks.com/license/
 * @author Semenov Alexander <semenov@skeeks.com>
 */
/* @var $this yii\web\View */

\skeeks\cms\themes\unifyshop\assets\ShopUnifyCartAsset::register($this);
\skeeks\cms\shop\widgets\ShopGlobalWidget::widget();
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
]); ?>

    <!--=== Content Part ===-->
    <section class="sx-cart-layout g-mt-0 g-pb-0">
        <div class="container g-py-20">
            <div class="row">


                <? if (\Yii::$app->shop->cart->isEmpty) : ?>
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
                    <?= \skeeks\cms\shopCartStepsWidget\ShopCartStepsWidget::widget(); ?>
                    <hr/>
                    <!-- LEFT -->
                    <div class="col-lg-9 col-sm-8">
                        <?= \skeeks\cms\shopCartItemsWidget\ShopCartItemsListWidget::widget([
                            'dataProvider' => new \yii\data\ActiveDataProvider([
                                'query'      => \Yii::$app->shop->cart->getShopBaskets(),
                                'pagination' =>
                                    [
                                        'defaultPageSize' => 100,
                                        'pageSizeLimit'   => [1, 100],
                                    ],
                            ]),

                        ]); ?>
                    </div>
                    <!-- RIGHT -->
                    <div class="col-lg-3 col-sm-4">
                        <? $url = \yii\helpers\Url::to(['/shop/cart/checkout']); ?>
                        <?= \Yii::$app->view->render("@app/views/modules/shop/cart/_result", [
                            'submit' => <<<HTML
    <a href="{$url}" class="btn btn-primary btn-lg btn-block size-15" data-pjax="0">
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
<? \skeeks\cms\widgets\Pjax::end(); ?>