<?php
$src = isset($_GET["src"]) ? $_GET["src"] : "";
$name = isset($_GET["name"]) ? $_GET["name"] : "Dream Furnishings";
?>

<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title><?php echo $name; ?></title>
    <script src="js/jquery-1.12.0.min.js"></script>
    <script type="text/javascript" src="zoomlib/jquery-ui.js"></script>
    <script type="text/javascript" src="zoomlib/hammer.min.js"></script>
    <script type="text/javascript" src="zoomlib/jquery.hammer.js"></script>
    <script type="text/javascript" src="zoomlib/jquery.mousewheel.min.js"></script>
    <script type="text/javascript" src="zoomlib/src/imgViewer.js"></script>
</head>
<body>
            <div align="center">
                <img  id="image4" src="<?php echo $src;?>"/>
            </div>
<script type="text/javascript">
;(function($) {

    $("#image4").imgViewer();
})(jQuery);
</script>
    </body>
</html>