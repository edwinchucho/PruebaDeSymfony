$(document).ready(function() {
	var $table =$('.js-table');
	$table.find('.js-deleteItem').on('click', function (e) {
		e.preventDefault();
		var deleteUrl = $(this).data('url');
		$('#confirmdelete').attr('href',deleteUrl);
	});
});
