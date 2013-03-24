		<div id="footer-wrap" class="row">
			<div id="footer span12">
            Justin Schoeff &nbsp;&nbsp;|&nbsp;&nbsp;<a href="<?php echo BASEPATH;?>/contact/" title="click to e-mail me">Contact me</a>
			</div>
        </div>
</div> <!-- /container -->

<!-- SCRIPTIES -->
<?php
foreach($this->JS as $js)
    echo "\t".'<script src="assets/js/'.$js.'.js"></script>'."\n";
?>
</body>
</html>
