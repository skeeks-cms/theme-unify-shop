$(function() {

    $("body").on("mouseenter", ".sx-product-image", function () {

        var secondImgSrc = $(this).data("second-src");

        if (secondImgSrc) {
            //console.log($(this).attr("src"));
            //console.log($(this).data("second-src"));
            $(this).attr("data-first-src", $(this).attr("src"));
            $(this).attr("src", secondImgSrc)
        }
    });

    $("body").on("mouseleave", ".sx-product-image", function () {
        var fiestImgSrc = $(this).data("first-src");
        if (fiestImgSrc) {
            console.log(fiestImgSrc);
            /*$(this).attr("data-first-src", $(this).attr("src"));*/
            $(this).attr("src", fiestImgSrc)
        }
    });
});