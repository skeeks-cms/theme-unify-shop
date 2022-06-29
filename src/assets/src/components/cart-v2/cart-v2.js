(function (sx, $, _) {
    sx.createNamespace('classes.cartv2', sx);


    sx.classes.cartv2.Checkout = sx.classes.Component.extend({

        _init: function () {
        },


        updateOrderResult: function () {

            var self = this;
            
            var cart = sx.Shop.getCartData();

            this._updateOrderResultBlock("sx-money-items", cart.moneyItems.amount, cart.moneyItems.convertAndFormat);
            this._updateOrderResultBlock("sx-money-delivery", cart.moneyDelivery.amount, cart.moneyDelivery.convertAndFormat);
            this._updateOrderResultBlock("sx-money-vat", cart.moneyVat.amount, cart.moneyVat.convertAndFormat);
            this._updateOrderResultBlock("sx-money-discount", cart.moneyDiscount.amount, cart.moneyDiscount.convertAndFormat);
            this._updateOrderResultBlock("sx-money", cart.money.amount, cart.money.convertAndFormat);
            this._updateOrderResultBlock("sx-weight", cart.weight.value, cart.weight.convertAndFormat);

            //Включена бесплатная доставка
            if (cart.freeDelivery.is_active) {
                if (cart.freeDelivery.sx_need_price.amount > 0) {
                    $(".sx-free-delivery").show();
                    $(".sx-free-delivery-success").hide();

                    var currentValue = $(".sx-need-price").text();
                    if (currentValue != cart.freeDelivery.sx_need_price.convertAndFormat) {
                        $(".sx-need-price").empty().append(cart.freeDelivery.sx_need_price.convertAndFormat);

                        setTimeout(function () {
                            $(".sx-need-price").addClass("sx-blink-text");
                        }, 400);

                        setTimeout(function () {
                            $(".sx-need-price").removeClass("sx-blink-text");
                        }, 900);

                    }


                    /*$(".sx-delivery-btn-price").each(function() {
                        $(this).empty().append($(this).data("money"));
                    });*/

                } else {
                    $(".sx-free-delivery").hide();
                    $(".sx-free-delivery-success").show();

                    /*var zeroMoney = $("#sx-money-zero").text();
                    $(".sx-delivery-btn-price").each(function() {
                        $(this).empty().append(zeroMoney);
                    });*/
                }
            } else {
                $(".sx-free-delivery").hide();
                $(".sx-free-delivery-success").hide();
            }

            cart.deliveries.forEach(function (item) {
                var jDelivery = $(".sx-delivery[data-id=" + item.id + "]");
                var jDeliveryMoney = $(".sx-delivery-btn-price", jDelivery);

                if (jDeliveryMoney.length) {

                    var currentMoneyAmount = jDeliveryMoney.data("money-current-amount");

                    if (currentMoneyAmount != item.sx_need_price.amount) {
                        jDeliveryMoney.empty().append(item.sx_need_price.convertAndFormat);
                        jDeliveryMoney.data("money-current-amount", item.sx_need_price.amount);

                        setTimeout(function () {
                            jDeliveryMoney.addClass("sx-blink-text");
                        }, 400);

                        setTimeout(function () {
                            jDeliveryMoney.removeClass("sx-blink-text");
                        }, 900);
                    }
                }
            });

            cart.paysystems.forEach(function (item) {
                var jPaysystem = $(".sx-paysystem[data-id=" + item.id + "]");
                if (item.is_allow) {
                    jPaysystem.data("is_allow", 1);
                    jPaysystem.data("is_allow_message", item.is_allow_message);
                    jPaysystem.removeClass("sx-disabled");
                } else {

                    //Если выбран этот способ оплаты
                    if (jPaysystem.hasClass("sx-checked")) {
                        var selectOther = $(".sx-paysystem:not(.sx-disabled):eq(0)", self.getJPaysystem());
                        if (selectOther.length) {
                            selectOther.click();
                        }
                    }

                    jPaysystem.data("is_allow", 0);
                    jPaysystem.data("is_allow_message", item.is_allow_message);
                    jPaysystem.addClass("sx-disabled");


                }
            });


            return this;
        },

        _updateOrderResultBlock: function (css_class, value, formatedValue) {
            var jBlocks = $("." + css_class);
            value = Number(value);

            jBlocks.each(function () {
                var currentValue = Number($(this).data("value"));

                if (value != currentValue) {

                    //Если значение меняется
                    var jChangeBlock = $(this);
                    jChangeBlock.empty().append(formatedValue);
                    jChangeBlock.data("value", value);

                    setTimeout(function () {
                        jChangeBlock.addClass("sx-blink-text");
                    }, 400);

                    setTimeout(function () {
                        jChangeBlock.removeClass("sx-blink-text");
                    }, 900);
                }

                var jBlock = $(this).closest(".sx-order-result-block");

                if (jBlock.length > 0) {
                    if (value > 0) {
                        jBlock.removeClass("sx-hidden");
                    } else {
                        jBlock.addClass("sx-hidden");
                        /*setTimeout(function() {
                            jBlock.addClass("sx-hidden");
                        }, 2000);*/

                    }
                }
            });

            return this;
        },

        _onDomReady: function () {
            var self = this;

            $("#sx-phone").mask("+7 999 999-99-99");
            $('.form-group').FloatLabel();

            sx.Shop.on("change", function () {
                self.updateOrderResult();
            });

            $("body").on("click", ".sx-remove-order-item", function () {
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
                ajaxQuery.on("success", function () {

                });
                ajaxQuery.execute();
            });

            $('body').on("click", '.sx-paysystem', function () {

                self.hideGlobalError();


                var jPaySystem = $(this);
                var payId = $(this).data("id");

                if (jPaySystem.hasClass("sx-disabled")) {

                    if (!jPaySystem.hasClass("sx-error-showed")) {
                        jPaySystem.addClass("sx-error-showed");

                        jPaySystem.tooltip({
                            'title': jPaySystem.data("is_allow_message"),
                            'placement': 'right',
                            'template': '<div class="tooltip sx-error-tooltip" role="tooltip"><div class="arrow"></div><div class="tooltip-inner"></div></div>',
                        });
                        jPaySystem.tooltip("show");
                        jPaySystem.on("hide.bs.tooltip", function() {
                            setTimeout(function () {
                                jPaySystem.removeClass("sx-error-showed");
                                jPaySystem.tooltip("dispose");
                            }, 200);
                        });

                        /*setTimeout(function () {
                            jPaySystem.tooltip("hide");
                        }, 3000);*/
                    }


                    return false;
                }

                $('.sx-paysystem').removeClass("sx-checked");
                $('.sx-paysystem .sx-checked-icon').empty();

                $(".sx-paysystem-tab", self.getJPaysystem()).hide();
                var jPaysystemTab = $(".sx-paysystem-tab[data-id=" + payId + "]", self.getJPaysystem());
                jPaysystemTab.show();


                jPaySystem.addClass("sx-checked");
                $(".sx-checked-icon", jPaySystem).append($(".sx-checked-icon", jPaySystem).data("icon"));

                //Если обработчик не указан
                var ajaxQuery = sx.Shop.createAjaxUpdateOrder({
                    'data': {
                        'shop_pay_system_id': payId,
                    }
                });

                var Handler = new sx.classes.AjaxHandlerStandartRespose(ajaxQuery);

                Handler.on("success", function () {
                });
                ajaxQuery.execute();

            });

            //Если есть данные для обработчика доставки
            $('body').on("submit", '.sx-delivery-tab form', function () {

                self.hideGlobalError();


                var payId = $(this).closest(".sx-delivery-tab").data("id");
                //Если обработчик не указан
                var ajaxQuery = sx.Shop.createAjaxUpdateOrder({
                    'data': {
                        'shop_delivery_id': payId,
                        'delivery_nandler': $(this).serialize()
                    }
                });

                var Handler = new sx.classes.AjaxHandlerStandartRespose(ajaxQuery);

                Handler.on("success", function () {
                });
                ajaxQuery.execute();

                return false;
            });

            $('body').on("click", '.sx-delivery', function () {
                self.hideGlobalError();

                var jPaySystem = $(this);
                var payId = $(this).data("id");

                if (jPaySystem.hasClass("sx-disabled")) {

                    if (jPaySystem.hasClass("sx-disabled")) {

                        if (!jPaySystem.hasClass("sx-error-showed")) {
                            jPaySystem.addClass("sx-error-showed");

                            jPaySystem.tooltip({
                                'title': jPaySystem.data("is_allow_message"),
                                'trigger': 'focus',
                                'placement': 'right',
                                'template': '<div class="tooltip sx-error-tooltip" role="tooltip"><div class="arrow"></div><div class="tooltip-inner"></div></div>',
                            });
                            jPaySystem.tooltip("show");

                            setTimeout(function () {
                                jPaySystem.tooltip("hide");
                                setTimeout(function () {
                                    jPaySystem.removeClass("sx-error-showed");
                                    jPaySystem.tooltip("dispose");
                                }, 200);
                            }, 3000);
                        }


                        return false;
                    }
                    
                    return false;
                }

                $('.sx-delivery').removeClass("sx-checked");
                $('.sx-delivery .sx-checked-icon').empty();

                jPaySystem.addClass("sx-checked");
                $(".sx-checked-icon", jPaySystem).append($(".sx-checked-icon", jPaySystem).data("icon"));

                $(".sx-delivery-tab", self.getJDelivery()).hide();
                var jDeliveryTab = $(".sx-delivery-tab[data-id=" + payId + "]", self.getJDelivery());
                jDeliveryTab.show();

                //Есть ли данные для обработчика
                var deliveryForm = $("form", jDeliveryTab);
                if (deliveryForm.length > 0) {
                    deliveryForm.trigger("change-delivery");
                } else {

                    //Если обработчик не указан
                    var ajaxQuery = sx.Shop.createAjaxUpdateOrder({
                        'data': {
                            'shop_delivery_id': payId
                        }
                    });

                    var Handler = new sx.classes.AjaxHandlerStandartRespose(ajaxQuery);

                    Handler.on("success", function () {
                    });
                    ajaxQuery.execute();
                }
            });

            //Изменение любого поля по заказу - сохранит данные сразу
            $('body').on('click', '.btn-submit-order', function () {
                var jBtn = $(this);
                if (jBtn.hasClass("sx-disabled")) {
                    return false;
                }
                jBtn.addClass("sx-disabled");
                jBtn.empty().append(jBtn.data("process"));

                var jBlocker = sx.block(".sx-cart-layout");

                //Можно для начала проверить на клиенте данные потом отправтиь на сервер
                //Проверка данных получателя
                if ($(".sx-receiver-data").is(":visible")) {
                    var isEmpty = true;
                    $("input", $(".sx-receiver-data")).each(function () {
                        if ($(this).val() != '') {
                            isEmpty = false;
                        }
                    });

                    if (isEmpty == true) {
                        $(".sx-receiver-triggger").click();
                    }
                }


                var ajaxQuery = sx.ajax.preparePostQuery(self.get("checkout_backend"));
                var Handler = new sx.classes.AjaxHandlerStandartRespose(ajaxQuery, {
                    'allowResponseErrorMessage': false
                });
                Handler.on("success", function (e, data) {
                    jBlocker.unblock();
                });
                Handler.on("error", function (e, data) {
                    if (data.data.error_element_id) {
                        var jErrorElement = $("#" + data.data.error_element_id);
                        if (jErrorElement.length > 0) {

                            jErrorElement.addClass("is-invalid");
                            var jCheckoutBlock = jErrorElement.closest(".sx-checkout-block");
                            //new sx.classes.Location("#" + jCheckoutBlock.attr("id"));

                            self.showBlockError(jCheckoutBlock, data.message);
                        }
                    } else if (data.data.error_code) {
                        var jErrorElement = $("[data-field='" + data.data.error_code + "']");
                        if (jErrorElement.length > 0) {

                            jErrorElement.addClass("is-invalid");
                            var jCheckoutBlock = jErrorElement.closest(".sx-checkout-block");
                            //new sx.classes.Location("#" + jCheckoutBlock.attr("id"));

                            self.showBlockError(jCheckoutBlock, data.message);
                        }
                    }

                    self.getJError().empty().append(data.message).show();

                    //Кнопку в исходное положение
                    jBtn.removeClass("sx-disabled");
                    jBtn.empty().append(jBtn.data("value"));
                    jBlocker.unblock();
                });
                ajaxQuery.execute();

                return false;
            });


            $('body').on('change', '.sx-save-after-change', function () {
                var element = $(this);
                var field = $(this).data('field');
                var val = $(this).val();

                var data = {};
                data[field] = val;

                var ajaxQuery = sx.Shop.createAjaxUpdateOrder({
                    'data': data
                });

                var Handler = new sx.classes.AjaxHandlerStandartRespose(ajaxQuery, {
                    'allowResponseErrorMessage': false,
                });
                Handler.on("success", function () {
                    element.removeClass("is-invalid");
                });
                Handler.on("error", function (e, data) {
                    element.addClass("is-invalid");
                    self.showBlockError(element.closest(".sx-checkout-block"), data.message);
                });

                ajaxQuery.execute();
            });

            $("input, textarea").on("focus", function () {
                if ($(this).hasClass('is-invalid')) {
                    $(this).removeClass('is-invalid');
                    self.hideGlobalError();
                }
            });

            $('body').on('click', '.sx-receiver-triggger', function () {
                self.hideGlobalError();
                if ($(".sx-receiver-data").is(":visible")) {
                    $(".sx-receiver-data").slideUp();

                    //Обнулить значения
                    $(".populated", $(".sx-receiver-data")).removeClass("populated");

                    $("input", $(".sx-receiver-data")).each(function () {
                        if ($(this).val()) {
                            $(this).val("").trigger("change").trigger("focusout");
                        }
                    });

                    $(this).empty().append($(this).data("text-other"));
                } else {
                    $(".sx-receiver-data").slideDown();
                    $(this).empty().append($(this).data("text-me"));
                }

                return false;
            });

            if ($('.sx-receiver-triggger').data("value") == 1) {
                $(".sx-receiver-data").slideDown();
                $(".sx-receiver-triggger").empty().append($(".sx-receiver-triggger").data("text-me"));
            }
        },

        showBlockError(jCheckoutBlock, errormessage, timeout = 3000) {
            jCheckoutBlock.tooltip({
                'title': errormessage,
                'placement': 'right',
                'template': '<div class="tooltip sx-error-tooltip" role="tooltip"><div class="arrow"></div><div class="tooltip-inner"></div></div>',
            });
            jCheckoutBlock.tooltip("show");

            setTimeout(function () {
                jCheckoutBlock.tooltip("hide");
                setTimeout(function () {
                    jCheckoutBlock.tooltip("dispose");
                }, 200);
            }, timeout);
        },

        hideGlobalError: function () {
            var self = this;
            self.getJError().hide().empty();

            return this;
        },

        getJCartLayout: function () {
            return $(".sx-cart-layout");
        },

        getJDelivery: function () {
            return $(".sx-delivery-block");
        },

        getJPaysystem: function () {
            return $(".sx-paysystem-block");
        },

        getJError: function () {
            return $(".sx-order-error");
        }
    });
})(sx, sx.$, sx._);