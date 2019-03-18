<?php
/**
 * @link https://cms.skeeks.com/
 * @copyright Copyright (c) 2010 SkeekS
 * @license https://cms.skeeks.com/license/
 * @author Semenov Alexander <semenov@skeeks.com>
 */
/* @var $this yii\web\View */
?>
<?
$catalog = \skeeks\cms\models\CmsTree::find()->where(['dir' => 'catalog'])->one();
echo \skeeks\cms\cmsWidgets\treeMenu\TreeMenuCmsWidget::widget([
    'namespace' => 'home-tree-slider',
    'viewFile'  => '@app/views/widgets/TreeMenuCmsWidget/revolution-slider',
    'treePid'   => $catalog ? $catalog->id : null,
    'enabledRunCache'   => \skeeks\cms\components\Cms::BOOL_N,
]);
?>


