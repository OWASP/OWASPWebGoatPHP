<!--navbar
============-->
<div class="navbar navbar-inverse navbar-static-top">
	<div class="container">
		<a href="#" class="navbar-brand" style="color:white"><b>Single User Mode</b></a>

		<button class="navbar-toggle" data-toggle="collapse" data-target=".navHeaderCollapse">
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
		</button>
		<div class="collapse navbar-collapse navHeaderCollapse">
			<ul class="nav navbar-nav navbar-right">
				<li><a href="<?php echo jf::url()?>">Home</a></li>
				<li><a href="<?php echo jf::url().'/about'?>">About</a></li>
				<li><a href="#">Documentation</a></li>
				<li><a href="#">Github</a></li>
				<li><a href="#contact" data-toggle="modal">Contact</a></li>
				<li><a href="<?php echo jf::url().'/user/logout'?>">Logout</a></li>
			</ul>
		</div>
	</div>
</div>

<div class="container">
	<div class="row">
		<div class="col-lg-3">

			<!--Accordion
			============-->
			<div class="panel-group" id="accordion">

				<div class="panel panel-default">
					<div class="panel-heading">
						<h4 class="panel-title">
							<a data-toggle="collapse" data-parent="accordion" href="#section1">
								General
							</a>
						</h4>
					</div>

					<div class="panel-collapse collapse" id="section1">
						<div class="panel-body">
							<ul class="nav nav-pills nav-stacked">
	  							<li><a href="#">HTTP Basics</a></li>
				  				<li><a href="#">HTTP splitting</a></li>
							</ul>
						</div>
					</div>
				</div>

				<div class="panel panel-default">
					<div class="panel-heading">
						<h4 class="panel-title">
							<a data-toggle="collapse" data-parent="accordion" href="#section2">
								Access Control Flaws
							</a>
						</h4>
					</div>

					<div class="panel-collapse collapse" id="section2">
						<div class="panel-body">
							<ul class="nav nav-pills nav-stacked">
	  							<li><a href="#"><span class="glyphicon glyphicon-ok"></span> Using Access Control Matrix</a></li>
				  				<li><a href="#"><span class="glyphicon glyphicon-ok"></span> Bypass RBAC</a></li>
							</ul>
						</div>
					</div>
				</div>

				<div class="panel panel-default">
					<div class="panel-heading">
						<h4 class="panel-title">
							<a data-toggle="collapse" data-parent="accordion" href="#section3"> Authentication Flaws
							</a>
						</h4>
					</div>

					<div class="panel-collapse collapse in" id="section3">
						<div class="panel-body">
							<ul class="nav nav-pills nav-stacked">
	  							<li class="active"><a href="#"><span class="glyphicon glyphicon-ok"></span> Forgot Password</a></li>
				  				<li><a href="#">Password Strength</a></li>
				  				<li><a href="#">Basic Authentication</a></li>
				  				<li><a href="#">Multi Level Login 1</a></li>
				  				<li><a href="#">Multi Level Login 2</a></li>
							</ul>
						</div>
					</div>
				</div>

				<div class="panel panel-default">
					<div class="panel-heading">
						<h4 class="panel-title">
							<a data-toggle="collapse" data-parent="accordion" href="#section4">
								Brute Force
							</a>
						</h4>
					</div>

					<div class="panel-collapse collapse" id="section4">
						<div class="panel-body">
							<ul class="nav nav-pills nav-stacked">
	  							<li><a href="#">Brute Force Challenge 1</a></li>
				  				<li><a href="#">Brute Force Challenge 2</a></li>
				  				<li><a href="#">Brute Force Challenge 3</a></li>
							</ul>
						</div>
					</div>
				</div>


				<div class="panel panel-default">
					<div class="panel-heading">
						<h4 class="panel-title">
							<a data-toggle="collapse" data-parent="accordion" href="#section5">
								Cross Site Scripting
							</a>
						</h4>
					</div>

					<div class="panel-collapse collapse" id="section5">
						<div class="panel-body">
							<ul class="nav nav-pills nav-stacked">
	  							<li><a href="#">Phishing with XSS</a></li>
				  				<li><a href="#">Stored XSS</a></li>
				  				<li><a href="#">Reflected XSS</a></li>
							</ul>
						</div>
					</div>
				</div>

				<div class="panel panel-default">
					<div class="panel-heading">
						<h4 class="panel-title">
							<a data-toggle="collapse" data-parent="accordion" href="#section6">
								Injection Flaws
							</a>
						</h4>
					</div>

					<div class="panel-collapse collapse" id="section6">
						<div class="panel-body">
							<ul class="nav nav-pills nav-stacked">
	  							<li><a href="#">Blind SQL injection</a></li>
				  				<li><a href="#">XPATH injection</a></li>
							</ul>
						</div>
					</div>
				</div>

				<div class="panel panel-default">
					<div class="panel-heading">
						<h4 class="panel-title">
							<a data-toggle="collapse" data-parent="accordion" href="#section7">
								Network Attacks
							</a>
						</h4>
					</div>

					<div class="panel-collapse collapse" id="section7">
						<div class="panel-body">
							<ul class="nav nav-pills nav-stacked">
	  							<li><a href="#">Man In The Middle</a></li>
				  				<li><a href="#">DDOS attack</a></li>
							</ul>
						</div>
					</div>
				</div>

				<div class="panel panel-default">
					<div class="panel-heading">
						<h4 class="panel-title">
							<a data-toggle="collapse" data-parent="accordion" href="#section8">
								Session Management
							</a>
						</h4>
					</div>

					<div class="panel-collapse collapse" id="section8">
						<div class="panel-body">
							<ul class="nav nav-pills nav-stacked">
	  							<li><a href="#">Session Fixation</a></li>
							</ul>
						</div>
					</div>
				</div>

				<div class="panel panel-default">
					<div class="panel-heading">
						<h4 class="panel-title">
							<a data-toggle="collapse" data-parent="accordion" href="#section9">
								Web Services
							</a>
						</h4>
					</div>

					<div class="panel-collapse collapse" id="section9">
						<div class="panel-body">
							<ul class="nav nav-pills nav-stacked">
	  							<li><a href="#">SOAP requests</a></li>
				  				<li><a href="#">WSDL scanning</a></li>
							</ul>
						</div>
					</div>
				</div>

			</div><!--Accordion ends-->
		</div>

		<div class="col-lg-9">

			<!-- Main content
			==================-->
			<div class="panel panel-default">
				<div class="panel-body">
					<div class="page-header">
						<h3>Forgot Password</h3>
					</div>
					<div class="page-body">
						<p>Web Applications frequently provide their users the ability to retrieve a forgotten password. Unfortunately, many web applications fail to implement the mechanism properly. The information required to verify the identity of the user is often overlt simplistic.
						</p>
						<h4>General Goal(s)</h4>
						<p>Users can retreive their password if they can answer the secret question properly. There is no lock-out mechanism on this 'Forgot Password' page. Your user name is 'webgoatphp' and your favorite color is 'red'.
						</p>
						<br>
						<div class="span5 center">
							<h4><strong>WebgoatPHP password recovery</strong></h4>
							<p>
								Please input your username, See the OWASP admin if you do not have an account
							</p>
							<form class="form-inline" role="form">
									<div class="form-group">
						    		<label class="sr-only" for="exampleInputEmail2">UserName</label>
									<input type="email" class="form-control" id="exampleInputEmail2" placeholder="Enter email">
	  							</div>
					            <button type="submit" class="btn btn-default">Submit</button>
							</form>
						</div>
					</div>
				</div>
			</div><!--Main content ends-->
			<hr>


			<!-- Options
			============-->
			<div class="row">
				<div class="col-lg-9 center"><!--To Place it in the center-->
					<div class="btn-group" data-toggle="buttons">
				  		<label class="btn btn-default">
    						<input type="radio" name="options" id="option1"> Hints
  						</label>
  						<label class="btn btn-default">
    						<input type="radio" name="options" id="option2"> Parameter Inspector
  						</label>
  						<label class="btn btn-default">
    						<input type="radio" name="options" id="option3"> Cookie Inspector
  						</label>
  						<label class="btn btn-default">
    						<input type="radio" name="options" id="option4"> Lesson Plan
  						</label>
  						<label class="btn btn-default">
    						<input type="radio" name="options" id="option5"> Show Code
  						</label>
  						<label class="btn btn-default">
    						<input type="radio" name="options" id="option6"> Solution
  						</label>
					</div>
				</div>
			</div>

		</div>
	</div><!--Row ends-->
</div><!--container ends-->