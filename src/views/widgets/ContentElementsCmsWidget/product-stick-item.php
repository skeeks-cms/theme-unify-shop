<?php
/**
 * @author Semenov Alexander <semenov@skeeks.com>
 * @link http://skeeks.com/
 * @copyright 2010 SkeekS (СкикС)
 * @date 06.03.2015
 *
 * @var \v3toys\skeeks\models\V3toysProductContentElement $model
 *
 */
/* @var $this yii\web\View */
/* @var $shopProduct \skeeks\cms\shop\models\ShopProduct */
//$shopProduct = \skeeks\cms\shop\models\ShopProduct::getInstanceByContentElement($model);
$shopProduct = $model->shopProduct;

//Если этот товар привязан к главному
$infoModel = $model;
if ($shopProduct && $shopProduct->main_pid) {
    if ($shopProduct->shopMainProduct->isOfferProduct) {
        $shopWithOffers = $shopProduct->shopMainProduct->shopProductWhithOffers;
        $element = $shopWithOffers->cmsContentElement;
        $infoModel = $element;
        $infoModel->name = $element->name;
    } else {
        $infoModel = $shopProduct->shopMainProduct->cmsContentElement;
    }
}

$count = $model->relatedPropertiesModel->getSmartAttribute('reviews2Count');
$rating = $model->relatedPropertiesModel->getSmartAttribute('reviews2Rating');
//$v3ProductElement = new \v3toys\parsing\models\V3toysProductContentElement($model->toArray());
$priceHelper = \Yii::$app->shop->cart->getProductPriceHelper($model);

?>
<div class="js-slide">
    <div class="g-px-5">
        <!-- Product -->
        <figure class="g-pos-rel g-mb-10">
            <a class="" href="<?= $model->url; ?>" target="_blank" title="<?= $infoModel->name; ?>">
                <? if ($infoModel->image) : ?>
                    <img class="img-fluid" src="<?= \Yii::$app->imaging->thumbnailUrlOnRequest($infoModel->image ? $infoModel->image->src : null,
                        new \skeeks\cms\components\imaging\filters\Thumbnail([
                            'w' => \Yii::$app->unifyShopTheme->product_slider_img_preview_width,
                            'h' => \Yii::$app->unifyShopTheme->product_slider_img_preview_height,
                            'm' => \Yii::$app->unifyShopTheme->product_slider_img_preview_crop,
                        ]), $infoModel->code
                    ); ?>" alt="<?= $infoModel->name; ?>">
                <? else : ?>
                    <img class="img-fluid" src="<?= \skeeks\cms\helpers\Image::getCapSrc(); ?>" alt="<?= $infoModel->name; ?>">
                <? endif; ?>
            </a>
            <!--<figcaption class="w-100 g-bg-primary g-bg-black--hover text-center g-pos-abs g-bottom-0 g-transition-0_2 g-py-5">
                <a class="g-color-white g-font-size-11 text-uppercase g-letter-spacing-1 g-text-underline--none--hover" href="#!">New Arrival</a>
            </figcaption>-->
        </figure>

        <div class="media text-center">
            <!-- Product Info -->
            <div class="d-flex flex-column" style="width: 100%;">
                <? if ($priceHelper && (float)$priceHelper->minPrice->money->amount > 0) : ?>
                    <?
                    $prefix = "";
                    if ($shopProduct->isOffersProduct) {
                        $prefix = \Yii::t('skeeks/unify-shop', 'from')." ";
                    }
                    ?>
                    <? if ($priceHelper->hasDiscount) : ?>
                        <span class="d-block sx-new-price sx-list-new-price g-color-primary g-font-size-20"><?= $prefix; ?><?= $priceHelper->minMoney; ?></span>
                        <div><span class="sx-old-price sx-list-old-price old"><?= $prefix; ?><?= $priceHelper->basePrice->money; ?></span></div>
                    <? else : ?>
                        <span class="d-block sx-new-price sx-list-new-price g-color-primary g-font-size-20"><?= $prefix; ?><?= $priceHelper->minMoney; ?></span>
                    <? endif; ?>
                <? endif; ?>


                <div class="g-color-black mb-1 card-prod--title">
                    <a class="sx-main-text-color g-color-primary--hover g-text-underline--none--hover sx-card-prod--title-a" target="_blank" href="<?= $model->url; ?>" title="<?= $infoModel->name; ?>">
                        <?= $infoModel->name; ?>
                    </a>
                </div>
                <?/* if ($model->cmsTree) : */?><!--
                    <a class="d-inline-block g-color-gray-dark-v5 g-font-size-13" href="<?/*= $model->cmsTree->url; */?>"><?/*= $model->cmsTree->name; */?></a>
                --><?/* endif; */?>

            </div>
            <!-- End Product Info -->

            <!-- Products Icons -->
            <!--<ul class="list-inline media-body text-right">
                <li class="list-inline-item align-middle mx-0">
                    <a class="u-icon-v1 u-icon-size--sm g-color-gray-dark-v5 g-color-primary--hover g-font-size-15 rounded-circle" href="#!"
                       data-toggle="tooltip"
                       data-placement="top"
                       title="Add to Cart">
                        <i class="icon-finance-100 u-line-icon-pro"></i>
                    </a>
                </li>
                <li class="list-inline-item align-middle mx-0">
                    <a class="u-icon-v1 u-icon-size--sm g-color-gray-dark-v5 g-color-primary--hover g-font-size-15 rounded-circle" href="#!"
                       data-toggle="tooltip"
                       data-placement="top"
                       title="Add to Wishlist">
                        <i class="icon-medical-022 u-line-icon-pro"></i>
                    </a>
                </li>
            </ul>-->
            <!-- End Products Icons -->
        </div>
        <!-- End Product -->
    </div>
</div>
