document.addEventListener("DOMContentLoaded", init, false);

/**
 * Cookie Object
 */
function Cookie(name) {
	this.name = name;

	this.set = function(data, expire) {
		if (expire) {
			var expire_string;
			var date = new Date();
			date.setTime(date.getTime() + (expire * 60 * 1000));
			expire_string = "; expires=" + expire.toUTCString;
		} else {
			expire_string = "";
		}
		document.cookie = this.name+"="+data+expire_string+"; path=/";
	};

	this.read = function() {
		var name = this.name + "=";
		var ca = document.cookie.split(';');
		for(var i=0;i < ca.length;i++) {
			var c = ca[i];
			while (c.charAt(0)==' ') c = c.substring(1,c.length);
			if (c.indexOf(name) == 0) return c.substring(name.length,c.length);
		}
		return null;
	};

	this.erase = function() {
		this.set("", -1);
	};
}


/**
 * Manage the session
 */
function Session() {
	var cookie = new Cookie('session');
	var id = null;

	if(cookie.read() == null) {
		generate();

	} else if(JSON.parse(cookie.read())['activity'] < Math.round(+new Date()/1000) - 600) {
		/*
		 * 10 minute expiration
		 */
		generate();
	} else if(JSON.parse(cookie.read())['activity'] < Math.round(+new Date()/1000) - 10) {
		update();
	}

	function generate() {
		id = generateId(10);
		var session = {
			"id"        : id,
			"activity"  : Math.round(+new Date()/1000)
		};

		cookie.set(JSON.stringify(session), 10);
	}

	function update() {
		var session = JSON.parse(cookie.read());

		session.activity = Math.round(+new Date()/1000);
		id = session.id;

		cookie.set(JSON.stringify(session), 10);
	}

	return id;
}

function init() {
	var Stats = {
		"start"     : Math.round(+new Date()/1000),
		"uri"           :   document.URL,
		"window"    : {
			"height"     :   {
				"inner"     :   window.innerHeight,
				"outer"     :   window.outerHeight
			},
			"width"     :   {
				"inner"     :   window.innerWidth,
				"outer"     :   window.outerWidth
			}
		},
		"screen"    :   {
			"height"     :   {
				"available" :   window.screen.availHeight,
				"total"     :   window.screen.height
			},
			"width"     :   {
				"available" :   window.screen.availWidth,
				"total"     :   window.screen.width
			},
			'depth'     :   {
				'color'     :   window.screen.colorDepth,
				'pixel'     :   window.screen.pixelDepth
			}
		},
		"user"    :   {
			"agent"         :   navigator.userAgent,
			"referrer"      :   document.referrer.split('/')[2],
			"language"      :   navigator.language,
			"cookies"       :   {
				"enabled"       :   navigator.cookieEnabled
			},
			"token"         :   'hoEndE7iLa',
			"tracking_id"    :   Session()
		},
		"timeline"  : [],
		"tmp"       :       {
			"event"             : null,
			"timer"             : null
		},
		"functions" :   {
			recordMouseClick  :   function()  {
				var coords = getEventStats(Stats.tmp.event);
				coords.type = 'mouse.click';
				Stats.timeline.push(coords);
			},
			recordMouseHover   :   function() {
				clearTimeout(Stats.tmp.timer);
				Stats.tmp.timer = setTimeout(function() {
					var coords = getEventStats(Stats.tmp.event);
					coords.type = 'mouse.hover';
					Stats.timeline.push(coords);
					Stats.tmp.timer = null;
				}, 200 );
			},
			recordWindowResize  :  function() {
				clearTimeout(Stats.tmp.timer);
				Stats.tmp.timer = setTimeout(function() {
					Stats.timeline.push({
						"type"  :   'window.resize',
						"height"     :   {
							"inner"     :   window.innerHeight,
							"outer"     :   window.outerHeight
						},
						"width"     :   {
							"inner"     :   window.innerWidth,
							"outer"     :   window.outerWidth
						},
						"time"  :   Math.round(+new Date()/1000)
					});
					console.log('Recorded window resize');
					Stats.tmp.timer = null;
				}, 600 );
			},
			recordWindowScroll   :   function() {
				if (window.pageXOffset || window.pageYOffset) {
					sX = window.pageXOffset;
					sY = window.pageYOffset;
				} else {
					sX = document.documentElement.scrollLeft || document.documentElement.scrollLeft;
					sY = document.documentElement.scrollTop || document.documentElement.scrollTop;
				}

				Stats.timeline.push({
					"type"  :   'window.scroll',
					"x"     :   sX,
					"y"     :   sY,
					"time"  :   Math.round(+new Date()/1000)
				});
			},
			setEvent         :   function(event) {
				Stats.tmp.event = event;
			},
			sendData             :   function() {
				delete Stats.tmp;
				delete Stats.functions;
				Stats.finish = Math.round(+new Date()/1000);

				makeCORSRequest(JSON.stringify(Stats));
			}
		}
	};

	document.addEventListener("mousedown", function() {
		if(getMouseButtonPressed(event) == 'left') {
			Stats.functions.recordMouseClick();
		}
	});

	document.addEventListener("mousemove", function() {
		Stats.functions.setEvent(event);
		Stats.functions.recordMouseHover();
	});

	window.addEventListener('scroll', function () {
		Stats.functions.setEvent(event);
		Stats.functions.recordWindowScroll();
		console.log('Recorded Window Scroll.');
	});

	window.addEventListener('resize', function() {
		Stats.functions.recordWindowResize();
	});

	window.addEventListener("beforeunload", function() {
		Stats.functions.sendData();
	});
}

