$(document).ready(() => {

	ParseLink();

});

function ParseLink() {
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
				url: "http://api.linkpreview.net/?key=5c1ef253ea0bb55f8c1c115741faf7519ccc644af7074&q="+link,
				method: "GET",
				dataType: "JSON",
				success: (data) => {

					if (data.image === "") {
						data.image = "asset/img/notpreview.png";
					}


					e.innerHTML += `<div class="linkpreview">
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