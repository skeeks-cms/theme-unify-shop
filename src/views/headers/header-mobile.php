<?php
/**
 * @link https://cms.skeeks.com/
 * @copyright Copyright (c) 2010 SkeekS
 * @license https://cms.skeeks.com/license/
 * @author Semenov Alexander <semenov@skeeks.com>
 */
?>


<?php
/**
 * @link https://cms.skeeks.com/
 * @copyright Copyright (c) 2010 SkeekS
 * @license https://cms.skeeks.com/license/
 * @author Semenov Alexander <semenov@skeeks.com>
 */
/* @var $this yii\web\View */
/* @see https://htmlstream.com/public/preview/unify-v2.6.1/unify-main/shortcodes/headers/classic-header--topbar-1.html */
\skeeks\assets\unify\base\UnifyHsHamburgersAsset::register($this);
//\skeeks\assets\unify\base\UnifyHsMegamenuAsset::register($this);
\skeeks\cms\themes\unify\assets\components\UnifyThemeHeaderMobileAsset::register($this);

?>
<? $items = [];

$models = \skeeks\cms\models\CmsTree::find()->cmsSite()->andWhere(['level' => 1])->active()
    //->andWhere(['active' => 'Y'])
    ->orderBy(['priority' => SORT_ASC])
    ->all();
if ($models) {
    foreach ($models as $model) {
        $tmpItems = [];
        if ($model->children) {
            foreach ($model->getChildren()->active()->all() as $child) {
                if ($child->isActive) {


                    $tmpItems2 = [];
                    /**
                     * @var $child \skeeks\cms\models\CmsTree
                     */
                    if ($child->activeChildren) {
                        foreach ($child->activeChildren as $subchild) {
                            $tmpItems2[] = [
                                'label' => $subchild->name,
                                'url'   => $subchild->url,
                            ];
                        }
                    }

                    if ($tmpItems2) {
                        $tmpItems[] = [
                            'label' => $child->name,
                            'url'   => $child->url,
                            'items' => $tmpItems2,
                        ];
                    } else {
                        $tmpItems[] = [
                            'label' => $child->name,
                            'url'   => $child->url,
                        ];
                    }
                }
            }
        }

        $data = [
            'label' => $model->name,
            'url'   => $model->url,
        ];

        if ($tmpItems) {
            $data['items'] = $tmpItems;
        }

        $items[] = $data;
    }

    if ($this->theme->is_header_auth) {
        if (\Yii::$app->user->isGuest) {
            $items[] = [
                'label' => "Вход",
                'url'   => \skeeks\cms\helpers\UrlHelper::construct('cms/auth/login'),
            ];
        } else {
            $items[] = [
                'label' => "Мой кабинет",
                'url'   => \Yii::$app->cms->afterAuthUrl,
            ];
        }
    }

    if (\Yii::$app->cms->cmsSite->shopSite->is_show_cart) {
        $items[] = [
            'label' => "Избранное",
            'url'   => \yii\helpers\Url::to(['/shop/favorite']),
        ];
        $items[] = [
            'label' => "Корзина",
            'url'   => \yii\helpers\Url::to(['/shop/cart']),
        ];
    }

}

?>

<div style="display: none; ">
    <?= skeeks\yii2\mmenu\Menu::widget([
        'id'            => 'sx-menu',
        'clientOptions' => [
            //'slidingSubmenus'   =>  false,
            'navbar'     => [
                'title' => 'Меню',
            ],
            //'setSelected'   =>  true,
            'offCanvas'  => [
                'position'     => "right",
                'pageSelector' => "#mm-0",
            ],
            'extensions' =>
                [
                    "fx-panels-slide-100",
                    "position-right",
                    "theme-dark"
                    /*
                    'shadow-page',
                    'shadow-panels',
                    "fx-menu-slide",
                    "fx-panels-slide-0",
                    "border-none", "fullscreen", "position-right"*/
                ],
            'dragOpen'   => [
                'open'     => true,
                'pageNode' => "#mm-0",
            ],

            'navbars' =>
                [
                    "position" => "bottom",
                    'content'  => [
                        \Yii::$app->skeeks->site->cmsSitePhone ? '<a href="tel:'.\Yii::$app->skeeks->site->cmsSitePhone->value.'" class="g-color-white g-color-white--hover">
                            '.\Yii::$app->skeeks->site->cmsSitePhone->value.'
                        </a>' : "",
                    ],
                ],
        ],

        'items' => $items,
    ]); ?>

</div>

