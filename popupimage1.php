<?php
$src = isset($_GET["src"]) ? $_GET["src"] : "";
$name = isset($_GET["name"]) ? $_GET["name"] : "Dream Furnishings";
?>

<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title><?php echo $name; ?></title>

<link href="http://www.jqueryscript.net/css/jquerysctipttop.css" rel="stylesheet" type="text/css">
<link href="css/jquery.imagefullzoom.css" rel="stylesheet" type="text/css">
    <style>
.imageZoom-buttons {
    margin-top: 11px;
    float: left;
    position: absolute;
    font-size: 85px;
    padding-left: 10px;
    z-index: 1000;
    cursor: pointer;
    top:0px;
}
    </style>
</head>
<body>
<div class="demo" style="margin: 0 auto;">
  <a href="<?php echo $src;?>">
    <img src="<?php echo $src;?>" alt="Alt text"/>
  </a>
</div>
<script src="js/jquery-1.12.0.min.js"></script>
<script src="js/jquery.imagefullzoom.js?1234"></script>
<script>
$('.demo').imageZoom();
</script>


</body>
</html>
