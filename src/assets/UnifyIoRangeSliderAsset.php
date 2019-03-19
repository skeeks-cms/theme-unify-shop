<?php
/**
 * @link https://cms.skeeks.com/
 * @copyright Copyright (c) 2010 SkeekS
 * @license https://cms.skeeks.com/license/
 * @author Semenov Alexander <semenov@skeeks.com>
 */

namespace skeeks\cms\themes\unifyshop\assets;

use skeeks\assets\unify\base\UnifyAsset;

/**
 * @author Semenov Alexander <semenov@skeeks.com>
 */
class UnifyIoRangeSliderAsset extends UnifyAsset
{
    public $css = [
        'admin-template/assets/vendor/ion-range-slider/css/ion.rangeSlider.css',
        //'admin-template/assets/vendor/ion-range-slider/css/ion.rangeSlider.skinModern.css',
        //'admin-template/assets/vendor/ion-range-slider/css/ion.rangeSlider.skinNice.css',
        'admin-template/assets/vendor/ion-range-slider/css/ion.rangeSlider.skinRound.css',
        //'admin-template/assets/vendor/ion-range-slider/css/ion.rangeSlider.skinSimple.css',
        //'admin-template/assets/vendor/ion-range-slider/css/ion.rangeSlider.skinSquare.css',
    ];
    public $js = [
        'admin-template/assets/vendor/ion-range-slider/js/ion.rangeSlider.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
    ];
}
