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
use skeeks\yii2\form\fields\NumberField;
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
    public $right_col_width = 475;

    public $right_bg_color = '';

    public $right_padding = '';

    public $info_block_view_type = 'v1';
    public $sliders_align = 'center';
    public $properties_view_file = 'two-columns';
    public $images_view_file = '_product-images';

    public static function descriptorConfig()
    {
        return array_merge(parent::descriptorConfig(), [
            'name' => \Yii::t('skeeks/shop/app', 'Настройки страницы товара'),
        ]);
    }

    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), [
            'is_show_title_in_short_description' => \Yii::t('skeeks/shop/app', 'Показывать заголовок в блоке справа?'),
            'is_show_title_in_breadcrumbs'       => \Yii::t('skeeks/shop/app', 'Показывать заголовок в хлебных крошках?'),
            'right_col_width'                    => \Yii::t('skeeks/shop/app', 'Ширина правой колонки с ценой'),
            'right_bg_color'                     => \Yii::t('skeeks/shop/app', 'Цвет фона'),
            'right_padding'                      => \Yii::t('skeeks/shop/app', 'Отступы внутри блока'),
            'info_block_view_type'               => \Yii::t('skeeks/shop/app', 'Вариант отображения детальной информации'),
            'sliders_align'                      => \Yii::t('skeeks/shop/app', 'Выравнивать слайдеры (похожие товары и ранее просмотренные)'),
            'properties_view_file'               => \Yii::t('skeeks/shop/app', 'Шаблон отображения характеристик'),
            'images_view_file'                   => \Yii::t('skeeks/shop/app', 'Шаблон галереи картинок'),
        ]);
    }
    public function attributeHints()
    {
        return array_merge(parent::attributeLabels(), [
            'info_block_view_type' => \Yii::t('skeeks/shop/app', 'Влияет на отображении информации о товаре (описание, характеристики, отзывы)'),
        ]);
    }

    public function rules()
    {
        return ArrayHelper::merge(parent::rules(), [
            [['is_show_title_in_short_description'], 'boolean'],
            [['is_show_title_in_breadcrumbs'], 'boolean'],
            [['info_block_view_type'], 'string'],
            [['right_col_width'], 'integer'],
            [['right_bg_color'], 'string'],
            [['right_padding'], 'integer'],
            [['sliders_align'], 'string'],
            [['properties_view_file'], 'string'],
            [['images_view_file'], 'string'],
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
                'class'  => FieldSet::class,
                'name'   => 'Хлебные крошки',
                'fields' => [
                    'is_show_title_in_breadcrumbs' => [
                        'class'       => BoolField::class,
                        'allowNull'   => false,
                        'formElement' => BoolField::ELEMENT_CHECKBOX,
                    ],
                ],
            ],
            'images'      => [
                'class'  => FieldSet::class,
                'name'   => 'Галерея изображений',
                'fields' => [
                    'images_view_file' => [
                        'class' => SelectField::class,
                        'items' => [
                            '_product-images'          => 'Превью картинки снизу',
                            '_product-images-vertical' => 'Превью картинки слева',
                        ],
                    ],
                ],
            ],

            'header'     => [
                'class'  => FieldSet::class,
                'name'   => 'Правый блок с ценой',
                'fields' => [

                    'right_col_width' => [
                        'class'  => NumberField::class,
                        'append' => 'px',
                    ],

                    'is_show_title_in_short_description' => [
                        'class'     => BoolField::class,
                        'allowNull' => false,
                    ],

                    'right_bg_color' => [
                        'class'       => WidgetField::class,
                        'widgetClass' => ColorInput::class,
                    ],

                    'right_padding' => [
                        'class' => SelectField::class,
                        'items' => [
                            '5'  => '5px',
                            '10' => '10px',
                            '15' => '15px',
                            '20' => '20px',
                            '25' => '25px',
                        ],
                    ],
                ],
            ],
            'info'       => [
                'class'  => FieldSet::class,
                'name'   => 'Детальная информация о товаре',
                'fields' => [

                    'info_block_view_type' => [
                        'class' => SelectField::class,
                        'items' => [
                            'v1' => 'Стандартное отображение',
                            'v2' => 'Сворачиваемые блоки',
                            'v3' => 'Табы',
                        ],
                    ],

                    'properties_view_file' => [
                        'class' => SelectField::class,
                        'items' => [
                            'default'     => 'Отображать таблицей',
                            'two-columns' => 'Характеристики с точками (2 колонки)',
                        ],
                    ],
                ],
            ],
            'additional' => [
                'class'  => FieldSet::class,
                'name'   => 'Прочее',
                'fields' => [
                    'sliders_align'           => [
                        'class' => SelectField::class,
                        'items' => [
                            'center' => 'По центру',
                            'left'   => 'По левому краю',
                        ],
                    ],
                ],
            ],
        ];
    }

    public function addCss()
    {
        if (!\Yii::$app->mobileDetect->isMobile) {
            \Yii::$app->view->registerCss(<<<CSS

@media (min-width: 992px) {
    .sx-product-page--left-col {
        width: calc(100% - {$this->right_col_width}px);
        margin-right: 15px;
    }
    .sx-product-page--right-col {
        width: {$this->right_col_width}px;
    }
}
            
CSS
            );
        }

        if ($this->sliders_align == 'left') {
            \Yii::$app->view->registerCss(<<<CSS
.sx-products-slider-wrapper .sx-products-slider--title {
    text-align: left;
}
.sx-products-stick .slick-track {
    margin-left: 0;
}
CSS
            );
        }


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