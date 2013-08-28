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
	<link rel="stylesheet" href="<?php echo base_url();?>static/css/bootstrap.min.css" type="text/css" />
	
	<!-- Validation -->
	<script type="text/javascript" src="<?php echo base_url();?>static/js/jquery.validate.min.js"></script>
	
	<!-- Form plugin -->
	<script type="text/javascript" src="<?php echo base_url();?>static/js/jquery.form.min.js"></script>
	
	<!-- Multiple select plugin -->
	<script type="text/javascript" src="<?php echo base_url();?>static/js/bootstrap-select.min.js"></script>
	<link rel="stylesheet" href="<?php echo base_url();?>static/css/bootstrap-select.css" type="text/css" />
	

</head>
<body>
	<div class="navbar">
	    <div class="navbar-inner">
	      <div class="container" style="width: auto;">
	        <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
	          <span class="icon-bar"></span>
	          <span class="icon-bar"></span>
	          <span class="icon-bar"></span>
	        </a>
	        <a class="brand" href="<?=site_url('dashboard/index')?>">AdNetwork.net</a>
	        <div class="nav-collapse">
	          <ul class="nav">
	            <li <?php if($section=='publishers'){?>class="active"<?php }?>><a href="<?=site_url('dashboard/publishers')?>">Publishers</a></li>
	            <!--<li <?php if($section=='segments'){?>class="active"<?php }?>><a href="<?=site_url('dashboard/segments')?>">Segments</a></li>
	            <li <?php if($section=='duplicate_creatives'){?>class="active"<?php }?>><a href="<?=site_url('dashboard/duplicate_creatives')?>">Duplicador</a></li>
	            <li <?php if($section=='associate_creatives'){?>class="active"<?php }?>><a href="<?=site_url('dashboard/associate_creatives')?>">Creatives</a></li>
	            <li <?php if($section=='block_urls'){?>class="active"<?php }?>><a href="<?=site_url('dashboard/block_urls')?>">URLs blocking</a></li>
	            <li <?php if($section=='actions'){?>class="active"<?php }?>><a href="<?=site_url('dashboard/actions')?>">Logs</a></li> -->
	            <li <?php if($section=='upload_creatives'){?>class="active"<?php }?>><a href="<?=site_url('dashboard/upload_creatives')?>">Creative Upload</a></li>
	            <li <?php if($section=='test_tags'){?>class="active"<?php }?>><a href="<?=site_url('dashboard/test_tags')?>">Test Tag</a></li>
	            <li <?php if($section=='exclude_publishers'){?>class="active"<?php }?>><a href="<?=site_url('dashboard/exclude_publishers')?>">Exclude Pub/Adv</a></li>
				<li <?php if($section=='arbitrage'){?>class="active"<?php }?>><a href="<?=site_url('dashboard/arbitrage')?>">Arbitrage</a></li>
				<li <?php if($section=='copy_targeting'){?>class="active"<?php }?>><a href="<?=site_url('dashboard/copy_targeting')?>">Copy Targeting</a></li>
	          </ul>
	          <ul class="nav pull-right">
	            <li class="divider-vertical"></li>
	              <ul class="nav">
		            <li><a href="<?=site_url('auth/logout')?>">Salir</a></li>
		          </ul>
	          </ul>
	        </div><!-- /.nav-collapse -->
	      </div>
	    </div><!-- /navbar-inner -->
	  </div>

	<div class="container" >
	      <div class="row" style="min-height: 550px;">
        	  