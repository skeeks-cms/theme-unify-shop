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
echo $this->render("catalog-".\Yii::$app->unifyShopTheme->product_list_view_file, [
    'model'           => $model
]);
?>

