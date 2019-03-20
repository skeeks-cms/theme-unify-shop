<?php
/**
 * @link https://cms.skeeks.com/
 * @copyright Copyright (c) 2010 SkeekS
 * @license https://cms.skeeks.com/license/
 * @author Semenov Alexander <semenov@skeeks.com>
 */
namespace skeeks\cms\themes\unifyshop\assets;
use skeeks\cms\base\AssetBundle;
use skeeks\cms\shop\assets\ShopAsset;

/**
 * @author Semenov Alexander <semenov@skeeks.com>
 */
class ShopUnifyCartAsset extends AssetBundle
{
    public $sourcePath = '@skeeks/cms/themes/unifyshop/assets/src';

    public $css = [];
    public $js = [
        'js/classes/Shop.js',
    ];
    public $depends = [
        UnifyThemeShopAsset::class,
        ShopAsset::class,
    ];
}