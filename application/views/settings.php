<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="sv" lang="sv">
	<!--
		Name: Screenly
		Version : 0.1
		Site	: http://screener.scripter.se/
		Author	: Kristian Nordman
		Design  : Henrik Norberg
	-->
	<head>
		<title>Showtime ***BETA***</title>
		<meta http-equiv="content-type" content="text/html;charset=utf-8" />
		<meta content="width=device-width; initial-scale=1.0" name="viewport" /> <!-- Ta bort och mobilgudarna skall bajsa p� ditt huvud! -->
    	<script type="text/javascript" src="/js/jquery.js"></script>
    	<script type="text/javascript" src="/js/jquery.ui.js"></script>
    	<script type="text/javascript" src="/js/jquery.form.min.js"></script>
    	<?php
        if (isset($js))
        	foreach ($js as $j)
        		echo '<script type="text/javascript" src="'.$j.'"></script>'."\n";
        ?>
    	<!-- stylesheets -->
		<style type="text/css">
        @import url(/css/style.css);
        @import url(/css/font/stylesheet.css);
        <?php
        if (isset($css))
        	foreach ($css as $c)
        		echo "        @import url('".$c."');\n";
        ?>
        </style>
	</head>
<body>
<div id="wrapper">
	<?php echo View::factory('menu'); ?>
<div id="inner-wrapper">
		<div id="settings_content"><?php echo $content; ?></div>
	</div>

	<div id="footer">
		<p>
			&#169; 2014 Scripter
		</p>
	</div> <!-- footer -->
</div> <!-- wrapper --><?php echo View::factory('forms/playlist'); ?>
<?php echo View::factory('forms/asset'); ?>
<?php echo View::factory('forms/devices'); ?>
<?php echo View::factory('playlist_devices'); ?>
<?php echo View::factory('popup'); ?>
</body>
</html>