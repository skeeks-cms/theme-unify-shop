<?php
/**
 * @author Semenov Alexander <semenov@skeeks.com>
 * @link http://skeeks.com/
 * @copyright 2010 SkeekS (СкикС)
 * @date 26.07.2015
 */
namespace skeeks\cms\themes\unifyshop\assets;

use yii\web\AssetBundle;

/**
 * Class ProductFiterWidgetAsset
 * @package v3p\aff\widgets\filter\assets
 */
class ProductFiterWidgetAsset extends AssetBundle
{
    public $sourcePath = '@skeeks/cms/themes/unifyshop/assets/src';

    public $css = [
        'css/product-filter.css'
    ];
    public $js = [
        'js/product-filter.js'
    ];
    public $depends = [
        UnifyThemeShopAsset::class,
        UnifyIoRangeSliderAsset::class,
    ];
}
