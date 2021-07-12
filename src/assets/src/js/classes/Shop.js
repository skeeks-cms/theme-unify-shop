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
    
    
    $("body").on("mouseenter", ".sx-product-image", function() {

        var secondImgSrc = $(this).data("second-src");

        if (secondImgSrc) {
            //console.log($(this).attr("src"));
            //console.log($(this).data("second-src"));
            $(this).attr("data-first-src", $(this).attr("src"));
            $(this).attr("src", secondImgSrc)
        }
    });

    $("body").on("mouseleave", ".sx-product-image", function() {
        var fiestImgSrc = $(this).data("first-src");
        if (fiestImgSrc) {
            console.log(fiestImgSrc);
            /*$(this).attr("data-first-src", $(this).attr("src"));*/
            $(this).attr("src", fiestImgSrc)
        }
    });
    
})(sx, sx.$, sx._);