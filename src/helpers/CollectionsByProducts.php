<?php
/**
 * @link https://cms.skeeks.com/
 * @copyright Copyright (c) 2010 SkeekS
 * @license https://cms.skeeks.com/license/
 * @author Semenov Alexander <semenov@skeeks.com>
 */

namespace skeeks\cms\themes\unifyshop\helpers;

use skeeks\cms\models\CmsContentElement;
use skeeks\cms\shop\models\ShopCollection;
use yii\db\ActiveQuery;

/**
 * Коллекции товаров каталога, бренда или сохранённого фильтра для режима «Коллекции».
 *
 * Сначала одним запросом выбираются id коллекций подходящих товаров, затем сами
 * коллекции по списку id. Прежний вариант соединял shop_collection с производной
 * таблицей товаров (INNER JOIN (...) main): MariaDB выполняла её как LATERAL DERIVED
 * для каждой из всех коллекций сайта, и подсчёт со списком занимали 0,4–0,9 с.
 */
class CollectionsByProducts
{
    /**
     * @param ActiveQuery $productsQuery запрос товаров каталога (с примененными фильтрами); не изменяется
     * @return ActiveQuery запрос коллекций, отсортированный по популярности
     */
    public static function query(ActiveQuery $productsQuery)
    {
        $idsQuery = clone $productsQuery;
        $idsQuery->with = [];

        //Первая колонка — id коллекции (для column()); realPrice нужен, если фильтр цены использует HAVING
        $select = [
            'collection_id' => "collections.id",
            CmsContentElement::tableName().".id",
        ];
        if (is_array($productsQuery->select) && isset($productsQuery->select['realPrice'])) {
            $select['realPrice'] = $productsQuery->select['realPrice'];
        }

        $idsQuery->select($select);
        $idsQuery->innerJoinWith("shopProduct.collections as collections", false);
        $idsQuery->groupBy(['collections.id']);
        $idsQuery->orderBy([]);
        $idsQuery->limit(-1)->offset(-1);

        $ids = $idsQuery->column();

        return ShopCollection::find()
            ->andWhere([ShopCollection::tableName().".id" => $ids])
            //id — для устойчивого порядка коллекций с одинаковым счётчиком между страницами
            ->orderBy([
                ShopCollection::tableName().".show_counter" => SORT_DESC,
                ShopCollection::tableName().".id"           => SORT_DESC,
            ]);
    }
}
