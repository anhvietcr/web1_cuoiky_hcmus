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
			// https://docs.microlink.io/api/#introduction (250 req / week)
			// Backup: https://github.com/kasp3r/link-preview
			$.ajax({
				url: "https://api.microlink.io/?url="+link,
				method: "GET",
				dataType: "JSON",
				success: (data) => {

					if (data.data.image == null && data.data.logo == null) { 
						data.data.image = "asset/img/notpreview.png";
					}

					if (data.data.image == null) {data.data.image = data.data.logo}
					if (data.data.image.width < 700) {data.data.image.width = 700}
					if (data.data.image.height < 500) {data.data.image.height = 500}

					e.innerHTML += `<div class="linkpreview linkpreview-status">
						<a href="${data.url}">
							<img src="${data.data.image.url}" width="${data.data.image.width}" height="${data.data.image.height}">
							<h3>${data.data.title}</h3>
							<p>${data.data.description}</p>
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
		}
	})
}