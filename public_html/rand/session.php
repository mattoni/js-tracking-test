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

		function init() {
			var Stats = {
				"account"   :   {
					"token" :  "hoEndE7iLa"
				},
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
					"language"      :   navigator.language,
					"cookies"       :   {
						"enabled"       :   navigator.cookieEnabled
					},
					"url"           :   document.URL
				},
				"timeline"  : [],
				"tmp"       :       {
					"event"             : null,
					"timer"             : null
				},
				"functions" :   {
					"recordMouseClick"  :   function()  {
						var coords = getEventCoordinates(Stats.tmp.event);
						coords.type = 'mouse_click';
						Stats.timeline.push(coords);
					},
					"recordMouseMove"   :   function() {
						clearTimeout(Stats.tmp.timer);
						Stats.tmp.timer = setTimeout(function() {
							var coords = getEventCoordinates(Stats.tmp.event);
							coords.type = 'mouse_move';
							Stats.timeline.push(coords);
							Stats.tmp.timer = null;
						}, 200 );
					},
					recordMouseScroll   :   function() {
						if (window.pageXOffset || window.pageYOffset) {
							sX = window.pageXOffset;
							sY = window.pageYOffset;
						} else {
							sX = document.documentElement.scrollLeft || document.documentElement.scrollLeft;
							sY = document.documentElement.scrollTop || document.documentElement.scrollTop;
						}

						Stats.timeline.push({
							"type"  :   'mouse_scroll',
							"x"     :   sX,
							"y"     :   sY,
							"time"  :   Math.round(+new Date()/1000)
						});
					},
					"setEvent"          :   function(event) {
						Stats.tmp.event = event;
					},
					"sendData"             :   function() {
						delete Stats.tmp;
						delete Stats.functions;
						makeCORSRequest(JSON.stringify(Stats));
					}
				}
			};


			document.addEventListener("mousedown", function() {
				Stats.functions.setEvent(event);
				Stats.functions.recordMouseClick();
				console.log('Recorded Mouse Click.');
				Stats.functions.sendData();
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

			window.addEventListener("beforeunload", function() {
				Stats.functions.sendData();
			});
		}

		function getEventCoordinates(event) {
			return {
				"x"     : event.pageX,
				"y"     : event.pageY,
				"time"  : Math.round(+new Date()/1000)
			};
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


</body>

</html>

