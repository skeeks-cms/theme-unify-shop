<?php
/**
 * @link https://cms.skeeks.com/
 * @copyright Copyright (c) 2010 SkeekS
 * @license https://cms.skeeks.com/license/
 * @author Semenov Alexander <semenov@skeeks.com>
 */

namespace skeeks\cms\themes\unifyshop\widgets\filter;

use skeeks\yii2\queryfilter\QueryFilterShortUrlWidget;
/**
 * @author Semenov Alexander <semenov@skeeks.com>
 */
class ProductFiterWidget extends QueryFilterShortUrlWidget
{
    public function init()
    {
        \Yii::$app->seo->canurl->ADDimportant_pname($this->filtersParamName);
        parent::init();
    }

    public function loadFromRequest()
    {
        if ($data = \Yii::$app->request->post()) {
            //Чистить незаполненные
            if (isset($data['_csrf'])) {
                unset($data['_csrf']);
            }
            foreach ($data as $handlerName => $handlerData) {
                if (is_array($data[$handlerName])) {
                    foreach ($data[$handlerName] as $key => $value) {
                        if (!$value && $value != '0') {
                            unset($data[$handlerName][$key]);
                        }
                    }
                }
            }
            $this->_data = $data;
            $this->load($data);
            \Yii::$app->response->redirect($this->getFilterUrl());
            \Yii::$app->end();
            $newUrl = $this->getFilterUrl();
            \Yii::$app->view->registerJs(<<<JS
window.history.pushState('page', 'title', '{$newUrl}');
JS
            );
        } elseif ($data = \Yii::$app->request->get($this->filtersParamName)) {
            $data = (array)unserialize(base64_decode($data));
            $this->_data = $data;
            $this->load($data);
        }
        return $this;
    }
}