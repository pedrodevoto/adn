<?php
$login = array(
	'name'	=> 'login',
	'id'	=> 'login',
	'value' => set_value('login'),
	'maxlength'	=> 80,
	'size'	=> 30,
);
if ($this->config->item('use_username', 'tank_auth')) {
	$login_label = 'Email or login';
} else {
	$login_label = 'Email';
}
?>
<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Dashboard</title>
	<!-- Le HTML5 shim, for IE6-8 support of HTML elements -->
    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->

	<!-- jQuery  -->
	<!--<script type="text/javascript" src="<?php echo base_url();?>static/js/jquery-1.10.2.min.js"></script>-->
	<script type="text/javascript" src="//code.jquery.com/jquery.js"></script>
	
	<!-- Bootstrap -->
	<script type="text/javascript" src="<?php echo base_url();?>static/js/bootstrap.min.js"></script>
	<link rel="stylesheet" href="<?php echo base_url();?>static/css/bootstrap.css" type="text/css" />
	
	<!-- Validation -->
	<script type="text/javascript" src="<?php echo base_url();?>static/js/jquery.validate.min.js"></script>
	
	<!-- Form plugin -->
	<script type="text/javascript" src="<?php echo base_url();?>static/js/jquery.form.min.js"></script>
	
	<!-- Multiple select plugin -->
	<script type="text/javascript" src="<?php echo base_url();?>static/js/bootstrap-select.min.js"></script>
	<link rel="stylesheet" href="<?php echo base_url();?>static/css/bootstrap-select.css" type="text/css" />
	

	
	<style>
	body {
	  padding-top: 40px;
	  padding-bottom: 40px;
	  background-color: #eee;
	}

	.form-signin {
	  max-width: 330px;
	  padding: 15px;
	  margin: 0 auto;
	}
	.form-signin .form-signin-heading,
	.form-signin .checkbox {
	  margin-bottom: 10px;
	}
	.form-signin .checkbox {
	  font-weight: normal;
	}
	.form-signin .form-control {
	  position: relative;
	  font-size: 16px;
	  height: auto;
	  padding: 10px;
	  -webkit-box-sizing: border-box;
	     -moz-box-sizing: border-box;
	          box-sizing: border-box;
	}
	.form-signin .form-control:focus {
	  z-index: 2;
	}
	.form-signin input[type="text"] {
	  margin-bottom: -1px;
/*	  border-bottom-left-radius: 0;
	  border-bottom-right-radius: 0;*/
	}
	.form-signin input[type="password"] {
	  margin-bottom: 10px;
	  border-top-left-radius: 0;
	  border-top-right-radius: 0;
	}
	</style>

</head>
<body>
    <div class="container">
		<?php echo form_open($this->uri->uri_string(), array('class'=>'form-signin')); ?>
		<h2 class="form-signin-heading">Password reset</h2>
		<input name="login" type="text" class="form-control" id="login" placeholder="User / email address" required autofocus />
		<br />
        <button class="btn btn-lg btn-primary btn-block" type="submit" name="reset">Get new password</button>

		<?php echo form_close(); ?>
    </div> <!-- /container -->
	
</body>
</html>