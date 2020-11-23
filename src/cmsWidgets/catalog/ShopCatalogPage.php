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
class ShopCatalogPage extends Widget
{
    /**
     * @var bool Показывать подразделы перед товарами?
     */
    public $is_show_subtree_col_left = true;
    

    public static function descriptorConfig()
    {
        return array_merge(parent::descriptorConfig(), [
            'name' => \Yii::t('skeeks/shop/app', 'Настройки левой колонки'),
        ]);
    }

    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), [
            'is_show_subtree_col_left' => \Yii::t('skeeks/shop/app', 'Показывать подразделы перед фильтрами?'),
        ]);
    }

    public function attributeHints()
    {
        return array_merge(parent::attributeLabels(), [
            'is_show_subtree_col_left' => \Yii::t('skeeks/shop/app', 'Показывать фильтры в левой колонке?'),
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
            
            'left-col' => [
                'class' => FieldSet::class,
                'name' => 'Левая колонка',
                'fields' => [
                    'is_show_subtree_col_left' => [
                        'class' => BoolField::class,
                        'allowNull' => false
                    ],
                ]
            ],

        ];
    }
}