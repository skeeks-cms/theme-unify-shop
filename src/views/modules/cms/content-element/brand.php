<?php
/**
 * @link https://cms.skeeks.com/
 * @copyright Copyright (c) 2010 SkeekS
 * @license https://cms.skeeks.com/license/
 * @author Semenov Alexander <semenov@skeeks.com>
 */
/* @var $this yii\web\View */

if (@$isShowMainImage !== false) {
    $isShowMainImage = true;
}
$this->theme->element_content_layout = 'no-col';
$image = $model->image;
if (!$image && $model->main_cce_id) {
    $image = $model->mainCmsContentElement->image;
}
$description = $model->description_full;
if (!$description && $model->main_cce_id) {
    $description = $model->mainCmsContentElement->description_full;
}

$brandTree = \skeeks\cms\models\CmsTree::find()->cmsSite()->andWhere(['view_file' => 'brands'])->one();
if ($brandTree) {
    \Yii::$app->breadcrumbs->parts = [];
    \Yii::$app->breadcrumbs->setPartsByTree($brandTree)->append([
        'name' => $model->name,
        'url'  => $model->url,
    ]);
}

$this->registerCss(<<<CSS
.sx-saved-filters-list .list-inline-item a {
    display: inline-flex;
    min-height: 100%;
    min-width: 20rem;
    overflow: hidden;
    text-align: left;
}
.sx-saved-filters-list .list-inline-item .sx-img-wrapper {
    margin-right: 0.5rem;
}

.sx-img-wrapper img {
    border-radius: var(--base-radius);
}

/*.sx-brand-page {
    min-height: 80vh;
    padding: 1.5rem 0;
}*/

.sx-brand-page .sx-container {
    padding-top: 1.5rem;
    background: white;
    padding-bottom: 1.5rem;
    min-height: 80vh;
}

    


.sx-brand-page .sx-brand-img {
    border-radius: var(--base-radius);
    background: var(--second-bg-color);
    padding: 0.5rem;
}
.sx-brand-page .sx-brand-img img {
    border-radius: var(--base-radius);
}

.sx-properties {
    margin-top: 1.5rem;
    border-radius: var(--base-radius);
    background: var(--second-bg-color);
    padding: 1rem;
}

.sx-properties p:last-child {
    margin-bottom: 0;
}
.sx-properties p {
    margin-bottom: 0.5rem;
}

.sx-categories {
    margin-top: 1.5rem;
}
.sx-description {
    margin-top: 1.5rem;
    
    border-radius: var(--base-radius);
    background: var(--second-bg-color);
    padding: 1rem;
}

CSS
);
?>


