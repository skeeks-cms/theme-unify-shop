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
use skeeks\yii2\form\fields\BoolField;
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
    public $is_show_title_in_short_description = false;
    public $width_col_images = 8;
    public $width_col_short_info = 4;

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
            'width_col_short_info' => \Yii::t('skeeks/shop/app', 'Ширина колонки с коротким описанием товара'),
        ]);
    }
    public function attributeHints()
    {
        return array_merge(parent::attributeLabels(), [
            'width_col_images' => \Yii::t('skeeks/shop/app', 'Указать цифру от 1 до 12'),
            'width_col_short_info' => \Yii::t('skeeks/shop/app', 'Указать цифру от 1 до 12'),
        ]);
    }

    public function rules()
    {
        return ArrayHelper::merge(parent::rules(), [
            [['is_show_title_in_short_description'], 'boolean'],
            [['is_show_title_in_breadcrumbs'], 'boolean'],
            [['width_col_images'], 'integer'],
            [['width_col_short_info'], 'integer'],
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
            'is_show_title_in_breadcrumbs' => [
                'class' => BoolField::class,
                'allowNull' => false
            ],
            'is_show_title_in_short_description' => [
                'class' => BoolField::class,
                'allowNull' => false
            ],
            'width_col_images',
            'width_col_short_info',
        ];
    }
}