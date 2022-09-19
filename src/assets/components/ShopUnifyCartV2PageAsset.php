<?php
/**
 * @link https://cms.skeeks.com/
 * @copyright Copyright (c) 2010 SkeekS
 * @license https://cms.skeeks.com/license/
 * @author Semenov Alexander <semenov@skeeks.com>
 */

namespace skeeks\cms\themes\unifyshop\assets\components;

use skeeks\cms\admin\assets\JqueryMaskInputAsset;
use skeeks\cms\base\AssetBundle;
use skeeks\cms\themes\unify\assets\components\UnifyThemeFloatLabelAsset;
use skeeks\cms\themes\unifyshop\assets\UnifyThemeShopAsset;
use yii\helpers\ArrayHelper;

/**
 * @author Semenov Alexander <semenov@skeeks.com>
 */
class ShopUnifyCartV2PageAsset extends AssetBundle
{
    public $sourcePath = '@skeeks/cms/themes/unifyshop/assets/src/components/cart-v2';

    public $css = [
        'cart-v2.css',
    ];
    public $js = [
        'cart-v2.js',
    ];
    public $depends = [
        UnifyThemeFloatLabelAsset::class,
        JqueryMaskInputAsset::class,
        UnifyThemeShopAsset::class,
    ];

    public function init()
    {
        if (isset(\Yii::$app->view->theme->themeAssetClass)) {
            //$this->depends = ArrayHelper::merge($this->depends, [\Yii::$app->view->theme->themeAssetClass]);
        }
    }
}