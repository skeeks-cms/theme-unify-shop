/*!
 * @author Semenov Alexander <semenov@skeeks.com>
 * @link http://skeeks.com/
 * @copyright 2010 SkeekS (СкикС)
 * @date 28.11.2017
 */
(function($){

	$(document).ready(function(){

        /* ==========================================================================
			Catalog
			========================================================================== */

		$('.card-prod').matchHeight({
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
		});

    });

})(jQuery);