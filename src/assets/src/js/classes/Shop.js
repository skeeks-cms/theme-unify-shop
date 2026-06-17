/*!
 * @author Semenov Alexander <semenov@skeeks.com>
 * @link http://skeeks.com/
 * @copyright 2010 SkeekS (СкикС)
 * @date 21.09.2015
 */
(function (sx, $, _) {
    /**
     * Маленькая верхняя корзина
     */
    sx.classes.shop.SmallCart = sx.classes.shop.CartPjax.extend({
        _init: function () {
            var self = this;
            this.applyParentMethod(sx.classes.shop.CartPjax, '_init', []);
            this.bind('update', function () {
                //Если на странице показывается только одна корзина, то при добавлении в нее товара, будет происходить ее октрытие
                if (_.size(self.Shop.carts) == 1) {
                    $(".sx-cart-small-open-trigger").click();
                }
                var quantity = self.Shop.get('cartData').quantity;
                var countShopBaskets = self.Shop.get('cartData').countShopBaskets;

                $('.sx-count-baskets').empty().append(countShopBaskets);
                $('.sx-count-quantity').empty().append(quantity);

                if (countShopBaskets > 0) {
                    $('.sx-count-baskets').fadeIn();
                } else {
                    $('.sx-count-baskets').fadeOut();
                }
                if (quantity > 0) {
                    $('.sx-count-quantity').fadeIn();
                } else {
                    $('.sx-count-quantity').fadeOut();
                }
            });
        }
    });
    /**
     * Полная корзина на странице заказа
     */
    sx.classes.shop.FullCart = sx.classes.shop.CartPjax.extend({
        _init: function () {
            this.applyParentMethod(sx.classes.shop.CartPjax, '_init', []);

            this.on('update', function () {
                sx.Shop.trigger("update");
            });
        },

        _onDomReady: function () {
            $('body').on('change', '#sx-cart-full input.sx-basket-quantity', function () {
                sx.Shop.updateBasket($(this).data('basket_id'), $(this).val());
            });
        }
    });
    /**
     * Использование стандартного ajax Handler
     */
    sx.classes.shop.App = sx.classes.shop._App.extend({
        /**
         * @returns {sx.classes.AjaxQuery}
         */
        ajaxQuery: function () {
            var ajax = sx.ajax.preparePostQuery('/');
            new sx.classes.AjaxHandlerStandartRespose(ajax, {
                'enableBlocker': false
            });
            return ajax;
        }
    });


    /**
     * Электронная коммерция
     */
    /*$(function () {*/
        window.dataLayer = window.dataLayer || [];

        sx.onReady(function () {
            var ensureRightCart = function () {
                var jRightCart = $("#sx-cart-right");
                if (jRightCart.length) {
                    return jRightCart;
                }

                var cartUrl = sx.Shop.get("cart-url") || "/shop/cart";
                jRightCart = $(
                    '<div id="sx-cart-right" class="sx-cart-right-auto">' +
                        '<div class="sx-inner">' +
                            '<div class="sx-cart-header">' +
                                '<div class="h1">Корзина</div>' +
                                '<a href="#" class="sx-close-mobile" aria-label="Закрыть">&times;</a>' +
                            '</div>' +
                            '<div class="sx-cart-body">' +
                                '<div class="sx-order-items"></div>' +
                            '</div>' +
                            '<div class="sx-cart-footer">' +
                                '<div class="sx-order-result">' +
                                    '<div class="col-12 sx-order-result-block sx-hidden">' +
                                        '<div class="float-right sx-money-items" data-value="0"></div>' +
                                        '<div class="pull-left">Сумма</div>' +
                                    '</div>' +
                                    '<div class="g-my-10 col-12 sx-order-result-itogo">' +
                                        '<div class="float-right size-20 sx-money" data-value="0"></div>' +
                                        '<div class="pull-left">Итого</div>' +
                                    '</div>' +
                                '</div>' +
                                '<div class="col-12">' +
                                    '<a href="' + cartUrl + '" class="btn btn-xxl btn-block btn-primary" data-pjax="0">Оформить заказ</a>' +
                                '</div>' +
                            '</div>' +
                        '</div>' +
                    '</div>'
                );
                $("body").append(jRightCart);

                return jRightCart;
            };

            var getMoneyAmount = function (money) {
                return money ? Number(money.amount) : 0;
            };

            var getMoneyFormatted = function (money) {
                return money ? money.convertAndFormat : "";
            };

            var updateRightCartCheckoutButton = function (jRightCart, cart) {
                var totalFormatted = getMoneyFormatted(cart.money);
                var jButton = $(".sx-right-cart-checkout, .sx-cart-footer a.btn[href]:last", jRightCart).first();

                if (!jButton.length) {
                    return;
                }

                if (!jButton.data("checkout-label")) {
                    jButton.data("checkout-label", $.trim(jButton.text()) || "Оформить заказ");
                }

                jButton.empty().append(jButton.data("checkout-label") + (totalFormatted ? " - " + totalFormatted : ""));
            };

            var updateRightCart = function () {
                var jRightCart = $("#sx-cart-right");
                if (!jRightCart.length) {
                    return;
                }

                var cart = sx.Shop.getCartData ? sx.Shop.getCartData() : sx.Shop.get("cartData");
                if (!cart) {
                    return;
                }

                if (cart.html_order_items) {
                    $(".sx-order-items", jRightCart).empty().append(cart.html_order_items);
                }

                if (cart.moneyItems) {
                    $(".sx-money-items", jRightCart)
                        .data("value", cart.moneyItems.amount)
                        .empty()
                        .append(cart.moneyItems.convertAndFormat);
                }

                if (cart.money) {
                    $(".sx-money", jRightCart)
                        .data("value", cart.money.amount)
                        .empty()
                        .append(cart.money.convertAndFormat);
                }

                $(".sx-order-result-itogo", jRightCart).addClass("sx-hidden");

                var isMoneyItemsDifferent = getMoneyAmount(cart.moneyItems) != getMoneyAmount(cart.money);
                $(".sx-money-items", jRightCart).closest(".sx-order-result-block").toggleClass("sx-hidden", !isMoneyItemsDifferent);
                updateRightCartCheckoutButton(jRightCart, cart);
            };

            var openRightCart = function () {
                var jRightCart = ensureRightCart();
                var requestFrame = window.requestAnimationFrame || function (callback) {
                    return window.setTimeout(callback, 0);
                };

                updateRightCart();
                $("body").addClass("sx-right-cart-opened");

                requestFrame(function () {
                    requestFrame(function () {
                        jRightCart.addClass("sx-open");
                    });
                });
            };

            sx.Shop.on("beforeAddProduct", openRightCart);
            sx.Shop.on("beforeAddProducts", openRightCart);
            sx.Shop.on("change", updateRightCart);

            $("body").on("click", "#sx-cart-right .sx-close-mobile", function () {
                $("#sx-cart-right").removeClass("sx-open");
                $("body").removeClass("sx-right-cart-opened");
                return false;
            });

            $(document).on("mouseup", function (e) {
                var jRightCart = $("#sx-cart-right");
                if (jRightCart.hasClass("sx-open") && !jRightCart.is(e.target) && jRightCart.has(e.target).length === 0) {
                    jRightCart.removeClass("sx-open");
                    $("body").removeClass("sx-right-cart-opened");
                }
            });

            $("body").on("click", "#sx-cart-right .sx-remove-order-item", function (e) {
                e.stopImmediatePropagation();

                var jItem = $(this).closest(".sx-order-item");
                var id = jItem.data("id");

                sx.block(jItem);

                var ajaxQuery = sx.Shop.createAjaxRemoveBasket(id);
                ajaxQuery.onSuccess(function () {
                    jItem.slideUp();
                });
                ajaxQuery.execute();
            });

            $("body").on("change", "#sx-cart-right input.sx-basket-quantity", function (e) {
                e.stopImmediatePropagation();

                var ajaxQuery = sx.Shop.createAjaxUpdateBasket($(this).data("basket_id"), $(this).val());
                ajaxQuery.execute();
            });

            //Просмотр страницы товара
            sx.Shop.on("detail", function (e, data) {
                dataLayer.push({
                    "ecommerce": {
                        "currencyCode": sx.Shop.get("currencyCode"),
                        "detail": {
                            "products": [
                                data
                            ]
                        }

                    }
                });
            });

            //Просмотр добавление товара в корзину
            sx.Shop.on("add", function (e, data) {
                var add = {};
                if (data.product) {
                    add = {
                        'products' : [
                            data.product
                        ]
                    }
                } else {
                    add = {
                        'products' : data.products
                    }
                }
                
                dataLayer.push({
                    "ecommerce": {
                        "currencyCode": sx.Shop.get("currencyCode"),
                        "add": add
                    }
                });
            });

            //Просмотр добавление товара в корзину
            sx.Shop.on("remove", function (e, data) {
                dataLayer.push({
                    "ecommerce": {
                        "currencyCode": sx.Shop.get("currencyCode"),
                        "remove": {
                            "products": [
                                data.product
                            ]
                        }
                    }
                });
            });

            //Просмотр добавление товара в корзину
            sx.Shop.on("purchase", function (e, data) {
                dataLayer.push({
                    "ecommerce": {
                        "currencyCode": sx.Shop.get("currencyCode"),
                        "purchase": {
                            "actionField": {
                                'id': data.order.id,
                                'revenue': data.order.money.amount,
                            },
                            "products": data.products
                        }
                    }
                });
            });
        });
    /*});*/


})
(sx, sx.$, sx._);
