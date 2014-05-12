<?php if (jf::$RunMode->IsEmbed()) return;?>

<!--contact modal
==================-->
<div class="modal fade" id="contact">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h2>Contact Us</h2>
			</div>
			<div class="modal-body">
				<p>If you have any queries please write to us at <span style="color:#d2322d">webgoatphp at owasp dot org</span></p>
				<p>Thank You</p>
			</div>
			<div class="modal-footer">
				<a href="#" class="btn btn-default" data-dismiss="modal">Close</a>
			</div>
		</div>
	</div>
</div>

<!--footer
===========-->
<div class="navbar navbar-default navbar-fixed-bottom">
	<div class="container">
		<p class="navbar-text pull-left"><strong>The project is under development.</strong></p>.
		<a href="#" class="btn navbar-btn btn-danger pull-right">Contribute!</a>
	</div>
</div>


<script type="text/javascript" src="<?php echo jf::url().'/script/jquery-2.1.1.min.js'?>"></script>
<script type="text/javascript" src="<?php echo jf::url().'/script/bootstrap.min.js'?>"></script>
</body>
</html>