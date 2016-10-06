function notify(message, type) {
	$.growl({
		message: message
	}, {
		element: '#notification-container',
		type: type,
		allow_dismiss: true,
		placement: {
			from: 'right',
			align: 'right'
		},
		spacing: 10,
		z_index: 1031,
		delay: 2500,
		timer: 2000,
		url_target: '_blank',
		mouse_over: false,
		animate: {
			enter: 'animated fadeInDown',
			exit: 'animated fadeOutUp'
		},
		icon_type: 'class',
		template: '<div data-growl="container" class="alert" role="alert">' +
		'<button type="button" class="close" data-growl="dismiss">' +
		'<span aria-hidden="true">&times;</span>' +
		'<span class="sr-only">Close</span>' +
		'</button>' +
		'<span data-growl="message"></span>' +
		'</div>'
	});
}
