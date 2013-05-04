<footer id="footer" class="clearfix">
		<div>
			<?php echo $footer;?>

		</div>

		<!-- BOTTOM -->
		<div id="bottom" class="bottom">
			<div id="bottom-left" class="left">
				<a href="http://www.sphinxen.se/testlab">TestLab</a>
			</div>
			<div id="bottom-center" class="left align-center">
				<a href="http://validator.w3.org/check?uri=http%3A%2F%2Fwww.sphinxen.se%2Ftestlab&charset=%28detect+automatically%29&doctype=Inline&group=0&user-agent=W3C_Validator%2F1.3">Validator</a>
			</div>

			<div id="bottom-right" class="left align-right">
				<p>Thirsty for more?<br />
				Try some <cite><a href="https://github.com/sphinxen/Lemonade">lemonade</a></cite>!<br>
				</p>
			</div>
		</div>
</footer>

	<?php foreach ($javascripts as $javascript) :?>
		<script src="<?php echo $javascript;?>" type="text/javascript"></script>
	<?php endforeach;?>


</body>
</html>

