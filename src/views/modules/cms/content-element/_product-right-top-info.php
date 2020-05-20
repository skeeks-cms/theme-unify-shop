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
/* @var $singlPage \skeeks\cms\themes\unifyshop\cmsWidgets\product\ShopProductSinglPage */

?>

<div class="topmost-row">
    <div class="row no-gutters">
        <div class="col-5">
            <div data-product-id="<?= ($shopOfferChooseHelper && $shopOfferChooseHelper->offerCmsContentElement) ? $shopOfferChooseHelper->offerCmsContentElement->id : $model->id; ?>" class="item-lot">
                Код:&nbsp;<?= $shopOfferChooseHelper && $shopOfferChooseHelper->offerCmsContentElement ? $shopOfferChooseHelper->offerCmsContentElement->id : $model->id; ?></div>
        </div>

        <div class="col-7">
            <? if ($singlPage->is_allow_product_review) : ?>
                <?
                $messages = \skeeks\cms\reviews2\models\Reviews2Message::findAllowedForElement($model)->all();
                $rating = \skeeks\cms\reviews2\models\Reviews2Message::getRatingForMessages($messages);
                $reviews2Count = count($messages);
                ?>
                <div class="d-flex flex-row feedback-review cf float-right">

                    <div class="sx-feedback-links  g-mr-10">
                        <a href="#sx-reviews" class="sx-scroll-to g-color-gray-dark-v2 g-font-size-13 sx-dashed  g-brd-primary--hover g-color-primary--hover">
                            <?
                            echo \Yii::t(
                                'app',
                                '{n, plural, =0{нет отзывов} =1{один отзыв} one{# отзыв} few{# отзыва} many{# отзывов} other{# отзыва}}',
                                ['n' => $reviews2Count]
                            );
                            ?>
                        </a>
                    </div>

                    <? if ($rating > 0) : ?>
                        <div class="product-rating " itemprop="aggregateRating" itemscope="" itemtype="http://schema.org/AggregateRating">
                            <div class="js-rating-show g-color-yellow" data-rating="<?= $rating; ?>"></div>
                            <meta itemprop="ratingValue" content="<?= $rating ? $rating : 0; ?>">
                            <meta itemprop="reviewCount" content="<?= $reviews2Count ? $reviews2Count : 0; ?>">
                        </div>
                    <? else : ?>
                        <div class="product-rating ">
                            <div class="js-rating-show g-color-yellow" data-rating="<?= $rating; ?>"></div>
                        </div>
                    <? endif; ?>


                </div>
            <? endif; ?>


        </div>
    </div>
</div>
