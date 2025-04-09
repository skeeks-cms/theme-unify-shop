<?php
/**
 * @link https://cms.skeeks.com/
 * @copyright Copyright (c) 2010 SkeekS
 * @license https://cms.skeeks.com/license/
 * @author Semenov Alexander <semenov@skeeks.com>
 */
/* @var $this yii\web\View */

/**
 * @var $model \skeeks\cms\models\CmsTree
 * @var $cmsContentProperty \skeeks\cms\models\CmsContentProperty
 *
 */

$q = \skeeks\cms\shop\models\ShopBrand::find()
    ->with("logo")
    ->innerJoinWith('products as products', false)
    ->addSelect(\skeeks\cms\shop\models\ShopBrand::tableName().".*")
    ->andWhere([\skeeks\cms\shop\models\ShopBrand::tableName().".is_active" => 1])
    ->groupBy(\skeeks\cms\shop\models\ShopBrand::tableName().".id")
    ->orderBy([\skeeks\cms\shop\models\ShopBrand::tableName().".name" => SORT_ASC]);

$this->registerCss(<<<CSS
.sx-brand-item-wrapper {
    text-align: center;
    margin-bottom: 30px;
}

.sx-empty-list {
    min-height: 400px;
    display: flex;
  align-items: center;
  justify-content: center;
}

.sx-inactive {
    opacity: 0.1;
}

.sx-filters .form-group {
    margin-bottom: 0px;
}
.sx-filters {
    background: var(--second-bg-color);
    padding: 20px 20px;
    border-radius: 4px;
    margin: 20px 0;
}
.sx-filter {
    margin-bottom: 20px;
}
.sx-filter:last-child {
    margin-bottom: 0px;
}

.sx-alphabet-wrapper, .sx-alphabet-group-wrapper {
    background: white;
    border: 1px solid #ced4da;
    border-radius: 4px;
}


.sx-alphabet-group-wrapper ul {
    margin-bottom: 0;
    display: flex;
    padding: 0;
}
.sx-alphabet-wrapper ul {
    margin-bottom: 0;
    display: flex;
    flex-wrap: wrap;
    padding: 0;
}


.sx-alphabet-wrapper ul li span, .sx-alphabet-group-wrapper ul li span {
    margin: auto;
}
.sx-alphabet-wrapper ul li, .sx-alphabet-group-wrapper ul li {
    padding: 7px 10px;
    margin-right: 0px;
    display: inline-flex;
    text-align: center;
    height: 43px;
}
.sx-alphabet-group-wrapper ul li {
    width: 100%;
}


.sx-alphabet-wrapper ul li.sx-active, .sx-alphabet-group-wrapper ul li.sx-active,
.sx-alphabet-wrapper ul li:hover, .sx-alphabet-group-wrapper ul li:hover
 {
    background: var(--primary-color);
    color: white;
    cursor: pointer;
}


.sx-brands-page {
    padding-top: 20px;
    padding-bottom: 20px;
    padding-left: 15px;
    padding-right: 15px;
}

.sx-alphabet {
    display: none;
}

.sx-brand-item:hover {
    text-decoration: none;
    opacity: 0.8;
}
.sx-brand-item {
    color: var(--text-color);
}

.sx-brand-item .sx-title {
    text-decoration: none;
    font-size: 16px;
}

.sx-search-brand {
    position: relative;
}

.sx-search-brand button {
    position: absolute;
    right: 0;
    top: 50%;
    transform: translateY(-50%);
}

.sx-brand-item .sx-image {
    margin-bottom: 0.8rem;
}


.sx-brand-item .sx-image img {
    max-width: 100%;
    border-radius: var(--base-radius); 

}

.sx-brand-item .sx-country {
    opacity: 0.8;
}
.sx-brand-item .sx-total-products {
    opacity: 0.8;
}


CSS
);

$enAlphabet = [
    'A',
    'B',
    'C',
    'D',
    'E',
    'F',
    'G',
    'H',
    'I',
    'J',
    'K',
    'L',
    'M',
    'N',
    'O',
    'P',
    'Q',
    'R',
    'S',
    'T',
    'U',
    'V',
    'W',
    'X',
    'Y',
    'Z',
];

$ruAlphabet = [
    'А',
    'Б',
    'В',
    'Г',
    'Д',
    'Е',
    'Ж',
    'З',
    'И',
    'К',
    'Л',
    'М',
    'Н',
    'О',
    'П',
    'Р',
    'С',
    'Т',
    'У',
    'Ф',
    'Ц',
    'Э',
    'Ю',
    'Я',
];

$numberAlphabet = [
    '0',
    '1',
    '2',
    '3',
    '4',
    '5',
    '6',
    '7',
    '8',
    '9',
];

