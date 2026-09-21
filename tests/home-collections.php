<?php
// Проверяем настоящий блок главной на SQLite и уже проверенных связях коллекций.
require __DIR__.'/collection-lists.php';
class HomeProduct extends yii\db\ActiveRecord {public static function tableName(){return 'home_product';}}
class HomeCollection extends TestCollection {
    public static function tableName(){return 'home_collection';}
    public function getShopProducts(){return $this->hasMany(HomeProduct::class,['id'=>'product_id'])->viaTable('home_collection_product',['collection_id'=>'id']);}
}
class_alias(HomeCollection::class, 'skeeks\cms\shop\models\ShopCollection');
$db->createCommand('CREATE TABLE home_collection (id INTEGER PRIMARY KEY, image_id INTEGER, brand_id INTEGER, cms_image_id INTEGER, created_at INTEGER, show_counter INTEGER)')->execute();
$db->createCommand('INSERT INTO home_collection SELECT *, image_id, id*10, 100-id FROM collection')->execute();
$db->createCommand('CREATE TABLE home_product (id INTEGER PRIMARY KEY)')->execute();
$db->createCommand('CREATE TABLE home_collection_product (collection_id INTEGER, product_id INTEGER)')->execute();
$db->createCommand('INSERT INTO home_product VALUES (1)')->execute();
$db->createCommand('INSERT INTO home_collection_product SELECT id,1 FROM collection WHERE id<>11')->execute();
foreach(['home_collection','home_product','home_collection_product'] as $table)$db->schema->getTableSchema($table);
$source=str_replace("\r\n","\n",file_get_contents(dirname(__DIR__).'/src/views/modules/cms/tree/home.php'));
$start=strpos($source,'$collectionsQuery =');
$end=strpos($source,'<? if (\Yii::$app->shop->shopContents)');
$check($start!==false && $end>$start,'Не найден блок коллекций главной');
$temp=tempnam(sys_get_temp_dir(),'collections-home-');
file_put_contents($temp,"<?php\n".substr($source,$start,$end-$start));
try {
    foreach([0,1,99] as $page) {
        Yii::$app->request->setQueryParams(['page'=>$page+1]);
        $seen=[];
        Yii::getLogger()->messages=[];
        Yii::$app->view->renderFile($temp);
        $expectedIds=$page===0?[1,2,4,5,10,8,7,5]:[7,8,10,4,2,1];
        $check($seen===$expectedIds,'Состав или порядок двух блоков изменился: '.json_encode($seen));
        $counts=0;
        foreach(Yii::getLogger()->messages as $m) {
            if($m[1]!==yii\log\Logger::LEVEL_PROFILE_BEGIN || $m[2]!=='yii\db\Command::query')continue;
            if(strpos($m[0],'SELECT COUNT(*)')===0)$counts++;
            $check(strpos($m[0],'FROM '.chr(96).'home_product'.chr(96))===false,'Загружены неиспользуемые товары');
        }
        $check($counts===1,'Главная должна выполнить один COUNT, выполнено '.$counts);
    }
    $db->createCommand()->delete('home_collection_product')->execute();
    $seen=[];
    Yii::getLogger()->messages=[];
    $html=Yii::$app->view->renderFile($temp);
    $check($seen===[] && strpos($html,'sx-home-collection-')===false,'Пустые блоки коллекций не скрыты');
    $check($queries()===1,'Пустая главная должна выполнить только COUNT');
    echo "OK: главная выполняет один COUNT; обе сортировки, страницы и пустой набор сохранены, товары не загружаются.\n";
} finally {unlink($temp);}
