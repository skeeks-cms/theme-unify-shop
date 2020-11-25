<?php
/**
 * @link https://cms.skeeks.com/
 * @copyright Copyright (c) 2010 SkeekS
 * @license https://cms.skeeks.com/license/
 * @author Semenov Alexander <semenov@skeeks.com>
 */

namespace skeeks\cms\themes\unifyshop\components;

use Imagine\Image\ManipulatorInterface;
use skeeks\cms\backend\widgets\ActiveFormBackend;
use skeeks\cms\base\Component;
use skeeks\cms\models\CmsAgent;
use skeeks\cms\modules\admin\widgets\BlockTitleWidget;
use skeeks\cms\themes\unify\assets\UnifyThemeAsset;
use skeeks\yii2\form\fields\BoolField;
use skeeks\yii2\form\fields\FieldSet;
use skeeks\yii2\form\fields\HtmlBlock;
use skeeks\yii2\form\fields\NumberField;
use skeeks\yii2\form\fields\SelectField;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
/**
 * @property string $prooductListItemCssClasses;
 * @property int    $productListPerPageSize;
 *
 * @author Semenov Alexander <semenov@skeeks.com>
 */
class UnifyShopThemeSettingsComponent extends Component
{
    public $catalog_img_preview_height = 200;
    public $catalog_img_preview_width = 260;
    public $catalog_img_preview_crop = ManipulatorInterface::THUMBNAIL_INSET;

    public $product_slider_items = 5;
    public $product_slider_img_preview_width = 200;
    public $product_slider_img_preview_height = 200;
    public $product_slider_img_preview_crop = ManipulatorInterface::THUMBNAIL_INSET;

    public $is_allow_filters = true;
    public $is_show_catalog_subtree_before_products = true;

    /**
     * @var string
     */
    public $product_list_view_file = "left-col";
    public $product_list_count_columns = 3;
    public $product_list_count_columns_mobile = 2;


    public $product_page_view_file = "default";


    public function attributeLabels()
    {
        return ArrayHelper::merge(parent::attributeLabels(), [
            'catalog_img_preview_width'  => 'Ширина превью картинки товара',
            'catalog_img_preview_height' => 'Высота превью картинки товара',
            'catalog_img_preview_crop'   => 'Режим обрезки превью картинки товара',

            'product_slider_items'              => 'Количество товаров в слайдере',
            'product_slider_img_preview_width'  => 'Ширина превью картинки товара',
            'product_slider_img_preview_height' => 'Высота превью картинки товара',
            'product_slider_img_preview_crop'   => 'Режим обрезки превью картинки товара',

            'product_list_view_file'            => 'Отображение товаров',
            'product_list_count_columns'        => 'Количество колонок с товарами',
            'product_list_count_columns_mobile' => 'Количество колонок с товарами (мобильная версия)',

            'is_show_catalog_subtree_before_products' => 'Показывать подразделы перед списком товаров?',
            'is_allow_filters'                        => 'Показывать фильтры?',
            'product_page_view_file'                  => 'Шаблон страницы одного товара',
        ]);
    }

    public function attributeHints()
    {
        return ArrayHelper::merge(parent::attributeLabels(), [
                //'is_show_popular_collection' => 'Начальный статус заказа'
            ]
        );
    }

    /**
     * Можно задать название и описание компонента
     * @return array
     */
    static public function descriptorConfig()
    {
        return array_merge(parent::descriptorConfig(), [
            'name'  => 'Настройки темы Unify (Магазин)',
            'image' => [
                UnifyThemeAsset::class,
                'img/template-preview.png',
            ],
        ]);
    }

    /**
     * @return ActiveForm
     */
    public function beginConfigForm()
    {
        return ActiveFormBackend::begin();
    }

    public function rules()
    {
        return ArrayHelper::merge(parent::rules(), [

            [
                [
                    'catalog_img_preview_height',
                    'catalog_img_preview_width',
                    'product_slider_items',
                    'product_slider_img_preview_width',
                    'product_slider_img_preview_height',
                    'product_list_count_columns',
                    'product_list_count_columns_mobile',
                    'is_allow_filters',
                    'is_show_catalog_subtree_before_products',
                ],
                'integer',
            ],
            [
                [
                    'catalog_img_preview_crop',
                    'product_slider_img_preview_crop',
                    'product_list_view_file',
                    'product_page_view_file',
                ],
                'string',
            ],
        ]);
    }