$this->registerJs(<<<JS

(function(sx, $, _)
{
    sx.classes.Brands = sx.classes.Component.extend({

        _init: function()
        {
            
        },

        _onDomReady: function()
        {
            var self = this;
            
            $(".sx-alphabet-group-wrapper ul li").on("click", function() {
                $(".sx-alphabet-group-wrapper ul li").removeClass("sx-active");
                $(this).addClass("sx-active");
                
                self.updateInstance();
            });
            
            $("input, select", self.getJForm()).on("change", function() {
                self.getJForm().submit();
                return false;
            });
            
            $(".sx-alphabet li").on("click", function() {
                if ($(this).hasClass("sx-inactive")) {
                    return false;
                }
                //console.log($(this).text().trim());
                $("#f-letter").val($(this).text().trim());
                self.getJForm().submit();
                return false;
            });
            
            
            var activeLetter = $(".sx-alphabet ul li.sx-active");
            if (activeLetter.length) {
                var activeId = activeLetter.closest(".sx-alphabet").attr("id");
                $("[data-container=" + activeId + "]").click();
            }
            
            self.updateInstance();
        },
        
        updateInstance: function() {
            var startVisible = $(".sx-alphabet-group-wrapper ul li.sx-active").data("container");
            $(".sx-alphabet").hide();
            $("#" + startVisible).show();
        },
        
        getJForm: function() {
            return $("#sx-filters");
        }
    });

    new sx.classes.Brands();

})(sx, sx.$, sx._);

JS
);

$filtersNames = [];

\Yii::$app->seo->canUrl->ADDimportant_pname("f");

$filters = new \skeeks\cms\base\DynamicModel();
$filters->formName = 'f';

$filters->defineAttribute("country");
$filters->defineAttribute("letter");
$filters->defineAttribute("q");

$filters->addRule("country", 'integer');
$filters->addRule("q", 'string');
$filters->addRule("letter", 'string');

$filters->load(\Yii::$app->request->get());
if ($filters->letter == 'Все') {
    $filters->letter = '';
}


if ($filters->q) {
    $q->andWhere([
        'LIKE',
        "name",
        $filters->q,
    ]);

    $filtersNames[] = 'поиск «'.$filters->q.'»';
}


$countries = [];
/**
 * @var $countryContentProperty \skeeks\cms\models\CmsContentProperty
 */
$country_content_id = null;
if ($filters->country) {
    $q->andWhere(['products.country_alpha2' => $filters->country]);

    $filtersCountry = \skeeks\cms\models\CmsCountry::find()->alpha2($filters->country)->one();
    $filtersNames[] = 'страна «'.$filtersCountry->name.'»';

}

$cloneQ = clone $q;
$cloneQ->addSelect(['letter' => new \yii\db\Expression("SUBSTRING(name, 1, 1)")]);
$cloneQ->groupBy(['letter']);

$availableLetters = $cloneQ->asArray()->all();
$availableLetters = \yii\helpers\ArrayHelper::map($availableLetters, function ($row) {
    return \skeeks\cms\helpers\StringHelper::ucfirst($row['letter']);
}, function ($row) {
    return \skeeks\cms\helpers\StringHelper::ucfirst($row['letter']);
});

if ($filters->letter) {
    $filtersNames[] = 'на букву «'.$filters->letter.'»';
    $q->andWhere(new \yii\db\Expression("name LIKE '{$filters->letter}%'"));
}

$totalOffers = $q->count();
$q->addSelect(['total_products' => new \yii\db\Expression("count(*)")]);


//print_r($q->createCommand()->rawSql);die;

$dataProvider = new \yii\data\ActiveDataProvider([
    'query'      => $q,
    'sort'       => [
        'defaultOrder' => [
            'priority' => SORT_ASC,
        ],
    ],
    'pagination' => [
        'defaultPageSize' => 24,
    ],
]);

