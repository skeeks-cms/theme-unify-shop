<?php
/**
 * @link https://cms.skeeks.com/
 * @copyright Copyright (c) 2010 SkeekS
 * @license https://cms.skeeks.com/license/
 * @author Semenov Alexander <semenov@skeeks.com>
 */
?>

<?= $this->render('@skeeks/cms/themes/unify/views/headers/header-v4', [
    'content' => $this->render("@app/views/headers/_header_shop"),
]); ?>
<?php if ($this->theme->brand_line_is_active) : ?>
    <?php if (!\Yii::$app->mobileDetect->isMobile) : ?>
        <?= $this->render('@app/views/headers/_header-brands'); ?>
    <?php endif; ?>
<?php endif; ?>

