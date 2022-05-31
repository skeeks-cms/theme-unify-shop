(function(sx, $, _)
{
    sx.createNamespace('classes.cartv2', sx);

    sx.classes.cartv2.Checkout = sx.classes.Component.extend({

        _init: function()
        {},


        updateOrderResult: function() {

            var cart = sx.Shop.getCartData();

            this._updateOrderResultBlock("sx-money-items", cart.moneyItems.amount, cart.moneyItems.convertAndFormat);
            this._updateOrderResultBlock("sx-money-delivery", cart.moneyDelivery.amount, cart.moneyDelivery.convertAndFormat);
            this._updateOrderResultBlock("sx-money-vat", cart.moneyVat.amount, cart.moneyVat.convertAndFormat);
            this._updateOrderResultBlock("sx-money-discount", cart.moneyDiscount.amount, cart.moneyDiscount.convertAndFormat);
            this._updateOrderResultBlock("sx-money", cart.moneyDiscount.amount, cart.money.convertAndFormat);
            this._updateOrderResultBlock("sx-weight", cart.weight.value, cart.weight.convertAndFormat);

            return this;
        },

        _updateOrderResultBlock: function(css_class, value, formatedValue) {
            var jBlocks = $("." + css_class);
            value = Number(value);

            jBlocks.each(function() {
                var currentValue = Number($(this).data("value"));
                if (value != currentValue) {
                    //Если значение меняется
                    $(this).empty().append(formatedValue)
                    $(this).data("value", "value");
                }

                var jBlock = $(this).closest(".sx-order-result-block");

                if (jBlock.length > 0) {
                    if (value > 0) {
                        jBlock.removeClass("sx-hidden");
                    } else {
                        jBlock.addClass("sx-hidden");
                    }
                }
            });

            return this;
        },

        _onDomReady: function()
        {
            var self = this;

            $("#sx-phone").mask("+7 999 999-99-99");
            $('.form-group').FloatLabel();

            sx.Shop.on("change", function() {
                self.updateOrderResult();
            });

            $("body").on("click", ".sx-remove-order-item", function() {
                var jItem = $(this).closest(".sx-order-item");
                var ID = jItem.data("id");

                sx.block(jItem);

                var ajaxQuery = sx.Shop.createAjaxRemoveBasket(ID);
                ajaxQuery.onSuccess(function (e, data) {
                    jItem.slideUp();
                });
                ajaxQuery.execute();
            });

            $('body').on('change', 'input.sx-basket-quantity', function () {
                var ajaxQuery = sx.Shop.createAjaxUpdateBasket($(this).data('basket_id'), $(this).val());
                ajaxQuery.on("success", function() {

                });
                ajaxQuery.execute();
            });

            $('body').on("click", '.sx-paysystem', function() {

                var jPaySystem = $(this);
                var payId = $(this).data("id");

                $('.sx-paysystem').removeClass("sx-checked");
                $('.sx-paysystem .sx-checked-icon').empty();
                
                jPaySystem.addClass("sx-checked");
                $(".sx-checked-icon", jPaySystem).append($(".sx-checked-icon", jPaySystem).data("icon"));
            });
            
            $('body').on("click", '.sx-delivery', function() {

                var jPaySystem = $(this);
                var payId = $(this).data("id");

                $('.sx-delivery').removeClass("sx-checked");
                $('.sx-delivery .sx-checked-icon').empty();
                
                jPaySystem.addClass("sx-checked");
                $(".sx-checked-icon", jPaySystem).append($(".sx-checked-icon", jPaySystem).data("icon"));
                
                
                var ajaxQuery = sx.Shop.createAjaxUpdateOrder({
                    'data' : {
                        'shop_delivery_id' : payId
                    }
                });

                ajaxQuery.on("success", function() {

                });

                ajaxQuery.execute();
                
            });

            //Изменение любого поля по заказу - сохранит данные сразу
            $('body').on('change', '.sx-save-after-change', function () {
                var field = $(this).data('field');
                var val = $(this).val();

                var data = {};
                data[field] = val;

                var ajaxQuery = sx.Shop.createAjaxUpdateOrder({
                    'data' : data
                });

                ajaxQuery.on("success", function() {

                });

                ajaxQuery.execute();
            });
        }
    });
})(sx, sx.$, sx._);