function getEventStats(event) {
	return {
		"x"     : event.pageX,
		"y"     : event.pageY,
		"path"  : getDomStructure(event),
		"time"  : Math.round(+new Date()/1000)
	};
}

function getMouseButtonPressed(event) {
	var button;

	if (event.which == null) {
		/* IE case */
		button = (event.button < 2) ? "left" :
			((event.button == 4) ? "middle" : "right");
	}
	else {
		/* All others */
		button= (event.which < 2) ? "left" :
			((event.which == 2) ? "middle" : "right");
	}

	return button;
}

function getDomStructure(event) {
	var rightArrowParents = [],
		elm,
		entry;

	for (elm = event.target; elm; elm = elm.parentNode) {
		entry = elm.tagName.toLowerCase();
		if (entry === "html") {
			break;
		}
		if (elm.className) {
			entry += "." + elm.className.replace(/ /g, '.');
		}
		rightArrowParents.push(entry);
	}
	rightArrowParents.reverse();
	return rightArrowParents.join(" ");
}

function generateId(size) {
	var text = "";
	var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";

	for( var i=0; i < size; i++ )
		text += possible.charAt(Math.floor(Math.random() * possible.length));

	return text;
}

function createCORSRequest(method, url) {
	var xhr = new XMLHttpRequest();
	if ("withCredentials" in xhr) {
		// XHR for Chrome/Firefox/Opera/Safari.
		xhr.open(method, url, true);
	} else if (typeof XDomainRequest != "undefined") {
		// XDomainRequest for IE.
		xhr = new XDomainRequest();
		xhr.open(method, url);
	} else {
		// CORS not supported.
		xhr = null;
	}
	return xhr;
}

function makeCORSRequest(data) {
	console.log(data);
	var url = 'http://web.dev.concurra.com/api/tracker/v1/log/';

	var xhr = createCORSRequest('POST', url);
	if (!xhr) {
		alert('CORS not supported');
		return;
	}

	// Response handlers.
	xhr.onload = function() {
		var text = xhr.responseText;
		alert('Response from CORS request to ' + url + ': ' + text);
	};

	xhr.onerror = function() {
		alert('Woops, there was an error making the request.');
	};

	xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	xhr.send(data);
}
