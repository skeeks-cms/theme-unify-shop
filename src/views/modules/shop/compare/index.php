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

$qCompare = \Yii::$app->shop->shopUser->getCmsCompareElements()->select(['cms_content_element_id']);

$dataProvider->query->andWhere(['in', \skeeks\cms\shop\models\ShopCmsContentElement::tableName().".id", $qCompare]);

$qElements = clone $dataProvider->query;

$dataProvider->query->joinWith('cmsTree as cmsTree');
$dataProvider->query->addSelect([
    \skeeks\cms\shop\models\ShopCmsContentElement::tableName().".*",
    'total'     => new \yii\db\Expression("count(1)"),
    'tree_name' => 'cmsTree.name',
]);
$dataProvider->query->groupBy(["tree_id"]);
$dataProvider->query->orderBy(["total" => SORT_DESC]);

$treesData = $dataProvider->query->asArray()->all();

$selectedTree = $treesData[0];
$selectedTreeId = \yii\helpers\ArrayHelper::getValue($treesData, "0.tree_id");

if (\Yii::$app->request->get("tree_id")) {
    $selectedTreeId = \Yii::$app->request->get("tree_id");
}
$selectedTreeModel = \skeeks\cms\models\CmsTree::findOne($selectedTreeId);

$qElements->andWhere(['tree_id' => $selectedTreeId]);

/**
 * @var \skeeks\cms\shop\models\ShopCmsContentElement[] $elements
 */
$elements = $qElements->all();
?>

    <div class="sx-compare-wrapper" style="padding-bottom: 20px; padding-top: 20px;">

        <div class="sx-arrows">
            <a href="#" class="sx-arrow-btn sx-arrow-right-btn"><i class="hs-icon hs-icon-arrow-right"></i></a>
            <a href="#" class="sx-arrow-btn sx-arrow-left-btn"><i class="hs-icon hs-icon-arrow-left"></i></a>
        </div>
        <div class="sx-catalog-h1-wrapper">
            <div><h1 class="sx-breadcrumbs-h1 sx-catalog-h1"><?php echo \Yii::t('skeeks/shop/app', 'Сравнение товаров'); ?></h1></div>
            <div class="sx-catalog-total-offers" style="color: #979797;
    margin-top: auto;
    margin-left: 12px;
    font-size: 15px;">(<?php echo \Yii::t('app', '{n, plural, =0{нет товаров} =1{# товар} one{# товар} few{# товара} many{# товаров} other{# товаров}}', ['n' => $qCompare->count()],
                    'ru_RU'); ?>)
            </div>
        </div>

        <div class="sx-trees">
            <ul class="list-unstyled list-inline" style="margin-bottom: 10px;">

                <? foreach ($treesData as $treeData) : ?>

                    <? if ($selectedTreeId == \yii\helpers\ArrayHelper::getValue($treeData, "tree_id")) : ?>
                        <li class="list-inline-item sx-active" style="margin-bottom: 5px;" data-value_id="" data-property_id="">
                            <div class="btn btn-primary">
        <span data-toggle="tooltip" data-html="true" title="" data-original-title="Применена категорию">
        <?php echo \yii\helpers\ArrayHelper::getValue($treeData, "tree_name"); ?>
        <?php echo \yii\helpers\ArrayHelper::getValue($treeData, "total"); ?> </span>
                                <!--<i class="hs-icon hs-icon-close sx-close-btn" data-toggle="tooltip" title="" data-original-title="Удалить категорию"></i>-->
                            </div>
                        </li>
                    <? else : ?>
                        <li class="list-inline-item" style="margin-bottom: 5px;" data-value_id="" data-property_id="">
                            <a href="<?php echo \yii\helpers\Url::to(['/shop/compare', 'tree_id' => \yii\helpers\ArrayHelper::getValue($treeData, "tree_id")]); ?>" class="btn btn-default">
                                            <span data-toggle="tooltip" data-html="true" title="" data-original-title="Выбрать категорию">
                                            <?php echo \yii\helpers\ArrayHelper::getValue($treeData, "tree_name"); ?>
                                            <?php echo \yii\helpers\ArrayHelper::getValue($treeData, "total"); ?> </span>
                            </a>
                        </li>
                    <? endif; ?>


                <? endforeach; ?>


            </ul>

        </div>

        <div class="sx-compare-manager">

            <div class="sx-elements sx-product-list">
                <div class="sx-elements-inner">
                <? foreach ($elements as $element) : ?>
                    <div class="sx-product-card-wrapper">
                        <?php echo $this->render('@app/views/products/product-list-item', [
                            'model' => $element,
                        ]); ?>
                    </div>
                <? endforeach; ?>
                <div class="sx-product-card-wrapper">
                    <div class="sx-product-card h-100 sx-add-more">
                        <a href="<?php echo $selectedTreeModel->url; ?>">
                            <div><i class="icon-plus"></i></div>
                            <div>Добавить товар к сравнению</div>
                        </a>
                    </div>
                </div>
                </div>
            </div>

            <div class="sx-props">
                <? foreach ($element->relatedPropertiesModel->properties as $property) : ?>

                <?
                    if ($property->handler instanceof \skeeks\cms\relatedProperties\propertyTypes\PropertyTypeText) {
                        if ($property->handler->fieldElement == "textarea") {
                            continue;
                        }
                    }
                    if ($property->handler instanceof \skeeks\cms\relatedProperties\userPropertyTypes\UserPropertyTypeComboText) {
                        continue;
                    }
                    ?>
                    <div class="sx-prop">
                        <div class="sx-prop-name"> <?php echo $property->name; ?></div>
                        <div class="sx-prop-values">
                            <? foreach ($elements as $elem) : ?>
                                <div class="sx-product-property-value">
                                    <?php echo $elem->relatedPropertiesModel->getAttributeAsText($property->code); ?>
                                </div>
                            <? endforeach; ?>
                        </div>
                    </div>

                <? endforeach; ?>


            </div>

        </div>

        <?php /*echo $this->render("@app/views/products/product-list", [
                    'dataProvider' => $dataProvider,
                ]); */ ?>
    </div>

