<?php
/**
 * @link https://cms.skeeks.com/
 * @copyright Copyright (c) 2010 SkeekS
 * @license https://cms.skeeks.com/license/
 * @author Semenov Alexander <semenov@skeeks.com>
 */
/* @var $this yii\web\View */
/* @var $shopProduct \skeeks\cms\shop\models\ShopProduct */
$this->registerCss(<<<CSS
.sx-quantities-wrapper .sx-quantities-row {
    padding-top: 5px;
    padding-bottom: 5px;
}
CSS
);
?>
<? if (\Yii::$app->skeeks->site->shopSite->is_show_quantity_product) : ?>
    <!-- 'available' || 'not-available' || '' -->

    <!--Если сайт собирает товары с других-->
    <?php if (\Yii::$app->skeeks->site->shopSite->is_receiver) : ?>
        <?php
        /**
         * @var $source \skeeks\cms\shop\models\ShopImportCmsSite
         */
        $forOrder = 0;
        if ($sources = \skeeks\cms\shop\models\ShopImportCmsSite::find()->cmsSite()->sort()->all()) : ?>
            <div style="margin-top: 10px;" class="sx-quantities-wrapper">
                <?php foreach ($sources as $source) : ?>
                    <?php
                    $sourceProduct = $shopProduct->shopMainProduct->getShopAttachedProducts()->joinWith("cmsContentElement as cmsContentElement")
                        ->andWhere(["cmsContentElement.cms_site_id" => $source->sender_cms_site_id])->one();
                    $address = $source->senderCmsSite->cmsSiteAddress;
                    if (!$address && $sourceProduct) {
                        $forOrder = $sourceProduct->quantity + $forOrder;
                    }

                    ?>
                    <?php if ($sourceProduct && $address) : ?>
                        <?php
                        /**
                         * @var \skeeks\cms\shop\models\ShopProduct $sourceProduct
                         */
                        if ($sourceProduct->shopStoreProducts) :  ?>
                            <? foreach ($sourceProduct->shopStoreProducts as $shopStoreProduct) : ?>
                                <div class="d-flex flex-row sx-quantities-row">
                                    <div class="" style="width: 100%; line-height: 1;">
                                        <?php echo $shopStoreProduct->shopStore->name; ?>
                                        <br/><small style="color: green;">Можно забрать сейчас!</small>
                                    </div>
                                    <div class="">
                                        <b style="float: right;"><?php echo (float) $shopStoreProduct->quantity; ?>&nbsp;<?php echo $sourceProduct->measure->symbol; ?></b>
        
                                    </div>
                                </div>
                            <? endforeach; ?>
                        <? else : ?>
                            <div class="d-flex flex-row sx-quantities-row">
                                <div class="" style="width: 100%; line-height: 1;">
                                    <?php echo $source->senderCmsSite->name; ?>
                                    <?php if ($address) : ?>
                                        <br/><small style="color: gray;"><?php echo $address->value; ?></small>
                                        <br/><small style="color: green;">Можно забрать сейчас!</small>
                                    <?php endif; ?>
                                </div>
                                <div class="">
                                    <b style="float: right;"><?php echo $sourceProduct->quantity; ?>&nbsp;<?php echo $sourceProduct->measure->symbol; ?></b>
    
                                </div>
                            </div>
                        <? endif; ?>
                        
                    <?php endif; ?>
                <?php endforeach; ?>
                <?php if ($forOrder) : ?>
                    <div class="d-flex flex-row sx-quantities-row">
                        <div class="" style="width: 100%; line-height: 1;">
                            На складе
                            <br/><small style="color: gray;">Привезем под заказ в течение 2-х дней</small>
                        </div>
                        <div class="">
                            <?php if ($forOrder > 10) : ?>
                                <b style="float: right;"><?php echo $forOrder > 10 ? "много" : $forOrder; ?>
                                </b>
                            <?php else: ?>
                                <b style="float: right;"><?php echo $forOrder; ?>&nbsp;<?php echo $shopProduct->measure->symbol; ?>
                                </b>
                            <?php endif; ?>


                        </div>
                    </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    <?php else : ?>
        <div class="availability-row available" style="">
            <? if ($shopProduct->quantity > 10) : ?>
                <span class="row-label"><?= \Yii::t("skeeks/unify-shop", "In stock over 10"); ?> <?= $shopProduct->measure->symbol; ?></span>
            <? else : ?>
                <span class="row-label"><?= \Yii::t("skeeks/unify-shop", "In stock"); ?>:</span> <span class="row-value"><?= $shopProduct->quantity; ?> <?= $shopProduct->measure->symbol; ?></span>
            <? endif; ?>
        </div>
    <?php endif; ?>
<? endif; ?>