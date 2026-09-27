<?php
/**
 * Страница ошибки магазина.
 * 404 — навигационная страница: каталог, его основные разделы, контакты.
 * Остальные ошибки выводятся базовым шаблоном cms.
 *
 * @var $this yii\web\View
 * @var $name string
 * @var $message string
 * @var $exception Exception
 */

use skeeks\cms\models\CmsTree;
use yii\helpers\Html;

$statusCode = isset($exception->statusCode) ? $exception->statusCode : $exception->getCode();

if ($statusCode != 404) {
    echo $this->render('@skeeks/cms/views/error/error', [
        'name'      => $name,
        'message'   => $message,
        'exception' => $exception,
    ]);
    return;
}

$this->title = \Yii::t('skeeks/cms', 'Страница не найдена');
$this->registerMetaTag(['name' => 'robots', 'content' => 'noindex, follow'], 'robots');

$catalogTree = null;
$sectionTrees = [];
$contactsTree = null;
$phone = null;

//Страница ошибки не должна падать из-за навигации
try {
    $cmsSite = \Yii::$app->cms->cmsSite;
    $shopSite = $cmsSite->hasProperty('shopSite') ? $cmsSite->shopSite : null;
    $catalogTree = $shopSite ? $shopSite->catalogMainCmsTree : null;

    //Разделы-ссылки на произвольный адрес (прайсы, внешние сайты) в навигацию не выводим
    $notFreeRedirect = ['or', ['redirect' => null], ['redirect' => '']];

    if ($catalogTree) {
        //Основные разделы каталога
        $sectionTrees = $catalogTree->getActiveChildren()->andWhere($notFreeRedirect)->orderBy(['priority' => SORT_ASC])->limit(8)->all();
    } else {
        //Каталога нет — разделы первого уровня, как в верхнем меню
        $sectionTrees = CmsTree::find()->cmsSite()->active()->andWhere(['level' => 1])->andWhere($notFreeRedirect)->orderBy(['priority' => SORT_ASC])->limit(8)->all();
    }

    $contactsTree = CmsTree::find()->cmsSite()->active()->andWhere(['level' => 1, 'code' => ['contacts', 'kontakty']])->limit(1)->one();
    $phone = $cmsSite->cmsSitePhone;
} catch (\Throwable $e) {
    \Yii::error("404 navigation: ".$e->getMessage(), __METHOD__);
}

$this->registerCss(<<<CSS
.sx-error-section {
    min-height: 50vh;
    display: flex;
    text-align: center;
    padding: 40px 0;
}
.sx-error-section .sx-container {
    margin: auto;
    max-width: 760px;
}
.sx-error-section .sx-error-text {
    margin-bottom: 24px;
}
.sx-error-section .sx-buttons {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    gap: 10px;
    margin-top: 16px;
}
.sx-error-section .sx-error-sections a {
    border: 1px solid;
    border-radius: 4px;
    padding: 8px 16px;
}
.sx-error-section .sx-error-contacts {
    margin-top: 24px;
}
CSS
);
?>
<div class="sx-error-section">
    <div class="container sx-container">
        <h1><?= \Yii::t('skeeks/cms', 'Страница не найдена'); ?></h1>
        <div class="sx-error-text">
            <?= \Yii::t('skeeks/cms', 'Возможно, страница была удалена, адрес изменился или ссылка устарела.'); ?>
            <?php if ($catalogTree) : ?>
                <?= \Yii::t('skeeks/cms', 'Перейдите в каталог или свяжитесь с нами.'); ?>
            <?php endif; ?>
        </div>

        <div class="sx-buttons">
            <?php if ($catalogTree) : ?>
                <a href="<?= $catalogTree->url; ?>" class="btn btn-primary btn-xl"><?= \Yii::t('skeeks/cms', 'Перейти в каталог'); ?></a>
            <?php else : ?>
                <a href="<?= \yii\helpers\Url::home(); ?>" class="btn btn-primary btn-xl"><?= \Yii::t('skeeks/cms', 'Вернуться на главную'); ?></a>
            <?php endif; ?>
        </div>

        <?php if ($sectionTrees) : ?>
            <div class="sx-buttons sx-error-sections">
                <?php foreach ($sectionTrees as $tree) : ?>
                    <a href="<?= $tree->url; ?>" class="btn btn-outline-primary"><?= Html::encode($tree->name); ?></a>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <?php if ($contactsTree || $phone) : ?>
            <div class="sx-error-contacts">
                <?php if ($contactsTree) : ?>
                    <a href="<?= $contactsTree->url; ?>"><?= Html::encode($contactsTree->name); ?></a>
                <?php endif; ?>
                <?php if ($contactsTree && $phone) : ?> · <?php endif; ?>
                <?php if ($phone) : ?>
                    <a href="tel:<?= Html::encode(preg_replace('/[^\d+]/', '', $phone->value)); ?>"><?= Html::encode($phone->value); ?></a>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <?php if ($catalogTree) : ?>
            <div class="sx-error-contacts">
                <a href="<?= \yii\helpers\Url::home(); ?>"><?= \Yii::t('skeeks/cms', 'Вернуться на главную'); ?></a>
            </div>
        <?php endif; ?>
    </div>
</div>
