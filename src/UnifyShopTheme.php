<?php
/**
 * @link https://cms.skeeks.com/
 * @copyright Copyright (c) 2010 SkeekS
 * @license https://cms.skeeks.com/license/
 * @author Semenov Alexander <semenov@skeeks.com>
 */

namespace skeeks\cms\themes\unifyshop;

use Imagine\Image\ManipulatorInterface;
use skeeks\cms\modules\admin\widgets\BlockTitleWidget;
use skeeks\cms\themes\unify\UnifyTheme;
use skeeks\cms\themes\unifyshop\assets\UnifyThemeShopAsset;
use skeeks\cms\widgets\formInputs\comboText\ComboTextInputWidget;
use skeeks\yii2\form\fields\BoolField;
use skeeks\yii2\form\fields\FieldSet;
use skeeks\yii2\form\fields\HtmlBlock;
use skeeks\yii2\form\fields\NumberField;
use skeeks\yii2\form\fields\SelectField;
use skeeks\yii2\form\fields\TextareaField;
use skeeks\yii2\form\fields\WidgetField;
use yii\helpers\ArrayHelper;

/**
 *
 * @property string $prooductListItemCssClasses;
 * @property int    $productListPerPageSize;
 *
 * @author Semenov Alexander <semenov@skeeks.com>
 */
class UnifyShopTheme extends UnifyTheme
{
    /**
     * @var array
     */
    public $pathMap = [
        '@app/views' => [
            '@skeeks/cms/themes/unifyshop/views',
            '@skeeks/cms/themes/unify/views',
        ],
    ];

    /**
     * @return array
     */
    static public function descriptorConfig()
    {
        return array_merge(parent::descriptorConfig(), [
            'name'        => "Unify (Магазин)",
            'description' => <<<HTML
<p>Базовая тема магазина!</p>
<p>Подходит для классического интернет-магазина.</p>
HTML
            ,
            'image'       => [UnifyThemeShopAsset::class, 'images/shop-preview.png'],
        ]);
    }

    /**
     * @return array
     */
    public function _getDefaultTreeViews()
    {
        return ArrayHelper::merge(parent::_getDefaultTreeViews(), [
            'catalog'      => [
                'name'        => 'Страница с товарами',
                'description' => '',
            ],
            'main-catalog' => [
                'name'        => 'Страница с категориями + подкатериями',
                'description' => 'Этот шаблон отображает вложенные разделы в виде категорий и их подкатегорий',
            ],
            'sub-catalog' => [
                'name'        => 'Страница с категориями',
                'description' => 'Этот шаблон отображает вложенные разделы в виде категорий',
            ],
            'brands'       => [
                'name'        => 'Страница с брендами',
                'description' => 'Этот шаблон отображает бренды + возможность поиска по ним',
            ],
        ]);
    }

    public $themeAssetClass = 'skeeks\cms\themes\unifyshop\assets\UnifyThemeShopAsset';


    public $product_card_img_preview_height = 500;
    public $product_card_img_preview_width = 700;
    public $product_card_img_preview_crop = ManipulatorInterface::THUMBNAIL_INSET;

    public $catalog_img_preview_height = 200;
    public $catalog_img_preview_width = 260;
    public $catalog_is_show_measure = 1;
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
    public $product_list_images = "1";

    public $is_join_second_trees = 0;


    public $product_page_view_file = "default";

    public $cart_view = "v2";
    //public $cart_after_btn_text = "Нажимая «Оформить заказ», вы соглашаетесь с условиями использования и оплаты";
    public $cart_after_btn_text = "";
    public $cart_after_comment_text = "";
    public $cart_delivery_text = "";
    public $cart_paysystem_text = "";
    public $cart_contact_text = "";
    public $cart_is_show_delivery_btn_price = false;

