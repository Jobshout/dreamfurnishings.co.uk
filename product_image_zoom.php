<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>jQuery Image Zoom Demo</title>

<link href="http://www.jqueryscript.net/css/jquerysctipttop.css" rel="stylesheet" type="text/css">
<link href="jquery.imagezoom.css" rel="stylesheet" type="text/css">
</head>

<body>

<h1 style="margin-top:150px;">jQuery Image Zoom Demo</h1>
<div class="demo">
  <a href="large.jpg">
    <img src="small.jpg" alt="Alt text"/>
  </a>
</div>
<script src="http://code.jquery.com/jquery-1.11.1.min.js"></script>
<script src="jquery.imagezoom.js"></script>
<script>
$('.demo').imageZoom();
</script>
</body>
</html>
