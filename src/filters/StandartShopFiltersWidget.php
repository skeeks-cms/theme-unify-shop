<?php
/**
 * @link https://cms.skeeks.com/
 * @copyright Copyright (c) 2010 SkeekS
 * @license https://cms.skeeks.com/license/
 * @author Semenov Alexander <semenov@skeeks.com>
 */

namespace skeeks\cms\themes\unifyshop\filters;


use skeeks\cms\shop\queryFilter\AvailabilityFiltersHandler;
use skeeks\cms\themes\unify\queryFilter\SortFiltersHandler;
use skeeks\cms\themes\unify\widgets\filters\FiltersWidget;
use yii\helpers\ArrayHelper;
/**
 *
 * Стандартный работ фильтров для магазина
 *
 *
 * @author Semenov Alexander <semenov@skeeks.com>
 */
class StandartShopFiltersWidget extends FiltersWidget
{
    public function init()
    {
        parent::init();

        //Регистрация и настройка стандартных фильтров
        $availabilityFiltersHandler = new \skeeks\cms\shop\queryFilter\AvailabilityFiltersHandler();
        $availabilityFiltersHandler->value = (int)\Yii::$app->skeeks->site->shopSite->is_show_product_no_price;

        $sortFiltersHandler = new \skeeks\cms\shop\queryFilter\SortFiltersHandler();
        $availabilityFiltersHandler->viewFileVisible = '@app/views/filters/availability-filter';
        $sortFiltersHandler->viewFileVisible = '@app/views/filters/sort-filter';

        $this
            ->registerHandler($availabilityFiltersHandler, 'availability')
            ->registerHandler($sortFiltersHandler, 'sort');
    }

    /**
     * @return SortFiltersHandler
     */
    public function getSortHandler()
    {
        return ArrayHelper::getValue($this->handlers, 'sort');
    }
    /**
     * @return AvailabilityFiltersHandler
     */
    public function getAvailabilityHandler()
    {
        return ArrayHelper::getValue($this->handlers, 'availability');
    }
}