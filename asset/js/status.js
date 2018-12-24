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

		/*
			Like & Unlike
		 */
		else if (full_id.indexOf('nonlike') >= 0) {
			console.log("liked " + id);

			LikeStatus(id);


		} else if (full_id.indexOf('like') >= 0) {
			console.log("unlike " + id)

			UnLikeStatus(id);

		} else {

			// share
			console.log("share " + id)

		}
	});


	/*
		Send POST request add new comment
	 */
	$('.frmComment').on('submit', (e) => {

		let frm = $(e.target);
		let frmId = frm.attr('id');
		let id = frmId.split('-')[1];

		// empty content comment
		if ($('#content_comment_'+id).val() === "") {return;}

		// stop send request
		e.preventDefault();

		// Ajax send to API and get result
		$.ajax({
			url: 'inc/Handler/CommentHandler.php',
			type: 'POST',
			data: $("#frmComment-"+id).serialize(),
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
})

function LikeStatus(id) {

	let username = $('meta[name=username]').attr('value');

	$.ajax({
		url: 'inc/Handler/LikeHandler.php',
		type: 'POST',
		data: {
			id_status: id,
			username: username,
			type: 'like'
		},
		dataType: 'json',
		success: (data) => {

			if (data.status === 200) {

				// Update frontend
				let numnonlike = $('#numnonlike-'+id)[0].innerHTML;
				let numnlike = $('#numlike-'+id)[0].innerHTML;
				let numUpdate;

				if (numnonlike === "") {
					numUpdate = 1;
				} else {
					let liked = parseInt(numnonlike, 10);
					numUpdate = liked + 1;
				}

				$('#numlike-'+id)[0].innerHTML = numUpdate;
				$('#numnonlike-'+id)[0].innerHTML = numUpdate;
				$('#reaction-like-'+id).prop("style", "display: table-cell");
				$('#reaction-nonlike-'+id).prop("style", "display: none");
			}
		}, 
		error: (err) => {
			console.log("Error: ");
			console.log(err);
		}
	})
}

function UnLikeStatus(id) {

	let username = $('meta[name=username]').attr('value');

	$.ajax({
		url: 'inc/Handler/LikeHandler.php',
		type: 'POST',
		data: {
			id_status: id,
			username: username,
			type: 'unlike'
		},	
		dataType: 'json',
		success: (data) => {

			if (data.status === 200) {

				// Update frontend
				let numnonlike = $('#numnonlike-'+id)[0].innerHTML;
				let numnlike = $('#numlike-'+id)[0].innerHTML;
				let numUpdate;

				if (numnonlike === "") {
					numUpdate = "";
				} else {
					let liked = parseInt(numnonlike, 10);

					if (liked < 2) {
						numUpdate = "";
					} else {
						numUpdate = liked - 1;
					}
				}
				
				$('#numlike-'+id)[0].innerHTML = numUpdate;
				$('#numnonlike-'+id)[0].innerHTML = numUpdate;
				$('#reaction-like-'+id).prop("style", "display: none");
				$('#reaction-nonlike-'+id).prop("style", "display: table-cell");
			}
		}, 
		error: (err) => {
			console.log("Error: ");
			console.log(err);
		}
	})
}