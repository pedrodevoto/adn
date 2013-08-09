<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Cli extends CI_Controller {
	
	public function download_size_enum()
	{
		$this->load->model('Creative');
		$this->load->library('rightmedia');
		
		$this->rightmedia->download_size_enum();
	}
	
	public function download_offer_types()
	{
		$this->load->library('rightmedia');
		$this->rightmedia->download_offer_types();
	}
	
	public function download_creative_tags()
	{
		$this->load->library('rightmedia');
		$this->rightmedia->download_creative_tags();
	}
	
	public function download_languages()
	{
		$this->load->library('rightmedia');
		$this->rightmedia->download_languages();
	}
	
	public function set_rm_credentials($username, $password)
	{		
		$this->load->library('Rightmedia', array('login'=>FALSE));
		$this->rightmedia->set_credentials($username, $password);
	}
}