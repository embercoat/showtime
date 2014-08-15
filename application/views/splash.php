<html>
  <head>
    <style type="text/css">
        @import url(/css/style.css);
        @import url(/css/font/stylesheet.css);
    </style>
  </head>
  <body style="background: #000000 center no-repeat">
	<div id="logo" style="width: 1050px;margin-left: -25%;position: absolute;left: 50%;top: 125px;">
		<h1 style="color: white; font-size: 95px; float: left; margin-right: 60px;">Showtime</h1>
		<img src="/images/stuk_black.jpg" style="float: left;" />
	</div>
	<div style="width: 1050px;margin-left: -25%;position: absolute;left: 50%;top: 350px; color: white;">
        <p>IP: <span style="font-size: 40px;"><?php echo $data['address']; ?></span></p>
        <p>Name: <span style="font-size: 40px;"><?php echo $data['name']; ?></span></p>
	</div>
  </body>
</html>