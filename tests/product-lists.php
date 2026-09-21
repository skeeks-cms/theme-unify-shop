<?php
namespace {
    // Reuse the isolated SQLite shop fixture; no installed site is bootstrapped.
    require $argv[2] ?? __DIR__.'/../../cms-shop/tests/product-card-data.php';
}
namespace skeeks\cms\themes\unify\assets\components {
    class UnifyThemeStickAsset {public static function register($view){}}
}
namespace skeeks\cms\themes\unify\widgets {
    class ScrollAndSpPager extends \yii\widgets\LinkPager {
        public $container;public $item;public $triggerOffset;
    }
}
namespace {
    class CardImage extends \yii\db\ActiveRecord {public static function tableName(){return 'card_image';}}
    class CardPrice extends \yii\db\ActiveRecord {public static function tableName(){return 'card_price';}}
    class CardProduct extends \yii\db\ActiveRecord {
        public static function tableName(){return 'card_product';}
        public function getBaseProductPrice(){return $this->hasOne(CardPrice::class,['product_id'=>'id'])->andWhere(['type_price_id'=>1]);}
        public function getShopProductPrices(){return $this->hasMany(CardPrice::class,['product_id'=>'id']);}
    }
    class CardElement extends \yii\db\ActiveRecord {
        public static function tableName(){return 'card_element';}
        public function getShopProduct(){return $this->hasOne(CardProduct::class,['id'=>'id']);}
        public function getImage(){return $this->hasOne(CardImage::class,['id'=>'image_id']);}
        public function getImages(){return $this->hasMany(CardImage::class,['element_id'=>'id'])->orderBy(['priority'=>SORT_ASC]);}
    }
    class CardTheme extends \yii\base\Theme {
        public $product_slider_items=4;public $catalog_per_page=5;
        public $prooductListItemCssClasses='fixture-card';public $pagination_trigger_offset=1;
    }
    foreach([
        'CREATE TABLE card_product (id INTEGER PRIMARY KEY)',
        'CREATE TABLE card_element (id INTEGER PRIMARY KEY, image_id INTEGER)',
        'CREATE TABLE card_price (id INTEGER PRIMARY KEY, product_id INTEGER, type_price_id INTEGER, price NUMERIC)',
        'CREATE TABLE card_image (id INTEGER PRIMARY KEY, element_id INTEGER, priority INTEGER)'
    ] as $sql)$db->createCommand($sql)->execute();
    for($id=1;$id<=26;$id++){
        $db->createCommand()->insert('card_product',['id'=>$id])->execute();
        $db->createCommand()->insert('card_element',['id'=>$id,'image_id'=>$id])->execute();
        $db->createCommand()->insert('card_price',['id'=>$id,'product_id'=>$id,'type_price_id'=>1,'price'=>$id*10])->execute();
        $db->createCommand()->insert('card_image',['id'=>$id,'element_id'=>$id,'priority'=>1])->execute();
    }
    foreach(['card_product','card_element','card_price','card_image'] as $table)$db->schema->getTableSchema($table);
    Yii::$app->set('shop',$shop);
    Yii::$app->set('view',new \yii\web\View(['theme'=>new CardTheme(['basePath'=>__DIR__])]));
    Yii::$app->set('request',new \yii\web\Request(['cookieValidationKey'=>'fixture','scriptFile'=>__FILE__,'scriptUrl'=>'/index.php','hostInfo'=>'https://fixture.invalid','baseUrl'=>'']));
    Yii::$app->controller=new \yii\web\Controller('fixture',Yii::$app);
    $root=dirname(__DIR__).'/src/views';
    Yii::setAlias('@app/views/products',$root.'/products');
    $expectBatch=true;
    $seen=[];
    Yii::$app->view->on(\yii\base\View::EVENT_BEFORE_RENDER,static function($event)use(&$seen,$check,$countQueries,&$expectBatch){
        if(basename($event->viewFile)!=='product-list-item.php')return;
        $m=$event->params['model'];$data=$event->params['cardData']??null;
        if (!$expectBatch) {$check($data===null,'Older shop must use the legacy card path');$seen[]=(int)$m->id;$event->isValid=false;return;}
        $check($data && $data->hasProduct($m->id),'ListView must pass this list snapshot');
        $check($m->isRelationPopulated('image') && $m->isRelationPopulated('images'),'Images must be eager loaded');
        $check($m->shopProduct->isRelationPopulated('baseProductPrice') && $m->shopProduct->isRelationPopulated('shopProductPrices'),'Prices must be eager loaded');
        $before=$countQueries();$m->image;$m->images;$m->shopProduct->baseProductPrice;$m->shopProduct->shopProductPrices;
        $check($before===$countQueries(),'No per-card relation queries');
        $seen[]=(int)$m->id;$event->isValid=false; // UI markup is outside this data-flow test.
    });
    $provider=new \yii\data\ActiveDataProvider(['query'=>CardElement::find()->orderBy('id')->limit(15),'pagination'=>false,'sort'=>false]);
    Yii::getLogger()->messages=[];
    Yii::$app->view->renderFile($root.'/widgets/ContentElementsCmsWidget/products-stick.php',['widget'=>(object)['dataProvider'=>$provider,'label'=>'Fixture']]);
    $check($seen===range(1,15),'Slider limit/order preserved');
    $selects=array_filter(Yii::getLogger()->messages,static function($m){return $m[1]===\yii\log\Logger::LEVEL_PROFILE_BEGIN && strpos($m[0],'FROM `card_element`')!==false;});
    $check(count($selects)===1,'Slider must not fetch a separate probe product');
    $seen=[];
    $provider=new \yii\data\ActiveDataProvider(['query'=>CardElement::find()->where(['>','id',3])->orderBy('id'),'pagination'=>['page'=>1,'params'=>[]],'sort'=>false]);
    Yii::$app->view->renderFile($root.'/products/product-list.php',['dataProvider'=>$provider]);
    $check($seen===range(9,13),'Catalog filter, ordering and second page preserved');
    $seen=[];
    $provider=new \yii\data\ActiveDataProvider(['query'=>CardElement::find()->where(['id'=>-1]),'pagination'=>false,'sort'=>false]);
    Yii::$app->view->renderFile($root.'/widgets/ContentElementsCmsWidget/products-stick.php',['widget'=>(object)['dataProvider'=>$provider,'label'=>'Empty']]);
    $check($seen===[],'Empty slider must not render cards');
    $expectBatch=false;$seen=[];
    $legacyView=tempnam(sys_get_temp_dir(),'skeeks-list-');
    try {
        file_put_contents($legacyView,str_replace('\\skeeks\\cms\\shop\\helpers\\ProductCardData','\\skeeks\\cms\\shop\\helpers\\UnavailableProductCardData',file_get_contents($root.'/widgets/ContentElementsCmsWidget/products-stick.php')));
        $provider=new \yii\data\ActiveDataProvider(['query'=>CardElement::find()->orderBy('id')->limit(2),'pagination'=>false,'sort'=>false]);
        Yii::$app->view->renderFile($legacyView,['widget'=>(object)['dataProvider'=>$provider,'label'=>'Legacy']]);
        $check($seen===[1,2],'Theme remains compatible with older shop packages');
    } finally {unlink($legacyView);}
    echo "OK: real Yii ListView passes batch context; eager loading, slider query, pagination, filters and empty list verified\n";
}
