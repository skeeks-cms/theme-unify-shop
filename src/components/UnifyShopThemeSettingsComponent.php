<?php
/**
 * @link https://cms.skeeks.com/
 * @copyright Copyright (c) 2010 SkeekS
 * @license https://cms.skeeks.com/license/
 * @author Semenov Alexander <semenov@skeeks.com>
 */
namespace skeeks\cms\themes\unifyshop\components;
use skeeks\cms\base\Component;
use skeeks\cms\components\Cms;
use skeeks\cms\models\CmsAgent;
use skeeks\cms\models\CmsContent;
use skeeks\cms\models\CmsContentProperty;
use skeeks\cms\models\CmsUser;
use skeeks\cms\shop\models\ShopCart;
use skeeks\cms\shop\models\ShopOrderStatus;
use skeeks\cms\shop\models\ShopPersonType;
use skeeks\cms\shop\models\ShopTypePrice;
use skeeks\cms\themes\unify\assets\UnifyThemeAsset;
use skeeks\yii2\form\fields\BoolField;
use skeeks\yii2\form\fields\FieldSet;
use skeeks\yii2\form\fields\HtmlBlock;
use skeeks\yii2\form\fields\SelectField;
use skeeks\yii2\form\fields\TextareaField;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
/**
 * @author Semenov Alexander <semenov@skeeks.com>
 */
class UnifyShopThemeSettingsComponent extends Component
{

    public $collection_item_view = 'v2';

    /**
     * Можно задать название и описание компонента
     * @return array
     */
    static public function descriptorConfig()
    {
        return array_merge(parent::descriptorConfig(), [
            'name' => 'Настройки темы unify (Магазин)',
            'image' => [
                UnifyThemeAsset::class,
                'img/template-preview.png',
            ],
        ]);
    }

    public function rules()
    {
        return ArrayHelper::merge(parent::rules(), [
            [
                [
                    'is_show_popular_collection',
                    'is_show_new_collection',

                    'is_show_home_modern_catalog',
                    'is_show_home_old_catalog',
                    'is_show_home_slider',
                ],
                'boolean',
            ],
            ['collection_item_view', 'string']
        ]);
    }

    public function getConfigFormFields()
    {
        return [
            'main' => [
                'class' => FieldSet::class,
                'name' => \Yii::t('skeeks/shop/app', 'Main'),

                'fields' => [

                    'is_show_popular_collection' => [
                        'class' => BoolField::class,
                        'allowNull' => false,
                        'formElement' => BoolField::ELEMENT_RADIO_LIST,
                    ],
                    'is_show_new_collection' => [
                        'class' => BoolField::class,
                        'allowNull' => false,
                        'formElement' => BoolField::ELEMENT_RADIO_LIST,
                    ],
                    'is_show_home_modern_catalog' => [
                        'class' => BoolField::class,
                        'allowNull' => false,
                        'formElement' => BoolField::ELEMENT_RADIO_LIST,
                    ],
                    'is_show_home_old_catalog' => [
                        'class' => BoolField::class,
                        'allowNull' => false,
                        'formElement' => BoolField::ELEMENT_RADIO_LIST,
                    ],
                    'is_show_home_slider' => [
                        'class' => BoolField::class,
                        'allowNull' => false,
                        'formElement' => BoolField::ELEMENT_RADIO_LIST,
                    ],
                    'collection_item_view' => [
                        'class' => SelectField::class,
                        'items' => [
                            'v1' => 'Вариант 1 (маленькие блоки как на tesser.ru)',
                            'v2' => 'Вариант 2 (Большие фото со всплывающей кнопкой Подробнее)',
                        ],
                    ],

                ],
            ],
        ];
    }

    public function attributeLabels()
    {
        return ArrayHelper::merge(parent::attributeLabels(), [
                'is_show_popular_collection' => 'Показывать слайдер с популярными коллекциями на главной?',
                'is_show_new_collection' => 'Показывать слайдер с новыми коллекциями на главной?',
                'collection_item_view'             => "Вариант отображения коллекции",

                'is_show_home_modern_catalog'=> "Показывать современный каталог на главной",
                'is_show_home_old_catalog'=> "Показывать обычный каталог на главной",
                'is_show_home_slider'=> "Показывать слайдер на главной",
            ]
        );
    }

    public function attributeHints()
    {
        return ArrayHelper::merge(parent::attributeLabels(), [
                //'is_show_popular_collection' => 'Начальный статус заказа'
            ]
        );
    }

}