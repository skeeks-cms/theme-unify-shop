<?php
/**
 * @link https://cms.skeeks.com/
 * @copyright Copyright (c) 2010 SkeekS
 * @license https://cms.skeeks.com/license/
 * @author Semenov Alexander <semenov@skeeks.com>
 */
/* @var $this yii\web\View */
/* @see https://htmlstream.com/public/preview/unify-v2.6.1/unify-main/shortcodes/headers/classic-header--topbar-1.html */

\skeeks\assets\unify\base\UnifyHsDropdownAsset::register($this);
\skeeks\assets\unify\base\UnifyHsHeaderAsset::register($this);
$this->registerJs(<<<JS

// initialization of HSDropdown component
  $.HSCore.components.HSDropdown.init($('[data-dropdown-target]'), {
    afterOpen: function(){
      $(this).find('input[type="search"]').focus();
    }
  });

$(window).on('load', function () {
    // initialization of header
    $.HSCore.components.HSHeader.init($('#js-header'));
    $.HSCore.helpers.HSHamburgers.init('.hamburger');

    // initialization of HSMegaMenu component
    $('.js-mega-menu').HSMegaMenu({
        event: 'hover',
        pageContainer: $('.container'),
        breakpoint: 991
    });


    $('#dropdown-megamenu').HSMegaMenu({
        event: 'hover',
        pageContainer: $('.container'),
        breakpoint: 767
    });

});
JS
);
?>

