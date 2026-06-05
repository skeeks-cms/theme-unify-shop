<?php

/**
 * @link https://cms.skeeks.com/
 * @copyright Copyright (c) 2010 SkeekS
 * @license https://cms.skeeks.com/license/
 * @author Semenov Alexander <semenov@skeeks.com>
 */
/* @var $this yii\web\View */

?>

<?php if ($this->beginCache('header-fast-brands-html-v2', [
    'duration' => 7200*4,
    'dependency' => new \yii\caching\TagDependency([
        'tags' => [
            \Yii::$app->skeeks->site->cacheTag
        ],
    ]),
])) : ?>

<?php



$q = \skeeks\cms\shop\models\ShopBrand::find()
        ->with("logo")
        ->with("country")
        ->innerJoinWith('products as products', false)
        ->addSelect(\skeeks\cms\shop\models\ShopBrand::tableName() . ".*")
        ->andWhere([\skeeks\cms\shop\models\ShopBrand::tableName() . ".is_active" => 1])
        ->groupBy(\skeeks\cms\shop\models\ShopBrand::tableName() . ".id")
        ->orderBy([\skeeks\cms\shop\models\ShopBrand::tableName() . ".name" => SORT_ASC]);


$cloneQ = clone $q;

$cloneQ->addSelect(['letter' => new \yii\db\Expression("SUBSTRING(name, 1, 1)")]);
$cloneQ->groupBy(['letter']);

$availableLetters = $cloneQ->asArray()->all();
$availableLetters = \yii\helpers\ArrayHelper::map($availableLetters, function ($row) {
    return \skeeks\cms\helpers\StringHelper::ucfirst($row['letter']);
}, function ($row) {
    return \skeeks\cms\helpers\StringHelper::ucfirst($row['letter']);
});

$brandsArray = [];

foreach ($q->all() as $brand) {
    $name = \skeeks\cms\helpers\StringHelper::ucfirst($brand['name']);
    $letter = \skeeks\cms\helpers\StringHelper::substr($name, 0, 1);
    $brandsArray[$letter][] = $brand;
}

$collectionsAjaxUrl = \yii\helpers\Url::to(['/shop/brand/collections']);


?>

<?php if ($availableLetters) : ?>
    <div class="sx-fast-brands" data-collections-url="<?= $collectionsAjaxUrl; ?>">
        <div class="sx-container container">
            <div class="sx-fast-brands-content">
                <div class="sx-b-title">Бренды:</div>
                <div class="sx-b-list">
                    <ul>
                        <?php foreach ($availableLetters as $letter) : ?>
                            <li>
                                <?php echo $letter; ?>

                                <div class="sx-alphabet">
                                    <?
                                    /**
                                     * @var $brand \skeeks\cms\shop\models\ShopBrand
                                     */
                                    foreach ($brandsArray[$letter] as $brand) : ?>
                                        <div class="sx-brand-item">
                                            <span style="position: relative;">
                                                <a href="<?php echo $brand->url; ?>"
                                                   class="sx-brand-link"
                                                   data-brand-id="<?= $brand->id; ?>">
                                                    <? if ($this->theme->brand_line_is_flags && $brand->country && $brand->country->flag_image_id) : ?>
                                                        <? $flag = $brand->country->flag; ?>

                                                        <span class="sx-flag">
                                                            <img class="img-fluid"
                                                                 src="<?= \Yii::$app->imaging->thumbnailUrlOnRequest($flag->src,
                                                                     new \skeeks\cms\components\imaging\filters\Thumbnail([
                                                                         'w' => 0,
                                                                         'h' => 20,
                                                                         'm' => \Imagine\Image\ImageInterface::THUMBNAIL_OUTBOUND,
                                                                     ]), $brand->country->alpha2
                                                                 ); ?>" alt="<?= $brand->country->name; ?>">
                                                        </span>
                                                    <? endif; ?>
                                                    <span><?php echo $brand->name; ?></span>
                                                </a>
                                                <div class="sx-brand-collections-popup">
                                                    <div class="sx-brand-collections-loading">Загрузка...</div>
                                                </div>
                                            </span>


                                        </div>
                                    <? endforeach; ?>
                                </div>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>

