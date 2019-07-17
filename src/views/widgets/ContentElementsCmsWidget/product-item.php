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
//$shopProduct = \skeeks\cms\shop\models\ShopProduct::getInstanceByContentElement($model);
$shopProduct = $model->shopProduct;

$count = $model->relatedPropertiesModel->getSmartAttribute('reviews2Count');
$rating = $model->relatedPropertiesModel->getSmartAttribute('reviews2Rating');
//$v3ProductElement = new \v3toys\parsing\models\V3toysProductContentElement($model->toArray());
$priceHelper = \Yii::$app->shop->cart->getProductPriceHelper($model);

?>
    <article class="card-prod h-100 to-cart-fly-wrapper">
        <div class="card-prod--labels">
                    <?/*
                    if ( $enum->id == 141) : */?><!--
                        <div class="card-prod--label red"><?/*=$enum->value;*/?></div>
                        <div class="clear"></div>
                    <?/* endif; */?>
                    <?/*
                    if ( $enum->id == 143) : */?>
                        <div class="card-prod--label blue"><?/*=$enum->value;*/?></div>
                        <div class="clear"></div>
                    --><?/* endif; */?>
        </div>
        <? if ($shopProduct && $shopProduct->baseProductPrice && $shopProduct->minProductPrice && $shopProduct->minProductPrice->id != $shopProduct->baseProductPrice->id) :
            $percent =  (int)(100-$shopProduct->minProductPrice->money->getAmount()*100/$shopProduct->baseProductPrice->money->getAmount()); ?>
            <div class="card-prod--sale">
                <div><span class="number">-<?=(int)$percent;?></span><span class="percent">%</span></div>
                <div class="caption">скидка</div>
            </div>
        <? endif; ?>
        <div class="card-prod--photo">
            <a href="<?= $model->url; ?>" data-pjax="0">
                <img class="to-cart-fly-img" src="<?= \Yii::$app->imaging->thumbnailUrlOnRequest($model->image ? $model->image->src : \Yii::$app->cms->noImageUrl,
                    new \skeeks\cms\components\imaging\filters\Thumbnail([
                        'w' => 260,
                        'h' => 200,
                        'm' => \Imagine\Image\ImageInterface::THUMBNAIL_INSET,
                    ]), 'img'.$model->id
                ); ?>" title="<?= \yii\helpers\Html::encode($model->name); ?>" alt="<?= \yii\helpers\Html::encode($model->name); ?>" />
            </a>
        </div>
        <div class="card-prod--inner">

            <!--<div class="card-prod--reviews">
                <?/* if ($count>0) : */?>
                    <div class="rating">
                        <div class="star <?/*= ($rating > 0) ? "active" :''*/?>"></div>
                        <div class="star <?/*= ($rating > 2) ? "active" :''*/?>"></div>
                        <div class="star <?/*= ($rating > 3) ? "active" :''*/?>"></div>
                        <div class="star <?/*= ($rating > 4) ? "active" :''*/?>"></div>
                        <div class="star <?/*= ($rating >=5 ) ? "active" :''*/?>"></div>
                    </div>
                <?/* else : */?>
                    <div class="rating">
                        <div class="star"></div>
                        <div class="star"></div>
                        <div class="star"></div>
                        <div class="star"></div>
                        <div class="star"></div>
                    </div>
                <?/* endif; */?>
                <div class="caption"><a href="<?/*=$model->url.'#tab-reviews'*/?>">(<?/*= (int) $count;*/?> отзывов)</a></div>
            </div>-->


            <?/* if ($model->relatedPropertiesModel->getSmartAttribute('typeConstruct')) : $prop = $model->relatedPropertiesModel->getSmartAttribute('typeConstruct'); */?>
                <!--<div class="card-prod--category">
                    <?/* if ($model->cmsTree) : */?>
                        <a href="<?/*= $model->cmsTree->url; */?>"><?/*= $model->cmsTree->name; */?></a>
                    <?/* endif; */?>
                </div>-->
            <?/* endif; */?>

            <div class="card-prod--title">
                <a href="<?= $model->url; ?>" title="<?= $model->name; ?>" data-pjax="0" class="g-color-gray-dark-v2 g-font-weight-600 g-line-height-1"><?= $model->name; ?></a>
            </div>
            <? if (isset($shopProduct)) : ?>
            <div class="card-prod--price">
                <? if ($priceHelper->hasDiscount) : ?>
                    <div class="old"><?= $priceHelper->basePrice->money; ?></div>
                    <div class="new"><?= $priceHelper->minMoney; ?></div>
                <? else : ?>
                    <div class="new g-color-primary g-font-size-20"><?= $priceHelper->minMoney; ?></div>
                <? endif; ?>
                
                <?/* if ($shopProduct->minProductPrice && $shopProduct->baseProductPrice && $shopProduct->minProductPrice->id == $shopProduct->baseProductPrice->id) : */?><!--
                    <div class="new g-color-primary g-font-size-20"><?/*= \Yii::$app->money->convertAndFormat($shopProduct->minProductPrice->money); */?></div>
                <?/* else : */?>
                    <?/* if ($shopProduct->baseProductPrice && $shopProduct->minProductPrice) : */?>
                    <div class="old"><?/*= \Yii::$app->money->convertAndFormat($shopProduct->baseProductPrice->money); */?></div>
                    <div class="new"><?/*= \Yii::$app->money->convertAndFormat($shopProduct->minProductPrice->money); */?></div>
                    <?/* endif; */?>
                --><?/* endif; */?>
            </div>

            <div class="card-prod--actions">
                <? if ($shopProduct->minProductPrice && $shopProduct->minProductPrice->price == 0) : ?>
                    <? if ($shopProduct->quantity > 0 && \Yii::$app->shop->is_show_button_no_price) : ?>
                        <?= \yii\helpers\Html::tag('button', "<i class=\"icon cart\"></i>Купить", [
                            'class' => 'btn btn-primary js-to-cart to-cart-fly-btn',
                            'type' => 'button',
                            'onclick' => new \yii\web\JsExpression("sx.Shop.addProduct({$shopProduct->id}, 1); return false;"),
                        ]); ?>

                    <? else : ?>
                        <?= \yii\helpers\Html::tag('a', "Подробнее", [
                            'class' => 'btn btn-primary',
                            'href' => $model->url,
                            'data' => ['pjax' => 0],
                        ]); ?>
                    <? endif; ?>

                <? else : ?>
                    <? if ($shopProduct->quantity > 0 && $shopProduct->minProductPrice) : ?>
                        <?= \yii\helpers\Html::tag('button', "<i class=\"icon cart\"></i>Купить", [
                            'class' => 'btn btn-primary js-to-cart to-cart-fly-btn',
                            'type' => 'button',
                            'onclick' => new \yii\web\JsExpression("sx.Shop.addProduct({$shopProduct->id}, 1); return false;"),
                        ]); ?>

                    <? else : ?>
                        <?= \yii\helpers\Html::tag('a', "Подробнее", [
                            'class' => 'btn btn-primary',
                            'href' => $model->url,
                            'data' => ['pjax' => 0],
                        ]); ?>
                    <? endif; ?>
                <? endif; ?>
            </div>
            <? endif; ?>
        </div>
        <div class="card-prod--hidden">
            <div class="card-prod--inner">
                <div class="with-icon-group">
                    

                    <?/* if ($model->relatedPropertiesModel->getSmartAttribute('totalDetaley')) : $prop = $model->relatedPropertiesModel->getSmartAttribute('totalDetaley'); */?><!--
                    <p class="with-icon"><img src="<?/*= \v3project\themes\mega\assets\ThemeMegaBuildAsset::getAssetUrl('images/details.png'); */?>" alt="">деталей: <?/*=$prop;*/?></p>
                    --><?/* endif; */?>
                    <!--<p class="with-icon"><img src="<?/*= \v3project\themes\mega\assets\ThemeMegaBuildAsset::getAssetUrl('images/age.png'); */?>" alt="">возраст:
                        --><?/*= $v3ProductElement->v3toysProductProperty ? $v3ProductElement->v3toysProductProperty->ageString : ""; */?>
                </div>

                <?/* if ($v3ProductElement->v3toysProductProperty->sku) : */?><!--
                    <p>Артикул: <?/*= $v3ProductElement->v3toysProductProperty->sku; */?></p>
                <?/* endif; */?>
                <?/* if ($prop = $model->relatedPropertiesModel->getSmartAttribute('brand')) : */?>
                    <p>Бренд:  <?/*=$prop; */?></p>
                --><?/* endif; */?>
            </div>
        </div>
    </article>
