<?php
/**
 * @link https://cms.skeeks.com/
 * @copyright Copyright (c) 2010 SkeekS
 * @license https://cms.skeeks.com/license/
 * @author Semenov Alexander <semenov@skeeks.com>
 */
?>
<?= $this->render('@skeeks/cms/themes/unify/views/headers/header-mobile', [
    'content' => $this->render("@app/views/headers/_header_shop"),
]); ?>
