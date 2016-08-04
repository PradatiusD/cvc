(function ($) {
	
	var CVCBreadcrumb = $(".cvc-breadcrumb")
	var $breadcrumb   = CVCBreadcrumb.find("a").detach();

	var links = [];

	$breadcrumb.each(function () {

		var href = $(this).attr('href');
		var text = $(this).text();

		links.push({
			href: href,
			text: text
		});
	});

	CVCBreadcrumb.empty();

	var newCrumb = $(document.createElement('ul'));
	newCrumb.addClass('breadcrumbs');

	for (var i = 0; i < links.length; i++) {

		var $li = $(document.createElement('li'));
		var $a  = $(document.createElement('a'));
		$a.text(links[i].text);
		$a.attr('href', links[i].href);
		$li.append($a);
		newCrumb.append($li);
	}

	CVCBreadcrumb.append(newCrumb);

})(jQuery);