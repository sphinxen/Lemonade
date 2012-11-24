<?php 
/**
*	@author Josef Karlsson <sphinxen83@gmail.com>
*	@package Lemonade
*
* 	This is a template for display posts and comments
*/

if(!defined('BASE')) die('No direct access!');
?>

<div id="<?php echo $id;?>" class="<?php echo $class;?>">
	<div class="<?php echo $class;?>-headline"><img src="<?php echo $grav_url;?>" />
		<?php echo $author;?>  
		<?php echo $date;?>
		<?php if ($id_user == $_SESSION['id']) : ?>
		<div class="right"><a href="<?php echo $url;?>/delete/<?php echo $post_id;?>">delete</a> | <a href="<?php echo $url;?>/edit/<?php echo $post_id;?>">edit</a></div>
		<?php endif;?>
	</div>
	<div class="<?php echo $class;?>-data"><?php echo $post;?></div>
	<?php if($edited != null) :?>
		<div>Last edited: <?php echo $edited;?></div>
	<?php endif;?>
	<br/>
	<p><a href="<?php echo $url;?>/add/<?php echo $post_id;?>/0">comment</a></p>

</div>
<hr />