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
?>
<? if (\Yii::$app->skeeks->site->shopSite->is_show_quantity_product) : ?>
    <!-- 'available' || 'not-available' || '' -->
    <div style="margin-top: 10px;" class="sx-quantities-wrapper">
        <?php if($shopStoreProducts) : ?>
            <?php foreach($shopStoreProducts as $shopStoreProduct) : ?>
                <div class="d-flex flex-row sx-quantities-row">
                    <div class="" style="width: 100%; line-height: 1;">
                        <?php echo $shopStoreProduct->shopStore->name; ?>

                        <br/>
                        <?php if($shopStoreProduct->quantity > 0) : ?>
                            <small style="color: green;">Можно забрать сейчас!</small>
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
            <?php endforeach; ?>
        <?php endif; ?>

        <?php if($shopProduct->quantity) : ?>
            <div class="d-flex flex-row sx-quantities-row">
                <div class="" style="width: 100%; line-height: 1;">
                    На складе
                    <!--<br/><small style="color: gray;">Привезем под заказ</small>-->
                </div>
                <div class="">
                    <?php if ($shopProduct->quantity > 10) : ?>
                        <b style="float: right;"><?php echo $shopProduct->quantity > 10 ? "много" : (float) $shopProduct->quantity . " " . $shopProduct->measure->symbol; ?>
                        </b>
                    <?php else: ?>
                        <b style="float: right;"><?php echo (float) $shopProduct->quantity; ?>&nbsp;<?php echo $shopProduct->measure->symbol; ?>
                        </b>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
<? endif; ?>