<?php

$this->registerJs(<<<JS
sx.classes.CompareManager = sx.classes.Component.extend({

    _init: function()
    {
        $(".sx-compare-manager").offset().top;
        $(window).scrollTop();
    },
    
    _onDomReady: function()
    {
        var self = this;
        
        this.updateInstance();
        
        $(window).on("scroll", function() {
            self.updateInstance();
        });
        $(window).on("resize", function() {
            self.updateInstance();
        });
        
        var jCompareManager = $(".sx-compare-manager");
        var jProps = $(".sx-props");
        var jElements = $(".sx-elements");
        var jElementsInner = $(".sx-elements-inner");
        
        var jRightBtn = $(".sx-arrow-right-btn");
        var jLeftBtn = $(".sx-arrow-left-btn");
        
        var jPropValues = $(".sx-prop-values");
        
        jRightBtn.on("click", function() {
            var width = $(".sx-product-card-wrapper").width();
            var left = width + 14;
            if (jElements.data("x")) {
                left = left + jElements.data("x");
            }
            jElements.data("x", left);
            
            jElementsInner.css("transform", 'translateX(-' + left + 'px)');
            jPropValues.css("transform", 'translateX(-' + left + 'px)');
            setTimeout(function() {
                self.updateInstance();
            }, 300);
            return false;
        });
        
        jLeftBtn.on("click", function() {
            var width = $(".sx-product-card-wrapper").width();
            var left = width + 14;
            if (jElements.data("x")) {
                left = jElements.data("x") - left;
            }
            
            jElements.data("x", left);
            
            jElementsInner.css("transform", 'translateX(-' + left + 'px)');
            jPropValues.css("transform", 'translateX(-' + left + 'px)');
            setTimeout(function() {
                self.updateInstance();
            }, 300);
            return false;
        });
    },
    
    updateInstance: function()
    {
        var jCompareManager = $(".sx-compare-manager");
        var jProps = $(".sx-props");
        var jElements = $(".sx-elements");
        
        var topPosition = jCompareManager.offset().top;
        if ($(window).scrollTop() > topPosition) {
            jCompareManager.addClass("sx-fixed");
            setTimeout(function() {
                jProps.css("padding-top", jElements.height() + 50);
            }, 100);
            
        } else {
            jCompareManager.removeClass("sx-fixed");
            
            setTimeout(function() {
                jProps.css("padding-top", 0);
            }, 100);
        }; 
        
        var windowWidth = $(window).width();
        var hasRightBtn = false;
        var hasLeftBtn = false;
        /*console.log($(".sx-add-more").offset().left);*/
        if ($(".sx-add-more").offset().left + $(".sx-add-more").width() > windowWidth) {
                hasRightBtn = true;
        };
        
        if (jElements.data("x")) {
            console.log(jElements.data("x"));
            hasLeftBtn = true;
        }
        
        if (hasRightBtn) {
            $(".sx-arrows").addClass("sx-show-right");
        } else {
            $(".sx-arrows").removeClass("sx-show-right");
        }
        
        
        if (hasLeftBtn) {
            $(".sx-arrows").addClass("sx-show-left");
        } else {
            $(".sx-arrows").removeClass("sx-show-left");
        }
        
    }
});

new sx.classes.CompareManager ();
JS
);

