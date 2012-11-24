<!DOCTYPE html>
<html>
<head>
        <title><?php echo $title;?></title>
        <link rel="icon" type="image/ico" href="<?php echo BASE;?>assets/images/favicon.ico" />
        <link rel="shortcut icon" href="<?php echo BASE;?>assets/images/favicon.ico" />

        <meta charset="UTF-8"/>

        <?php foreach ($stylesheets as $stylesheet) :?>
        <link rel="stylesheet" type="text/css" href="<?php echo $stylesheet;?>" media="screen" />
        <?php endforeach;?>

        
        <link rel="stylesheet/less" type="text/css" href="<?php echo BASE;?>assets/css/style.less" media="screen" />
        

        <script type="text/javascript" src="<?php echo BASE;?>assets/js/less-min.js"></script>
        <script type="text/javascript">var BASE_URL = '<?php echo BASE; ?>';</script> 

</head>
