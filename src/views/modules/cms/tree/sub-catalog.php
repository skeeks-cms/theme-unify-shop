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
.sx-subcatalog-page .sx-breadcrumbs-wrapper {
    padding-top: 1rem;
    padding-bottom: 1rem;
}
.sx-subcatalog-wrapper {
    min-height: calc(100vh - 100px);
}

CSS
);

?>

<?php if (@$savedFilter) : ?>
    <?php
    echo $this->render("@app/views/modules/cms/tree/catalog", [
        'model'       => $model,
        'savedFilter' => @$savedFilter,
    ]);
    ?>
<?php else : ?>
    <div class="sx-subcatalog-page">
        <div class="container sx-container">

            <?= $this->render('@app/views/breadcrumbs', [
                'model'      => $model,
                'isShowH1'   => true,
                'isShowLast' => true,
            ]); ?>

        </div>

        <div class="sx-subcatalog-wrapper">
            <?php echo $this->render("_sub-catalog"); ?>
        </div>
    </div>
<?php endif; ?>

