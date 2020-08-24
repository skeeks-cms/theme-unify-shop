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
                    <div class="col-sm-12">
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
                    </div>
                <? else: ?>
                    <div class="col-md-12 g-my-50 sx-steps">
                        <?= \skeeks\cms\shopCartStepsWidget\ShopCartStepsWidget::widget([
                            'viewFile' => '@app/views/modules/shop/cart/_steps',
                        ]); ?>
                    </div>
                    <!-- LEFT -->
                    <div class="col-lg-9 col-sm-8">
                        <? /*= \skeeks\cms\shopCartItemsWidget\ShopCartItemsListWidget::widget([
                            'dataProvider' => new \yii\data\ActiveDataProvider([
                                'query'      => \Yii::$app->shop->cart->getShopBaskets(),
                                'pagination' =>
                                    [
                                        'defaultPageSize' => 1000,
                                        'pageSizeLimit'   => [1, 1000],
                                    ],
                            ]),

                        ]); */ ?>

                        <div class="sx-order-items g-bg-gray-light-v5">
                            <? foreach (\Yii::$app->shop->shopUser->shopOrder->shopOrderItems as $orderItem) : ?>
                                <div class="row no-gutters sx-order-item">
                                    <div class="col" style="max-width: 180px;">
                                        <a href="<?= $orderItem->url; ?>" data-pjax="0">
                                            <img src="<?= \skeeks\cms\helpers\Image::getSrc(
                                                \Yii::$app->imaging->getImagingUrl($orderItem->image ? $orderItem->image->src : null, new \skeeks\cms\components\imaging\filters\Thumbnail([
                                                    'h' => 150,
                                                    'w' => 150,
                                                    //'m' => \Imagine\Image\ManipulatorInterface::THUMBNAIL_INSET,
                                                ]))
                                            ) ?>" class="sx-lazy" alt="<?= $orderItem->name; ?> title="<?= $orderItem->name; ?> width="150"/>
                                        </a>
                                    </div>
                                    <div class="col">
                                        <div class="card-prod--title text-left" style="min-height: auto;">
                                            <a href="<?= $orderItem->url; ?>" class="product_name sx-card-prod--title-a g-px-0" data-pjax="0">
                                                <?= $orderItem->name; ?>
                                            </a>
                                        </div>
                                        <? if ($orderItem->shopBasketProps) : ?>
                                            <div class="sx-order-item-properties">
                                                <? foreach ($orderItem->shopBasketProps as $prop) : ?>
                                                    <p><?= $prop->name; ?>: <?= $prop->value; ?></p>
                                                <? endforeach; ?>
                                            </div>
                                        <? endif; ?>
                                        <div class="d-flex flex-row sx-quantity-wrapper">
                                            <!--<input type="number" value="<? /*= round($orderItem->quantity); */ ?>" name="qty"
                                                           class="sx-basket-quantity" maxlength="3" max="999" min="1"
                                                           data-basket_id="<? /*= $orderItem->id; */ ?>"/>-->

                                            <span class="d-flex flex-row sx-quantity-group">
                                                <div class="my-auto sx-minus">-</div>
                                                <div class="my-auto">
                                                    <input
                                                            value="<?= (float)$orderItem->quantity; ?>"
                                                            class="form-control sx-quantity-input sx-basket-quantity"
                                                            data-measure_ratio="<?= $orderItem->shopProduct ? $orderItem->shopProduct->measure_ratio : ""; ?>"
                                                            data-basket_id="<?= $orderItem->id; ?>"
                                                    />
                                                </div>
                                                <div class="my-auto sx-plus">+</div>
                                            </span>
                                            <div class="my-auto g-ml-10">
                                                <?= $orderItem->measure_name; ?>
                                            </div>

                                        </div>

                                        <div class="sx-order-item-price">
                                            <?= $orderItem->moneyOriginal; ?> / <?= $orderItem->measure_name; ?>
                                        </div>
                                    </div>
                                    <div class="col my-auto" style="max-width: 19px;">
                                        <!--<img onclick="sx.Shop.removeBasket('<? /*= $orderItem->id; */ ?>'); return false;" class="img-remove" src="<? /*= \common\themes\app\assets\AppThemeAsset::getAssetUrl("img/icons/remove-circle-1.svg"); */ ?>" />-->
                                        <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="times-circle" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"
                                             class="svg-inline--fa fa-times-circle fa-w-16"
                                             onclick="sx.Shop.removeBasket('<?= $orderItem->id; ?>'); return false;"
                                             style="cursor: pointer; 
                                             width: 12px;
                                             height: 12px;transform-origin: 0.625em 0.5625em;overflow: visible;color: #a9a9a9; margin-top: -13px;">
                                            <g transform="translate(256 256)" class="">
                                                <g transform="translate(64, 32)  scale(1, 1)  rotate(0 0 0)" class="">
                                                    <path fill="currentColor"
                                                          d="M256 8C119 8 8 119 8 256s111 248 248 248 248-111 248-248S393 8 256 8zm121.6 313.1c4.7 4.7 4.7 12.3 0 17L338 377.6c-4.7 4.7-12.3 4.7-17 0L256 312l-65.1 65.6c-4.7 4.7-12.3 4.7-17 0L134.4 338c-4.7-4.7-4.7-12.3 0-17l65.6-65-65.6-65.1c-4.7-4.7-4.7-12.3 0-17l39.6-39.6c4.7-4.7 12.3-4.7 17 0l65 65.7 65.1-65.6c4.7-4.7 12.3-4.7 17 0l39.6 39.6c4.7 4.7 4.7 12.3 0 17L312 256l65.6 65.1z"
                                                          transform="translate(-256 -256)" class=""></path>
                                                </g>
                                            </g>
                                        </svg>
                                    </div>
                                </div>
                            <? endforeach; ?>
                        </div>

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