<?php
/**
 * @link https://cms.skeeks.com/
 * @copyright Copyright (c) 2010 SkeekS
 * @license https://cms.skeeks.com/license/
 * @author Semenov Alexander <semenov@skeeks.com>
 */

namespace skeeks\cms\themes\unifyshop;

use skeeks\cms\backend\widgets\filters\Bootstrap4ActiveField;
use skeeks\cms\themes\unify\assets\UnifyBootstrapAsset;
use skeeks\cms\themes\unify\assets\UnifyBootstrapPluginAsset;
use skeeks\cms\themes\unify\assets\UnifyJqueryAsset;
use yii\base\Theme;

/**
 * @property string      $favicon путь к фавиконке
 * @property string|null $logoSrc путь к лого, если передать null, то будет лого по умолчанию
 * @property string|null $logoHref Url с логотипа
 *
 * @property string      $slideNavClasses read-only
 * @property string      $headerClasses read-only
 *
 * @author Semenov Alexander <semenov@skeeks.com>
 */
class UnifyShopTheme extends Theme
{
    public $pathMap = [
        '@app/views' => [
            '@skeeks/cms/themes/unifyshop/views',
        ],
    ];
}