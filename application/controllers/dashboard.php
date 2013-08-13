<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

Class Dashboard extends CI_Controller {
	
	public function __construct() 
	{
		parent::__construct();
		$this->load->helper('url');
		$this->load->helper('form');
		$this->load->database();
		
		$this->load->library('tank_auth'); // Authentication library
		
		if (!$this->tank_auth->is_logged_in()) {
			redirect('/auth/login/'); // Redirects if not logged in (SHOULD IMPLEMENT AJAX HANDLER)
		}
	}
	
	public function index() 
	{
		$this->upload_creatives();
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
		$this->load->view('subsections/duplicate_creatives');
		$this->load->view('footer');
	}
	
	public function associate_creatives()
	{
		$data['section'] = 'associate_creatives';
		$this->load->view('header', $data);
		$this->load->view('subsections/associate_creatives');
		$this->load->view('footer');
	}
	
	public function block_urls()
	{
		$data['section'] = 'block_urls';
		$this->load->view('header', $data);
		$this->load->view('subsections/block_urls');
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
		
		$data['offer_types'] = $this->db->order_by('description')->get_where('offer_types', 'parent_id is null')->result();
		for ($i = 0; $i < count($data['offer_types']); $i++) {
			$data['offer_types'][$i]->sub_offer_types = $this->db->get_where('offer_types', array('parent_id'=>$data['offer_types'][$i]->id))->result();
		}
		$data['creative_themes'] = $this->db->select('DISTINCT tertiary_category AS category', FALSE)->where(array('secondary_category'=>'Creative Themes', 'is_enabled'=>1))->get('creative_tags')->result();
		for ($i = 0; $i < count($data['creative_themes']); $i++) {
			$data['creative_themes'][$i]->sub_categories = $this->db->select('id, tag, description')->get_where('creative_tags', array('tertiary_category'=>$data['creative_themes'][$i]->category, 'is_enabled'=>1, 'secondary_category'=>'Creative Themes'))->result();
		}
		
		$data['creative_specs'] = $this->db->select('DISTINCT tertiary_category AS category', FALSE)->where(array('secondary_category'=>'Creative Specs', 'is_enabled'=>1))->get('creative_tags')->result();
		for ($i = 0; $i < count($data['creative_specs']); $i++) {
			$data['creative_specs'][$i]->sub_categories = $this->db->select('id, tag, description')->get_where('creative_tags', array('tertiary_category'=>$data['creative_specs'][$i]->category, 'is_enabled'=>1, 'secondary_category'=>'Creative Specs'))->result();
		}
		
		$data['languages'] = $this->db->get('languages')->result();

		$this->load->view('header', $data);
		$this->load->view('subsections/upload_creatives');
		$this->load->view('footer');
	}
	
	public function test_tags()
	{
		$data['section'] = 'test_tags';
		$this->load->view('header', $data);
		$this->load->view('subsections/test_tags');
		$this->load->view('footer');
	}
	
	public function exclude_publishers()
	{
		$data['section'] = 'exclude_publishers';
		$this->load->view('header', $data);
		$this->load->view('subsections/exclude_publishers');
		$this->load->view('footer');
	}
	
}