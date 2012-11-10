<?php $this->view('heads/xhtml-1.0-strict');?>
<body>
	<div id='container' class='span-24'>
		<div id='header'>
			<?php echo !empty($menu['user']) ? $this->menu->GenerateNavigation($menu['user']) : '';?>
			<div class="logo clearfix">
				<a class="" href="<?php echo BASE?>">			
				<span class="left">
					<?php echo !empty($logo) ? '<img src="'.$logo.'" alt="Logo" />' : '';?><?php echo $textlogo;?>
				</span>
				</a>
			</div>
			<?php echo $this->menu->GenerateNavigation($menu['main']);?>
			<?php echo $header;?>
		</div>
		<div id="content" class="clearfix">
			<div id="content-left" class="span-6">
				<?php echo $content['left'];?>
			</div>
			<div id="content-main" class="span-11">
	 			<?php echo $content['main'];?>
	 		</div>
	 		<div id="content-right" class="span-6 right">
	 			<?php echo $this->menu->GenerateNavigation($menu['admin']); ?>
	 			<?php echo $content['right'];?>
	 		</div>
		</div>
		<div id="footer">
			<?php echo $footer;?>
		</div>
	</div>
</body>
</html>