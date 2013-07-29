<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

Class Dashboard extends CI_Controller {
	
	public function __construct() 
	{
		parent::__construct();
		$this->load->helper('url');
		$this->load->helper('form');
	}
	
	public function index() 
	{
		$data['section'] = 'publishers';
		$this->load->view('header', $data);
		$this->load->view('subsections/publishers');
		$this->load->view('footer');
	}
	
	public function segments()
	{
		$data['section'] = 'segments';
		$this->load->view('header', $data);
		$this->load->view('subsections/segments');
		$this->load->view('footer');
	}
	
	public function duplicate_creatives()
	{
		$data['section'] = 'duplicate_creatives';
		$this->load->view('header', $data);
		
		$this->load->view('footer');
	}
	
	public function associate_creatives()
	{
		$data['section'] = 'associate_creatives';
		$this->load->view('header', $data);
		
		$this->load->view('footer');
	}
	
	public function block_urls()
	{
		$data['section'] = 'block_urls';
		$this->load->view('header', $data);
		
		$this->load->view('footer');
	}
	
	public function actions()
	{
		$data['section'] = 'actions';
		$this->load->view('header', $data);
		
		$this->load->view('footer');
	}
	
	public function upload_creatives()
	{
		$data['section'] = 'upload_creatives';
		$this->load->view('header', $data);
		
		$this->load->view('footer');
	}
	
	public function test_tags()
	{
		$data['section'] = 'test_tags';
		$this->load->view('header', $data);
		
		$this->load->view('footer');
	}
	
	public function exclude_publishers()
	{
		$data['section'] = 'exclude_publishers';
		$this->load->view('header', $data);
		
		$this->load->view('footer');
	}
	
}