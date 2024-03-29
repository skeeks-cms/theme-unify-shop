<?php
/**
 * @link https://cms.skeeks.com/
 * @copyright Copyright (c) 2010 SkeekS
 * @license https://cms.skeeks.com/license/
 * @author Semenov Alexander <semenov@skeeks.com>
 */

namespace yii\web;

use common\components\ProjectComponent;
use common\components\TelegramComponent;
use common\components\unify\TemplateUnify;
use common\themes\unify\SkeeksUnifyTheme;
use kartik\mpdf\Pdf;
use skeeks\cms\themes\unifyshop\components\UnifyShopThemeSettingsComponent;
use skeeks\cms\themes\unifyshop\UnifyShopTheme;
use skeeks\crm\components\TinkoffIntegrationApi;
use v3project\themes\mega\ThemeMega;
use yii\base\Theme;


/**
 * @property View|PView                      $view
 *
 * Class Application
 * @package yii\web
 */
class Application
{
}


/**
 * @property Theme|UnifyShopTheme $theme
 * @author Semenov Alexander <semenov@skeeks.com>
 */
class View extends PView
{
}
/**
 * @property Theme|UnifyShopTheme $theme
 * @author Semenov Alexander <semenov@skeeks.com>
 */
class PView
{
}