<section class="sx-brand-page">
    <div class="container sx-container">
        <div class="row">
            <div class="col-md-12">

                <? /* if (!$this->theme->is_image_body_begin) : */ ?>

                <? /* endif; */ ?>
                <div class="sx-content" itemscope itemtype="http://schema.org/NewsArticle">
                    <!-- Микроразметка новости-статьи -->
                    <meta itemscope itemprop="mainEntityOfPage" itemType="https://schema.org/WebPage" itemid="<?= $model->getUrl(true); ?>"/>
                    <meta itemprop="headline" content="<?= $model->seoName; ?>">
                    <?php if ($model->createdBy) : ?>
                        <span itemprop="author" itemscope itemtype="https://schema.org/Person"><meta itemprop="name" content="<?= $model->createdBy->displayName; ?>"></span>
                    <?php endif; ?>

                    <span itemprop="publisher" itemtype="http://schema.org/Organization" itemscope="">
                        <meta itemprop="name" content="<?= \Yii::$app->cms->appName; ?>">
                        <?php if (\Yii::$app->skeeks->site->cmsSiteAddress) : ?>
                            <meta itemprop="address" content="<?= \Yii::$app->skeeks->site->cmsSiteAddress->value; ?>">
                        <?php endif; ?>

                        <?php if (\Yii::$app->skeeks->site->cmsSitePhone) : ?>
                            <meta itemprop="telephone" content="<?= \Yii::$app->skeeks->site->cmsSitePhone->value; ?>">
                        <?php endif; ?>

                                <span itemprop="logo" itemtype="http://schema.org/ImageObject" itemscope="">
                                    <link itemprop="url" href="<?= $this->theme->logo; ?>">
                                    <meta itemprop="image" content="<?= $this->theme->logo; ?>">
                                </span>
                            </span>
                    <meta itemprop="datePublished" content="<?= \Yii::$app->formatter->asDate($model->created_at, "php:Y-m-d"); ?>"/>
                    <meta itemprop="dateModified" content="<?= \Yii::$app->formatter->asDate($model->updated_at, "php:Y-m-d"); ?>"/>
                    <meta itemprop="genre" content="<?= $model->cmsTree ? $model->cmsTree->name : ""; ?>"/>
                    <? if ($model->description_short) : ?>
                        <meta itemprop="description" content="<?= strip_tags((string)$model->description_short); ?>"/>
                    <? else : ?>
                        <meta itemprop="description" content="<?= \yii\helpers\StringHelper::truncate(strip_tags((string)$model->description_full), 250); ?>"/>
                    <? endif; ?>
                    <? if ($model->image) : ?>
                        <span itemprop="image" itemscope itemtype="https://schema.org/ImageObject">
                        <link itemprop="url" href="<?= $model->getUrl(true); ?>">
                        <span itemprop="image" content="<?= $model->image->src; ?>">
                            <meta itemprop="width" content="<?= $model->image->image_width; ?>">
                            <meta itemprop="height" content="<?= $model->image->image_height; ?>">
                        </span>
                    </span>
                    <? endif; ?>
                    <!-- /Микроразметка новости -->
                    <div class="d-flex">

                        <? if ($image) : ?>
                            <div class="sx-brand-img my-auto">
                                <img src="<?= \Yii::$app->imaging->thumbnailUrlOnRequest($image ? $image->src : null,
                                    new \skeeks\cms\components\imaging\filters\Thumbnail([
                                        'w' => 100,
                                        'h' => 100,
                                    ]), $model->code
                                ) ?>" title="<?= $model->seoName; ?>" alt="<?= $model->seoName; ?>" class="img-responsive"/>
                            </div>
                        <? endif; ?>
                        <div class="col my-auto">

                            <?= $this->render('@app/views/breadcrumbs', [
                                'model' => $model,
                            ]) ?>

                        </div>
                    </div>


                    <?
                    $infoModel = $model;
                    if ($model->main_cce_id) {
                        $infoModel = $model->mainCmsContentElement;
                    }
                    $widget = \skeeks\cms\rpViewWidget\RpViewWidget::beginWidget('brand-properties', [
                        'model'                   => $infoModel,
                        'visible_only_has_values' => true,
                    ]);
                    /*$widget->viewFile = '@app/views/widgets/RpWidget/default';*/
                    /* $widget->viewFile = '@app/views/modules/cms/content-element/_product-properties';*/
                    ?>
                    <? if ($widget->rpAttributes) : ?>
                        <div class="row">
                            <div class="col-md-6 col-12">
                                <div class="sx-properties">

                                    <? $widget::end(); ?>
                                </div>
                            </div>
                        </div>
                    <? endif; ?>


                    <!--Товары и категории-->
                    <?php
                    $q = \skeeks\cms\shop\models\ShopCmsContentElement::find()->cmsSite()->innerJoinWith("shopProduct as shopProduct");
                    $q->joinWith("cmsContentElementProperties as values", true, "INNER JOIN");
                    $q->andWhere([
                        'values.value_element_id' => $model->id,
                    ]);
                    $q->select([
                        \skeeks\cms\shop\models\ShopCmsContentElement::tableName().".tree_id",
                    ]);

                    $q->groupBy([\skeeks\cms\shop\models\ShopCmsContentElement::tableName().".tree_id"]);

                    $cmsTree = \skeeks\cms\models\CmsTree::find()->cmsSite()->andWhere(['id' => $q]);
                    $cmsTrees = $cmsTree->all();

                    ?>

                    <?php if ($cmsTrees) : ?>
                        <div class="sx-categories">
                            <?
                            $brandProperty = \skeeks\cms\models\CmsContentProperty::find()->cmsSite()->andWhere(['is_vendor' => 1])->one();
                            ?>
                            <?php if ($brandProperty) : ?>
                                <div class="sx-saved-filters-list">
                                    <!--<h3>Категории:</h3>-->
                                    <ul class="list-unstyled list-inline" style="margin-bottom: 10px;">
                                        <? foreach ($cmsTrees as $cmsTree) : ?>
                                            <?php
                                            $sf = \skeeks\cms\models\CmsSavedFilter::find()->cmsSite()->andWhere(['cms_tree_id' => $cmsTree->id])->andWhere(['value_content_element_id' => $model->id])->one();
                                            if (!$sf) {
                                                $sf = new \skeeks\cms\models\CmsSavedFilter();
                                                $sf->cms_tree_id = $cmsTree->id;
                                                $sf->value_content_element_id = $model->id;

                                                $sf->cms_content_property_id = $brandProperty->id;

                                                if (!$sf->save()) {
                                                    print_r($sf->errors);
                                                    die;
                                                }
                                            }
                                            echo $this->render("@app/views/modules/cms/tree/catalogs/_category", [
                                                'isActive'     => 0,
                                                'image'        => $sf->image,
                                                'seoName'      => $sf->seoName,
                                                'displayName'  => $sf->cmsTree->name,
                                                'code'         => $sf->cmsTree->code,
                                                'url'          => $sf->url,
                                                'description'  => $sf->propertyValueName,
                                                'image_width'  => 100,
                                                'image_height' => 100,
                                            ]); ?>
                                        <? endforeach; ?>
                                    </ul>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>

                    <?php if ($description) : ?>
                        <div class="sx-description">
                            <?= $description; ?>
                        </div>

                    <?php endif; ?>


                </div>
            </div>
        </div>
    </div>

</section>