<?php $this->endCache(); endif; ?>

<?php
$this->registerCss(<<<CSS
.sx-fast-brands .sx-flag img {
    width: 1.5rem;
    margin-right: 0.3rem;
}

.sx-fast-brands {
    background: var(--second-bg-color);
    width: 100%;
    position: relative;
}

.sx-fast-brands-content .sx-b-list ul li a:hover {
    text-decoration: none;
    color: var(--primary-color);
}

.sx-fast-brands-content .sx-b-list ul li:hover {
    background: var(--primary-color);
    color: white;
}

.sx-fast-brands-content .sx-b-list ul li a {
    color: var(--text-color);
}

.sx-fast-brands-content .sx-b-list ul li {
    list-style-type: none;
    cursor: pointer;
    padding: 1rem 1rem;
    --countRow: 4;
}

.sx-fast-brands-content .sx-b-list {
    width: 100%;
}

.sx-fast-brands-content .sx-b-list ul {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 0;
    width: 100%;
}

.sx-fast-brands-content {
    display: flex;
    justify-content: start;
    align-items: center;
}

.sx-fast-brands .sx-alphabet {
    min-height: 10rem;
    align-content: flex-start;
    cursor: default;

    display: grid;
    gap: 10px;
    grid-template-columns: repeat(4, 25%);
    grid-template-rows: repeat(var(--countRow), 20px);

    position: absolute;
    top: 3rem;
    left: 0;
    z-index: 999;
    width: 100%;
    background: white;
    border-radius: 0 0 5px 5px;
    padding: 15px 30px;
    box-shadow: 0px 4px 31px rgba(0, 0, 0, 0.07),
                0px 1.6711px 12.9511px rgba(0, 0, 0, 0.0503198),
                0px 0.89345px 6.92426px rgba(0, 0, 0, 0.0417275),
                0px 0.50086px 3.88168px rgba(0, 0, 0, 0.035),
                0px 0.266px 2.06153px rgba(0, 0, 0, 0.0282725),
                0px 0.11069px 0.85785px rgba(0, 0, 0, 0.0196802);

    opacity: 0;
    visibility: hidden;
    transform: translateY(10px) scale(0.98);

    transition:
        opacity .25s ease,
        transform .25s ease,
        visibility .25s ease;
}

.sx-fast-brands-content .sx-b-list ul li:hover .sx-alphabet {
    opacity: 1;
    visibility: visible;
    transform: translateY(0) scale(1);
}

.sx-fast-brands .sx-brand-item {
    position: relative;
    min-width: 0;
}

.sx-fast-brands .sx-brand-link {
    display: inline-flex;
    align-items: center;
    max-width: 100%;
    cursor: pointer;
}

.sx-fast-brands .sx-brand-collections-popup {
    position: absolute;
    
    
    top: 50%;
    left: calc(100% + 10px);

    transform: translateY(-50%) scale(0.98);
    
    /*top: 100%;
    left: 0;
    right: auto;*/

    min-width: 220px;
    max-width: 320px;
    max-height: 260px;
    overflow-y: auto;

    display: block;
    opacity: 0;
    visibility: hidden;
    /*transform: translateY(8px) scale(0.98);*/
    pointer-events: none;

    margin-top: 8px;
    padding: 12px 15px;

    background: #fff;
    color: var(--text-color);
    border-radius: 6px;
    box-shadow: 0 10px 30px rgba(0,0,0,.14);

    z-index: 50;

    transition:
        opacity .18s ease,
        transform .18s ease,
        visibility .18s ease;
}

/*.sx-fast-brands .sx-brand-collections-popup.is-open {
    opacity: 1;
    visibility: visible;
    transform: translateY(0) scale(1);
    pointer-events: auto;
}*/

.sx-fast-brands .sx-brand-collections-popup.is-open {
    opacity: 1;
    visibility: visible;
    pointer-events: auto;
    transform: translateY(-50%) scale(1);
}

.sx-fast-brands .sx-brand-collections-popup.sx-popup-left {
    left: auto;
    right: 0;
}

.sx-fast-brands .sx-brand-collections-popup a {
    display: block;
    padding: 4px 0;
    color: var(--text-color);
    cursor: pointer;
}

.sx-fast-brands .sx-brand-collections-popup a:hover {
    color: var(--primary-color);
}

.sx-fast-brands .sx-brand-collections-loading,
.sx-fast-brands .sx-brand-collections-empty {
    color: #777;
    font-size: 0.9rem;
}
CSS
);


