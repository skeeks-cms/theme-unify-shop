/*!
 * @author Semenov Alexander <semenov@skeeks.com>
 * @link http://skeeks.com/
 * @copyright 2010 SkeekS (СкикС)
 * @date 28.11.2017
 */
(function ($) {

    $("body").on("click", ".sx-quantity-group .sx-plus", function () {

        /*var jWrapper = $(this).closest(".sx-quantity-wrapper");
        $(".sx-plus", jWrapper).trigger("up");*/
        $(this).trigger("up");
        return false;
    });

    $("body").on("click", ".sx-quantity-group .sx-minus", function () {
        /*var jWrapper = $(this).closest(".sx-quantity-wrapper");
        $(".sx-minus", jWrapper).trigger("down");*/

        $(this).trigger("down");
        return false;
    });


    function normalizeQuantity(value, ratio, min) {

        value = Number(value);
        ratio = Number(ratio) || 1;
        min   = Number(min) || ratio;

        if (isNaN(value)) value = 0;

        // точность шага
        var precision = (ratio.toString().split('.')[1] || '').length;
        var factor = Math.pow(10, precision);

        // в целые
        var valueInt = Math.round(value * factor);
        var ratioInt = Math.round(ratio * factor);

        // минимум
        if (valueInt < ratioInt) {
            valueInt = ratioInt;
        }

        // нормализация по шагу
        var count = Math.round(valueInt / ratioInt);
        valueInt = count * ratioInt;

        // обратно
        var result = valueInt / factor;

        return Number(result.toFixed(precision));
    }

    $("body").on("up", ".sx-quantity-group .sx-plus", function () {

    var jInput = $(this)
        .closest(".sx-quantity-group")
        .find(".sx-quantity-input");

    var ratio = jInput.data("measure_ratio");
    var min   = jInput.data("measure_ratio_min");
    var value = Number(jInput.val()) + Number(ratio);

    var newVal = normalizeQuantity(value, ratio, min);

    jInput.val(newVal).focus().trigger("change", { result: "up" });

    return false;
});
        


    $("body").on("down", ".sx-quantity-group .sx-minus", function () {

    var jInput = $(this)
        .closest(".sx-quantity-group")
        .find(".sx-quantity-input");

    var ratio = jInput.data("measure_ratio");
    var min   = jInput.data("measure_ratio_min");
    var value = Number(jInput.val()) - Number(ratio);

    var newVal = normalizeQuantity(value, ratio, min);

    jInput.val(newVal).focus().trigger("change", { result: "down" });

    return false;
});

    $("body").on("updatewidth", ".sx-quantity-group .sx-quantity-input", function () {
        var measure_ratio = Number($(this).data("measure_ratio")) || 1;
        var newVal = $(this).val();
        var length = (String(newVal).length - 1) || 1;

        var jHidden = $("<span>").append(newVal);

        $(this).after(jHidden);

        $(this).css("width", jHidden.width() + 5);
        jHidden.remove();
        /*$(this).attr("size", length);*/
    });

    $(document).on('pjax:complete', function (e) {
        $(".sx-quantity-group .sx-quantity-input").trigger("updatewidth");
    });

    $("body").on("keyup", ".sx-quantity-group .sx-quantity-input", function () {
        $(this).trigger("updatewidth");
    });

    $("body").on("updateOther", ".sx-quantity-input", function () {

    var jCurrent = $(this);

    var ratio = Number(jCurrent.data("measure_ratio")) || 1;
    var value = Number(jCurrent.val()) || 0;

    // коэффициент — сколько шагов
    var coefficient = Math.round(value / ratio);

    var jWrapper = jCurrent.closest(".sx-quantity-wrapper");

    $(".sx-quantity-input", jWrapper).each(function () {

        var jInput = $(this);

        if (jInput.hasClass("sx-current-changed")) {
            jInput.removeClass("sx-current-changed");
            return;
        }

        var inputRatio = Number(jInput.data("measure_ratio")) || 1;
        var min = Number(jInput.data("measure_ratio_min")) || inputRatio;

        // считаем через нормализацию
        var newVal = normalizeQuantity(
            coefficient * inputRatio,
            inputRatio,
            min
        );

        jInput.val(newVal);
        jInput.trigger("updatewidth");
    });
});

    $("body").on("change", ".sx-quantity-input", function () {

    var jInput = $(this);

    var ratio = jInput.data("measure_ratio");
    var min   = jInput.data("measure_ratio_min");
    var value = jInput.val();

    var newVal = normalizeQuantity(value, ratio, min);

    jInput.val(newVal);
    jInput.trigger("updatewidth");

    jInput
        .focus()
        .addClass("sx-current-changed")
        .trigger("updateOther");

    return false;
});
        


    $(".sx-quantity-group .sx-quantity-input").trigger("updatewidth");


    $(function () {
        sx.onReady(function () {
            sx.Shop.on("change", function (e, data) {

                var jCarts = $(".sx-js-cart");
                if (!jCarts.length) {
                    return false;
                }

                var totalQuantity = sx.Shop.get('cartData').quantity;
                var totalItems = sx.Shop.get('cartData').countShopBaskets;
                var totalMoney = sx.Shop.get('cartData').money.convertAndFormat;
                var totalMoneyAmount = Number(sx.Shop.get('cartData').money.amount);

                if (totalQuantity) {
                    jCarts.addClass("sx-is-full-cart");
                } else {
                    jCarts.removeClass("sx-is-full-cart");
                }

                $('.sx-total-quantity', jCarts).empty().append(totalQuantity);
                $('.sx-total-items', jCarts).empty().append(totalItems);
                $('.sx-total-money', jCarts).empty();

                if (totalMoneyAmount > 0) {
                    $('.sx-total-money', jCarts).append(totalMoney);
                }

            });
        })
    })


    $(document).ready(function () {

        $('body').on("click", '.to-cart-fly-btn', function () {

            if ($(".sx-mobile-layout").length) {
                $("#sx-top-cart").animate({
                    transform: 'scale(1.3)'
                }, 200, function () {
                    $(this).animate({
                        transform: 'scale(1)'
                    }, 200, function () {
                        $(this).removeAttr('style');
                    });
                });
            } else {
                var jToCartWrapper = $(this).closest('.to-cart-fly-wrapper');
                var jToCartFlyImg = $('.to-cart-fly-img', jToCartWrapper);
                if (!jToCartFlyImg[0]) {
                    jToCartFlyImg = $('.sx-stick-slider .slick-current img', jToCartWrapper);
                } else {
                    if (jToCartFlyImg.length > 1) {
                        jToCartFlyImg = $(jToCartFlyImg[0]);
                        console.log(jToCartFlyImg);
                    }
                }
                
                if (jToCartFlyImg.length == 0) {
                    return false;
                }

                var jToCartFlyImgFly = jToCartFlyImg
                        .clone()
                        .css({
                            'position': 'absolute',
                            'z-index': '11100',
                            top: jToCartFlyImg.offset().top,
                            left: jToCartFlyImg.offset().left,
                        })
                        .appendTo("body")
                    //.after(jToCartFlyImg)
                ;

                _.delay(function () {
                    jToCartFlyImgFly
                        /*.css({
                            transform: 'scale(1.2)',
                            transition: 'all 0.2s'
                        })*/
                        .animate({

                            opacity: 0.05,
                            left: $("#sx-top-cart").offset()['left'],
                            top: $("#sx-top-cart").offset()['top'],
                            width: 20,
                            transform: 'scale(.10) rotate(360deg)'
                        }, 1000, function () {
                            $(this).remove();

                            $("#sx-top-cart").animate({
                                transform: 'scale(1.3)'
                            }, 200, function () {
                                $(this).animate({
                                    transform: 'scale(1)'
                                }, 200, function () {
                                    $(this).removeAttr('style');
                                });
                            });
                        });
                }, 100);
            }


        });
    });

})(jQuery);
