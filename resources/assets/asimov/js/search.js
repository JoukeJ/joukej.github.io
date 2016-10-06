$(document).ready(function () {
	$('.color-filter').children('.btn').click(function () {
		var color = $(this).attr('data-color');

		$('#search-results').children().each(function () {
			var show = $(this).find('.card').attr('data-color') == color;

			if (!show) {
				$(this).hide();
			} else {
				$(this).show();
			}
		});
	});
});
