<?php
/**
 * @link https://cms.skeeks.com/
 * @copyright Copyright (c) 2010 SkeekS
 * @license https://cms.skeeks.com/license/
 * @author Semenov Alexander <semenov@skeeks.com>
 */

namespace skeeks\cms\themes\unifyshop\cmsWidgets\catalog;

use skeeks\cms\backend\widgets\ActiveFormBackend;
use skeeks\cms\base\Widget;
use skeeks\cms\widgets\ColorInput;
use skeeks\yii2\form\fields\BoolField;
use skeeks\yii2\form\fields\FieldSet;
use skeeks\yii2\form\fields\SelectField;
use skeeks\yii2\form\fields\WidgetField;
use yii\helpers\ArrayHelper;

/**
 * @author Semenov Alexander <semenov@skeeks.com>
 */
class ShopCatalogNoColPage extends Widget
{
    /**
     * @var bool Зафиксировать фильтры при скроле страницы
     */
    public $is_fix_filters_on_scroll = true;
    

    public static function descriptorConfig()
    {
        return array_merge(parent::descriptorConfig(), [
            'name' => \Yii::t('skeeks/shop/app', 'Настройки страницы списка товаров'),
        ]);
    }

    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), [
            'is_fix_filters_on_scroll' => \Yii::t('skeeks/shop/app', 'Зафиксировать фильтры при скроле страницы?'),
        ]);
    }

    public function attributeHints()
    {
        return array_merge(parent::attributeLabels(), [
            'is_fix_filters_on_scroll' => \Yii::t('skeeks/shop/app', 'В этом случае фильтры будут зафикисированы всегда перед глазами клиента.'),
        ]);
    }

    public function rules()
    {
        return ArrayHelper::merge(parent::rules(), [
            [['is_show_subtree_col_left'], 'boolean'],
        ]);
    }

    /**
     * @return ActiveForm
     */
    public function beginConfigForm()
    {
        return ActiveFormBackend::begin();
    }

    /**
     * @return array
     */
    public function getConfigFormFields()
    {
        return [
            
            'main' => [
                'class' => FieldSet::class,
                'name' => 'Основное',
                'fields' => [
                    'is_fix_filters_on_scroll' => [
                        'class' => BoolField::class,
                        'allowNull' => false
                    ],
                ]
            ],

        ];
    }
}