//$total = $q->select($select)->limit(-1)->offset(-1)->orderBy([])->count('*');
$dataProvider->setTotalCount($totalOffers);
?>
<section class="sx-brands-page">
    <div class="container sx-container">


        <?= $this->render('@app/views/breadcrumbs', [
            'model'      => $model,
            'isShowH1'   => false,
            'isShowLast' => true,
        ]); ?>

        <div class="sx-catalog-h1-wrapper">
            <div><h1 class="sx-breadcrumbs-h1 sx-catalog-h1"><?php echo $model->seoName; ?><?php echo $filtersNames ? " + ".implode(" + ", $filtersNames) : ""; ?></h1></div>
            <div class="sx-catalog-total-offers" style="color: #979797;
    margin-top: auto;
    margin-left: 12px;
    font-size: 15px;">(<?php echo \Yii::t('app', '{n, plural, =0{нет брендов} =1{# бренд} one{# бренд} few{# брендов} many{# брендов} other{# брендов}}', ['n' => $totalOffers],
                    'ru_RU'); ?>)
            </div>
        </div>


        <div class="sx-filters">

            <? $form = \yii\bootstrap\ActiveForm::begin([
                'enableClientValidation' => false,
                'enableAjaxValidation'   => false,
                'method'                 => "get",
                'id'                     => "sx-filters",
            ]); ?>

            <div style="display: none;">
                <?php
                echo $form->field($filters, "letter");
                ?>
            </div>

            <div class="row">
                <div class="col-md-4 sx-filter">
                    <div class="sx-search-brand">
                        <?php echo $form->field($filters, "q")->textInput(
                            [
                                'placeholder' => 'Поиск по брендам',
                            ]
                        )->label(false); ?>
                        <button type="submit" class="btn">
                            <i class="icon-magnifier"></i>
                        </button>
                    </div>
                </div>
                <div class="col-md-4 sx-filter">

                    <?php echo $form->field($filters, "country")->widget(
                        \skeeks\cms\widgets\AjaxSelectModel::class,
                        [
                            'modelClass'       => \skeeks\cms\models\CmsCountry::class,
                            'modelPkAttribute' => 'alpha2',
                            'options'          => ['placeholder' => 'Все страны'],
                            'pluginOptions'    => [
                                'allowClear' => true,
                            ],
                            'searchQuery'      => function ($word = '') {
                                $query = \skeeks\cms\models\CmsCountry::find();
                                if ($word) {
                                    $query->search($word);
                                }
                                return $query;
                            },
                        ]
                    )->label(false); ?>
                </div>
                <div class="col-md-4 sx-filter">
                    <div class="sx-alphabet-group-wrapper">
                        <div class="sx-alphabet-group">
                            <ul>
                                <li class="sx-active" data-container="sx-en"><span>A-Z</span></li>
                                <li data-container="sx-ru"><span>А-Я</span></li>
                                <li data-container="sx-numbers"><span>0-9</span></li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="col-12 sx-filter">
                    <div class="sx-alphabet-wrapper">
                        <div id="sx-en" class="sx-alphabet">
                            <ul>
                                <? foreach ($enAlphabet as $letter) : ?>
                                    <li class="<?php echo $letter == $filters->letter ? 'sx-active' : ''; ?> <?php echo in_array($letter, $availableLetters) ? '' : 'sx-inactive'; ?>"><span><?php echo $letter; ?></span>
                                    </li>
                                <? endforeach; ?>
                                <?php if ($filters->letter) : ?>
                                    <li class="<?php echo $letter == $filters->letter ? 'sx-active' : ''; ?>"><span>Все</span></li>
                                <?php endif; ?>
                            </ul>
                        </div>
                        <div id="sx-ru" class="sx-alphabet">
                            <ul>
                                <? foreach ($ruAlphabet as $letter) : ?>
                                    <li class="<?php echo $letter == $filters->letter ? 'sx-active' : ''; ?> <?php echo in_array($letter, $availableLetters) ? '' : 'sx-inactive'; ?>"><span><?php echo $letter; ?></span>
                                    </li>
                                <? endforeach; ?>
                                <?php if ($filters->letter) : ?>
                                    <li class="<?php echo $letter == $filters->letter ? 'sx-active' : ''; ?>"><span>Все</span></li>
                                <?php endif; ?>
                            </ul>
                        </div>
                        <div id="sx-numbers" class="sx-alphabet">
                            <ul>
                                <? foreach ($numberAlphabet as $letter) : ?>
                                    <li class="<?php echo $letter == $filters->letter ? 'sx-active' : ''; ?> <?php echo in_array($letter, $availableLetters) ? '' : 'sx-inactive'; ?>"><span><?php echo $letter; ?></span>
                                    </li>
                                <? endforeach; ?>
                                <?php if ($filters->letter) : ?>
                                    <li class="<?php echo $letter == $filters->letter ? 'sx-active' : ''; ?>"><span>Все</span></li>
                                <?php endif; ?>
                            </ul>
                        </div>
                    </div>
                </div>

            </div>
            <? $form::end(); ?>
        </div>

        <? echo \yii\widgets\ListView::widget([
            'dataProvider' => $dataProvider,
            'itemView'     => '@app/views/brands/brand-item',
            'emptyText'    => '<div class="sx-empty-list"><div class="h1">Брендов нет</div></div>',
            'options'      => [
                'class' => '',
                'tag'   => 'div',
            ],
            'itemOptions'  => [
                'tag' => false,
            ],
            'pager'        => [
                'container' => '.sx-brand-list',
                'item'      => '.sx-brand-item-wrapper',
                'class'     => \skeeks\cms\themes\unify\widgets\ScrollAndSpPager::class,
            ],
            //'summary'      => "Всего товаров: {totalCount}",
            'summary'      => false,
            //"\n{items}<div class=\"box-paging\">{pager}</div>{summary}<div class='sx-js-pagination'></div>",
            'layout'       => '<div class="row"><div class="col-md-12 sx-product-list-summary">{summary}</div></div>
                    <div class="no-gutters row sx-brand-list">{items}</div>
                    <div class="row"><div class="col-md-12">{pager}</div></div>',
        ])
        ?>

    </div>
</section>