<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

Class Ajax extends CI_Controller {
	
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
			$creative = new Creative();
			
			$creative->advertiser = $this->input->post('advertiser');
			$creative->line = $this->input->post('line');
			$creative->url = $this->input->post('url');
			$creative->offertype = $this->input->post('offertype');
			$creative->prefix = $this->input->post('prefix');
			$creative->clicktag = $this->input->post('clicktag');
			$creative->suffix = $this->input->post('suffix');
			$creative->language = $this->input->post('language');
			$creative->specs = $this->input->post('specs');
			$creative->themes = $this->input->post('themes');

			$creative->type = 'file';
			$creative->setFile($file);
			
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
		$config['allowed_types'] = 'zip|rar';
		
		$this->load->library('upload', $config);
		
		if (!$this->upload->do_upload('zip')) {
			die('Error: ' . $this->upload->display_errors('', ''));
		}
		$filedata = $this->upload->data();
		
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
		
		$extracted_files = get_filenames($upload_path.'extract', TRUE);
		
		return $extracted_files;
	}
	
}