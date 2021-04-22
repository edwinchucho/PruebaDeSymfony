$(document).ready(function() {
	var $table =$('.js-table');
	$table.find('.js-delete').on('click', function (e) {
		e.preventDefault();
		var deleteUrl = $(this).data('url');
		var $row = $(this).closest('tr');
		$.ajax({
			method: 'DELETE',
			url: deleteUrl,
			success:function () {
				$row.fadeOut();
			}
		})
	});
});
