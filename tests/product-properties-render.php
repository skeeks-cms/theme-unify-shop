<?php
namespace {
    require $argv[1] ?? '/app/vendor/autoload.php';
    require dirname($argv[1] ?? '/app/vendor/autoload.php').'/yiisoft/yii2/Yii.php';
    new \yii\console\Application(['id'=>'rp-render-test','basePath'=>__DIR__,'vendorPath'=>dirname($argv[1] ?? '/app/vendor/autoload.php')]);
}
namespace skeeks\cms\rpViewWidget {
    // Изолируем данные и жизненный цикл виджета, исполняя настоящие шаблоны темы.
    class RpViewWidget extends \yii\base\Component {
        public static $last;
        public static $view;
        public static $values=[];
        public $model; public $viewFile; public $params=[]; public $calls=0;
        public static function beginWidget($id,$config){$w=new self();$w->model=$config['model'];return self::$last=$w;}
        public function getRpAttributes(){$this->calls++;return self::$values;}
        public function getUrl($code){return $code==='color'?'/filter/red':false;}
        public static function end(){echo self::$view->render(self::$last->viewFile,array_merge(self::$last->params,['widget'=>self::$last]));}
    }
}
namespace {
    class TestProperties {
        public function getRelatedProperty($code){return (object)['name'=>$code,'hint'=>'','is_multiple'=>false,'is_vendor_code'=>false,'cms_measure_code'=>null];}
    }
    use skeeks\cms\rpViewWidget\RpViewWidget;
    \Yii::setAlias('@webroot', sys_get_temp_dir());
    \Yii::setAlias('@web', '');
    \Yii::setAlias('@bower', dirname($argv[1] ?? '/app/vendor/autoload.php').'/bower-asset');
    \Yii::$app->set('assetManager', new \yii\web\AssetManager(['basePath'=>sys_get_temp_dir(),'baseUrl'=>'/assets','bundles'=>['yii\web\JqueryAsset'=>false]]));
    $view=new \yii\web\View();
    RpViewWidget::$view=$view;
    $model=(object)['relatedPropertiesModel'=>new TestProperties(),'productDescriptionFull'=>'Описание'];
    $root=dirname(__DIR__).'/src/views';
    $legacy=$argv[2]??null;
    $check=static function($ok,$message){if(!$ok)throw new \RuntimeException($message);};
    foreach(['default','two-columns'] as $style)foreach([1,2,3] as $version)foreach([[],['color'=>'Красный','zero'=>'0']] as $values){
        RpViewWidget::$values=$values;
        $args=['model'=>$model,'shopProduct'=>(object)['isOfferProduct'=>false],'singlPage'=>(object)['properties_view_file'=>$style]];
        \Yii::setAlias('@app/views/widgets/RpWidget',$root.'/widgets/RpWidget');
        $result=$view->renderFile($root.'/modules/cms/content-element/_product-info-v'.$version.'.php',$args);
        $check(RpViewWidget::$last->calls===1,'Повторное форматирование характеристик');
        if($legacy){
            \Yii::setAlias('@app/views/widgets/RpWidget',$legacy.'/widgets/RpWidget');
            $previous=$view->renderFile($legacy.'/modules/cms/content-element/_product-info-v'.$version.'.php',$args);
            $check(str_replace("\r\n", "\n", $result)===str_replace("\r\n", "\n", $previous),'Изменилась разметка варианта '.$version.'/'.$style);
        }
    }
    \Yii::setAlias('@app/views/widgets/RpWidget',$root.'/widgets/RpWidget');
    foreach(['default','two-columns'] as $style){
        $widget=RpViewWidget::beginWidget('standalone',['model'=>$model]);
        RpViewWidget::$values=['color'=>'Красный'];
        $view->renderFile($root.'/widgets/RpWidget/'.$style.'.php',['widget'=>$widget]);
        $check($widget->calls===1,'Самостоятельный рендер должен получать данные виджета');
        $widget->calls=0;
        $html=$view->renderFile($root.'/widgets/RpWidget/'.$style.'.php',['widget'=>$widget,'rpAttributes'=>[]]);
        $check($widget->calls===0 && trim($html)==='','Пустые подготовленные данные не должны запускать getter');
    }
    echo "OK: три варианта блока, два шаблона, пустые и заполненные данные; один вызов форматирования, разметка сохранена.\n";
}
