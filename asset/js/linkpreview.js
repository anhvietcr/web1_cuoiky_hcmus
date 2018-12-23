$(document).ready(() => {

	ParseLinkStatus();
	ParseLinkComment();

});

// function MyParseHTML(link, data) {

// CROS EROR
// 	var result = {
// 		title: "",
// 		description: "",
// 		image: ""
// 	};

// 	result.title= /(?<=<title>)(.*?)(?=<\/title>)/g.exec(data);
// 	result.description = /(?<=<meta name=\"description\" content=\")(.*?)(?=\">)/.exec(data);
// 	let image = /(?<=href=\")(.*?\.ico)/.exec(data);

// 	if (image.indexOf("http") < 0) {
// 		image = link + image;
// 	}

// 	result.image = image;

// 	return result;
// }

function ParseLinkStatus() {
	let contents = $('.new-content').toArray();
	$.each(contents, (i, e) => {

		let content = e.innerText;

		if (content.indexOf("http") >= 0) {

			// get link
			let result = /http.?\S+|www.?\S+|\S+\.com|\S+\.net|\S+\.org|\S+\.vn|\S+\.social/g.exec(content);
			let link = result[0];

			// Replace string with a href
			contentHTML = content.replace(link, "<a href="+link+" target='_blank'>"+link+"</a>");
			e.innerHTML = contentHTML;

			// Call REST API and get meta tag
			$.ajax({
				url: "http://api.linkpreview.net/?key=5c1f054d4eddb181615962bac050f4454517b82e942b1&q="+link,
				method: "GET",
				dataType: "JSON",
				success: (data) => {

					if (data.image === "") {
						data.image = "asset/img/notpreview.png";
					}

					e.innerHTML += `<div class="linkpreview linkpreview-status">
						<a href="${data.url}">
							<img src="${data.image}">
							<h3>${data.title}</h3>
							<p>${data.description}</p>
						</a>
					</div>
					`;

					// remove img next sibling if avalable
					if (e.nextSibling.nextElementSibling.localName == "img") {
						$(e.nextSibling.nextElementSibling).replaceWith("");
					}
				},
				error: (err) => {
					console.log("Error: ");
					console.log(err);
				}
			})
		}
	})
}

function ParseLinkComment() {
	let contents = $('#content-commment').toArray();
	$.each(contents, (i, e) => {

		let content = e.innerText;

		if (content.indexOf("http") >= 0) {

			// get link
			let result = /http.?\S+|www.?\S+|\S+\.com|\S+\.net|\S+\.org|\S+\.vn|\S+\.social/g.exec(content);
			let link = result[0];

			// Replace string with a href
			contentHTML = content.replace(link, "<a href="+link+" target='_blank'>"+link+"</a>");
			e.innerHTML = contentHTML;

			// Call REST API and get meta tag
			// $.ajax({
			// 	url: "http://api.linkpreview.net/?key=5c1f054d4eddb181615962bac050f4454517b82e942b1&q="+link,
			// 	method: "GET",
			// 	dataType: "JSON",
			// 	success: (data) => {

			// 		if (data.image === "") {
			// 			data.image = "asset/img/notpreview.png";
			// 		}


			// 		e.innerHTML += `<div class="linkpreview linkpreview-comment">
			// 			<a href="${data.url}">
			// 				<img src="${data.image}">
			// 				<h3>${data.title}</h3>
			// 				<p>${data.description}</p>
			// 			</a>
			// 		</div>

			// 		`;

			// 		// remove img next sibling if avalable
			// 		if (e.nextSibling.nextElementSibling.localName == "img") {
			// 			$(e.nextSibling.nextElementSibling).replaceWith("");
			// 		}
			// 	},
			// 	error: (err) => {
			// 		console.log("Error: ");
			// 		console.log(err);
			// 	}
			// })
		}
	})
}