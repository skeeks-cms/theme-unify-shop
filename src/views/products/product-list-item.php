<?php
/**
 * @author Semenov Alexander <semenov@skeeks.com>
 * @link http://skeeks.com/
 * @copyright 2010 SkeekS (СкикС)
 * @date 06.03.2015
 *
 * @var \skeeks\cms\shop\models\ShopProduct           $shopProduct
 * @var \skeeks\cms\shop\models\ShopCmsContentElement $model
 * @var \skeeks\cms\shop\models\ShopCmsContentElement $infoModel
 * @var                                               $this yii\web\View
 */
\skeeks\cms\themes\unifyshop\assets\components\ShopUnifyProductCardAsset::register($this);
\skeeks\cms\themes\unify\assets\FontAwesomeAsset::register($this);
\skeeks\cms\themes\unify\assets\VanillaLazyLoadAsset::register($this);

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


$shopStoreProducts = $shopProduct->getShopStoreProducts(\Yii::$app->shop->allStores)->all();
$quantityAvailable = 0;
if ($shopStoreProducts) {
    foreach ($shopStoreProducts as $shopStoreProduct) {
        $quantityAvailable = $quantityAvailable + $shopStoreProduct->quantity;
    }
}

$isShowPrice = false;
if ($priceHelper && \Yii::$app->cms->cmsSite->shopSite->is_show_prices) {
    $isShowPrice = true;

    //нужно проверить наличие
    if (\Yii::$app->cms->cmsSite->shopSite->is_show_prices_only_quantity) {

        if ($shopStoreProducts) {
            if ($quantityAvailable > 0) {
                $isShowPrice = true;
            } else {
                $isShowPrice = false;
            }
        } else {
            $isShowPrice = true;
        }

    }
}

?>
<? echo \yii\helpers\Html::beginTag("div", [
    'class' => 'sx-product-card h-100 to-cart-fly-wrapper '.\Yii::$app->adult->renderCssClass($model),
    'data'  => [
        'id' => $shopProduct->id,
    ],
]); ?>

<?

$isAdded = \Yii::$app->shop->cart->getShopFavoriteProducts()->andWhere(['shop_product_id' => $shopProduct->id])->exists();
$isCompireAdded = \Yii::$app->shop->shopUser->getCmsCompareElements()->andWhere(['cms_content_element_id' => $shopProduct->id])->exists();
?>


