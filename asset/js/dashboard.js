$(document).ready(() => {

	// Giả lập input upload file bằng button
	$('#status-image-btn').on('click', (e) => {
		e.preventDefault();

		$('#status-image').click();
	})


	// Zoom image
	$(".image_status").on("click", (e) => {
		
		onClickImageStatus(e);
	});

})

function onClickImageStatus(e) {

	// Focus background
	$("body").addClass("focus");

	// Zoom in image
	$(e.target).addClass("center-image");

	// Outside and zoomout image
	$("body").click((body) => {
		if(!$(body.target).is(".image_status")) {

			$(e.target).removeClass("center-image");
			$("body").removeClass("focus");

		}
	})
}