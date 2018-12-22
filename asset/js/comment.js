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
			
				// listen event form submit
				eventSubmitComment(id);

			} else {
				comment_status.removeClass('show-comment-status');
				comment_status.addClass('hide-comment-status');
			}
		}

		// Like/ Unline on status
		if (full_id.indexOf('like') > 0) {
			console.log(id);
		}
	})



})

function eventSubmitComment(id) {
	
	// Send POST comment
	$('.frmComment-'+id).on('submit', (e) => {

		// empty content comment
		if ($('#content_comment_'+id).val() === "") {return;}

		// stop send request
		e.preventDefault();

		// Ajax send to API and get result
		$.ajax({
			url: 'inc/Handler/CommentHandler.php',
			type: 'POST',
			data: $(".frmComment-"+id).serialize(),
			success: (data) => {
				let result = JSON.parse(data);

				if (result.status === 200) {

					// append new element comment
					let element = `
					    <div class="detail-comment">
					        <span id="icon">
					            <img src='${result.avatar}' alt='icon'>
					        </span>
					        <span id="content">
					            <span id="user-comment">
					                <a href="profile.php?id=${result.id_user}"> ${result.name} </a>
					            </span>
					            <span id="content-commment">
					                ${result.content}
					            </span> 
					        </span>
					    </div>
					    `;	

					$('#show-comment-'+id).append(element);

					console.log(result.respText);

					// remove input text
					$('#content_comment_'+id).val("");

					// update number comment
					let numcom = parseInt($('#numcom-'+id)[0].innerHTML, 10);
					let numUpdate = numcom >= 0 ? numcom+1 : "(1)";
					$('#numcom-'+id)[0].innerHTML = numUpdate;

				} else {
					console.log(result.respText);
				}
			},
			error: (err) => {

				console.log(err);
			}
		});
	})
}