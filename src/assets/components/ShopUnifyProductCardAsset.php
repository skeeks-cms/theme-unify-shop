<?php
/**
 * @link https://cms.skeeks.com/
 * @copyright Copyright (c) 2010 SkeekS
 * @license https://cms.skeeks.com/license/
 * @author Semenov Alexander <semenov@skeeks.com>
 */
namespace skeeks\cms\themes\unifyshop\assets\components;
use skeeks\cms\base\AssetBundle;
use skeeks\cms\shop\assets\ShopAsset;
use skeeks\cms\themes\unifyshop\assets\UnifyThemeShopAsset;

/**
 * @author Semenov Alexander <semenov@skeeks.com>
 */
class ShopUnifyProductCardAsset extends AssetBundle
{
    public $sourcePath = '@skeeks/cms/themes/unifyshop/assets/src/components/product-card';

    public $css = [
        'product-card.css'
    ];
    public $js = [

    ];
    public $depends = [
        UnifyThemeShopAsset::class,
    ];
}