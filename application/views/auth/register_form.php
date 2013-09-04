<?php
if ($use_username) {
	$username = array(
		'name'	=> 'username',
		'id'	=> 'username',
		'value' => set_value('username'),
		'maxlength'	=> $this->config->item('username_max_length', 'tank_auth'),
		'size'	=> 30,
	);
}
$email = array(
	'name'	=> 'email',
	'id'	=> 'email',
	'value'	=> set_value('email'),
	'maxlength'	=> 80,
	'size'	=> 30,
);
$password = array(
	'name'	=> 'password',
	'id'	=> 'password',
	'value' => set_value('password'),
	'maxlength'	=> $this->config->item('password_max_length', 'tank_auth'),
	'size'	=> 30,
);
$confirm_password = array(
	'name'	=> 'confirm_password',
	'id'	=> 'confirm_password',
	'value' => set_value('confirm_password'),
	'maxlength'	=> $this->config->item('password_max_length', 'tank_auth'),
	'size'	=> 30,
);
$captcha = array(
	'name'	=> 'captcha',
	'id'	=> 'captcha',
	'maxlength'	=> 8,
);
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
	.form-signin #username {
	  margin-bottom: -1px;
	  border-bottom-left-radius: 0;
	  border-bottom-right-radius: 0;
	}
	.form-signin #email,
	.form-signin #password {
  	  border-bottom-left-radius: 0;
  	  border-bottom-right-radius: 0;
	  border-top-left-radius: 0;
	  border-top-right-radius: 0;
	  margin-bottom: -1px;
	}
	
	.form-signin #confirm_password {
	  margin-bottom: 10px;
	  border-top-left-radius: 0;
	  border-top-right-radius: 0;
	}
	</style>

	<script>
	function checkPassword(input) {
	    if ($(input).val() != $('#password').val()) {
	        input.setCustomValidity('The two passwords must match.');
	    } else {
	        // input is valid -- reset the error message
	        input.setCustomValidity('');
	   }
	}
	</script>
</head>
<body>
    <div class="container">
		<?php echo form_open($this->uri->uri_string(), array('class'=>'form-signin')); ?>
			<h2 class="form-signin-heading">Please register</h2>
			<?php foreach($errors as $error): ?>
			<p class="text-danger"><?=$error?></p>
			<?php endforeach;?>
			<input type="text" class="form-control" name="username" value="" id="username" maxlength="20" placeholder="Username" required autofocus />

			<input type="email" class="form-control" name="email" value="" id="email" maxlength="80" placeholder="Email" required />
	
			<input type="password" class="form-control" name="password" value="" id="password" maxlength="20" placeholder="Password" required />

			<input type="password" class="form-control" name="confirm_password" value="" id="confirm_password" maxlength="20" placeholder="Confirm password" required oninput="checkPassword(this)" />		
			
			<button class="btn btn-lg btn-primary btn-block" type="submit" name="register" value="Register">Register</button>
		<?php echo form_close(); ?>
</body>
</html>