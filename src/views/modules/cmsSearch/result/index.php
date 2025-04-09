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
    'query' => \skeeks\cms\shop\models\ShopCmsContentElement::find()->cmsSite()->active(),
]);
//$dataProvider->query->cmsTree();

$dataProvider->pagination->pageSize = \Yii::$app->view->theme->productListPerPageSize;
$dataProvider->query->with('shopProduct');
$dataProvider->query->with('shopProduct.baseProductPrice');
$dataProvider->query->with('image');
$dataProvider->query->joinWith('shopProduct');

//\Yii::$app->shop->filterByMainPidContentElementQuery($dataProvider->query);
\Yii::$app->shop->filterByTypeContentElementQuery($dataProvider->query);

\Yii::$app->cmsSearch->buildElementsQuery($dataProvider->query);
/*\Yii::$app->cmsSearch->logResult($dataProvider);*/

$dataProvider->query->groupBy([\skeeks\cms\models\CmsContentElement::tableName().".id"]);
//print_r($dataProvider->query->createCommand()->rawSql);die;
$q = clone $dataProvider->query;
$select = [
        \skeeks\cms\models\CmsContentElement::tableName().".id"
];
$total = $q->select($select)->limit(-1)->offset(-1)->orderBy([])->count('*');
$dataProvider->setTotalCount($total);

?>

<section class="">
    <div class="container sx-container">
        <div class="row">
            <div class="col-12 sx-catalog-wrapper" style="padding-bottom: 20px; padding-top: 20px;">
                
        <div class="sx-catalog-h1-wrapper">
            <div><h1 class="sx-breadcrumbs-h1 sx-catalog-h1"><?php echo \Yii::t('app', '{n, plural, =0{нет товаров} =1{# товар} one{# товар} few{# товара} many{# товаров} other{# товаров}}', ['n' => $dataProvider->totalCount],
                    'ru_RU'); ?></h1></div>
            
        </div>
        
        <?php echo $this->render("@app/views/products/product-list", [
            'dataProvider' => $dataProvider,
        ]); ?>
    </div>
    </div>
    </div>
</section>