    public function getConfigFormFields()
    {
        return [
            'main' => [
                'class'  => FieldSet::class,
                'name'   => 'Список товаров',
                'fields' => [
                    'product_list_view_file' => [
                        'class' => SelectField::class,
                        'items' => [
                            'left-col' => 'С левой колонкой (фильтры в левой колонке)',
                            'no-col'   => 'Во всю ширину (фильтры в одну строку перед товарами)',
                        ],
                    ],

                    'product_list_count_columns' => [
                        'class' => SelectField::class,
                        'items' => [
                            2 => '2',
                            3 => '3',
                            4 => '4',
                        ],
                    ],

                    'product_list_count_columns_mobile' => [
                        'class' => SelectField::class,
                        'items' => [
                            1 => '1',
                            2 => '2',
                        ],
                    ],

                    [
                        'class'   => HtmlBlock::class,
                        'content' => BlockTitleWidget::widget(['content' => 'Отображение одного товара в списке']),
                    ],

                    'catalog_img_preview_width'  => [
                        'class'  => NumberField::class,
                        'append' => 'px',
                    ],
                    'catalog_img_preview_height' => [
                        'class'  => NumberField::class,
                        'append' => 'px',
                    ],
                    'catalog_img_preview_crop'   => [
                        'class' => SelectField::class,
                        'items' => [
                            ManipulatorInterface::THUMBNAIL_INSET    => 'Сохранять формат исходной картинки',
                            ManipulatorInterface::THUMBNAIL_OUTBOUND => 'Обрезать под размер',
                        ],
                    ],

                    [
                        'class'   => HtmlBlock::class,
                        'content' => BlockTitleWidget::widget(['content' => 'Фильтры']),
                    ],

                    'is_allow_filters' => [
                        'class'     => BoolField::class,
                        'allowNull' => false,
                    ],

                    [
                        'class'   => HtmlBlock::class,
                        'content' => BlockTitleWidget::widget(['content' => 'Каталог']),
                    ],

                    'is_show_catalog_subtree_before_products' => [
                        'class'     => BoolField::class,
                        'allowNull' => false,
                    ],
                ],
            ],

            'product' => [
                'class' => FieldSet::class,
                'name'  => \Yii::t('skeeks/shop/app', 'Товарная страница'),

                'fields' => [

                    'product_page_view_file' => [
                        'class' => SelectField::class,
                        'items' => [
                            'default' => 'Стандартный шаблон',
                            'minimal' => 'Минималистичный шаблон (описание и характеристики в правой колонке)',
                        ],
                    ],
                ],
            ],
            'stick'   => [
                'class' => FieldSet::class,
                'name'  => \Yii::t('skeeks/shop/app', 'Слайдеры товаров'),

                'fields' => [

                    'product_slider_items'              => [
                        'class' => SelectField::class,
                        'items' => [
                            2 => '2',
                            3 => '3',
                            4 => '4',
                            5 => '5',
                            6 => '6',
                        ],
                    ],
                    'product_slider_img_preview_width'  => [
                        'class'  => NumberField::class,
                        'append' => 'px',
                    ],
                    'product_slider_img_preview_height' => [
                        'class'  => NumberField::class,
                        'append' => 'px',
                    ],
                    'product_slider_img_preview_crop'   => [
                        'class' => SelectField::class,
                        'items' => [
                            ManipulatorInterface::THUMBNAIL_INSET    => 'Сохранять формат исходной картинки',
                            ManipulatorInterface::THUMBNAIL_OUTBOUND => 'Обрезать под размер',
                        ],
                    ],
                ],
            ],
        ];
    }


    public function getProoductListItemCssClasses()
    {
        $classes = ["col-sm-6"];
        if ($this->product_list_count_columns == 2) {
            $classes[] = 'col-lg-6 col-md-6';
        } elseif ($this->product_list_count_columns == 3) {
            $classes[] = 'col-lg-4 col-md-4';
        } elseif ($this->product_list_count_columns == 4) {
            $classes[] = 'col-lg-3 col-md-4';
        } else {
            $classes[] = 'col-lg-4 col-md-4';
        }

        if ($this->product_list_count_columns_mobile == 1) {
            $classes[] = 'col-12';
        } elseif ($this->product_list_count_columns == 2) {
            $classes[] = 'col-6';
        } else {
            $classes[] = 'col-6';
        }

        return implode(" ", $classes);
    }

    public function getProductListPerPageSize()
    {
        $result = 16;

        $countColumns = $this->product_list_count_columns;
        if (\Yii::$app->mobileDetect->isMobile) {
            $countColumns = $this->product_list_count_columns_mobile;
        }
        if ($countColumns % 3 == 0) {
            $result = 15;
        }
        return $result;
    }
}