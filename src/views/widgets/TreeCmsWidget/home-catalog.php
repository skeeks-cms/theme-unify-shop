<?php
/**
 * @author Semenov Alexander <semenov@skeeks.com>
 * @link http://skeeks.com/
 * @copyright 2010 SkeekS (СкикС)
 * @date 25.05.2015
 */
/* @var $this   yii\web\View */
/* @var $widget \skeeks\cms\cmsWidgets\treeMenu\TreeMenuCmsWidget */
/* @var $models  \skeeks\cms\models\Tree[] */
\skeeks\cms\themes\unify\assets\components\UnifyThemeSubcatalogAsset::register($this);
$this->registerCss(<<<CSS
.sx-home-subcatalog .sx-info {
    padding: 1.5rem;
    display: flex;
    flex-direction: column;
    flex-grow: 1;
    justify-content: space-between;
}
.sx-home-subcatalog .sx-item {
    background: var(--second-bg-color);
    border-radius: var(--base-radius);
    overflow: hidden;
    display: flex;
    flex-direction: column;
    transition: all 0.35s ease;
}
.sx-home-subcatalog .sx-item-wrapper {
    padding-bottom: 2rem;
    display: flex;
}
.sx-home-subcatalog .sx-item:hover {
    transform: translateY(-6px);
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.08);
}
.sx-home-subcatalog .sx-item .sx-title {
    line-height: 1;
    margin-bottom: 0.8rem;
}
.sx-home-subcatalog .sx-item .sx-desc {
    line-height: 1.5;
    margin-bottom: 0.8rem;
    font-size: 1rem;
}
CSS
);

?>
<? if ($models = $widget->activeQuery->all()) : ?>
    <div class="sx-section sx-home-section sx-home-subcatalog">
        <div class="container sx-container">
            <div class="row align-items-stretch">
                <? foreach ($models as $model) : ?>
                    <?= $this->render("_one-home-catalog", [
                        "widget" => $widget,
                        "model"  => $model,
                    ]); ?>
                <? endforeach; ?>
            </div>
        </div>
    </div>
<? endif; ?>
