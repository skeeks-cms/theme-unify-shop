<?php
namespace {
    require $argv[1] ?? '/app/vendor/autoload.php';
    require dirname($argv[1] ?? '/app/vendor/autoload.php').'/yiisoft/yii2/Yii.php';
    new \yii\console\Application(['id'=>'measures-test','basePath'=>__DIR__,'components'=>['db'=>['class'=>\yii\db\Connection::class,'dsn'=>'sqlite::memory:']]]);
}
namespace skeeks\cms\measure\models {
    class CmsMeasure extends \yii\db\ActiveRecord {public static function tableName(){return 'cms_measure';}}
}
namespace {
    $db=\Yii::$app->db;
    $db->createCommand('CREATE TABLE cms_measure (code TEXT PRIMARY KEY, symbol TEXT)')->execute();
    $db->createCommand()->batchInsert('cms_measure',['code','symbol'],[['pack','упак'],['piece','шт']])->execute();
    $db->schema->getTableSchema('cms_measure');
    $source=file_get_contents(dirname(__DIR__).'/src/views/modules/cms/content-element/_product-price.php');
    preg_match_all('/(\$measureMatches = \$shopProduct->measureMatches;.*?\$matchedMeasures = .*?;)\s*\?>/s',$source,$blocks);
    if(count($blocks[1])!==2)throw new \RuntimeException('Ожидались два блока единиц пересчёта');
    foreach($blocks[1] as $code)foreach([[],['pack'=>2,'piece'=>3],['piece'=>3,'missing'=>1,'pack'=>2]] as $values){
        $shopProduct=(object)['measureMatches'=>$values];
        \Yii::getLogger()->messages=[];
        eval($code);
        $queries=count(array_filter(\Yii::getLogger()->messages,static function($m){return $m[1]===\yii\log\Logger::LEVEL_PROFILE_BEGIN && $m[2]==='yii\db\Command::query';}));
        if($queries!==($values?1:0))throw new \RuntimeException('Лишние запросы единиц измерения');
        foreach($measureMatches as $key=>$ratio) {
            $old=\skeeks\cms\measure\models\CmsMeasure::find()->where(['code'=>$key])->one();
            $new=$matchedMeasures[$key]??null;
            if(($old?$old->attributes:null)!==($new?$new->attributes:null))throw new \RuntimeException('Результат поиска единицы изменился');
        }
        if($measureMatches!==$values)throw new \RuntimeException('Изменился порядок пересчётов');
    }
    echo "OK: оба блока единиц, пустой набор, отсутствующие коды и порядок; один SQL вместо запроса на код.\n";
}
