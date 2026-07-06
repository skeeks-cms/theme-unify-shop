<?php
/**
 * @link https://cms.skeeks.com/
 * @copyright Copyright (c) 2010 SkeekS
 * @license https://cms.skeeks.com/license/
 * @author Semenov Alexander <semenov@skeeks.com>
 */
/* @var $this yii\web\View */
/* @var $model \skeeks\cms\models\CmsTree */

$this->registerMetaTag([
    'name' => 'robots',
    'content' => 'noindex, nofollow',
], 'robots');
\Yii::$app->response->headers->set('X-Robots-Tag', 'noindex, nofollow');

$pjax = \skeeks\cms\widgets\PjaxLazyLoad::begin([
    'id' => 'sx-search-result-lazy',
]);

if ($pjax->isPjax) {

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

}

?>

<section class="">
    <div class="container sx-container">
        <div class="row">
            <div class="col-12 sx-catalog-wrapper" style="padding-bottom: 20px; padding-top: 20px;">
        <?php if ($pjax->isPjax) : ?>

        <div class="sx-catalog-h1-wrapper">
            <div><h1 class="sx-breadcrumbs-h1 sx-catalog-h1"><?php echo \Yii::t('app', '{n, plural, =0{нет товаров} =1{# товар} one{# товар} few{# товара} many{# товаров} other{# товаров}}', ['n' => $dataProvider->totalCount],
                    'ru_RU'); ?></h1></div>
            
        </div>
        
        <?php echo $this->render("@app/views/products/product-list", [
            'dataProvider' => $dataProvider,
        ]); ?>
        <?php else : ?>
            <div class="sx-search-lazy-placeholder">
                <div class="sx-search-lazy-placeholder__spinner"></div>
                <div class="sx-search-lazy-placeholder__text"><?= \Yii::t('skeeks/search', 'Searching') ?>...</div>
            </div>
            <style>
                .sx-search-lazy-placeholder {
                    min-height: 260px;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    flex-direction: column;
                    color: #555;
                    text-align: center;
                }
                .sx-search-lazy-placeholder__spinner {
                    width: 46px;
                    height: 46px;
                    margin-bottom: 18px;
                    border: 4px solid color-mix(in srgb, var(--primary-color, #8e12b5) 16%, transparent);
                    border-top-color: var(--primary-color, #8e12b5);
                    border-radius: 50%;
                    animation: sx-search-lazy-spin 0.8s linear infinite;
                }
                .sx-search-lazy-placeholder__text {
                    font-size: 28px;
                    font-weight: 600;
                    line-height: 1.25;
                }
                @keyframes sx-search-lazy-spin {
                    to {
                        transform: rotate(360deg);
                    }
                }
            </style>
        <?php endif; ?>
        <? $pjax::end(); ?>
    </div>
    </div>
    </div>
</section>
