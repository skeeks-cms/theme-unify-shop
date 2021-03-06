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
/*if (!@$shopStoreProducts) {
    $shopStoreProducts = $shopProduct->getShopStoreProducts(\Yii::$app->shop->stores);
}*/
$storeIds = \yii\helpers\ArrayHelper::map(\Yii::$app->shop->stores, "id", "id");
$supplierStoreIds = \yii\helpers\ArrayHelper::map(\Yii::$app->shop->supplierStores, "id", "id");
$supplierQuantity = 0;
foreach($shopStoreProducts as $shopStoreProduct) {
    if(in_array($shopStoreProduct->shopStore->id, $supplierStoreIds)) {
        $supplierQuantity = $supplierQuantity + $shopStoreProduct->quantity;
    }
}
?>
<? if (\Yii::$app->skeeks->site->shopSite->is_show_quantity_product) : ?>
    <!-- 'available' || 'not-available' || '' -->
    <div style="margin-top: 10px;" class="sx-quantities-wrapper">
        <?php if($shopStoreProducts) : ?>
            <?php foreach($shopStoreProducts as $shopStoreProduct) : ?>
                <?php if(in_array($shopStoreProduct->shopStore->id, $storeIds)) : ?>
                    <div class="d-flex flex-row sx-quantities-row">
                        <div class="" style="width: 100%; line-height: 1;">
                            <?php echo $shopStoreProduct->shopStore->name; ?>


                            <?php if($shopStoreProduct->shopStore->address) : ?>
                                <br/><small style="color: gray;"><?php echo $shopStoreProduct->shopStore->address; ?></small>
                            <?php endif; ?>

                            <?php if($shopStoreProduct->quantity > 0) : ?>
                                <br/><small style="color: green;">Можно забрать сейчас!</small>
                            <?php endif; ?>
                        </div>
                        <div class="">
                            <?php if ($shopStoreProduct->quantity > 10) : ?>
                                <b style="float: right;"><?php echo $shopStoreProduct->quantity > 10 ? "много" : (float) $shopStoreProduct->quantity . " " . $shopProduct->measure->symbol; ?>
                                </b>
                            <?php else: ?>
                                <b style="float: right;"><?php echo (float) $shopStoreProduct->quantity; ?>&nbsp;<?php echo $shopProduct->measure->symbol; ?>
                                </b>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
        <?php endif; ?>

        <?php if($supplierQuantity) : ?>
            <div class="d-flex flex-row sx-quantities-row">
                <div class="" style="width: 100%; line-height: 1;">
                    На складе
                    <!--<br/><small style="color: gray;">Привезем под заказ</small>-->
                </div>
                <div class="">
                    <?php if ($supplierQuantity > 10) : ?>
                        <b style="float: right;"><?php echo $supplierQuantity > 10 ? "много" : (float) $supplierQuantity . " " . $shopProduct->measure->symbol; ?>
                        </b>
                    <?php else: ?>
                        <b style="float: right;"><?php echo (float) $supplierQuantity; ?>&nbsp;<?php echo $shopProduct->measure->symbol; ?>
                        </b>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
<? endif; ?>