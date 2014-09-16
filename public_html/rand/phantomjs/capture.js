//create new webpage object
var page = new WebPage();

//load the page
page.open('https://afo.com', function (status) {
	//fire callback to take screenshot after load complete
	page.render('afo.png');
	//finish
	phantom.exit();
});
