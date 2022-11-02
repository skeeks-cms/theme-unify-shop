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
 * @var $savedFilter \skeeks\cms\models\CmsSavedFilter
 */
$this->registerCss(<<<CSS
.sx-main-catalog {
    display: flex;
    min-height: calc(100vh - 100px);
}
.sx-main-catalog > div{
    margin: auto;
}
CSS
);
if (@$savedFilter) {
    echo $this->render("@app/views/modules/cms/tree/catalog", [
        'model' => $model,
        'savedFilter' => @$savedFilter
    ]);
} else {
    echo $this->render("_main-catalog");
}
