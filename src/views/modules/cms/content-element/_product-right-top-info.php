<?php
/**
 * @link https://cms.skeeks.com/
 * @copyright Copyright (c) 2010 SkeekS
 * @license https://cms.skeeks.com/license/
 * @author Semenov Alexander <semenov@skeeks.com>
 */
/* @var $this yii\web\View */
/* @var $model \skeeks\cms\shop\models\ShopCmsContentElement */
/* @var $shopProduct \skeeks\cms\shop\models\ShopProduct */
/* @var $priceHelper \skeeks\cms\shop\helpers\ProductPriceHelper */
/* @var $singlPage \skeeks\cms\themes\unifyshop\cmsWidgets\product\ShopProductSinglPage */

?>

<div class="topmost-row">
    <div class="row no-gutters">
        <div class="col-5">
            <div data-product-id="<?= $model->id; ?>" class="item-lot" style="width: 100%;">
                Код:&nbsp;<?= $model->id; ?></div>
        </div>

        <div class="col-7">
        </div>
    </div>
</div>
