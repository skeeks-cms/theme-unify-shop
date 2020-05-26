<?php
/**
 * @author Semenov Alexander <semenov@skeeks.com>
 * @link http://skeeks.com/
 * @copyright 2010 SkeekS (СкикС)
 * @date 06.10.2015
 */
/* @var $this yii\web\View */
/* @var $model \skeeks\cms\shop\models\ShopOrder */
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
                <h1 style="margin-bottom: 0px;">Заказ <span class="g-color-primary">№<?= $model->id; ?></span> на сумму <span
                            class="g-color-primary"><?= \Yii::$app->money->convertAndFormat($model->moneyOriginal); ?></span>
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
                        <?php if($model->shopOrderStatus->description) : ?>
                            <i class="far fa-question-circle" title="<?php echo $model->shopOrderStatus->description; ?>"></i>
                        <?php endif; ?>

                    </div>
                </div>
                <div class="row sx-data-row">
                    <div class="col-3">Оплата</div>
                    <div class="col-9">
                        <?php if ($model->paid_at) : ?>
                            <span style='color: green;'>Оплачен</span>
                        <?php else: ?>
                            <!--<span style='color: gray;'>Не оплачен</span>-->
                        <?php endif; ?>

                        <?php if ($model->paySystem) : ?>
                            <?php echo \skeeks\cms\helpers\StringHelper::ucfirst($model->paySystem->name); ?>
                        <?php endif; ?>
                    </div>
                </div>
                

                <?php if ($model->shopDelivery) : ?>
                    <div class="row sx-data-row">
                        <div class="col-3">Доставка</div>
                        <div class="col-9">
                            <?php echo $model->shopDelivery->name; ?>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if($model->shopOrderStatus->order_page_description) : ?>
                    <div class="row" style="margin-top: 20px;">

                            <div class="g-brd-primary" style="background: #fafafa; border-left: 5px solid; padding: 20px; 10px;">
                                <?php echo $model->shopOrderStatus->order_page_description; ?>
                            </div>

                    </div>
                <?php endif; ?>
                
            </div>
        </div>


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
                                    <span class="float-right"><?= $model->weight; ?> г.</span>
                                    <span class="float-left">Вес</span>
                                </span>
                                <? endif; ?>
                                <hr style="margin: 5px 0;"/>

                                <span class="clearfix">
                                <span
                                        class="float-right size-20"><?= \Yii::$app->money->convertAndFormat($model->money); ?></span>
                                <strong class="float-left">ИТОГО</strong>
                            </span>
                                <? if ($model->allow_payment == \skeeks\cms\components\Cms::BOOL_Y && $model->paySystem) : ?>
                                    <? if ($model->paySystem->paySystemHandler && !$model->paid_at) : ?>
                                        <?= Html::a("Оплатить", $model->payUrl, [
                                            'class' => 'btn btn-lg btn-primary',
                                        ]); ?>
                                    <? else : ?>

                                    <? endif; ?>
                                <? else : ?>
                                    <? if ($model->paySystem && $model->paySystem->paySystemHandler) : ?>
                                        В настоящий момент, заказ находится в стадии проверки и сборки. Его можно будет оплатить позже.
                                    <? endif; ?>
                                <? endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

</section>