<!-- Header -->
<!--u-header--sticky-top-->
<header id="js-header" class="u-header  u-header--toggle-section u-header--change-appearance u-shadow-v19" data-header-fix-moment="80">
    <!-- Top Bar -->
    <!--u-header__section--hidden -->
    <div class="u-header__section u-header__section--dark g-bg-black g-transition-0_3 g-py-7">
        <div class="container">
            <div class="row flex-column flex-sm-row justify-content-between align-items-center text-uppercase g-font-weight-600 g-color-white g-font-size-12 g-mx-0--lg">
                <!--<div class="col-auto">
                    <ul class="list-inline mb-0">
                        <li class="list-inline-item">
                            <a href="#!" class="g-color-white g-color-primary--hover g-pa-3">
                                <i class="fa fa-facebook"></i>
                            </a>
                        </li>
                        <li class="list-inline-item">
                            <a href="#!" class="g-color-white g-color-primary--hover g-pa-3">
                                <i class="fa fa-twitter"></i>
                            </a>
                        </li>
                        <li class="list-inline-item">
                            <a href="#!" class="g-color-white g-color-primary--hover g-pa-3">
                                <i class="fa fa-tumblr"></i>
                            </a>
                        </li>
                        <li class="list-inline-item">
                            <a href="#!" class="g-color-white g-color-primary--hover g-pa-3">
                                <i class="fa fa-pinterest-p"></i>
                            </a>
                        </li>
                        <li class="list-inline-item">
                            <a href="#!" class="g-color-white g-color-primary--hover g-pa-3">
                                <i class="fa fa-google"></i>
                            </a>
                        </li>
                    </ul>
                </div>-->

                <div class="col-auto">
                    <i class="fa fa-phone g-font-size-18 g-valign-middle g-color-white g-mr-10 g-mt-minus-2"></i>
                    <a href="tel:<?= $this->theme->phone; ?>" class="g-color-white g-color-white--hover">
                        <?= $this->theme->phone; ?>
                    </a>
                </div>

                <div class="col-auto">
                    <i class="fa fa-envelope g-font-size-18 g-valign-middle g-color-white g-mr-10 g-mt-minus-2"></i>
                    <a href="mailto:<?= $this->theme->email; ?>" class="g-color-white g-color-white--hover">
                        <?= $this->theme->email; ?>
                    </a>
                </div>

                <div class="col-auto g-pos-rel">
                    <ul class="list-inline g-overflow-hidden g-pt-1 g-mx-minus-4 mb-0">
                        <? if (\Yii::$app->user->isGuest) : ?>
                            <li class="list-inline-item g-mx-4">
                                <i class="fa fa-user g-font-size-18 g-valign-middle g-color-white g-mr-10 g-mt-minus-2"></i>
                                <a class="g-color-white g-color-white--hover" href="<?= \skeeks\cms\helpers\UrlHelper::construct('cms/auth/login'); ?>">Вход</a>
                            </li>
                            <li class="list-inline-item g-mx-4">|</li>
                            <li class="list-inline-item g-mx-4">
                                <a class="g-color-white g-color-white--hover" href="<?= \skeeks\cms\helpers\UrlHelper::construct('cms/auth/register'); ?>">Регистрация</a>
                            </li>
                        <? else : ?>
                            <li class="list-inline-item g-mx-4">
                                <i class="fa fa-user g-font-size-18 g-valign-middle g-color-white g-mr-10 g-mt-minus-2"></i>
                                <a class="g-color-white g-color-white--hover" href="<?= \yii\helpers\Url::to(['/cms/upa-personal/update']) ?>"><?= \Yii::$app->user->identity->displayName; ?></a>
                            </li>
                        <? endif; ?>

                    </ul>
                </div>

                <div class="col-auto">
                    <!-- Basket -->
                    <div class="u-basket d-inline-block g-valign-middle g-pt-2">
                        <a href="#!" id="basket-bar-invoker" class="u-icon-v1 g-color-white g-text-underline--none--hover g-width-20 g-height-20 g-mr-40" aria-controls="basket-bar" aria-haspopup="true" aria-expanded="false" data-dropdown-event="hover" data-dropdown-target="#basket-bar"
                           data-dropdown-type="css-animation" data-dropdown-duration="300" data-dropdown-hide-on-scroll="false" data-dropdown-animation-in="fadeIn" data-dropdown-animation-out="fadeOut">
                            <span class="u-badge-v1--sm g-color-white g-bg-primary g-rounded-50x">3</span>
                            <i class="fa fa-shopping-cart"></i>
                        </a>

                        <div id="basket-bar" class="u-basket__bar u-dropdown--css-animation u-dropdown--hidden g-text-transform-none g-brd-top g-brd-2 g-brd-primary g-color-main g-mt-13" aria-labelledby="basket-bar-invoker">
                            <div class="js-scrollbar g-height-280">
                                <!-- Product -->
                                <div class="u-basket__product g-brd-white-opacity-0_3">
                                    <div class="row align-items-center no-gutters">
                                        <div class="col-4 g-pr-20">
                                            <a href="#!" class="u-basket__product-img">
                                                <img src="../../../assets/img-temp/150x150/img1.jpg" alt="Image Description">
                                            </a>
                                        </div>

                                        <div class="col-8">
                                            <h6 class="g-font-weight-600 g-mb-0">

                                                <a href="#!" class="g-color-white g-color-white--hover g-text-underline--none--hover">Black Glasses</a>

                                            </h6>
                                            <small class="g-color-gray-dark-v5 g-font-size-14">1 x $400.00</small>
                                        </div>
                                    </div>

                                    <button class="u-basket__product-remove" type="button">&times;</button>
                                </div>
                                <!-- End Product -->

                                <!-- Product -->
                                <div class="u-basket__product g-brd-white-opacity-0_3">
                                    <div class="row align-items-center no-gutters">
                                        <div class="col-4 g-pr-20">
                                            <a href="#!" class="u-basket__product-img">
                                                <img src="../../../assets/img-temp/150x150/img2.jpg" alt="Image Description">
                                            </a>
                                        </div>

                                        <div class="col-8">
                                            <h6 class="g-font-weight-600 g-mb-0">

                                                <a href="#!" class="g-color-white g-color-white--hover g-text-underline--none--hover">Black Glasses</a>

                                            </h6>
                                            <small class="g-color-gray-dark-v5 g-font-size-14">1 x $400.00</small>
                                        </div>
                                    </div>

                                    <button class="u-basket__product-remove" type="button">&times;</button>
                                </div>
                                <!-- End Product -->

                                <!-- Product -->
                                <div class="u-basket__product g-brd-white-opacity-0_3">
                                    <div class="row align-items-center no-gutters">
                                        <div class="col-4 g-pr-20">
                                            <a href="#!" class="u-basket__product-img">
                                                <img src="../../../assets/img-temp/150x150/img3.jpg" alt="Image Description">
                                            </a>
                                        </div>

                                        <div class="col-8">
                                            <h6 class="g-font-weight-600 g-mb-0">

                                                <a href="#!" class="g-color-white g-color-white--hover g-text-underline--none--hover">Black Glasses</a>

                                            </h6>
                                            <small class="g-color-gray-dark-v5 g-font-size-14">1 x $400.00</small>
                                        </div>
                                    </div>

                                    <button class="u-basket__product-remove" type="button">&times;</button>
                                </div>
                                <!-- End Product -->

                                <!-- Product -->
                                <div class="u-basket__product g-brd-white-opacity-0_3">
                                    <div class="row align-items-center no-gutters">
                                        <div class="col-4 g-pr-20">
                                            <a href="#!" class="u-basket__product-img">
                                                <img src="../../../assets/img-temp/150x150/img4.jpg" alt="Image Description">
                                            </a>
                                        </div>

                                        <div class="col-8">
                                            <h6 class="g-font-weight-600 g-mb-0">

                                                <a href="#!" class="g-color-white g-color-white--hover g-text-underline--none--hover">Black Glasses</a>

                                            </h6>
                                            <small class="g-color-gray-dark-v5 g-font-size-14">1 x $400.00</small>
                                        </div>
                                    </div>

                                    <button class="u-basket__product-remove" type="button">&times;</button>
                                </div>
                                <!-- End Product -->
                            </div>

                            <div class="g-brd-top g-brd-white-opacity-0_3 g-pa-15 g-pb-20">
                                <div class="d-flex flex-row align-items-center justify-content-between g-letter-spacing-1 g-font-size-16 g-mb-15">
                                    <strong class="text-uppercase g-font-weight-600 g-color-white">Subtotal</strong>
                                    <strong class="g-color-primary g-font-weight-600">$1200.00</strong>
                                </div>

                                <div class="d-flex flex-row align-items-center justify-content-between g-font-size-18">
                                    <a href="#!" class="btn u-btn-outline-primary rounded-0 g-width-120">View Cart</a>
                                    <a href="#!" class="btn u-btn-primary rounded-0 g-width-120">Checkout</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- End Basket -->

                    <!-- Search -->
                    <div class="d-inline-block g-valign-middle g-pos-rel g-top-minus-1">
                        <a href="#!" class="g-font-size-18 g-color-white g-color-primary--hover" aria-haspopup="true" aria-expanded="false" aria-controls="searchform-1" data-dropdown-target="#searchform-1" data-dropdown-type="css-animation" data-dropdown-duration="300" data-dropdown-animation-in="fadeInUp"
                           data-dropdown-animation-out="fadeOutDown">
                            <i class="fa fa-search"></i>
                        </a>

                        <!-- Search Form -->
                        <form id="searchform-1" class="u-searchform-v1 u-dropdown--css-animation u-dropdown--hidden g-bg-black g-pa-10 g-mt-10 g-box-shadow-none">
                            <div class="input-group g-brd-primary--focus">
                                <input class="form-control rounded-0 u-form-control g-brd-gray-light-v3" type="search" placeholder="Enter Your Search Here...">

                                <div class="input-group-addon p-0">
                                    <button class="btn rounded-0 btn-primary btn-md g-font-size-14 g-px-18" type="submit">Go</button>
                                </div>
                            </div>
                        </form>
                        <!-- End Search Form -->
                    </div>
                    <!-- End Search -->
                </div>
            </div>
        </div>
    </div>
    <!-- End Top Bar -->

    <div class="u-header__section u-header__section--light g-bg-white-opacity-0_8" data-header-fix-moment-exclude="g-bg-white-opacity-0_8" data-header-fix-moment-classes="d-none g-bg-white u-shadow-v18">
        <nav class="navbar navbar-expand-lg">
            <div class="container">
                <!-- Responsive Toggle Button -->
                <button class="navbar-toggler navbar-toggler-right btn g-line-height-1 g-brd-none g-pa-0 g-pos-abs g-top-3 g-right-0" type="button" aria-label="Toggle navigation" aria-expanded="false" aria-controls="navBar" data-toggle="collapse" data-target="#navBar">
              <span class="hamburger hamburger--slider">

            <span class="hamburger-box">

              <span class="hamburger-inner"></span>
              </span>
              </span>
                </button>
                <!-- End Responsive Toggle Button -->

                <!-- Logo -->
                <a href="<?= \yii\helpers\Url::home(); ?>" title="<?= $this->theme->title; ?>" class="navbar-brand">
                    <img src="<?= $this->theme->logo; ?>" alt="<?= $this->theme->title; ?>">
                </a>
                <!-- End Logo -->

                <!-- Navigation -->
                <div class="collapse navbar-collapse align-items-center flex-sm-row g-pt-10 g-pt-5--lg" id="navBar">

                    <?= \skeeks\cms\cmsWidgets\treeMenu\TreeMenuCmsWidget::widget([
                        'namespace'       => 'menu-top',
                        'viewFile'        => '@app/views/widgets/TreeMenuCmsWidget/menu-top',
                        'label'           => 'Верхнее меню',
                        'level'           => '1',
                        'enabledRunCache' => \skeeks\cms\components\Cms::BOOL_N,
                    ]); ?>

                </div>
                <!-- End Navigation -->
            </div>
        </nav>
    </div>
</header>
<!-- End Header -->
