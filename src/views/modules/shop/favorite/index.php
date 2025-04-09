<?php
/**
 * @link https://cms.skeeks.com/
 * @copyright Copyright (c) 2010 SkeekS
 * @license https://cms.skeeks.com/license/
 * @author Semenov Alexander <semenov@skeeks.com>
 */
/* @var $this yii\web\View */
/* @var $model \skeeks\cms\models\CmsTree */


$dataProvider = new \yii\data\ActiveDataProvider([
    'query' => \skeeks\cms\shop\models\ShopCmsContentElement::find()->active(),
]);
//$dataProvider->query->cmsTree();

$dataProvider->pagination->pageSize = \Yii::$app->view->theme->productListPerPageSize;
$dataProvider->query->with('shopProduct');
$dataProvider->query->with('shopProduct.baseProductPrice');
$dataProvider->query->with('image');
$dataProvider->query->joinWith('shopProduct');

//\Yii::$app->shop->filterBaseContentElementQuery($dataProvider->query);

$dataProvider->query->joinWith("shopProduct.shopFavoriteProducts as fav");
$dataProvider->query->andWhere(['is not', "fav.id", null]);
$dataProvider->query->andWhere(["fav.shop_user_id" => \Yii::$app->shop->shopUser->id]);

$filtersWidget = new \skeeks\cms\themes\unifyshop\filters\StandartShopFiltersWidget();
$baseQuery = clone $dataProvider->query;

/*\Yii::$app->breadcrumbs->createBase()->append(\Yii::t('skeeks/shop/app', 'Favorite products'));*/

/*echo $this->render("@app/views/modules/cms/tree/catalogs/".\Yii::$app->view->theme->product_list_view_file, [
    'dataProvider'  => $dataProvider,
    'filtersWidget' => $filtersWidget,
    'title'         => \Yii::t('skeeks/shop/app', 'Favorite products'),
]);*/
?>

<section class="">
    <div class="container sx-container">
        <div class="row">
            <div class="col-12 sx-catalog-wrapper" style="padding-bottom: 20px; padding-top: 20px;">
                
        <div class="sx-catalog-h1-wrapper">
            <div><h1 class="sx-breadcrumbs-h1 sx-catalog-h1"><?php echo \Yii::t('skeeks/shop/app', 'Favorite products'); ?></h1></div>
            <div class="sx-catalog-total-offers" style="color: #979797;
    margin-top: auto;
    margin-left: 12px;
    font-size: 15px;">(<?php echo \Yii::t('app', '{n, plural, =0{нет товаров} =1{# товар} one{# товар} few{# товара} many{# товаров} other{# товаров}}', ['n' => $dataProvider->count],
                    'ru_RU'); ?>)
            </div>
        </div>
        
        <?php echo $this->render("@app/views/products/product-list", [
            'dataProvider' => $dataProvider,
        ]); ?>
    </div>
    </div>
    </div>
</section>
