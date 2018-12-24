$(document).ready(() => {

		// Reaction action	
	$(".reaction li").on('click', (e) => {
		
		let li = $(e.target);
		let full_id = li.attr('id');
		let id = full_id.split('-')[2];

		/*
		 *	Hide/Show comment element on status
		 */
		if (full_id.indexOf('comment') > 0) {
			

			let comment_status = $('#comment-status-'+id);
			if (comment_status.hasClass('hide-comment-status')) {

				comment_status.removeClass('hide-comment-status');
				comment_status.addClass('show-comment-status');
			
			} else {
				comment_status.removeClass('show-comment-status');
				comment_status.addClass('hide-comment-status');
			}
		}

		// Like/ Unline on status
		if (full_id.indexOf('like') > 0) {
			console.log(id);
		}
	});

})