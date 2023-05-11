<?php
/**
 * @author Semenov Alexander <semenov@skeeks.com>
 * @link http://skeeks.com/
 * @copyright 2010 SkeekS (СкикС)
 * @date 26.07.2015
 */

namespace skeeks\cms\themes\unifyshop\assets;

use skeeks\assets\unify\base\UnifyHsScrollbarAsset;
use skeeks\cms\shop\assets\ShopAsset;
use skeeks\cms\themes\unify\assets\UnifyThemeAsset;
use yii\web\AssetBundle;
use yii\web\JqueryAsset;

/**
 * @author Semenov Alexander <semenov@skeeks.com>
 */
class ProductListImagesAsset extends AssetBundle
{
    public $sourcePath = '@skeeks/cms/themes/unifyshop/assets/src/components/product-list-images';

    public $css = [
        'product-list-images.css',
    ];
    public $js = [
        'product-list-images.js',
    ];
    public $depends = [
        JqueryAsset::class,
    ];
}
