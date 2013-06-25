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

        <script type="text/javascript" src="<?php echo BASE;?>assets/ckeditor/ckeditor.js"></script>
        <script type="text/javascript" src="<?php echo BASE;?>assets/js/less-min.js"></script>
        <script type="text/javascript">var BASE_URL = '<?php echo BASE; ?>';</script> 

</head>

<body>
<div id='base-container' class='container'>
		<header id='header'>
			<?php echo !empty($menu['user']) ? $this->menu->GenerateNavigation($menu['user']) : '';?>
			<div class="logo clearfix">
				<a class="" href="<?php echo BASE?>">
				<span class="left">
					<?php echo !empty($logo) ? '<img src="'.$logo.'" alt="Logo" />' : '';?><?php echo $title?>
				</span>
				</a>
			</div>
			<?php echo $header;?>
		</header>
		<nav>
		</nav>
		<div class="content main">
			<?php echo $content['main'] ?>
		</div>
		<footer id="footer" class="clearfix">
			<div id="bottom-left" class="left">
				<a href="http://www.sphinxen.se/testlab">TestLab</a>
			</div>
			<div id="bottom-center" class="left align-center">
				<a target="_blank" href="http://validator.w3.org/check?uri=http%3A%2F%2Fwww.sphinxen.se%2F&amp;charset=%28detect+automatically%29&amp;doctype=Inline&amp;group=0&amp;user-agent=W3C_Validator%2F1.3">Validator</a>
			</div>

			<div id="bottom-right" class="left align-right">
				<p>Thirsty for more?<br />
				Try some <cite><a href="https://github.com/sphinxen/Lemonade">lemonade</a></cite>!<br>
				</p>
			</div>
		</footer>
</div>

		<script src="<?php echo BASE;?>assets/js/lemonade.js" type="text/javascript"></script>

</body>
</html>