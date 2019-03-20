<?php
/**
 * @author Semenov Alexander <semenov@skeeks.com>
 * @link http://skeeks.com/
 * @copyright 2010 SkeekS (СкикС)
 * @date 26.07.2015
 */
namespace skeeks\cms\themes\unifyshop\assets;

use skeeks\assets\unify\base\UnifyHsScrollbarAsset;
use skeeks\cms\themes\unify\assets\UnifyDefaultAsset;
use yii\web\AssetBundle;

/**
 * @author Semenov Alexander <semenov@skeeks.com>
 */
class UnifyShopCatalogAsset extends AssetBundle
{
    public $sourcePath = '@skeeks/cms/themes/unifyshop/assets/src';

    public $css = [
        'css/unify-catalog.css'
    ];
    public $js = [
        //'js/jquery.matchHeight-min.js',
        'js/unify-catalog.js',
    ];
    public $depends = [
        UnifyDefaultAsset::class,
        UnifyHsScrollbarAsset::class
    ];
}
