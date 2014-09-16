/**
 Mouse coords

**/

if (event.pageX == null)
{
	// IE case
	var d= (document.documentElement &&
	document.documentElement.scrollLeft != null) ?
		document.documentElement : document.body;
	docX= event.clientX + d.scrollLeft;
	docY= event.clientY + d.scrollTop;
}
else
{
	// all other browsers
	docX= event.pageX;
	docY= event.pageY;
}
