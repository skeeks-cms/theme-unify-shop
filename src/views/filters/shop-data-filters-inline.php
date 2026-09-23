<?php
/**
 * Бренд и страна в виде выпадающих кнопок, как характеристики в eav-filters-inline.
 *
 * @link https://cms.skeeks.com/
 * @copyright Copyright (c) 2010 SkeekS
 * @license https://cms.skeeks.com/license/
 */
/* @var $this yii\web\View */
/* @var $handler \skeeks\cms\shop\queryFilter\ShopDataFiltersHandler */
/* @var $form \yii\widgets\ActiveForm */

$groups = [
    'brand_id' => ['name' => 'Бренд', 'options' => $handler->getBrandOptions()],
    'country'  => ['name' => 'Страна', 'options' => $handler->getCountryOptions()],
];
?>
<? foreach ($groups as $attribute => $group) : ?>
    <?
    $options = (array)$group['options'];
    if (!$options) {
        continue;
    }

    $values = (array)$handler->{$attribute};
    $class = '';
    $name = $group['name'];

    if ($values) {
        $class = 'opened sx-filter-selected';
        $name .= " <small>(".count($values).")</small>";

        $newOptions = [];
        foreach ($values as $value) {
            if (isset($options[$value])) {
                $newOptions[$value] = $options[$value];
                unset($options[$value]);
            }
        }
        $options = $newOptions + $options;
    }

    $searchOptions = "";
    if (count($options) > 3) {
        $searchOptions = <<<HTML
<div class="filter-search__input js-filter-search-hide" style="display: none;">
    <input type="text" class="form-control" placeholder="Введите название">
</div>
HTML;
    }
    ?>
    <?= $form->field($handler, $attribute, [
        'options'  => [
            'class' => 'dropdown sx-filter sx-filter-chekbox sx-inline-filter '.$class,
            'tag'   => 'div',
        ],
        'template' => <<<HTML
<a href="#" class="dropdown-toggle btn btn-default sx-inline-btn" data-toggle="dropdown">{$name}</a>
<div class="dropdown-menu keep-open">
    <div class="filter--group">
        <div class="filter--group--body">
            {$searchOptions}
            <div class="js-scrollbar" style="max-height: 280px;">
            {input}
            </div>
        </div>
        <div class="sx-btn-apply-wrapper">
            <button type="submit" class="btn btn-primary">Применить</button>
        </div>
    </div>
</div>
HTML
        ,
    ])->checkboxList($options, [
        'class' => 'sx-filters-checkbox-options filter--group--inner',
        'item'  => function ($index, $label, $name, $checked, $value) use ($attribute) {
            $input = \yii\helpers\Html::checkbox($name, $checked, [
                'id'    => 'filter-check-'.$attribute.'-'.$index,
                'value' => $value,
            ]);
            return <<<HTML
<div class="checkbox">
{$input}
<label for="filter-check-{$attribute}-{$index}">{$label}</label>
</div>
HTML;
        },
    ]); ?>
<? endforeach; ?>
