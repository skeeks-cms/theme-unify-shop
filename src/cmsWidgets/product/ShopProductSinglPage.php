<?php
/**
 * @link https://cms.skeeks.com/
 * @copyright Copyright (c) 2010 SkeekS
 * @license https://cms.skeeks.com/license/
 * @author Semenov Alexander <semenov@skeeks.com>
 */

namespace skeeks\cms\themes\unifyshop\cmsWidgets\product;

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
class ShopProductSinglPage extends Widget
{
    /**
     * @var Показывать заголовок в хлебных крошках?
     */
    public $is_show_title_in_breadcrumbs = true;
    /**
     * @var bool
     */
    public $is_show_title_in_short_description = false;
    /**
     * @var int
     */
    public $width_col_images = 8;

    public $right_bg_color = '';

    public $right_padding = '';

    public $info_block_view_type = 'v1';

    public static function descriptorConfig()
    {
        return array_merge(parent::descriptorConfig(), [
            'name' => \Yii::t('skeeks/shop/app', 'Отображение товара на детальной странице'),
        ]);
    }

    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), [
            'is_show_title_in_short_description' => \Yii::t('skeeks/shop/app', 'Показывать заголовок в блоке справа?'),
            'is_show_title_in_breadcrumbs' => \Yii::t('skeeks/shop/app', 'Показывать заголовок в хлебных крошках?'),
            'width_col_images' => \Yii::t('skeeks/shop/app', 'Ширина колонки с изображениями'),
            'right_bg_color' => \Yii::t('skeeks/shop/app', 'Цвет фона'),
            'right_padding' => \Yii::t('skeeks/shop/app', 'Отступы внутри блока'),
            'info_block_view_type' => \Yii::t('skeeks/shop/app', 'Вариант отображения детальной информации'),
        ]);
    }
    public function attributeHints()
    {
        return array_merge(parent::attributeLabels(), [
            'width_col_images' => \Yii::t('skeeks/shop/app', 'Влияет на ширину блока с картинками, так же на ширину и высоту самих изображений.'),
            'info_block_view_type' => \Yii::t('skeeks/shop/app', 'Влияет на отображении информации о товаре (описание, характеристики, отзывы)'),
        ]);
    }

    public function rules()
    {
        return ArrayHelper::merge(parent::rules(), [
            [['is_show_title_in_short_description'], 'boolean'],
            [['is_show_title_in_breadcrumbs'], 'boolean'],
            [['info_block_view_type'], 'string'],
            [['width_col_images'], 'integer'],
            [['right_bg_color'], 'string'],
            [['right_padding'], 'integer'],
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
            'breadcrumbs' => [
                'class' => FieldSet::class,
                'name' => 'Хлебные крошки',
                'fields' => [
                    'is_show_title_in_breadcrumbs' => [
                        'class' => BoolField::class,
                        'allowNull' => false,
                        'formElement' => BoolField::ELEMENT_CHECKBOX
                    ],
                ]
            ],
            'header-images' => [
                'class' => FieldSet::class,
                'name' => 'Верхняя часть (изображения товара)',
                'fields' => [
                    'width_col_images' => [
                        'class' => SelectField::class,
                        'items' => [
                            '8' => 'Широкий блок',
                            '7' => 'Средний блок',
                            '6' => '50% ширины',
                        ]
                    ],

                ]
            ],
            'header' => [
                'class' => FieldSet::class,
                'name' => 'Верхняя часть (блок справа с ценой)',
                'fields' => [

                    'is_show_title_in_short_description' => [
                        'class' => BoolField::class,
                        'allowNull' => false
                    ],

                    'right_bg_color' => [
                        'class'       => WidgetField::class,
                        'widgetClass' => ColorInput::class,
                    ],

                    'right_padding' => [
                        'class' => SelectField::class,
                        'items' => [
                            '5' => '5px',
                            '10' => '10px',
                            '15' => '15px',
                            '20' => '20px',
                            '25' => '25px',
                        ]
                    ],
                ]
            ],
            'info' => [
                'class' => FieldSet::class,
                'name' => 'Детальная информация о товаре',
                'fields' => [

                    'info_block_view_type' => [
                        'class' => SelectField::class,
                        'items' => [
                            'v1' => 'Стандартное отображение',
                            'v2' => 'Сворачиваемые блоки',
                        ]
                    ],
                ]
            ],
        ];
    }

    public function addCss()
    {
        if ($this->right_bg_color) {
            \Yii::$app->view->registerCss(<<<CSS
            .sx-right-product-info {
                background: {$this->right_bg_color};
            }
CSS
            );
        }
        if ($this->right_padding) {
            \Yii::$app->view->registerCss(<<<CSS
            .sx-right-product-info {
                padding: {$this->right_padding}px;
            }
CSS
            );
        }
    }
}