if ($this->theme->brand_line_is_collection) {

    $jsSettings = \yii\helpers\Json::encode([
            'brand_line_is_collection' => $this->theme->brand_line_is_collection,
            'brand_line_collection_trigger' => $this->theme->brand_line_collection_trigger,
    ]);

    $this->registerJs(<<<JS
    sx.jsBrandLineConfig = {$jsSettings};
JS
    );

    $this->registerJs(<<<'JS'
(function() {

    
    
    var popupMode = sx.jsBrandLineConfig.brand_line_collection_trigger; // 'click' | 'hover'

    function closeBrandCollections() {
        $('.sx-fast-brands .sx-brand-collections-popup')
            .removeClass('is-open sx-popup-left');
    }

    function openBrandCollections($link, $popup) {
        $popup.addClass('is-open').removeClass('sx-popup-left');

        var popupRect = $popup[0].getBoundingClientRect();
        var viewportWidth = window.innerWidth || document.documentElement.clientWidth;

        if (popupRect.right > viewportWidth - 15) {
            $popup.addClass('sx-popup-left');
        }
    }

    function loadCollections($link) {

        var $item = $link.closest('.sx-brand-item');
        var $popup = $item.find('.sx-brand-collections-popup').first();

        var brandId = $link.data('brand-id');
        var ajaxUrl = $link.closest('.sx-fast-brands').data('collections-url');

        if ($popup.data('loaded')) {
            openBrandCollections($link, $popup);
            return;
        }

        if ($popup.data('loading')) {
            openBrandCollections($link, $popup);
            return;
        }

        $popup
            .data('loading', true)
            .html('<div class="sx-brand-collections-loading">Загрузка...</div>');

        openBrandCollections($link, $popup);

        $.ajax({
            url: ajaxUrl,
            type: 'GET',
            data: {
                brand_id: brandId
            },
            success: function(html) {

                html = $.trim(html);

                if (!html) {
                    html = '<div class="sx-brand-collections-empty">Коллекций нет</div>';
                }

                $popup
                    .html(html)
                    .data('loaded', true);

                openBrandCollections($link, $popup);
            },
            error: function() {
                $popup.html('<div class="sx-brand-collections-empty">Не удалось загрузить коллекции</div>');
            },
            complete: function() {
                $popup.data('loading', false);
            }
        });
    }

    // ======== РЕЖИМ CLICK ========

    $(document).on('click', '.sx-fast-brands .sx-brand-link', function(e) {

        if (popupMode !== 'click') {
            return;
        }

        e.preventDefault();
        e.stopPropagation();

        var $link = $(this);
        var $popup = $link.closest('.sx-brand-item')
            .find('.sx-brand-collections-popup');

        if ($popup.hasClass('is-open')) {
            $popup.removeClass('is-open sx-popup-left');
            return;
        }

        closeBrandCollections();
        loadCollections($link);
    });

    // ======== РЕЖИМ HOVER ========

    $(document).on(
        'mouseenter',
        '.sx-fast-brands .sx-brand-link',
        function() {

            if (popupMode !== 'hover') {
                return;
            }

            loadCollections($(this));
        }
    );

    $(document).on(
        'mouseleave',
        '.sx-fast-brands .sx-brand-item',
        function() {
    
                if (popupMode !== 'hover') {
                return;
            }
    
            var $popup = $(this).find('.sx-brand-collections-popup');
    
            hideTimer = setTimeout(function() {
                $popup.removeClass('is-open sx-popup-left');
            }, 300); // можно 200-500 мс
        }
    );

    // Закрытие только для click режима
    $(document).on('click', function() {
        if (popupMode === 'click') {
            closeBrandCollections();
        }
    });

})();
JS
    );
}
?>
