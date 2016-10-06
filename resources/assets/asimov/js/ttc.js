$.ajaxSetup({
	headers: {
		'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
	}
});

$(document).ready(function () {
	$("[data-bootgrid='true']").bootgrid({
		rowCount: [10, 25, 50],
		templates: {
			search: ""
		},
		css: {
			icon: 'md icon',
			iconColumns: 'md-view-module',
			iconDown: 'md-expand-more',
			iconRefresh: 'md-refresh',
			iconUp: 'md-expand-less'
		}
	}).on("loaded.rs.jquery.bootgrid", function (e) {
		initSwConfirm();

		var currentBootgridPage = $(e.target).bootgrid('getCurrentPage')

		$("ul.pagination li").removeClass('active');
		$("ul.pagination li[class^='page-']").each(function(){
			if($(this).hasClass('page-'+currentBootgridPage)){
				$(this).addClass('active');
			}
		});
	});

	$('.datetimepicker').datetimepicker({
		format: "YYYY-MM-DD HH:mm:ss"
	});
	$('.datepicker').datetimepicker({
		format: "YYYY-MM-DD"
	});
});

function cloneElementAndAppendTo(element, appendTo) {

	clone = $(element).clone().removeClass('hidden').removeAttr('id')

	clone.find('input,select').each(function () {
		$(this).removeAttr('disabled');
	});

	clone.appendTo(appendTo);
}


$(document).ready(function () {
	$("#options-container").on('click', 'a', function () {
		$(this).closest('.option-group').remove();
	});

	$(".operator").change(function () {

		var option = $(this).find('option:selected').first();
		var index = '.' + option.attr('data-index');

		if (option.attr('data-operator') == "> <") {
			$(index).clone().appendTo($(index).parent());
			$(index).first().attr('placeholder', 'From').val('');
			$(index).last().attr('placeholder', 'To').val('');
			$(index).width('45%').css('float', 'left').css('margin-right', '4%');
		} else {
			if ($(index).size() > 1) {
				$(index).first().attr('placeholder', 'Value').val('');
				$(index).first().width('100%');
				$(index).last().remove();
			}
		}
	});


	$("select#repeating_survey").on('change', function () {
		if ($(this).val() != '') {
			$("#repeating_end_date").show();
		} else {
			$("#repeating_end_date").hide();
		}
	});

});
