<?php
/**
 * @link https://cms.skeeks.com/
 * @copyright Copyright (c) 2010 SkeekS
 * @license https://cms.skeeks.com/license/
 * @author Semenov Alexander <semenov@skeeks.com>
 */
/* @var $this yii\web\View */
/* @var $model \skeeks\cms\shop\models\ShopCmsContentElement */
/* @var $shopOfferChooseHelper \skeeks\cms\shop\helpers\ShopOfferChooseHelper */
/* @var $shopProduct \skeeks\cms\shop\models\ShopProduct */
/* @var $priceHelper \skeeks\cms\shop\helpers\ProductPriceHelper */

?>
<div class="sx-product-delivery-info g-mt-20" style="display: none;">
    <!-- Nav tabs -->
    <!--u-nav-v1-1-->
    <ul class="nav nav-justified  u-nav-v5-1" role="tablist" data-target="nav-1-1-accordion-default-hor-left-icons" data-tabs-mobile-type="accordion"
        data-btn-classes="btn btn-md btn-block rounded-0 u-btn-outline-lightgray g-mb-20">
        <li class="nav-item">
            <a class="nav-link active" data-toggle="tab" href="#nav-1-1-accordion-default-hor-left-icons--1" role="tab">
                <!--<i class="icon-christmas-037 u-tab-line-icon-pro "></i>-->
                <i class="fas fa-truck g-mr-3"></i>
                Условия доставки
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-toggle="tab" href="#nav-1-1-accordion-default-hor-left-icons--2" role="tab">
                <!--<i class="icon-communication-025 u-tab-line-icon-pro g-mr-3"></i>-->
                <i class="far fa-question-circle g-mr-3"></i>
                Помощь
            </a>
        </li>

    </ul>

    <!-- Tab panes -->
    <div id="nav-1-1-accordion-default-hor-left-icons" class="tab-content">
        <div class="tab-pane fade show active" id="nav-1-1-accordion-default-hor-left-icons--1" role="tabpanel">
            <? \skeeks\cms\cmsWidgets\text\TextCmsWidget::beginWidget('product-delivery-short'); ?>
            <p>Ближайшая дата доставки: 31 мар. 2019 г.</p>
            <p>Способы доставки: курьер, Почта России</p>
            <p>Регионы доставки: вся Россия</p>
            <? \skeeks\cms\cmsWidgets\text\TextCmsWidget::end(); ?>
        </div>

        <div class="tab-pane fade" id="nav-1-1-accordion-default-hor-left-icons--2" role="tabpanel">
            <? \skeeks\cms\cmsWidgets\text\TextCmsWidget::beginWidget('product-help-short'); ?>
            <p class="g-font-weight-600">Проблема с добавлением товара в корзину?</p>
            <p>Если у вас появилась сложность с добавлением товара в корзину, вы можете позвонить по номеру
                <?php if(\Yii::$app->skeeks->site->cmsSitePhone) : ?>
                    <a href="tel:<?= \Yii::$app->skeeks->site->cmsSitePhone->value; ?>"><?= \Yii::$app->skeeks->site->cmsSitePhone->value; ?></a> 
                <?php endif; ?>
                
                
                и оформить заказ по телефону.</p>
            <p>Пожалуйста, сообщите, какие проблемы с добавлением товара в корзину вы испытываете:</p>
            <? \skeeks\cms\cmsWidgets\text\TextCmsWidget::end(); ?>
        </div>
    </div>

    <!-- End Nav tabs -->

</div>
