/*!
 * @author Semenov Alexander <semenov@skeeks.com>
 * @link http://skeeks.com/
 * @copyright 2010 SkeekS (СкикС)
 * @date 28.11.2017
 */
(function ($) {

    $(document).ready(function () {

        $(document).on('pjax:complete', function (e) {
            /*if (e.target.id == self.get('id')) {
                new sx.classes.Location().href($(e.target));
            }*/
            $.HSCore.components.HSScrollBar.init($('.js-scrollbar'));
            // initialization of carousel
            $.HSCore.components.HSCarousel.init('.js-carousel');
        });
        $.HSCore.components.HSScrollBar.init($('.js-scrollbar'));
        // initialization of carousel
        $.HSCore.components.HSCarousel.init('.js-carousel');

        /* ==========================================================================
			Catalog
			========================================================================== */

        /*$('.card-prod').matchHeight({
            'byRow' : false
        });

        $('.card-prod').each(function(){
            var parent = $(this),
                classHover = 'hover',
                dropup = parent.find('.card-prod--actions .dropup');

            dropup.on('show.bs.dropdown', function () {
                parent.addClass(classHover);
            });
            dropup.on('hide.bs.dropdown', function () {
                parent.removeClass(classHover);
            });
        });*/

        // initialization of HSScrollBar component


        $('body').on("click", '.to-cart-fly-btn', function () {

            var jToCartWrapper = $(this).closest('.to-cart-fly-wrapper');
            var jToCartFlyImg = $('.to-cart-fly-img', jToCartWrapper);
            if (!jToCartFlyImg[0]) {
                jToCartFlyImg = $('.sx-stick-slider .slick-current img', jToCartWrapper);
                console.log(jToCartFlyImg);
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