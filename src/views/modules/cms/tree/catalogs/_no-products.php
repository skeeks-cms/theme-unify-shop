<?php
/**
 * @var $this yii\web\View
 *
 * @var $cmsTree \skeeks\cms\models\CmsTree
 * @var $savedFilter \skeeks\cms\models\CmsSavedFilter
 * @var $filtersWidget \skeeks\cms\themes\unifyshop\filters\StandartShopFiltersWidget
 */
$priceFilter = $filtersWidget->getPriceHandler();
$eavFilter = $filtersWidget->getEavHandler();
?>
<div class="sx-catalog-no-products" style="display: flex;
  align-items: center;
  justify-content: center;min-height: 500px;">
    <div class="sx-empty-content" style="text-align: center;">
        <div class="h1">Товары не найдены!</div>
        <?php if($savedFilter || $eavFilter->getApplied() || ($priceFilter->t || $priceFilter->f)) : ?>
            <div class="" style="margin-bottom: 20px;">У вас применены фильтры, попробуйте изменить условия!</div>
            <div class="">
                <a href="<?php echo $cmsTree->url; ?>" class="btn btn-primary btn-xxl">Сбросить фильтры</a>
            </div>
        <?php else : ?>
            <?php if($cmsTree->parent) : ?>
                <div class="" style="margin-bottom: 20px;">Товары в этом разделе возможно закончились, попробуйте посмотреть раздел «<?php echo $cmsTree->parent->name; ?>»</div>
                <div class="">
                    <a href="<?php echo $cmsTree->parent->url; ?>" class="btn btn-primary btn-xxl">Смотреть товары «<?php echo $cmsTree->parent->name; ?>»</a>
                </div>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</div>
