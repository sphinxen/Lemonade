<?xml version="1.0" encoding="ISO-8859-4"?> 
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title><?php echo $title;?></title>
	<link rel="icon" type="image/ico" href="<?php echo BASE;?>assets/images/favicon.ico" />
	<link rel="shortcut icon" href="<?php echo BASE;?>assets/images/favicon.ico" />

	<meta http-equiv="Content-Type" content="text/html; charset='ISO-8859-4'" />

 	<?php foreach ($stylesheets as $stylesheet) :?>
	<link rel="stylesheet" type="text/css" href="<?php echo $stylesheet;?>" media="screen" />
	<?php endforeach;?>

	<script type="text/javascript">
  		var BASE_URL = '<?php echo BASE; ?>';
	</script> 

	<?php foreach ($javascripts as $javascript) :?>
		<script src="<?php echo $javascript;?>" type="text/javascript"></script>
	<?php endforeach;?>
</head>
