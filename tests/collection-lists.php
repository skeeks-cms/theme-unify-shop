<?php
// Проверка настоящих шаблонов и Yii-связей на отдельной SQLite, без базы сайта.
namespace {
    defined('YII_ENABLE_ERROR_HANDLER') || define('YII_ENABLE_ERROR_HANDLER', false);
    require $argv[1] ?? '/app/vendor/autoload.php';
    require dirname($argv[1] ?? '/app/vendor/autoload.php').'/yiisoft/yii2/Yii.php';
    new \yii\console\Application(['id'=>'collections-test','basePath'=>__DIR__,'components'=>[
        'db'=>['class'=>\yii\db\Connection::class,'dsn'=>'sqlite::memory:'],
    ]]);
}
namespace skeeks\cms\themes\unify\widgets {
    class ScrollAndSpPager extends \yii\widgets\LinkPager {public $container;public $item;}
}
namespace {
    defined('YII_ENABLE_ERROR_HANDLER') || define('YII_ENABLE_ERROR_HANDLER', false);
    class TestImage extends \yii\db\ActiveRecord {public static function tableName(){return 'image';}}
    class TestSticker extends \yii\db\ActiveRecord {public static function tableName(){return 'sticker';}}
    class TestCountry extends \yii\db\ActiveRecord {public static function tableName(){return 'country';}}
    class TestBrand extends \yii\db\ActiveRecord {
        public static function tableName(){return 'brand';}
        public function getCountry(){return $this->hasOne(TestCountry::class,['id'=>'country_id']);}
    }
    class TestCollection extends \yii\db\ActiveRecord {
        public static function tableName(){return 'collection';}
        public function getImage(){return $this->hasOne(TestImage::class,['id'=>'image_id']);}
        public function getImages(){return $this->hasMany(TestImage::class,['id'=>'image_id'])->viaTable('collection_image',['collection_id'=>'id'])->orderBy(['priority'=>SORT_ASC]);}
        public function getShopCollectionStickers(){return $this->hasMany(TestSticker::class,['id'=>'sticker_id'])->viaTable('collection_sticker',['collection_id'=>'id']);}
        public function getBrand(){return $this->hasOne(TestBrand::class,['id'=>'brand_id']);}
    }
    class TestTheme extends \yii\base\Theme {public $prooductListItemCssClasses='fixture-card';}
    $db=Yii::$app->db;
    foreach([
        'CREATE TABLE image (id INTEGER PRIMARY KEY, priority INTEGER)',
        'CREATE TABLE sticker (id INTEGER PRIMARY KEY, name TEXT)',
        'CREATE TABLE country (id INTEGER PRIMARY KEY, name TEXT)',
        'CREATE TABLE brand (id INTEGER PRIMARY KEY, country_id INTEGER)',
        'CREATE TABLE collection (id INTEGER PRIMARY KEY, image_id INTEGER, brand_id INTEGER)',
        'CREATE TABLE collection_image (collection_id INTEGER, image_id INTEGER)',
        'CREATE TABLE collection_sticker (collection_id INTEGER, sticker_id INTEGER)',
        "INSERT INTO country VALUES (1,'Россия')",
        'INSERT INTO brand VALUES (1,1),(2,NULL)',
        "INSERT INTO sticker VALUES (1,'Новинка')",
        'INSERT INTO image VALUES (1,20),(2,10),(3,30)'
    ] as $sql) $db->createCommand($sql)->execute();
    for($id=1;$id<=12;$id++) {
        $db->createCommand()->insert('collection',['id'=>$id,'image_id'=>$id%3===0?null:1,'brand_id'=>$id%4===0?null:($id%2+1)])->execute();
        if($id%2) {
            $db->createCommand()->batchInsert('collection_image',['collection_id','image_id'],[[$id,1],[$id,2]])->execute();
            $db->createCommand()->insert('collection_sticker',['collection_id'=>$id,'sticker_id'=>1])->execute();
        }
    }
    foreach(['image','sticker','country','brand','collection','collection_image','collection_sticker'] as $table)$db->schema->getTableSchema($table);
    $check=static function($ok,$message){if(!$ok)throw new \RuntimeException($message);};
    $queries=static function(){return count(array_filter(Yii::getLogger()->messages,static function($m){return $m[1]===\yii\log\Logger::LEVEL_PROFILE_BEGIN && $m[2]==='yii\db\Command::query';}));};
    $snapshot=static function($m){
        return [
            'id'=>$m->id,
            'image'=>$m->image?$m->image->id:null,
            'images'=>array_map(static function($i){return $i->id;},$m->images),
            'stickers'=>array_map(static function($i){return $i->id;},$m->shopCollectionStickers),
            'brand'=>$m->brand?$m->brand->id:null,
            'country'=>$m->brand && $m->brand->country?$m->brand->country->name:null,
        ];
    };
    $expected=[];
    foreach(TestCollection::find()->orderBy('id')->all() as $m)$expected[$m->id]=$snapshot($m);
    Yii::$app->set('view',new \yii\web\View(['theme'=>new TestTheme(['basePath'=>__DIR__])]));
    Yii::$app->set('request',new \yii\web\Request(['cookieValidationKey'=>'fixture','scriptFile'=>__FILE__,'scriptUrl'=>'/index.php','hostInfo'=>'https://fixture.invalid','baseUrl'=>'']));
    Yii::$app->controller=new \yii\web\Controller('fixture',Yii::$app);
    $root=dirname(__DIR__).'/src/views/collections';
    Yii::setAlias('@app/views/collections',$root);
    $seen=[];
    Yii::$app->view->on(\yii\base\View::EVENT_BEFORE_RENDER,static function($event)use(&$seen,$queries,$check,$snapshot,$expected){
        if(basename($event->viewFile)!=='collection-list-item.php')return;
        $m=$event->params['model'];
        foreach(['image','images','shopCollectionStickers','brand'] as $relation)$check($m->isRelationPopulated($relation),'Связь не загружена: '.$relation);
        $before=$queries();
        $check($snapshot($m)===$expected[$m->id],'Связи карточки изменились');
        $check($queries()===$before,'Карточка выполняет отдельный SQL');
        $seen[]=(int)$m->id;
        $event->isValid=false; // Проверяем данные настоящего ListView; разметку сравниваем на сайте.
    });
    foreach(['collection-list.php','collection-list-no-page.php'] as $file) {
        $seen=[];
        $provider=new \yii\data\ActiveDataProvider(['query'=>TestCollection::find()->where(['>','id',2])->orderBy('id'),'pagination'=>['pageSize'=>4,'page'=>1,'params'=>[]],'sort'=>false]);
        Yii::getLogger()->messages=[];
        Yii::$app->view->renderFile($root.'/'.$file,['dataProvider'=>$provider,'label'=>'Коллекции']);
        $check($seen===[7,8,9,10],'Изменились фильтр, сортировка или страница');
        $check($provider->totalCount===10,'Изменился общий размер выборки');
        $check($queries()<=9,'Пакетная загрузка выполняет слишком много запросов: '.$queries());
        $seen=[];
        $provider=new \yii\data\ActiveDataProvider(['query'=>TestCollection::find()->where(['id'=>-1]),'pagination'=>false,'sort'=>false]);
        Yii::getLogger()->messages=[];
        Yii::$app->view->renderFile($root.'/'.$file,['dataProvider'=>$provider,'label'=>'Пусто']);
        $check($seen===[] && $queries()===1,'Пустой список загрузил лишние связи');
        $seen=[];
        $provider=new \yii\data\ActiveDataProvider(['query'=>TestCollection::find()->orderBy(['id'=>SORT_DESC])->limit(4),'pagination'=>false,'sort'=>false]);
        Yii::$app->view->renderFile($root.'/'.$file,['dataProvider'=>$provider,'label'=>'Без пагинации']);
        $check($seen===[12,11,10,9],'Изменились limit или сортировка без пагинации');
    }
    echo "OK: оба шаблона, пагинация, фильтры, пустые связи, порядок изображений; SQL внутри карточек отсутствует.\n";
}
