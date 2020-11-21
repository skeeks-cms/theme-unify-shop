<?php
/**
 * @link https://cms.skeeks.com/
 * @copyright Copyright (c) 2010 SkeekS
 * @license https://cms.skeeks.com/license/
 * @author Semenov Alexander <semenov@skeeks.com>
 */
/* @var $this yii\web\View */
/**
 * @var $model \skeeks\cms\models\CmsTree
 */
$catalogSettings = \skeeks\cms\themes\unifyshop\cmsWidgets\catalog\ShopCatalogPage::beginWidget("catalog");
$catalogSettings::end();

echo $this->render("catalog-" . $catalogSettings->view_file, [
    'model' => $model,
    'catalogSettings' => $catalogSettings
]);
?>

