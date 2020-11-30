<?php
/**
 * @author Semenov Alexander <semenov@skeeks.com>
 * @link http://skeeks.com/
 * @copyright 2010 SkeekS (СкикС)
 * @date 06.03.2015
 *
 * @var \skeeks\cms\shop\models\ShopCmsContentElement $model
 * @var                                               $this yii\web\View
 */
\skeeks\cms\themes\unifyshop\assets\components\ShopUnifyProductCardAsset::register($this);

$shopProduct = $model->shopProduct;
//Если этот товар привязан к главному
$infoModel = $model;

if ($model->main_cce_id) {
    $shopMainProduct = $model->mainCmsContentElement->shopProduct;
    if ($shopMainProduct->isOfferProduct) {
        $infoModel = $shopMainProduct->shopProductWhithOffers->cmsContentElement;
    } else {
        $infoModel = $model->mainCmsContentElement;
    }
}

/*if ($shopProduct->main_pid) {
    if ($shopProduct->shopMainProduct->isOfferProduct) {
        $element = $shopProduct->shopMainProduct->shopProductWhithOffers->cmsContentElement;
        $infoModel = $element;
        $infoModel->name = $element->name;
    } else {
        $infoModel = $shopProduct->shopMainProduct->cmsContentElement;
    }
}*/
$priceHelper = \Yii::$app->shop->cart->getProductPriceHelper($model);
?>
<div class="sx-product-card h-100 to-cart-fly-wrapper">
    <?
    $isAdded = \Yii::$app->shop->cart->getShopFavoriteProducts()->andWhere(['shop_product_id' => $shopProduct->id])->exists();
    ?>
    <div class="sx-favorite-product"
         data-added-icon-class="fas fa-heart"
         data-not-added-icon-class="far fa-heart"
         data-is-added="<?= (int)$isAdded ?>"
         data-product_id="<?= (int)$shopProduct->id ?>"
    >
        <a href="#" class="sx-favorite-product-trigger" data-pjax="0" style="font-size: 22px;">
            <? if ($isAdded) : ?>
                <i class="fas fa-heart"></i>
            <? else : ?>
                <i class="far fa-heart"></i>
            <? endif; ?>
        </a>
    </div>
    <div class="sx-product-card--photo">
        <a href="<?= $model->url; ?>" data-pjax="0">
            <? if ($infoModel->image) : ?>
                <img class="to-cart-fly-img" src="<?= \Yii::$app->imaging->thumbnailUrlOnRequest($infoModel->image ? $infoModel->image->src : null,
                    new \skeeks\cms\components\imaging\filters\Thumbnail([
                        'w' => \Yii::$app->unifyShopTheme->catalog_img_preview_width,
                        'h' => \Yii::$app->unifyShopTheme->catalog_img_preview_height,
                        'm' => \Yii::$app->unifyShopTheme->catalog_img_preview_crop,
                    ]), $model->code
                ); ?>" title="<?= \yii\helpers\Html::encode($infoModel->name); ?>" alt="<?= \yii\helpers\Html::encode($infoModel->name); ?>"/>
            <? else : ?>
                <img class="img-fluid to-cart-fly-img" src="<?= \skeeks\cms\helpers\Image::getCapSrc(); ?>" alt="<?= $infoModel->name; ?>">
            <? endif; ?>
        </a>
        <? if ($priceHelper->hasDiscount) : ?>
            <? $percent = (int)($priceHelper->percent * 100); ?>
            <? if ($percent > 0) : ?>
                <div class="sx-product-card--sale">
                    <div><span class="number">-<?= $percent; ?></span><span class="percent">%</span></div>
                    <div class="caption">скидка</div>
                </div>
            <? endif; ?>

        <? endif; ?>
    </div>
    <div class="sx-product-card--info">
        <? if (isset($shopProduct)) : ?>
            <div class="">
                <? if ($priceHelper) : ?>
                    <?
                    $prefix = "";
                    if ($shopProduct->isOffersProduct) {
                        $prefix = \Yii::t('skeeks/unify-shop', 'from')." ";
                    }
                    ?>
                    <? if ($priceHelper->hasDiscount && (float)$priceHelper->minMoney->getAmount() > 0) : ?>
                        <span class="new sx-new-price sx-list-new-price g-color-primary" data-amount="<?= $priceHelper->minMoney->getAmount(); ?>"><?= $prefix; ?><?= $priceHelper->minMoney; ?></span>
                        <span class="old sx-old-price sx-list-old-price" data-amount="<?= $priceHelper->minMoney->getAmount(); ?>"><?= $prefix; ?><?= $priceHelper->basePrice->money; ?></span>
                    <? else : ?>
                        <? if ((float)$priceHelper->minMoney->getAmount() > 0) : ?>
                            <div class="new sx-new-price sx-list-new-price g-color-primary" data-amount="<?= $priceHelper->minMoney->getAmount(); ?>"><?= $prefix; ?><?= $priceHelper->minMoney; ?></div>
                        <? endif; ?>
                    <? endif; ?>
                <? endif; ?>
            </div>
        <? endif; ?>
        <div class="sx-product-card--title">
            <a href="<?= $model->url; ?>" title="<?= $model->name; ?>" data-pjax="0" class="sx-product-card--title-a sx-main-text-color g-text-underline--none--hover"><?= $infoModel->name; ?></a>
        </div>
        <? if (isset($shopProduct)) : ?>
            <div class="sx-product-card--actions">
                <? if ($priceHelper && (float)$priceHelper->minMoney->getAmount() == 0) : ?>
                    <? if ($shopProduct->quantity > 0 && \Yii::$app->skeeks->site->shopSite->is_show_button_no_price && !$shopProduct->isOffersProduct) : ?>
                        <?= \yii\helpers\Html::tag('button', "<i class=\"icon cart\"></i>".\Yii::t('skeeks/unify-shop', 'To cart'), [
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
                    <? if ($shopProduct->quantity > 0 && !$shopProduct->isOffersProduct) : ?>
                        <?= \yii\helpers\Html::tag('button', "<i class=\"icon cart\"></i>".\Yii::t('skeeks/unify-shop', 'To cart'), [
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
</div>
