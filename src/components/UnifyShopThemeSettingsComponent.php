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
use skeeks\cms\components\Cms;
use skeeks\cms\models\CmsAgent;
use skeeks\cms\models\CmsContent;
use skeeks\cms\models\CmsContentProperty;
use skeeks\cms\models\CmsUser;
use skeeks\cms\modules\admin\widgets\BlockTitleWidget;
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
    public $catalog_is_show_subtree = true;
    public $catalog_is_show_subtree_col_left = false;

    public $catalog_img_preview_height = 200;
    public $catalog_img_preview_width = 260;
    public $catalog_img_preview_crop = ManipulatorInterface::THUMBNAIL_INSET;

    public $product_slider_items = 6;
    public $product_slider_img_preview_width = 200;
    public $product_slider_img_preview_height = 200;
    public $product_slider_img_preview_crop = ManipulatorInterface::THUMBNAIL_INSET;

    /**
     * Можно задать название и описание компонента
     * @return array
     */
    static public function descriptorConfig()
    {
        return array_merge(parent::descriptorConfig(), [
            'name' => 'Настройки темы Unify (Магазин)',
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
                    'catalog_is_show_subtree',
                    'catalog_is_show_subtree_col_left',
                ],
                'boolean',
            ],
            [
                [
                    'catalog_img_preview_height',
                    'catalog_img_preview_width',
                    'product_slider_items',
                    'product_slider_img_preview_width',
                    'product_slider_img_preview_height',
                ],
                'integer',
            ],
            [
                [
                    'catalog_img_preview_crop',
                    'product_slider_img_preview_crop',
                ],
                'string',
            ],
        ]);
    }

    public function getConfigFormFields()
    {
        return [
            'catalog' => [
                'class' => FieldSet::class,
                'name' => \Yii::t('skeeks/shop/app', 'Каталог'),

                'fields' => [

                    [
                        'class'   => HtmlBlock::class,
                        'content' => BlockTitleWidget::widget(['content' => 'Подразделы']),
                    ],


                    'catalog_is_show_subtree' => [
                        'class' => BoolField::class,
                        'allowNull' => false,
                        'formElement' => BoolField::ELEMENT_RADIO_LIST,
                    ],
                    'catalog_is_show_subtree_col_left' => [
                        'class' => BoolField::class,
                        'allowNull' => false,
                        'formElement' => BoolField::ELEMENT_RADIO_LIST,
                    ],

                    [
                        'class'   => HtmlBlock::class,
                        'content' => BlockTitleWidget::widget(['content' => 'Товары']),
                    ],

                    'catalog_img_preview_width',
                    'catalog_img_preview_height',
                    'catalog_img_preview_crop' => [
                        'class' => SelectField::class,
                        'items' => [
                            ManipulatorInterface::THUMBNAIL_INSET => 'Сохранять формат исходной картинки',
                            ManipulatorInterface::THUMBNAIL_OUTBOUND => 'Обрезать под размер'
                        ]
                    ],
                ],
            ],
            'stick' => [
                'class' => FieldSet::class,
                'name' => \Yii::t('skeeks/shop/app', 'Слайдеры товаров'),

                'fields' => [

                    'product_slider_items',
                    'product_slider_img_preview_width',
                    'product_slider_img_preview_height',
                    'product_slider_img_preview_crop' => [
                        'class' => SelectField::class,
                        'items' => [
                            ManipulatorInterface::THUMBNAIL_INSET => 'Сохранять формат исходной картинки',
                            ManipulatorInterface::THUMBNAIL_OUTBOUND => 'Обрезать под размер'
                        ]
                    ],
                ],
            ],

            /*'filters' => [
                'class' => FieldSet::class,
                'name' => \Yii::t('skeeks/shop/app', 'Фильтры'),

                'fields' => [
                    'filters_is_show_subtree' => [
                        'class' => BoolField::class,
                        'allowNull' => false,
                        'formElement' => BoolField::ELEMENT_RADIO_LIST,
                    ],
                ],
            ],*/
        ];
    }

    public function attributeLabels()
    {
        return ArrayHelper::merge(parent::attributeLabels(), [
                'catalog_is_show_subtree' => 'Показывать подразделы в каталоге?',
                'catalog_is_show_subtree_col_left' => 'Показывать подразделы в блоке слева перед фильтрами?',
                'catalog_img_preview_width' => 'Ширина превью картинки товара',
                'catalog_img_preview_height' => 'Высота превью картинки товара',
                'catalog_img_preview_crop' => 'Режим обрезки превью картинки товара',
                
                'product_slider_items' => 'Количество товаров в слайдере',
                'product_slider_img_preview_width' => 'Ширина превью картинки товара',
                'product_slider_img_preview_height' => 'Высота превью картинки товара',
                'product_slider_img_preview_crop' => 'Режим обрезки превью картинки товара',
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