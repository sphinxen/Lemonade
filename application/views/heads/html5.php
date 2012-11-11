<!DOCTYPE html>
<html>
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
