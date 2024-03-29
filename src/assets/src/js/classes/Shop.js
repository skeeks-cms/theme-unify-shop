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