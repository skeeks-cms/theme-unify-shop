<?php
// Настоящий фрагмент главной с отдельной SQLite и ArrayCache; кеш сайта не затрагивается.
require __DIR__.'/home-collections.php';
$db->createCommand('INSERT INTO home_collection_product SELECT id,1 FROM collection WHERE id<>11')->execute();
class CollectionCacheTheme extends TestTheme {
    public $catalog_img_preview_width=300;
    public $catalog_img_preview_crop='inset';
}
class CollectionTestCache extends yii\caching\ArrayCache {
    public $fragmentDurations=[];
    public function set($key,$value,$duration=null,$dependency=null){
        if(is_array($key) && $key[0]===yii\widgets\FragmentCache::class)$this->fragmentDurations[]=$duration;
        return parent::set($key,$value,$duration,$dependency);
    }
}
$cache=new CollectionTestCache();
Yii::$app->set('cache',$cache);
Yii::$app->set('skeeks',(object)['site'=>(object)['id'=>1,'cacheTag'=>'test-site-1']]);
$assets=[
 'skeeks\cms\themes\unify\assets\VanillaLazyLoadAsset',
 'skeeks\cms\themes\unifyshop\assets\components\ShopUnifyProductCardAsset',
 'skeeks\cms\themes\unifyshop\assets\ProductListImagesAsset',
];
Yii::$app->set('assetManager',new yii\web\AssetManager(['basePath'=>sys_get_temp_dir(),'baseUrl'=>'/assets','bundles'=>array_fill_keys($assets,['class'=>yii\web\AssetBundle::class])]));
$baseView=Yii::$app->view;
$baseView->theme=new CollectionCacheTheme(['basePath'=>__DIR__]);
$source=str_replace("\r\n","\n",file_get_contents(dirname(__DIR__).'/src/views/modules/cms/tree/home.php'));
$start=strpos($source,'// Кеш коллекций');
$end=strpos($source,'<? if (\Yii::$app->shop->shopContents)');
$check($start!==false && $end>$start,'Не найден кешируемый фрагмент');
$temp=tempnam(sys_get_temp_dir(),'collections-cache-');
file_put_contents($temp,"<?php\n".substr($source,$start,$end-$start));
$render=static function()use($baseView,$temp,$queries,$check,$assets){
    $view=clone $baseView;
    $view->css=[];$view->assetBundles=[];
    $view->on(yii\base\View::EVENT_BEFORE_RENDER, static function($event){
        if(basename($event->viewFile)!=='collection-list-item.php')return;
        echo '<span class="fixture-collection">'.$event->params['model']->id.'</span>';
        $event->isValid=false;
    });
    Yii::$app->set('view',$view);
    Yii::getLogger()->messages=[];
    $html=$view->renderFile($temp,['model'=>(object)['id'=>1]]);
    $n=$queries();
    $check(count($view->css)>0,'CSS не зарегистрирован');
    foreach($assets as $asset)$check(array_key_exists($asset,$view->assetBundles),'Не зарегистрирован asset '.$asset);
    return [$html,$n];
};
try{
    Yii::$app->request->setQueryParams([]);
    list($cold,$coldQueries)=$render();
    list($warm,$warmQueries)=$render();
    $check($coldQueries>0 && $warmQueries===0 && $cold===$warm,'Прогретый фрагмент выполняет SQL или меняет HTML');
    $check($cache->fragmentDurations===[28800],'Срок кеша должен быть 8 часов');
    yii\caching\TagDependency::invalidate($cache,['test-site-1']);
    list($rebuilt,$rebuiltQueries)=$render();
    $check($rebuiltQueries>0 && $rebuilt===$cold,'Тег сайта не сбросил фрагмент');
    Yii::$app->request->setQueryParams(['page'=>2]);
    list($page,$pageQueries)=$render();$check($pageQueries>0,'Страницы не разделены в ключе');
    Yii::$app->language='ru';list($lang,$langQueries)=$render();$check($langQueries>0,'Языки не разделены');
    Yii::$app->skeeks->site=(object)['id'=>2,'cacheTag'=>'test-site-2'];
    list($site,$siteQueries)=$render();$check($siteQueries>0,'Сайты не разделены');
    $db->createCommand()->delete('home_collection_product')->execute();
    yii\caching\TagDependency::invalidate($cache,['test-site-2']);
    list($empty,$emptyQueries)=$render();
    list($emptyWarm,$emptyWarmQueries)=$render();
    $check($emptyQueries===1 && $emptyWarmQueries===0 && $empty===$emptyWarm,'Пустой результат не кешируется');
    echo "OK: 8 часов, ноль SQL на попадании, сброс тегом сайта, разделение сайтов/языков/страниц; CSS и assets подключены в обоих режимах.\n";
} finally {unlink($temp);}
