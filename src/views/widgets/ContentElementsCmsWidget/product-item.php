<?php
/**
 * @author Semenov Alexander <semenov@skeeks.com>
 * @link http://skeeks.com/
 * @copyright 2010 SkeekS (СкикС)
 * @date 06.03.2015
 *
 * @var \skeeks\cms\shop\models\ShopCmsContentElement $model
 *
 */
/* @var $this yii\web\View */
//$shopProduct = \skeeks\cms\shop\models\ShopProduct::getInstanceByContentElement($model);
$shopProduct = $model->shopProduct;

$count = $model->relatedPropertiesModel->getSmartAttribute('reviews2Count');
$rating = $model->relatedPropertiesModel->getSmartAttribute('reviews2Rating');
//$v3ProductElement = new \v3toys\parsing\models\V3toysProductContentElement($model->toArray());
$priceHelper = \Yii::$app->shop->cart->getProductPriceHelper($model);

?>
<article class="card-prod h-100 to-cart-fly-wrapper">
    <div class="card-prod--labels">
        <? /*
                    if ( $enum->id == 141) : */ ?><!--
                        <div class="card-prod--label red"><? /*=$enum->value;*/ ?></div>
                        <div class="clear"></div>
                    <? /* endif; */ ?>
                    <? /*
                    if ( $enum->id == 143) : */ ?>
                        <div class="card-prod--label blue"><? /*=$enum->value;*/ ?></div>
                        <div class="clear"></div>
                    --><? /* endif; */ ?>
    </div>
    <? if ($priceHelper->hasDiscount) : ?>
        <? $percent = (int)($priceHelper->percent * 100); ?>
        <? if ($percent > 0) : ?>
            <div class="card-prod--sale">
                <div><span class="number">-<?= $percent; ?></span><span class="percent">%</span></div>
                <div class="caption">скидка</div>
            </div>
        <? endif; ?>

    <? endif; ?>
    <div class="card-prod--photo">
        <a href="<?= $model->url; ?>" data-pjax="0">
            <? if ($model->image) : ?>
                <img class="to-cart-fly-img" src="<?= \Yii::$app->imaging->thumbnailUrlOnRequest($model->image ? $model->image->src : null,
                    new \skeeks\cms\components\imaging\filters\Thumbnail([
                        'w' => 260,
                        'h' => 200,
                        'm' => \Imagine\Image\ImageInterface::THUMBNAIL_INSET,
                    ]), $model->code
                ); ?>" title="<?= \yii\helpers\Html::encode($model->name); ?>" alt="<?= \yii\helpers\Html::encode($model->name); ?>"/>
            <? else : ?>
                <img class="img-fluid to-cart-fly-img" src="<?= \skeeks\cms\helpers\Image::getCapSrc(); ?>" alt="<?= $model->name; ?>">
            <? endif; ?>
        </a>
    </div>
    <div class="card-prod--inner">

        <div class="card-prod--reviews">

        <? /* if ($model->relatedPropertiesModel->getSmartAttribute('typeConstruct')) : $prop = $model->relatedPropertiesModel->getSmartAttribute('typeConstruct'); */ ?>
        <!--<div class="card-prod--category">
                    <? /* if ($model->cmsTree) : */ ?>
                        <a href="<? /*= $model->cmsTree->url; */ ?>"><? /*= $model->cmsTree->name; */ ?></a>
                    <? /* endif; */ ?>
                </div>-->
        <? /* endif; */ ?>

        <div class="card-prod--title">
            <a href="<?= $model->url; ?>" title="<?= $model->name; ?>" data-pjax="0" class="g-color-gray-dark-v2 g-font-weight-600 g-line-height-1"><?= $model->name; ?></a>
        </div>
        <? if (isset($shopProduct)) : ?>
            <div class="card-prod--price">
                <? if ($priceHelper) : ?>
                    <?
                    $prefix = "";
                    if ($shopProduct->isTradeOffers()) {
                        $prefix = "от ";
                    }
                    ?>
                    <? if ($priceHelper->hasDiscount && (float)$priceHelper->minMoney->getAmount() > 0) : ?>
                        <div class="old sx-old-price" data-amount="<?= $priceHelper->minMoney->getAmount(); ?>"><?= $prefix; ?><?= $priceHelper->basePrice->money; ?></div>
                        <div class="new sx-new-price g-color-primary g-font-size-20" data-amount="<?= $priceHelper->minMoney->getAmount(); ?>"><?= $prefix; ?><?= $priceHelper->minMoney; ?></div>
                    <? else : ?>
                        <? if ((float)$priceHelper->minMoney->getAmount() > 0) : ?>
                            <div class="new sx-new-price g-color-primary g-font-size-20" data-amount="<?= $priceHelper->minMoney->getAmount(); ?>"><?= $prefix; ?><?= $priceHelper->minMoney; ?></div>
                        <? endif; ?>
                    <? endif; ?>
                <? endif; ?>
            </div>

            <div class="card-prod--actions">
                <? if ($priceHelper && (float)$priceHelper->minMoney->getAmount() == 0) : ?>
                    <? if ($shopProduct->quantity > 0 && \Yii::$app->shop->is_show_button_no_price && !$shopProduct->isTradeOffers()) : ?>
                        <?= \yii\helpers\Html::tag('button', "<i class=\"icon cart\"></i>В корзину", [
                            'class'   => 'btn btn-primary js-to-cart to-cart-fly-btn',
                            'type'    => 'button',
                            'onclick' => new \yii\web\JsExpression("sx.Shop.addProduct({$shopProduct->id}, 1); return false;"),
                        ]); ?>

                    <? else : ?>
                        <?= \yii\helpers\Html::tag('a', "Подробнее", [
                            'class' => 'btn btn-primary',
                            'href'  => $model->url,
                            'data'  => ['pjax' => 0],
                        ]); ?>
                    <? endif; ?>

                <? else : ?>
                    <? if ($shopProduct->quantity > 0 && !$shopProduct->isTradeOffers()) : ?>
                        <?= \yii\helpers\Html::tag('button', "<i class=\"icon cart\"></i>В корзину", [
                            'class'   => 'btn btn-primary js-to-cart to-cart-fly-btn',
                            'type'    => 'button',
                            'onclick' => new \yii\web\JsExpression("sx.Shop.addProduct({$shopProduct->id}, 1); return false;"),
                        ]); ?>
                    <? else : ?>
                        <?= \yii\helpers\Html::tag('a', "Подробнее", [
                            'class' => 'btn btn-primary',
                            'href'  => $model->url,
                            'data'  => ['pjax' => 0],
                        ]); ?>
                    <? endif; ?>
                <? endif; ?>
            </div>
        <? endif; ?>
    </div>
    <div class="card-prod--hidden">
        <div class="card-prod--inner">
            <div class="with-icon-group">


            </div>
        </div>
    </div>
</article>
