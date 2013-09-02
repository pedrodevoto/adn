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
	  background-color: #f5f5f5;
	}

	.form-signin {
	  max-width: 300px;
	  padding: 19px 29px 29px;
	  margin: 0 auto 20px;
	  background-color: #fff;
	  border: 1px solid #e5e5e5;
	  -webkit-border-radius: 5px;
	     -moz-border-radius: 5px;
	          border-radius: 5px;
	  -webkit-box-shadow: 0 1px 2px rgba(0,0,0,.05);
	     -moz-box-shadow: 0 1px 2px rgba(0,0,0,.05);
	          box-shadow: 0 1px 2px rgba(0,0,0,.05);
	}
	.form-signin .form-signin-heading,
	.form-signin .checkbox {
	  margin-bottom: 10px;
	}
	.form-signin input[type="text"] {
	  font-size: 16px;
	  height: auto;
	  margin-bottom: 15px;
	  padding: 7px 9px;
	}
	</style>
</head>
<body>
	<?php echo form_open($this->uri->uri_string(), array('class'=>'form-signin')); ?>
	<h2 class="form-signin-heading">Password reset</h2>
	<input name="login" type="text" class="input-block-level" maxlength="80" id="login" placeholder="Username / email address" required />
	<button class="btn btn-large btn-primary" type="submit" name="reset">Get new password</button>

	<?php echo form_close(); ?>
	
</body>
</html>