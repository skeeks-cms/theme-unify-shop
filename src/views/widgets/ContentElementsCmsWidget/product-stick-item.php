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

$count = $model->relatedPropertiesModel->getSmartAttribute('reviews2Count');
$rating = $model->relatedPropertiesModel->getSmartAttribute('reviews2Rating');
//$v3ProductElement = new \v3toys\parsing\models\V3toysProductContentElement($model->toArray());
$priceHelper = \Yii::$app->shop->cart->getProductPriceHelper($model);

?>
<div class="js-slide">
    <div class="g-px-10">
        <!-- Product -->
        <figure class="g-pos-rel g-mb-10">
            <a class="" href="<?= $model->url; ?>" title="<?= $model->name; ?>">
                <? if ($model->image) : ?>
                    <img class="img-fluid" src="<?= \Yii::$app->imaging->thumbnailUrlOnRequest($model->image ? $model->image->src : null,
                        new \skeeks\cms\components\imaging\filters\Thumbnail([
                            'w' => 200,
                            'h' => 200,
                            'm' => \Imagine\Image\ImageInterface::THUMBNAIL_INSET,
                        ]), $model->code
                    ); ?>" alt="<?= $model->name; ?>">
                <? else : ?>
                    <img class="img-fluid" src="<?= \skeeks\cms\helpers\Image::getCapSrc(); ?>" alt="<?= $model->name; ?>">
                <? endif; ?>
            </a>
            <!--<figcaption class="w-100 g-bg-primary g-bg-black--hover text-center g-pos-abs g-bottom-0 g-transition-0_2 g-py-5">
                <a class="g-color-white g-font-size-11 text-uppercase g-letter-spacing-1 g-text-underline--none--hover" href="#!">New Arrival</a>
            </figcaption>-->
        </figure>

        <div class="media text-center">
            <!-- Product Info -->
            <div class="d-flex flex-column">
                <? if ($priceHelper && (float)$priceHelper->minPrice->money->amount > 0) : ?>
                    <?
                    $prefix = "";
                    if ($shopProduct->isOffersProduct) {
                        $prefix = "от ";
                    }
                    ?>
                    <? if ($priceHelper->hasDiscount) : ?>
                        <div class="sx-old-price old"><?= $prefix; ?><?= $priceHelper->basePrice->money; ?></div>
                        <span class="d-block sx-new-price g-color-primary g-font-size-20"><?= $prefix; ?><?= $priceHelper->minMoney; ?></span>
                    <? else : ?>
                        <span class="d-block sx-new-price g-color-primary g-font-size-20"><?= $prefix; ?><?= $priceHelper->minMoney; ?></span>
                    <? endif; ?>
                <? endif; ?>


                <h4 class="h6 g-color-black mb-1">
                    <a class="u-link-v5 g-color-black g-color-primary--hover" href="<?= $model->url; ?>" title="<?= $model->name; ?>">
                        <?= $model->name; ?>
                    </a>
                </h4>
                <? if ($model->cmsTree) : ?>
                    <a class="d-inline-block g-color-gray-dark-v5 g-font-size-13" href="<?= $model->cmsTree->url; ?>"><?= $model->cmsTree->name; ?></a>
                <? endif; ?>

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
