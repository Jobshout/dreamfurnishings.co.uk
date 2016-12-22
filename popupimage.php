<?php
$src = isset($_GET["src"]) ? $_GET["src"] : "";
$name = isset($_GET["name"]) ? $_GET["name"] : "Dream Furnishings";
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta name="viewport" content="initial-scale=1, maximum-scale=1, user-scalable=no">
		<meta http-equiv="X-UA-Compatible" content="IE=Edge">

		<title><?php echo $name; ?></title>



		<!--[if LTE IE 8]>
			<link href="http://formstone.it/css/demo.ie.css" rel="stylesheet" type="text/css" media="all">
		<![endif]-->

		<script src="js/jquery-1.12.0.min.js"></script>

		<link href="zoomer/jquery.fs.zoomer.css" rel="stylesheet" type="text/css" media="all">
		<script src="zoomer/jquery.fs.zoomer.js"></script>

		<!--[DEMO:START-RESOURCES]-->

		<script src="zoomer/ie/jquery.fs.zoetrope.min.js"></script>

		<style>
			.demo .zoomer_wrapper { height: 500px; margin: 10px 0; overflow: hidden; width: 100%; }

			.demo .zoomer.dark_zoomer { background: #333 url(zoomer-bg-dark.png) repeat center; }
			.demo .zoomer.dark_zoomer img { box-shadow: 0 0 5px rgba(0, 0, 0, 0.5); }
		</style>

		<script>
			$(document).ready(function() {
				$(".demo .zoomer_basic").zoomer();

				$(".demo .zoomer_custom").zoomer({
					controls: {
						zoomIn: ".zoomer_custom_zoom_in",
						zoomOut: ".zoomer_custom_zoom_out"
					},
					customClass: "dark_zoomer",
					increment: 0.03,
					interval: 0.1,
					marginMax: 50
				});

			

				$(window).on("resize", function(e) {
					$(".demo .zoomer_wrapper").zoomer("resize");
				});

				$(window).one("pronto.load", function() {
					$(".demo .zoomer_basic").zoomer("destroy");
					$(".demo .zoomer_custom").zoomer("destroy");
					$(".demo .zoomer_tiled").zoomer("destroy");
					$(".demo .load_zoomer_tiled").off("click");
				});
			});
		</script>

		<!--[DEMO:END-RESOURCES]-->

	</head>
	<body class="gridlock demo" style="background-color:#eee">
		<article class="row page">
			<div class="mobile-full tablet-full desktop-8 desktop-push-2">
				
				<!--[DEMO:START-CONTENT]-->

				<div class="zoomer_wrapper zoomer_basic">
					<img src="<?php echo $src;?>">
                </div>
				
		  </div>
		</article>
		
	</body>
</html>
