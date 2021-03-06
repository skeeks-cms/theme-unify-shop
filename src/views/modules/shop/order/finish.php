<?php
/**
 * @link https://cms.skeeks.com/
 * @copyright Copyright (c) 2010 SkeekS
 * @license https://cms.skeeks.com/license/
 * @author Semenov Alexander <semenov@skeeks.com>
 */
/**
 * @var $this yii\web\View
 * @var $model \skeeks\cms\shop\models\ShopOrder
 */


use yii\helpers\Html;
$this->registerCss(<<<CSS
.sx-detail-order, .sx-buyer-info {
    font-size: 16px;
}
.sx-data .sx-data-row {
    padding: 5px 0;
}
.sx-data .sx-data-row:nth-of-type(2n+1) {
    background-color: #f7f7f7;
}

CSS
);
?>


<!-- Product page -->
<!--=== Content Part ===-->
<section class="container sx-detail-order-page" style="padding: 40px 0;">
    <div class="col-12">
        <div class="row">
            <div class="col-12">
                <h1 style="margin-bottom: 0px;">Заказ <span class="g-color-primary">№<?= $model->id; ?></span>
                    <?php if ((float)$model->moneyOriginal->amount > 0) : ?>
                        на сумму <span
                                class="g-color-primary"><?= \Yii::$app->money->convertAndFormat($model->moneyOriginal); ?></span>
                    <?php endif; ?>


                </h1>
                <p style="color: gray;">от <?= \Yii::$app->formatter->asDatetime($model->created_at); ?></p>
            </div>
        </div>

        <div class="sx-detail-order sx-data">
            <div class="col-12">

                <div class="row sx-data-row">
                    <div class="col-3">Статус</div>
                    <div class="col-9">
                        <?php echo Html::tag('span', $model->shopOrderStatus->name, ['style' => "padding: 2px 5px; color: {$model->shopOrderStatus->color}; background: {$model->shopOrderStatus->bg_color};"]); ?>
                        <?php if ($model->shopOrderStatus->description) : ?>
                            <i class="far fa-question-circle" data-toggle="tooltip" title="<?php echo $model->shopOrderStatus->description; ?>"></i>
                        <?php endif; ?>

                    </div>
                </div>
                <div class="row sx-data-row">
                    <div class="col-3">Оплата</div>
                    <div class="col-9">
                        <?php if ($model->paid_at) : ?>
                            <?php
                            $title = \Yii::$app->formatter->asDatetime($model->paid_at);
                            if ($model->paySystem) {
                                $title .= " ".\skeeks\cms\helpers\StringHelper::ucfirst($model->paySystem->name);
                            }
                            ?>
                            <span style='color: green;' title="<?php echo $title; ?>"><i class="fas fa-check"></i> оплачен</span>
                        <?php else: ?>
                            <?php if ($model->paySystem) : ?>
                                <?php echo \skeeks\cms\helpers\StringHelper::ucfirst($model->paySystem->name); ?>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>
                </div>


                <?php if ($model->shopDelivery) : ?>
                    <div class="row sx-data-row">
                        <div class="col-3">Доставка</div>
                        <div class="col-9">
                            <?php echo $model->shopDelivery->name; ?>
                            <?php if ((float)$model->moneyDelivery->amount > 0) : ?>
                                <span style="margin-left: 10px;">
                                    <?php echo $model->moneyDelivery; ?>
                            </span>
                            <? endif; ?>
                        </div>
                    </div>

                <?php endif; ?>

                <?php if ($model->lastStatusLog && $model->lastStatusLog->comment) : ?>
                    <div class="row" style="margin-top: 20px;">
                        <div class="g-brd-primary" style="background: #fafafa; border-left: 5px solid; padding: 20px; 10px;">
                            <?php echo nl2br($model->lastStatusLog->comment); ?>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if ($model->shopOrderStatus->order_page_description) : ?>
                    <div class="row" style="margin-top: 20px;">
                        <div class="g-brd-primary" style="background: #fafafa; border-left: 5px solid; padding: 20px; 10px;">
                            <?php echo $model->shopOrderStatus->order_page_description; ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <? if ($model->shopOrderStatus->is_payment_allowed && $model->paySystem && $model->paySystem->paySystemHandler && !$model->paid_at) : ?>
            <div style="margin-top: 20px; background: #fafafa; padding: 15px;">
                <?= Html::a("Оплатить", $model->payUrl, [
                    'class' => 'btn btn-xl btn-primary',
                    'style' => 'margin-right: 20px;',
                ]); ?>
                <?php if ($model->shopOrderStatus->autoNextShopOrderStatus) : ?>
                    Внимание. Статус вашего заказа будет изменен на "<?php echo $model->shopOrderStatus->autoNextShopOrderStatus->name; ?>" автоматически <?php echo \Yii::$app->formatter->asRelativeTime($model->lastStatusLog->created_at + $model->shopOrderStatus->auto_next_status_time); ?>
                <?php endif; ?>
            </div>

        <? elseif ($model->shopOrderStatus->clientAvailbaleStatuses) : ?>

            <div style="margin-top: 20px; background: #fafafa; padding: 15px;" class="sx-user-actions">
                <p>Выберите действие:</p>
                <?php foreach ($model->shopOrderStatus->clientAvailbaleStatuses as $availableStatus) : ?>
                    <button class="btn btn-xl btn-primary sx-btn-status"
                            style="margin-right: 20px; background: <?= $availableStatus->bg_color; ?>; border-color: <?= $availableStatus->bg_color; ?>; color: <?= $availableStatus->color; ?>"
                            data-status_id="<?php echo $availableStatus->id; ?>"
                    >
                        <?= $availableStatus->btnName; ?>
                    </button>
                <?php endforeach; ?>

                <?php if ($model->shopOrderStatus->autoNextShopOrderStatus) : ?>
                    Внимание. Статус вашего заказа будет изменен на "<?php echo $model->shopOrderStatus->autoNextShopOrderStatus->name; ?>" автоматически <?php echo \Yii::$app->formatter->asRelativeTime($model->lastStatusLog->created_at + $model->shopOrderStatus->auto_next_status_time); ?>
                <?php endif; ?>

            </div>


            <?
            $this->registerJs(<<<JS
$("body").on("click", ".sx-btn-status", function() {
    var newStatus = $(this).data("status_id");
    var AjaxQuery = sx.ajax.preparePostQuery();
    
    AjaxQuery.setData({
        'status_id': newStatus,
        'act': 'change'
    });
    
    var AjaxHandler = new sx.classes.AjaxHandlerStandartRespose(AjaxQuery, {
        'blockerSelector' : '.sx-user-actions'
    });
    AjaxHandler.on("success", function() {
        setTimeout(function() {
            window.location.reload();
        }, 1000);
    });
    
    AjaxQuery.execute();
});
JS
            );

            ?>


        <?php endif; ?>





        <?php if ($model->deliveryHandlerCheckoutModel) : ?>
            <div class="sx-delivery-info" style="
                    margin-top: 20px;
                    /*background: #f8f8f8;*/
                    /*padding: 20px;*/
                ">
                <div class="row">
                    <div class="col-12">
                        <h4>Подробнее о доставке</h4>
                    </div>
                </div>
                <div class="sx-data">
                    <div class="col-12">
                        <?php foreach ($model->deliveryHandlerCheckoutModel->getVisibleAttributes() as $attribute) : ?>

                            <div class="row sx-data-row">
                                <div class="col-3"><?php echo $model->deliveryHandlerCheckoutModel->getAttributeLabel($attribute); ?>
                                </div>
                                <div class="col-9">
                                    <?php echo $model->deliveryHandlerCheckoutModel->{$attribute}; ?>
                                </div>
                            </div>

                        <?php endforeach; ?>

                    </div>
                </div>
            </div>
        <?php endif; ?>


        <?php if ($model->shopBuyer) : ?>
            <div class="sx-buyer-info" style="
                    margin-top: 20px;
                    /*background: #f8f8f8;*/
                    /*padding: 20px;*/
                ">
                <div class="row">
                    <div class="col-12">
                        <h4>Данные покупателя</h4>
                    </div>
                </div>
                <div class="sx-data">
                    <div class="col-12">
                        <?php foreach ($model->shopBuyer->relatedPropertiesModel->toArray() as $k => $v) : ?>
                            <div class="row sx-data-row">
                                <div class="col-3"><?php echo \yii\helpers\ArrayHelper::getValue($model->shopBuyer->relatedPropertiesModel->attributeLabels(), $k); ?>
                                </div>
                                <div class="col-9">
                                    <?php echo $v; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <div class="sx-order-items" style="margin-top: 20px;">
            <h4>Содержимое заказа</h4>
            <div class="">

                <!-- cart content -->
                <?= \skeeks\cms\shopCartItemsWidget\ShopCartItemsListWidget::widget([
                    'dataProvider' => new \yii\data\ActiveDataProvider([
                        'query'      => $model->getShopBaskets(),
                        'pagination' =>
                            [
                                'defaultPageSize' => 100,
                                'pageSizeLimit'   => [1, 100],
                            ],
                    ]),
                    'footerView'   => false,
                    'itemView'     => '@skeeks/cms/shopCartItemsWidget/views/items-list-order-item',
                ]); ?>
            </div>

            <div class="row">
                <div class="col-md-6"></div>
                <div class="col-md-6 float-right">
                    <!-- /cart content -->
                    <div class="toggle-transparent toggle-bordered-full clearfix" style="background: #fcfcfc; padding: 20px; border:rgba(0,0,0,0.05) 1px solid; border-top-width: 0;">
                        <div class="toggle active" style="display: block;">
                            <div class="toggle-content" style="display: block;">

                            <span class="clearfix">
                                <span
                                        class="float-right"><?= \Yii::$app->money->convertAndFormat($model->moneyOriginal); ?></span>
                                <span class="float-left">Товары</span>
                            </span>
                                <? if ($model->moneyDiscount->getValue() > 0) : ?>
                                    <span class="clearfix">
                                    <span
                                            class="float-right"><?= \Yii::$app->money->convertAndFormat($model->moneyDiscount); ?></span>
                                    <span class="float-left">Скидка</span>
                                </span>
                                <? endif; ?>

                                <? if ($model->moneyDelivery->getValue() > 0) : ?>
                                    <span class="clearfix">
                                    <span
                                            class="float-right"><?= \Yii::$app->money->convertAndFormat($model->moneyDelivery); ?></span>
                                    <span class="float-left">Доставка</span>
                                </span>
                                <? endif; ?>

                                <? if ($model->moneyVat->getValue() > 0) : ?>
                                    <span class="clearfix">
                                    <span
                                            class="float-right"><?= \Yii::$app->money->convertAndFormat($model->moneyVat); ?></span>
                                    <span class="float-left">Налог</span>
                                </span>
                                <? endif; ?>

                                <? if ($model->weight > 0) : ?>
                                    <span class="clearfix">
                                    <span class="float-right"><?= $model->weightFormatted; ?></span>
                                    <span class="float-left">Вес</span>
                                </span>
                                <? endif; ?>
                                <hr style="margin: 5px 0;"/>

                                <span class="clearfix">
                                <span
                                        class="float-right size-20"><?= \Yii::$app->money->convertAndFormat($model->money); ?></span>
                                <strong class="float-left">ИТОГО</strong>
                            </span>
                                <? if ($model->shopOrderStatus->is_payment_allowed && $model->paySystem && $model->paySystem->paySystemHandler && !$model->paid_at) : ?>
                                    <div class="float-right" style="margin-top: 15px;">
                                        <?= Html::a("Оплатить", $model->payUrl, [
                                            'class' => 'btn btn-lg btn-primary',
                                        ]); ?>
                                    </div>
                                <? endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

</section>
