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
     * @var Показывать заголовок в хлебных крошках?
     */
    public $is_allow_filters = true;

    /**
     * @var bool Показывать подразделы перед товарами?
     */
    public $is_show_subtree_before_products = true;

    /**
     * @var bool Показывать подразделы перед товарами?
     */
    public $is_show_subtree_col_left = true;
    /**
     * @var bool Показывать подразделы перед фильтрами только когда нет фильтров?
     */
    public $is_show_subtree_col_left_no_filters = true;


    public static function descriptorConfig()
    {
        return array_merge(parent::descriptorConfig(), [
            'name' => \Yii::t('skeeks/shop/app', 'Настройки страницы каталога'),
        ]);
    }

    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), [
            'is_allow_filters' => \Yii::t('skeeks/shop/app', 'Включить фильтры?'),
            'is_show_subtree_before_products' => \Yii::t('skeeks/shop/app', 'Показывать подразделы перед товарами?'),
            'is_show_subtree_col_left' => \Yii::t('skeeks/shop/app', 'Показывать подразделы перед фильтрами?'),
            'is_show_subtree_col_left_no_filters' => \Yii::t('skeeks/shop/app', 'Показывать подразделы перед фильтрами только когда нет фильтров?'),
        ]);
    }

    public function attributeHints()
    {
        return array_merge(parent::attributeLabels(), [
            'is_show_subtree_col_left' => \Yii::t('skeeks/shop/app', 'Показывать фильтры в левой колонке?'),
            'is_show_subtree_col_left_no_filters' => \Yii::t('skeeks/shop/app', 'Если подразделы включены для отображения в левой колонке, показывать их только когда нет фильтров. Или отображать их всегда.'),
        ]);
    }

    public function rules()
    {
        return ArrayHelper::merge(parent::rules(), [
            [['is_allow_filters'], 'boolean'],
            [['is_show_subtree_before_products'], 'boolean'],
            [['is_show_subtree_col_left'], 'boolean'],
            [['is_show_subtree_col_left_no_filters'], 'boolean'],
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
            'filters' => [
                'class' => FieldSet::class,
                'name' => 'Фильтры',
                'fields' => [
                    'is_allow_filters' => [
                        'class' => BoolField::class,
                        'allowNull' => false
                    ],
                ]
            ],
            'sections' => [
                'class' => FieldSet::class,
                'name' => 'Разделы',
                'fields' => [
                    'is_show_subtree_before_products' => [
                        'class' => BoolField::class,
                        'allowNull' => false
                    ],
                    'is_show_subtree_col_left' => [
                        'class' => BoolField::class,
                        'allowNull' => false
                    ],
                    'is_show_subtree_col_left_no_filters' => [
                        'class' => BoolField::class,
                        'allowNull' => false
                    ],
                ]
            ],

        ];
    }
}