<div class="sx-product-card--photo">
    <?php echo \Yii::$app->adult->renderBlocked($model); ?>

    <a href="<?= $model->url; ?>" data-pjax="0">
        <? if ($infoModel->mainProductImage) : ?>
            <?php if (\Yii::$app->mobileDetect->isDesktop) : ?>

                <?php if ($this->theme->product_list_images == 1) : ?>
                    <?
                    \skeeks\cms\themes\unifyshop\assets\ProductListImagesAsset::register($this);

                    $images = [];
                    $images[] = $infoModel->mainProductImage;
                    if ($infoModel->images) {
                        $images = \yii\helpers\ArrayHelper::merge($images, $infoModel->images);
                    }
                    ?>
                    <div class="sx-list-images">
                        <?
                        $counter = 0;
                        foreach ($images as $image) : ?>
                            <? $counter++;

                            $preview = \Yii::$app->imaging->getPreview($image,
                                new \skeeks\cms\components\imaging\filters\Thumbnail([
                                    'w'          => $this->theme->catalog_img_preview_width,
                                    'h'          => $this->theme->catalog_img_preview_height,
                                    'm'          => \Yii::$app->view->theme->catalog_img_preview_crop ? \Yii::$app->view->theme->catalog_img_preview_crop : \Imagine\Image\ManipulatorInterface::THUMBNAIL_INSET,
                                    'sx_preview' => \skeeks\cms\components\storage\SkeeksSuppliersCluster::IMAGE_PREVIEW_MEDIUM,
                                ]), $model->code
                            );
                            ?>
                            <? if ($counter < 6) : ?>
                                <img class="sx-list-image lazy"
                                     style="aspect-ratio: <?= $preview->cssAspectRatio; ?>;"
                                     src="<?php echo \Yii::$app->cms->image1px; ?>"
                                     data-src="<?= $preview->src; ?>"

                                     title="<?= \yii\helpers\Html::encode($infoModel->productName); ?>"
                                     alt="<?= \yii\helpers\Html::encode($infoModel->productName); ?>"/>
                            <? endif; ?>
                        <? endforeach; ?>
                    </div>


                <?php elseif ($this->theme->product_list_images == 2) : ?>

                    <?
                    \skeeks\cms\themes\unifyshop\assets\ProductListImagesV2Asset::register($this);

                    $secondImage = null;

                    $preview = \Yii::$app->imaging->getPreview($infoModel->mainProductImage,
                        new \skeeks\cms\components\imaging\filters\Thumbnail([
                            'w'          => $this->theme->catalog_img_preview_width,
                            'h'          => $this->theme->catalog_img_preview_height,
                            'm'          => \Yii::$app->view->theme->catalog_img_preview_crop ? \Yii::$app->view->theme->catalog_img_preview_crop : \Imagine\Image\ManipulatorInterface::THUMBNAIL_INSET,
                            'sx_preview' => \skeeks\cms\components\storage\SkeeksSuppliersCluster::IMAGE_PREVIEW_MEDIUM,
                        ]), $model->code
                    );

                    if ($infoModel->images) {
                        $secondImage = $infoModel->images[0];
                    }


                    ?>
                    <img class="sx-product-image to-cart-fly-img lazy"
                         style="aspect-ratio: <?php echo $preview->cssAspectRatio; ?>;"
                         src="<?php echo \Yii::$app->cms->image1px; ?>"
                         data-src="<?= $preview->src; ?>"
                        <? if ($secondImage) : ?>

                            <?
                            $preview = \Yii::$app->imaging->getPreview($secondImage,
                                new \skeeks\cms\components\imaging\filters\Thumbnail([
                                    'w'          => $this->theme->catalog_img_preview_width,
                                    'h'          => $this->theme->catalog_img_preview_height,
                                    'm'          => \Yii::$app->view->theme->catalog_img_preview_crop ? \Yii::$app->view->theme->catalog_img_preview_crop : \Imagine\Image\ManipulatorInterface::THUMBNAIL_INSET,
                                    'sx_preview' => \skeeks\cms\components\storage\SkeeksSuppliersCluster::IMAGE_PREVIEW_MEDIUM,
                                ]), $model->code
                            );
                            ?>
                            data-second-src="<?php echo $preview->src; ?>"
                        <? endif; ?>
                         title="<?= \yii\helpers\Html::encode($infoModel->productName); ?>" alt="<?= \yii\helpers\Html::encode($infoModel->productName); ?>"/>

                <?php endif; ?>


            <?php else : ?>

                <?
                $preview = \Yii::$app->imaging->getPreview($infoModel->mainProductImage,
                    new \skeeks\cms\components\imaging\filters\Thumbnail([
                        'w'          => $this->theme->catalog_img_preview_width,
                        'h'          => $this->theme->catalog_img_preview_height,
                        'm'          => \Yii::$app->view->theme->catalog_img_preview_crop ? \Yii::$app->view->theme->catalog_img_preview_crop : \Imagine\Image\ManipulatorInterface::THUMBNAIL_INSET,
                        'sx_preview' => \skeeks\cms\components\storage\SkeeksSuppliersCluster::IMAGE_PREVIEW_SMALL,
                    ]), $model->code
                );
                ?>
                <img class="sx-product-image to-cart-fly-img lazy"
                     style="aspect-ratio: <?php echo $preview->cssAspectRatio; ?>;"
                     src="<?php echo \Yii::$app->cms->image1px; ?>"
                     data-src="<?= $preview->src; ?>"
                     title="<?= \yii\helpers\Html::encode($infoModel->productName); ?>" alt="<?= \yii\helpers\Html::encode($infoModel->productName); ?>"/>
            <?php endif; ?>


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
    <div>
    <? if (isset($shopProduct)) : ?>
        <div class="">
            <? if ($isShowPrice) : ?>
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
                        <div class="new sx-new-price sx-list-new-price g-color-primary" data-amount="<?= $priceHelper->minMoney->getAmount(); ?>"><?= $prefix; ?><?= $priceHelper->minMoney; ?>
                            <? if ($this->theme->catalog_is_show_measure == 1) : ?>
                                <span class="sx-measure">/ <?= $shopProduct->measure->symbol; ?></span>
                            <? endif; ?>
                        </div>
                    <? endif; ?>
                <? endif; ?>
            <? else : ?>
                <div class="new sx-new-price sx-list-new-price g-color-primary">&nbsp;</div>
            <? endif; ?>
        </div>
    <? endif; ?>
    <div class="sx-product-card--title">
        <a href="<?= $model->url; ?>" title="<?= $model->productName; ?>" data-pjax="0" class="sx-product-card--title-a sx-main-text-color g-text-underline--none--hover"><?= $infoModel->productName; ?></a>
    </div>
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


            <div class="sx-product-additional-btns">

                <div class="sx-compire-product"
                     data-added-icon-class="icon-equalizer sx-added"
                     data-not-added-icon-class="icon-equalizer"
                     data-is-added="<?= (int)$isCompireAdded ?>"
                     data-product_id="<?= (int)$shopProduct->id ?>"
                >
                    <a href="#" target="Добавить товар к сравнению"  class="sx-compire-product-trigger" data-pjax="0">
                        <? if ($isCompireAdded) : ?>
                            <i class="icon-equalizer sx-added"></i>
                        <? else : ?>
                            <i class="icon-equalizer"></i>
                        <? endif; ?>
                    </a>
                </div>

                <div class="sx-favorite-product"
                     data-added-icon-class="fas fa-heart sx-added"
                     data-not-added-icon-class="far fa-heart"
                     data-is-added="<?= (int)$isAdded ?>"
                     data-product_id="<?= (int)$shopProduct->id ?>"
                >
                    <a href="#" target="Добавить товар в избранное" class="sx-favorite-product-trigger" data-pjax="0">
                        <? if ($isAdded) : ?>
                            <i class="fas fa-heart sx-added"></i>
                        <? else : ?>
                            <i class="far fa-heart"></i>
                        <? endif; ?>
                    </a>
                </div>


            </div>

        </div>
    <? endif; ?>
</div>
<? echo \yii\helpers\Html::endTag("div"); ?>
