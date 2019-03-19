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
$v3ProductElement = $model;
?>
    <article class="card-prod">
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
                <img src="<?= \Yii::$app->imaging->thumbnailUrlOnRequest($model->image ? $model->image->src : null,
                    new \skeeks\cms\components\imaging\filters\Thumbnail([
                        'w' => 230,
                        'h' => 230,
                        'm' => \Imagine\Image\ImageInterface::THUMBNAIL_INSET,
                    ]), $model->code
                ); ?>" title="<?= \yii\helpers\Html::encode($model->name); ?>" alt="<?= \yii\helpers\Html::encode($model->name); ?>" />
            </a>
        </div>
        <div class="card-prod--inner">

            <div class="card-prod--reviews">
                <? if ($count>0) : ?>
                    <div class="rating">
                        <div class="star <?= ($rating > 0) ? "active" :''?>"></div>
                        <div class="star <?= ($rating > 2) ? "active" :''?>"></div>
                        <div class="star <?= ($rating > 3) ? "active" :''?>"></div>
                        <div class="star <?= ($rating > 4) ? "active" :''?>"></div>
                        <div class="star <?= ($rating >=5 ) ? "active" :''?>"></div>
                    </div>
                <? else : ?>
                    <div class="rating">
                        <div class="star"></div>
                        <div class="star"></div>
                        <div class="star"></div>
                        <div class="star"></div>
                        <div class="star"></div>
                    </div>
                <? endif; ?>
                <div class="caption"><a href="<?=$model->url.'#tab-reviews'?>">(<?=$count;?> отзывов)</a></div>
            </div>


            <?/* if ($model->relatedPropertiesModel->getSmartAttribute('typeConstruct')) : $prop = $model->relatedPropertiesModel->getSmartAttribute('typeConstruct'); */?>
                <div class="card-prod--category">
                    <?/*=$prop;*/?>
                </div>
            <?/* endif; */?>

            <div class="card-prod--title">
                <a href="<?= $model->url; ?>" title="<?= $model->name; ?>" data-pjax="0"><?= $model->name; ?></a>
            </div>
            <? if (isset($shopProduct)) : ?>
            <div class="card-prod--price">
                <? if ($shopProduct->minProductPrice && $shopProduct->baseProductPrice && $shopProduct->minProductPrice->id == $shopProduct->baseProductPrice->id) : ?>
                    <div class="new"><?= \Yii::$app->money->convertAndFormat($shopProduct->minProductPrice->money); ?></div>
                <? else : ?>
                    <? if ($shopProduct->baseProductPrice && $shopProduct->minProductPrice) : ?>
                    <div class="old"><?= \Yii::$app->money->convertAndFormat($shopProduct->baseProductPrice->money); ?></div>
                    <div class="new"><?= \Yii::$app->money->convertAndFormat($shopProduct->minProductPrice->money); ?></div>
                    <? endif; ?>
                <? endif; ?>
            </div>

            <div class="card-prod--actions">
                <? if ($shopProduct->quantity > 0 && $shopProduct->minProductPrice) : ?>
                    <?= \yii\helpers\Html::tag('button', "<i class=\"icon cart\"></i>Купить", [
                        'class' => 'btn js-to-cart',
                        'type' => 'button',
                        'onclick' => new \yii\web\JsExpression("sx.Shop.addProduct({$shopProduct->id}, 1); return false;"),
                        'data' => \yii\helpers\ArrayHelper::merge($model->toArray(['name', 'id']), [
                            'url' => $model->url,
                            'image' => \skeeks\cms\helpers\Image::getSrc($model->image ? $model->image->src : null),
                            'price' => \Yii::$app->money->convertAndFormat($shopProduct->minProductPrice->money),
                        ]),
                    ]); ?>
                <? else : ?>
                    <?= \yii\helpers\Html::tag('a', "Подробнее", [
                        'class' => 'btn to-cart',
                        'type' => 'button',
                        'href' => $model->url,
                        'data' => ['pjax' => 0],
                    ]); ?>
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