$this->registerCss(<<<CSS

.sx-product-list .sx-product-card-wrapper {
    padding-right: 7px !important;
    padding-left: 7px !important;
}


.sx-arrows a:hover {
    text-decoration: none;
}
.sx-show-right .sx-arrow-right-btn {
    display: flex;
}
.sx-show-left .sx-arrow-left-btn {
    display: flex;
}

.sx-arrow-right-btn {
    right: 1rem;
}
.sx-arrow-left-btn {
    left: 1rem;
}
.sx-arrow-btn {
    transition: 0.25s linear;
    color: gray;
    display: none;
    justify-content: center;
    align-items: center;
    position: fixed;
    
    z-index: 999;
    background: white;
    border-radius: 50%;
    font-size: 2rem;
    width: 4rem;
    height: 4rem;
    top: 50%;
    box-shadow: 0 5px 10px -6px rgba(0, 0, 0, 0.1);
}

.sx-compare-manager {
    overflow-x: hidden;
}

.sx-fixed .sx-elements .sx-elements-inner {
    width: 100%;
    /*overflow: hidden;*/
}
.sx-fixed .sx-elements {
    padding-left: 2rem;
    padding-right: 2rem;
    
    position: fixed;
    width: 100%;
    top: 0;
    left: 0;
    
    padding-top: 0.5rem;
    padding-bottom: 0.5rem;
    z-index: 9999;
    background: var(--bg-color);
    box-shadow: 0 5px 10px -6px rgba(0, 0, 0, 0.1);
   
}
.sx-fixed .sx-product-card .sx-product-card--photo {
    width: 30%;
    min-width: 4rem;
}
.sx-fixed .sx-product-card {
    display: flex;
    flex-direction: row;
    align-items: center;
    justify-content: center;
}

.sx-fixed .sx-product-card .sx-product-card--title a {
    font-size: 0.7rem;
}

.sx-compare-wrapper {
    padding: 2rem;
    /*width: 100%;*/
    overflow-x: hidden;
}
.sx-prop-name {
    color: gray;
    margin-bottom: 1rem;
    padding-left: 10px;
    padding-right: 10px;
}
.sx-prop {
    margin-top: 1rem;
    padding-top: 1rem;
    border-top: 1px solid silver;
}
.sx-prop-values .sx-product-property-value {
    width: 20%;
    min-width: 20%;
    padding-left: 7px;
    padding-right: 7px;
}
.sx-prop-values {
    display: flex;
}




.sx-add-more div {
    margin-bottom: 1rem;
}
.sx-add-more i {
    font-size: 3rem;
    
}
.sx-add-more a:hover {
    text-decoration: none;
}

.sx-add-more a {
    color: gray;
    font-size: 1.2rem;
    text-align: center;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-direction: column;
}
.sx-elements .sx-product-card-wrapper {
    width: 20%;
    min-width: 20%;
}
.sx-elements-inner {
    display: flex;
    transition: 0.25s linear;
    width: 100%;
}
.sx-prop-values {
    transition: 0.25s linear;
}

.sx-elements {
    display: flex;
    width: 100%;
    transition: 0.25s linear;
    margin-right: -7px !important;
    margin-left: -7px !important;
}
.sx-props {
    transition: 0.25s linear;
}
.sx-trees i {
    font-size: 0.5rem;
    margin-left: 0.25rem;
}
    
CSS
);