    public function getConfigFormModelData()
    {
        return ArrayHelper::merge(parent::getConfigFormModelData(), [
            'fields'          => [
                'products' => [
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

                        'catalog_img_preview_width' => [
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

                        'product_list_images' => [
                            'class' => SelectField::class,
                            'items' => [
                                '1' => 'Сменять 1 фото при наведении',
                                '2' => 'Показывать галерею изображений',
                            ],
                        ],

                        'catalog_is_show_measure' => [
                            'class'     => BoolField::class,
                            'allowNull' => false,
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

                        'is_join_second_trees' => [
                            'class'     => BoolField::class,
                            'allowNull' => false,
                        ],
                    ],
                ],

                'product'   => [
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


                        'product_card_img_preview_width'  => [
                            'class'  => NumberField::class,
                            'append' => 'px',
                        ],
                        'product_card_img_preview_height' => [
                            'class'  => NumberField::class,
                            'append' => 'px',
                        ],
                        'product_card_img_preview_crop'   => [
                            'class' => SelectField::class,
                            'items' => [
                                ManipulatorInterface::THUMBNAIL_INSET    => 'Сохранять формат исходной картинки',
                                ManipulatorInterface::THUMBNAIL_OUTBOUND => 'Обрезать под размер',
                            ],
                        ],

                    ],
                ],
                'cart_view' => [
                    'class' => FieldSet::class,
                    'name'  => \Yii::t('skeeks/shop/app', 'Оформление заказа'),

                    'fields' => [
                        'cart_view'                       => [
                            'class' => SelectField::class,
                            'items' => [
                                //"v1" => 'Оформление в несколько шагов',
                                "v2" => 'Товары и оформление на одной странице',
                            ],
                        ],
                        'cart_is_show_delivery_btn_price' => [
                            'class' => BoolField::class,
                        ],

                        'cart_after_btn_text' => [
                            'class'       => WidgetField::class,
                            'widgetClass' => ComboTextInputWidget::class,
                        ],

                        'cart_after_comment_text' => [
                            'class'       => WidgetField::class,
                            'widgetClass' => ComboTextInputWidget::class,
                        ],

                        'cart_contact_text'   => [
                            'class' => TextareaField::class,
                        ],
                        'cart_delivery_text'  => [
                            'class' => TextareaField::class,
                        ],
                        'cart_paysystem_text' => [
                            'class' => TextareaField::class,
                        ],
                    ],
                ],

                'stick' => [
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
            ],
            'attributeHints'  => [

            ],
            'attributeLabels' => [
                'cart_is_show_delivery_btn_price' => 'Выводить цену доставки в кнопки выбора доставки',
                'cart_view'                       => 'Шаблон оформления заказа',
                'cart_after_btn_text'             => 'Текст под кнопкой оформить заказ',
                'cart_after_comment_text'         => 'Текст под комментарием',
                'cart_delivery_text'              => 'Текст рядом с выбором способа получания',
                'cart_paysystem_text'             => 'Текст рядом с выбором способа оплаты',
                'cart_contact_text'               => 'Текст рядом с вводом данных покупателя',

                'catalog_img_preview_width'  => 'Ширина превью картинки товара',
                'catalog_img_preview_height' => 'Высота превью картинки товара',
                'catalog_img_preview_crop'   => 'Режим обрезки превью картинки товара',
                'catalog_is_show_measure'    => 'Выводить единицу измерения в карточку',

                'product_card_img_preview_height' => 'Высота превью картинки товара',
                'product_card_img_preview_width'  => 'Ширина превью картинки товара',
                'product_card_img_preview_crop'   => 'Режим обрезки превью картинки товара',

                'product_slider_items'              => 'Количество товаров в слайдере',
                'product_slider_img_preview_width'  => 'Ширина превью картинки товара',
                'product_slider_img_preview_height' => 'Высота превью картинки товара',
                'product_slider_img_preview_crop'   => 'Режим обрезки превью картинки товара',

                'product_list_view_file'            => 'Отображение товаров',
                'product_list_images'            => 'Отображение картинок товара в списке',
                'product_list_count_columns'        => 'Количество колонок с товарами',
                'product_list_count_columns_mobile' => 'Количество колонок с товарами (мобильная версия)',

                'is_show_catalog_subtree_before_products' => 'Показывать подразделы перед списком товаров?',
                'is_allow_filters'                        => 'Показывать фильтры?',
                'product_page_view_file'                  => 'Шаблон страницы одного товара',
                'is_join_second_trees'                    => 'Учитывать дополнительные разделы в каталоге?',
            ],
            'rules'           => [
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
                        'is_join_second_trees',
                        'cart_is_show_delivery_btn_price',

                        'product_card_img_preview_height',
                        'product_card_img_preview_width',
                        'catalog_is_show_measure',
                    ],
                    'integer',
                ],
                [
                    [
                        'catalog_img_preview_crop',
                        'product_slider_img_preview_crop',
                        'product_card_img_preview_crop',
                        'product_list_view_file',
                        'product_page_view_file',

                        'cart_after_comment_text',
                        'cart_after_btn_text',
                        'cart_delivery_text',
                        'cart_paysystem_text',
                        'cart_contact_text',

                        'product_list_images',
                    ],
                    'string',
                ],
            ],
        ]);
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