<!-- Header -->
<!--u-header--sticky-top-->
<header id="js-header" class="u-shadow-v19 u-header">
    <div class="u-header__section sx-main-menu-wrapper">
        <nav class="js-mega-menu navbar navbar-expand-lg hs-menu-initialized hs-menu-horizontal">
            <div class="container">
                <div class="sx-menu-mobile-top">
                    <!-- Logo -->
                    <a href="<?= \yii\helpers\Url::home(); ?>" title="<?= $this->theme->title; ?>" class="navbar-brand d-block">
                        <img src="<?= $this->theme->mobile_logo ? $this->theme->mobile_logo : $this->theme->logo; ?>" alt="<?= $this->theme->title; ?>">
                    </a>
                    <?php if(\Yii::$app->cms->cmsSite->cmsSitePhone) : ?>
                        <a href="tel:<?= \Yii::$app->cms->cmsSite->cmsSitePhone->value; ?>" class="sx-mobile-phone-top">
                            <?= \Yii::$app->cms->cmsSite->cmsSitePhone->value; ?>
                        </a>
                    <?php endif; ?>

                    <div class="sx-top-search-in-mobile">
                        <? if (\Yii::$app->view->theme->is_show_search_block) : ?>
                            <?php echo $this->render("@app/views/headers/_header-search"); ?>
                        <? endif; ?>
                    </div>
                    <a href="#sx-menu" class="navbar-toggler btn g-px-0 g-valign-middle">
                        <span class="hamburger">
                            <span class="hamburger-box">
                                <span class="hamburger-inner"></span>
                            </span>
                        </span>
                    </a>
                </div>
            </div>
        </nav>
    </div>
</header>

<div class="shop-menu-footer" id="shop-menu-footer">
    <?php \skeeks\assets\unify\base\UnifyIconSimpleLineAsset::register($this); ?>
    <div class="container">
        <div class="shop-menu-footer-inner-wrapper">
            <a class="sx-menu-item <?php echo \Yii::$app->cms->currentTree && \Yii::$app->cms->currentTree->level == 0 ? "sx-active" : ""; ?>" href="<?php echo \yii\helpers\Url::home(); ?>">
                <div class="sx-icon">
                    <i class="icon-home"></i>
                </div>
                <div class="sx-label">Главная</div>
            </a>

            <?php if ($catalogMainCmsTree = \Yii::$app->cms->cmsSite->shopSite->catalogMainCmsTree) : ?>

                <a class="sx-menu-item" href="<?php echo $catalogMainCmsTree->url; ?>">
                    <div class="sx-icon">
                        <i class="icon-list"></i>
                    </div>
                    <div class="sx-label">Каталог</div>
                </a>

            <? endif; ?>

            <?php if (\Yii::$app->cms->cmsSite->shopSite->is_show_cart) : ?>
                <?php
                $favQuery = \Yii::$app->shop->shopUser->getShopFavoriteProducts();
                $favoriteProducts = $favQuery->count();
                ?>
                <a class="sx-menu-item sx-favorite-products" href="<?= \yii\helpers\Url::to(['/shop/favorite']); ?>" data-total="<?= $favoriteProducts; ?>">
                    <div class="sx-icon">
                        <span class="sx-favorite-total-wrapper g-color-white g-bg-primary sx-badge" style="<?= $favoriteProducts > 0 ? "" : "display: none;"; ?>">
                            <span class="sx-favorite-total"><?= $favoriteProducts; ?></span>
                        </span>
                        <i class="icon-heart"></i>
                    </div>
                    <div class="sx-label">Избранное</div>
                </a>

                <a class="sx-menu-item sx-top-cart sx-js-cart <?php echo \Yii::$app->shop->cart->quantity ? "sx-is-full-cart" : ""; ?>" id="sx-top-cart" href="<?= \yii\helpers\Url::to(['/shop/cart']); ?>">
                    <div class="sx-icon">
                        <span class="sx-badge g-color-white g-bg-primary sx-total-quantity">
                            <?= \Yii::$app->shop->cart->quantity ? (int)\Yii::$app->shop->cart->quantity : ""; ?>
                        </span>
                        <i class="icon-basket"></i>
                    </div>
                    <div class="sx-label">Корзина</div>
                </a>
            <? endif; ?>

            <? if ($this->theme->is_header_auth) : ?>
                <? if (\Yii::$app->user->isGuest) : ?>
                    <a class="sx-menu-item " href="<?= \skeeks\cms\helpers\UrlHelper::construct('cms/auth/login'); ?>">
                        <div class="sx-icon">
                            <i class="icon-user"></i>
                        </div>
                        <div class="sx-label">Вход</div>
                    </a>
                <? else : ?>
                    <a class="sx-menu-item sx-user-mobile-menu-trigger" href="<?= \Yii::$app->cms->afterAuthUrl; ?>">
                        <div class="sx-icon">
                            <i class="icon-user"></i>
                        </div>
                        <div class="sx-label">Профиль</div>
                    </a>
                <? endif; ?>
            <? endif; ?>
        </div>
    </div>
</div>