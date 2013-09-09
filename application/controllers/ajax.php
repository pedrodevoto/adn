<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

Class Ajax extends CI_Controller {
	
	public function __construct()
	{
		parent::__construct();
		
		$this->load->library('tank_auth'); // Authentication library
		$this->load->helper('url');
		if (!$this->tank_auth->is_logged_in()) {
			//TODO implement not-logged-in-respones in ajax.php
			exit;
		}
	}
	
	public function upload_creatives()
	{
		$t = microtime(true);
		$files = $this->_get_extracted_files();
		if (count($files)==0) {
			die('Error: No files found.');
		}
		
		$this->load->model('Creative');
		$this->load->library('rightmedia');
		
		$creatives = array();
		foreach($files as $file) {
			if ($file['size'] > (40 * 1024)) {
				continue;
			}
			$creative = new Creative();
			
			$creative->advertiser = $this->input->post('advertiser');
			$creative->line = $this->input->post('line');
			$creative->url = trim($this->input->post('url'));
			$creative->offertype = $this->input->post('offertype');
			$creative->prefix = trim($this->input->post('prefix'));
			$creative->clicktag = trim($this->input->post('clicktag'));
			$creative->suffix = trim($this->input->post('suffix'));
			$creative->language = $this->input->post('language');
			$creative->specs = $this->input->post('specs');
			$creative->themes = $this->input->post('themes');

			$creative->type = 'file';
			$creative->setFile($file['server_path']);
			
			$creatives[] = $creative;
		}
		$this->rightmedia->upload_creatives($creatives, $this->input->post('line'));
		echo $this->rightmedia->creatives_uploaded.' creatives uploaded ('.implode(', ', $this->rightmedia->ids).')<br />';
		echo sprintf("Done in %f seconds", microtime(true)-$t);
	}
	
	private function _get_extracted_files()
	{
		$upload_path =  './uploads/'.time().rand().'/';
		if (!mkdir($upload_path)) {
			die('Error: Could not create directory to extract archive');
		}
		
		$config['upload_path'] = $upload_path;
		$config['allowed_types'] = 'zip|rar|jpg|jpeg|png|swf|gif';
		
		$this->load->library('upload', $config);
		
		if (!$this->upload->do_upload('zip')) {
			die('Error: ' . $this->upload->display_errors('', ''));
		}
		$filedata = $this->upload->data();
		
		if ($filedata['is_image'] or $filedata['file_type']=='application/x-shockwave-flash') {
			return array($filedata['full_path']);
		}
		$this->load->library('unzip');
		$this->unzip->allow(array('jpg', 'jpeg', 'png', 'swf', 'gif'));
		
		if (!mkdir($upload_path.'extract')) {
			die('Error: Could not create directory to extract archive');
		}
		
		$this->unzip->extract($filedata['full_path'], $upload_path.'extract');
		if ($this->unzip->error_string('', '')!='') {
			die('Error: '.$this->unzip->error_string('', ', '));
		}
		
		$this->load->helper('file');
		
		$extracted_files = get_dir_file_info($upload_path.'extract', FALSE);
		
		return $extracted_files;
	}
	
	public function get_pixels($io)
	{
		if (!$io)
			return;
		$this->load->library('rightmedia');
		$pixels = $this->rightmedia->get_pixels($io);
		$output = array();
		foreach ($pixels as $pixel) {
			$output[] = array('id'=>$pixel->id, 'name'=>$pixel->name);
		}
		echo json_encode($output);
	}
	
	public function create_test_tag()
	{
		$this->load->model('Test_tag');
		$this->load->library('rightmedia');
		
		$test_tag = new Test_tag();
		$test_tag->io = $this->input->post('io');
		$test_tag->pixel = $this->input->post('pixel');
		
		$this->rightmedia->create_test_tag($test_tag);
		$output = array();
		if ($this->rightmedia->last_error) {
			$output['error'] = $this->rightmedia->last_error;
		}
		else {
			$output['link_to_creats'] = site_url("dashboard/upload_creatives/1/?a=".$test_tag->adv_id."&l=".$test_tag->adv_line);
			$output['link_to_tag'] = site_url('ajax/get_test_tag/'.$test_tag->adv_name.'/'.$test_tag->section);
		}
		echo json_encode($output);
	}

	public function get_test_tag( $advertiser, $section, $size)
	{
		$data['section'] = $section;
		$data['size'] = $size;
		list($data['width'], $data['height']) = explode('x', $size);
		$data['advertiser'] = $advertiser;
		
		$this->load->view('subsections/test_tag', $data);
	}

	public function exclude_publishers()
	{
		$entity_type0 = $this->input->post('entity_type0');
		$lines = $this->numbers_to_array($this->input->post('lines'), ',');
		$entity_type = $this->input->post('entity_type');
		$entity_ids = $this->numbers_to_array($this->input->post('entity_ids'), ',');
		$default = $this->input->post('exclude')?TRUE:FALSE;

		$this->load->library('rightmedia');
		$this->rightmedia->exclude_publishers($entity_type0, $lines, $entity_type, $entity_ids, $default);
		echo implode('<br />', $this->rightmedia->errors);
		echo "Done";
	}
	
	private function numbers_to_array($input, $delimiter)
	{
		$output = array();
		foreach (explode(',', $input) as $item) {
			if (intval($item) > 0 ) {
				$output[] = $item;
			}
		}
		return $output;
	}

	public function arbitrage()
	{
		$adv_lines = $this->numbers_to_array($this->input->post('adv_lines'), ',');
		$this->load->library('rightmedia');
		$this->rightmedia->arbitrage($adv_lines);
		echo implode('<br />', $this->rightmedia->errors);
		echo "Done";
	}
	
	public function copy_targeting()
	{
		$options = $this->input->post('copyall')?'copyall':array(
			'techno'	=>	$this->input->post('techno'),
			'geo'		=>	$this->input->post('geo'),
			'freq'		=>	$this->input->post('freq'),
			'urls'		=>	$this->input->post('urls'),
			'channels'	=>	$this->input->post('channels'),
			'publishers'=>	$this->input->post('publishers'),
			'vurls'		=>	$this->input->post('vurls')
		);
		$from_line = $this->input->post('from_line');
		$to_lines = $this->numbers_to_array($this->input->post('to_lines'), ',');
		$this->load->library('rightmedia');
		$this->rightmedia->copy_targeting($from_line, $to_lines, $options);
		echo implode('<br />', $this->rightmedia->errors);
		echo "Done";
	}

	public function assign_manager()
	{
		$entity_type = $this->input->post('entity_type');
		$entity_ids = $this->numbers_to_array($this->input->post('entity_ids'), ',');
		$contact = $this->input->post('contact');
		$trafficker = $this->input->post('trafficker');
		$this->load->library('rightmedia');
		$this->rightmedia->assign_manager_trafficker($entity_type, $entity_ids, $contact, $trafficker);
		echo implode('<br />', $this->rightmedia->errors);
		echo "Done";
	}
	
	public function deactivate()
	{
		$entity_type = $this->input->post('entity_type');
		$entity_ids = $this->numbers_to_array($this->input->post('entity_ids'), ',');
		$this->load->library('rightmedia');
		$this->rightmedia->deactivate($entity_type, $entity_ids);
		echo implode('<br />', $this->rightmedia->errors);
		echo "Done";
	}
	
	public function get_line_items()
	{
		$entity_type = $this->input->post('entity_type');
		$entity_id = $this->input->post('entity_id');
		$this->load->library('rightmedia');
		if (!$insertion_orders = $this->rightmedia->get_ios_line_items($entity_type, $entity_id)) {
			echo 'err' . $this->rightmedia->last_error;
			return;
		}
		$this->load->helper('form');
		$data['insertion_orders'] = $insertion_orders;
		$this->load->view('subsections/edit_lines_table', $data);
	}
	
	public function update_line_items()
	{
		$line_items = $this->input->post('line_item');
		$this->load->library('rightmedia');
		$this->rightmedia->update_line_items($line_items);
		echo implode('<br />', $this->rightmedia->errors);
		echo "Done";
	}

}