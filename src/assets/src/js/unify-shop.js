/*!
 * @author Semenov Alexander <semenov@skeeks.com>
 * @link http://skeeks.com/
 * @copyright 2010 SkeekS (СкикС)
 * @date 28.11.2017
 */
(function ($) {

    $("body").on("click", ".sx-quantity-group .sx-plus", function () {

        var jWrapper = $(this).closest(".sx-quantity-wrapper");
        $(".sx-plus", jWrapper).trigger("up");
        return false;
    });

    $("body").on("click", ".sx-quantity-group .sx-minus", function () {
        var jWrapper = $(this).closest(".sx-quantity-wrapper");
        $(".sx-minus", jWrapper).trigger("down");
        return false;
    });

    $("body").on("up", ".sx-quantity-group .sx-plus", function () {
        //$(this).addClass("sx-clicked");


        var jGroup = $(this).closest(".sx-quantity-group");
        var jInput = $(".sx-quantity-input", jGroup);
        var measure_ratio = Number(jInput.data("measure_ratio")) || 1;
        var newVal = Number(jInput.val()) + measure_ratio;

        var count = newVal / measure_ratio;
        count = Math.round(count);

        newVal = count * measure_ratio;
        newVal = Math.floor(newVal * 100) / 100;
        jInput.val(newVal);
        jInput.focus().change();

        //var jWrapper = $(this).closest(".sx-quantity-wrapper");
        //$(".sx-plus", jWrapper).not(".sx-clicked").click();

        //$(this).removeClass("sx-clicked");
        return false;
    });

    $("body").on("down", ".sx-quantity-group .sx-minus", function () {
        var jGroup = $(this).closest(".sx-quantity-group");
        var jInput = $(".sx-quantity-input", jGroup);
        var measure_ratio = parseFloat(jInput.data("measure_ratio")) || 1;
        var newVal = parseFloat(jInput.val()) - measure_ratio;
        jInput.val(newVal);
        jInput.focus();
        jInput.change();
        return false;
    });

    $("body").on("updatewidth", ".sx-quantity-group .sx-quantity-input", function () {
        var measure_ratio = Number($(this).data("measure_ratio")) || 1;
        var newVal = $(this).val();


        var length = (String(newVal).length - 1) || 1;
        $(this).attr("size", length);
    });

    $(document).on('pjax:complete', function (e) {
        $(".sx-quantity-group .sx-quantity-input").trigger("updatewidth");
    });

    $("body").on("keyup", ".sx-quantity-group .sx-quantity-input", function () {
        $(this).trigger("updatewidth");
    });

    /*$("body").on("change", ".sx-secondary-quantity-group .sx-quantity-input", function () {
        var measure_ratio = Number($(this).data("measure_ratio")) || 1;
        var newVal = $(this).val();

        if (Number($(this).val()) < measure_ratio) {

        }
    });

    $("body").on("updateValue", ".sx-main-quantity-group .sx-quantity-input", function (e, data) {
         console.log("updateValue");
         console.log(data);
    });*/


    $("body").on("change", ".sx-main-quantity-group .sx-quantity-input", function () {

        /*$(this).trigger("updatevalue", {
            'value' : $(this).val()
        });*/

        var measure_ratio = Number($(this).data("measure_ratio")) || 1;
        var newVal = $(this).val();

        if (Number($(this).val()) < measure_ratio) {
            $(this).val(measure_ratio).focus();
            $(this).trigger("updatewidth");
            return false;
        }

        var count = newVal / measure_ratio;
        count = Math.round(count);

        newVal = count * measure_ratio;
        newVal = Math.floor(newVal * 100) / 100;

        $(this).val(newVal).focus();

        $(this).trigger("updatewidth");

        /*var jWrapper = $(this).closest(".sx-quantity-wrapper");
        $(".sx-secondary-quantity-group .sx-quantity-input", jWrapper).each(function() {
            $(this).val();
            var mr = Number($(this).data("measure_ratio")) || 1;
            
            newVal = count * measure_ratio;
            newVal = Math.floor(newVal * 100) / 100 ;
        });*/

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

                if (totalQuantity) {
                    jCarts.addClass("sx-is-full-cart");
                } else {
                    jCarts.removeClass("sx-is-full-cart");
                }

                $('.sx-total-quantity', jCarts).empty().append(totalQuantity);
                $('.sx-total-items', jCarts).empty().append(totalItems);
                $('.sx-total-money', jCarts).empty().append(totalMoney);

            });
        })
    })


    $(document).ready(function () {


        $('body').on("click", '.to-cart-fly-btn', function () {

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


        });


    });

})(jQuery);