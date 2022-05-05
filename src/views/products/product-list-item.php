<?php
/**
 * @author Semenov Alexander <semenov@skeeks.com>
 * @link http://skeeks.com/
 * @copyright 2010 SkeekS (СкикС)
 * @date 06.03.2015
 *
 * @var \skeeks\cms\shop\models\ShopCmsContentElement $model
 * @var \skeeks\cms\shop\models\ShopCmsContentElement $infoModel
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

$priceHelper = \Yii::$app->shop->shopUser->getProductPriceHelper($model);


$secondImage = null;
if ($infoModel->images) {
    $secondImage = $infoModel->images[0];
}
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
            <? if ($infoModel->mainProductImage) : ?>
                <img class="sx-product-image to-cart-fly-img" src="<?= \Yii::$app->imaging->thumbnailUrlOnRequest($infoModel->mainProductImage ? $infoModel->mainProductImage->src : null,
                    new \skeeks\cms\components\imaging\filters\Thumbnail([
                        'w' => \Yii::$app->view->theme->catalog_img_preview_width,
                        'h' => \Yii::$app->view->theme->catalog_img_preview_height,
                        'm' => \Yii::$app->view->theme->catalog_img_preview_crop ? \Yii::$app->view->theme->catalog_img_preview_crop : \Imagine\Image\ManipulatorInterface::THUMBNAIL_INSET,
                    ]), $model->code
                ); ?>" 
                     <? if ($secondImage) : ?>
                    data-second-src="<?= \Yii::$app->imaging->thumbnailUrlOnRequest($secondImage->src,
                        new \skeeks\cms\components\imaging\filters\Thumbnail([
                            'w' => \Yii::$app->view->theme->catalog_img_preview_width,
                            'h' => \Yii::$app->view->theme->catalog_img_preview_height,
                            'm' => \Yii::$app->view->theme->catalog_img_preview_crop,
                        ]), $model->code
                    ); ?>"
                <? endif; ?>
                     title="<?= \yii\helpers\Html::encode($infoModel->productName); ?>" alt="<?= \yii\helpers\Html::encode($infoModel->productName); ?>"/>
            <? else : ?>
                <img class="img-fluid to-cart-fly-img" src="<?= \skeeks\cms\helpers\Image::getCapSrc(); ?>" alt="<?= $infoModel->productName; ?>">
            <? endif; ?>
        </a>
        <? if ($priceHelper->hasDiscount) : ?>
            <? $percent = round($priceHelper->percent * 100, 0); ?>
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
                <? if ($priceHelper && \Yii::$app->cms->cmsSite->shopSite->is_show_prices) : ?>
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
            <a href="<?= $model->url; ?>" title="<?= $model->productName; ?>" data-pjax="0" class="sx-product-card--title-a sx-main-text-color g-text-underline--none--hover"><?= $infoModel->productName; ?></a>
        </div>
        <? if (isset($shopProduct)) : ?>
            <div class="sx-product-card--actions">
                <? if ($priceHelper && (float)$priceHelper->minMoney->getAmount() == 0
                &&
                        \Yii::$app->cms->cmsSite->shopSite->is_show_cart
                ) : ?>
                
                    <? if (
                            //$shopProduct->quantity > 0 &&
                        \Yii::$app->skeeks->site->shopSite->is_show_button_no_price && !$shopProduct->isOffersProduct) : ?>
                    
                    
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
                    <?

                    $shopStoreProducts = $shopProduct->getShopStoreProducts(\Yii::$app->shop->allStores)->all();
                    $quantityAvailable = 0;
                    if ($shopStoreProducts) {
                        foreach ($shopStoreProducts as $shopStoreProduct)
                        {
                            $quantityAvailable = $quantityAvailable + $shopStoreProduct->quantity;
                        }
                    }

                    if (
                        (!$shopStoreProducts || $quantityAvailable > 0)
                        &&
                        \Yii::$app->cms->cmsSite->shopSite->is_show_cart
                        && !$shopProduct->isOffersProduct
                    ) : ?>
                    
                    
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
