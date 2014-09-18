<!doctype html>

<html>
<head>
	<title>Record Events</title>
	<meta charset="utf-8">
	<style>
		/*
		test scrolling
		*/
		html,body { height:300%; }
	</style>
	<script type="text/javascript">
		document.addEventListener("DOMContentLoaded", init, false);

		/**
		 * Cookie Object
		 */
		function Cookie(name) {
			this.name = name;

			this.set = function(data, expire) {
				if (expire) {
					alert(expire.toUTCString);
					var expire_string = null;
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


		function Session() {
			var cookie = new Cookie('session');

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
				var session = {
					"id"        : generateId(),
					"activity"  : Math.round(+new Date()/1000)
				};

				cookie.set(JSON.stringify(session), 10);
			}

			function update() {
				var session = JSON.parse(cookie.read());

				session.activity = Math.round(+new Date()/1000);

				cookie.set(JSON.stringify(session), 10);
			}
		}

		function init() {
			alert(new Cookie('session').read());

			Session();

			var Stats = {
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
				"client"    :   {
					"agent"         :   navigator.userAgent,
					"referrer"      :   document.referrer.split('/')[2],
					"language"      :   navigator.language,
					"cookies"       :   {
						"enabled"       :   navigator.cookieEnabled
					},
					"url"           :   document.URL
				},
				"timeline"  : {
					"clicks"        :       [],
					"movements"     :       [],
					"scrolls"       :       [],
					"resizes"       :       []
				},
				"tmp"       :       {
					"event"             : null,
					"timer"             : null
				},
				"functions" :   {
					recordMouseClick  :   function()  {
						Stats.timeline.clicks.push(getEventStats(Stats.tmp.event));
					},
					recordMouseMove   :   function() {
						clearTimeout(Stats.tmp.timer);
						Stats.tmp.timer = setTimeout(function() {
							Stats.timeline.movements.push(getEventStats(Stats.tmp.event));
							Stats.tmp.timer = null;
						}, 200 );
					},
					recordWindowResize  :  function() {
						clearTimeout(Stats.tmp.timer);
						Stats.tmp.timer = setTimeout(function() {
							Stats.timeline.resizes.push({
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
					recordMouseScroll   :   function() {
						if (window.pageXOffset || window.pageYOffset) {
							sX = window.pageXOffset;
							sY = window.pageYOffset;
						} else {
							sX = document.documentElement.scrollLeft || document.documentElement.scrollLeft;
							sY = document.documentElement.scrollTop || document.documentElement.scrollTop;
						}

						Stats.timeline.scrolls.push({
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
				Stats.functions.recordMouseMove();
			});

			window.addEventListener('scroll', function () {
				Stats.functions.setEvent(event);
				Stats.functions.recordMouseScroll();
				console.log('Recorded Mouse Scroll.');
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

		function generateId() {
			var text = "";
			var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";

			for( var i=0; i < 5; i++ )
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
			var url = 'http://alexmattoni.com/rand/recordsession.php';

			var xhr = createCORSRequest('POST', url);
			if (!xhr) {
				alert('CORS not supported');
				return;
			}

			// Response handlers.
			xhr.onload = function() {
				var text = xhr.responseText;
				console.log('Response from CORS request to ' + url + ': ' + text);
			};

			xhr.onerror = function() {
				alert('Woops, there was an error making the request.');
			};

			xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
			xhr.send(data);
		}


	</script>
</head>
<body>
	<span>I'm Watching You. O_O</span>
	<div class="myclass">
		<div class="nested">
			test
		</div>
		<div class="hi">
			CLICK ME JAKE
		</div>
	</div>
	<p>asdfasfasf</p>
</body>

</html>

