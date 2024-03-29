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

/**
 * @author Semenov Alexander <semenov@skeeks.com>
 */
class UnifyThemeShopAsset extends AssetBundle
{
    public $sourcePath = '@skeeks/cms/themes/unifyshop/assets/src';

    public $css = [
        'css/unify-shop.css',
    ];
    public $js = [
        'vendor/jquery.transform2d.js',
        'js/unify-shop.js',
        'js/classes/Shop.js',
    ];
    public $depends = [
        UnifyThemeAsset::class,
        /*UnifyHsScrollbarAsset::class,*/
        ShopAsset::class
    ];

    public function registerAssetFiles($view)
    {
        parent::registerAssetFiles($view);

        \skeeks\cms\shop\widgets\ShopGlobalWidget::widget();

    }
}
