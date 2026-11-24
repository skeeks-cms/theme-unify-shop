<?php
/**
 * @link https://cms.skeeks.com/
 * @copyright Copyright (c) 2010 SkeekS
 * @license https://cms.skeeks.com/license/
 * @author Semenov Alexander <semenov@skeeks.com>
 */

namespace skeeks\cms\themes\unifyshop\helpers;

/**
 * Наличие товара в карточке: одно решение и для разметки schema.org/Offer, и для
 * видимого покупателю статуса.
 *
 * Правило совпадает с выбором кнопок в _product-price.php:
 * - на сайте не ведутся склады — наличие не подтверждено, ни разметки, ни статуса нет;
 * - остаток больше нуля — InStock, «В наличии» (только у поставщиков — с пометкой
 *   «на центральном складе», как в блоке остатков _product-quantity.php);
 * - остатка нет, но сайт разрешает заказ без остатка — BackOrder, «Под заказ»;
 * - остатка нет — OutOfStock, «Нет в наличии».
 */
class ProductAvailability
{
    const IN_STOCK = 'InStock';
    const BACK_ORDER = 'BackOrder';
    const OUT_OF_STOCK = 'OutOfStock';

    /**
     * @param array $shopStoreProducts остатки товара на складах сайта (ShopStoreProduct или объекты с quantity и shop_store_id)
     * @param bool  $hasStores на сайте есть склады (\Yii::$app->shop->allStores)
     * @param bool  $isOrderWithoutQuantity сайт разрешает заказ без остатка (shopSite->is_show_product_no_quantity)
     * @param array $supplierStoreIds id складов поставщиков
     * @return array|null ['schema' => 'InStock'|'BackOrder'|'OutOfStock', 'label' => string, 'note' => string|null]
     */
    public static function resolve(array $shopStoreProducts, $hasStores, $isOrderWithoutQuantity, array $supplierStoreIds = [])
    {
        if (!$hasStores) {
            return null;
        }

        $ownQuantity = 0;
        $supplierQuantity = 0;
        foreach ($shopStoreProducts as $shopStoreProduct) {
            $quantity = (float)$shopStoreProduct->quantity;
            if ($quantity <= 0) {
                continue;
            }
            if (in_array($shopStoreProduct->shop_store_id, $supplierStoreIds)) {
                $supplierQuantity += $quantity;
            } else {
                $ownQuantity += $quantity;
            }
        }

        if ($ownQuantity > 0) {
            return ['schema' => self::IN_STOCK, 'label' => 'В наличии', 'note' => null];
        }
        if ($supplierQuantity > 0) {
            return ['schema' => self::IN_STOCK, 'label' => 'В наличии', 'note' => 'на центральном складе'];
        }
        if ($isOrderWithoutQuantity) {
            return ['schema' => self::BACK_ORDER, 'label' => 'Под заказ', 'note' => null];
        }

        return ['schema' => self::OUT_OF_STOCK, 'label' => 'Нет в наличии', 'note' => null];
    }

    /**
     * Наличие для текущего сайта.
     *
     * @param array $shopStoreProducts
     * @return array|null
     */
    public static function forSite(array $shopStoreProducts)
    {
        $shop = \Yii::$app->shop;
        $supplierStoreIds = [];
        foreach ((array)$shop->supplierStores as $store) {
            $supplierStoreIds[] = $store->id;
        }

        return static::resolve(
            $shopStoreProducts,
            (bool)$shop->allStores,
            (bool)\Yii::$app->skeeks->site->shopSite->is_show_product_no_quantity,
            $supplierStoreIds
        );
    }
}
