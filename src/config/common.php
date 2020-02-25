<?php
/**
 * @author Semenov Alexander <semenov@skeeks.com>
 * @link http://skeeks.com/
 * @copyright 2010 SkeekS (СкикС)
 * @date 27.08.2015
 */
return [
    'components' => [
        'i18n' => [
            'translations' => [
                'skeeks/unify-shop' => [
                    'class'    => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@skeeks/cms/themes/unifyshop/messages',
                    'fileMap'  => [
                        'skeeks/unify-shop' => 'main.php',
                    ],
                ],
            ],
        ],

        'unifyShopTheme'   =>  [
            'class' => 'skeeks\cms\themes\unifyshop\components\UnifyShopThemeSettingsComponent',
        ],
    ],
];