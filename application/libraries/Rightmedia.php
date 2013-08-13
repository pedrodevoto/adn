<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Rightmedia {
	
	const SOAP_BASE = 'https://api-test.yieldmanager.com/api-1.37/';
	const KEY = "22]o<2IL20IqoE9k:0T32ZXDcmHn]6";

	// services
	private $__contact_client = NULL;
	private $__creative_client = NULL;
	private $__dictionary_client = NULL;
	private $__lineitem_client = NULL;
	
	private $token;
	
	private $ci;
	
	var $last_error;
	var $creatives_uploaded;
	var $ids = array();
	
	public function __construct($params = array('login'=>TRUE))
	{
		$this->ci =& get_instance();
		$this->ci->load->database();

		if ($params['login']) {
			$credentials = $this->ci->db->get('rm_credentials')->row();
				
			$password = $this->encrypt_decrypt('decrypt', $credentials->password);
		
			$this->__contact_client = new SoapClient($this::SOAP_BASE . 'contact.php?wsdl');
			$this->token = $this->__contact_client->login($credentials->username, $password);
		}
	}
	
	public function set_credentials($username, $password)
	{
		$this->ci->db->truncate('rm_credentials');
		$this->ci->db->insert('rm_credentials', array('username'=>$username, 'password'=>$this->encrypt_decrypt('encrypt', $password)));
	}
	
	private function encrypt_decrypt($action, $string) 
	{
	   $output = false;

	   $key = $this::KEY;

	   // initialization vector 
	   $iv = md5(md5($key));

	   if( $action == 'encrypt' ) {
	       $output = mcrypt_encrypt(MCRYPT_RIJNDAEL_256, md5($key), $string, MCRYPT_MODE_CBC, $iv);
	       $output = base64_encode($output);
	   }
	   else if( $action == 'decrypt' ){
	       $output = mcrypt_decrypt(MCRYPT_RIJNDAEL_256, md5($key), base64_decode($string), MCRYPT_MODE_CBC, $iv);
	       $output = rtrim($output);
	   }
	   return $output;
	}
	
	private function creative_client()
	{
		$this->__creative_client = $this->__creative_client?$this->__creative_client:new SoapClient($this::SOAP_BASE . 'creative.php?wsdl');
		return $this->__creative_client;
	}
	
	private function lineitem_client() 
	{
		$this->__lineitem_client = $this->__lineitem_client?$this->__lineitem_client:new SoapClient($this::SOAP_BASE . 'line_item.php?wsdl');
		return $this->__lineitem_client;
	}
	
	private function dictionary_client()
	{
		$this->__dictionary_client = $this->__dictionary_client?$this->__dictionary_client:new SoapClient($this::SOAP_BASE . 'dictionary.php?wsdl');
		return $this->__dictionary_client;
	}
	
	public function upload_creative(&$creative)
	{
		$supporting_file = $creative->getSupportingFile();
		$file_markers = $this->creative_client()->addSupportingFiles($this->token, array($supporting_file));
		$creative->file_markers = $file_markers[0];
		
		try {
			 $creative_id = $this->creative_client()->add($this->token, $creative->toUpload());
			 return $creative_id;
		}
		catch (Exception $e) {
			$this->last_error = $e->getMessage();
			return FALSE;
		}
	}
	
	public function upload_creatives(&$creatives, $line)
	{
		$supporting_files = array();
		foreach ($creatives as $creative) {
			$supporting_files[] = $creative->getSupportingFile();
		}
		$file_markers = $this->creative_client()->addSupportingFiles($this->token, $supporting_files);

		$creatives_to_upload = array();
		for ($i = 0; $i < count($file_markers); $i++) {
			$creatives[$i]->file_markers = $file_markers[$i];
			$creatives_to_upload[] = $creatives[$i]->toUpload();
		}
		
		try {
			$creatives_result = $this->creative_client()->addCreatives($this->token, $creatives_to_upload);
			
			$creatives_lineitem = array();
			for ($i = 0; $i < count($creatives); $i++) {
				if (!$creatives_result[$i])
					continue;
				$creatives[$i]->id = $creatives_result[$i]->item_id;
				$this->ids[] = $creatives[$i]->id;
				$this->creatives_uploaded++;
				if (empty($creatives[$i]->line))
					continue;
				$creative_lineitem = new StdClass();
				$creative_lineitem->creative_id = $creatives[$i]->id;
				$creative_lineitem->line_item_id = $creatives[$i]->line;
				$creatives_lineitem[] = $creative_lineitem;
			}
			$assign_result = $this->assign_creatives($creatives_lineitem);
			for ($i = 0; $i < count($creatives); $i++) {
				if (!empty($creatives[$i]->id))
					$this->set_creative_tags($creatives[$i]->id, $creatives[$i]->specs, $creatives[$i]->themes);
			}
			
		}
		catch (Exception $e) {
			$this->last_error = $e->getMessage();
			return FALSE;
		}
	}
	
	public function assign_creative($creative_id, $line_id)
	{
		try {
			$this->lineitem_client()->addCreative($this->token, $line_id, $creative_id);
			return TRUE;
		}
		catch (Exception $e) {
			$this->last_error = $e->getMessage();
			return FALSE;
		}
	}
	
	private function assign_creatives($creatives_lineitem)
	{
		try {
			$result = $this->lineitem_client()->addCreatives($this->token, $creatives_lineitem);
			return $result;
		}
		catch (Exception $e) {
			$this->last_error = $e->getMessage();
			return FALSE;
		}

	}
	public function set_creative_tags($creative, $specs, $themes)
	{
		$tags = array();
		if (is_array($specs)) {
			foreach ($specs as $spec) {
				$tags[] = $spec;
			}
		}
		if (is_array($themes)) {
			foreach ($themes as $theme) {
				$tags[] = $theme;
			}
		}
		
		if (count($tags)) {
			try {
				$this->creative_client()->setCreativeTags($this->token, $creative, $tags);
				return TRUE;
			}
			catch (Exception $e) {
				$this->last_error = $e->getMessage();
				return FALSE;
			}
		}
		
	}
	
	// background jobs
	public function download_size_enum()
	{
		$sizes = $this->dictionary_client()->getSizes($this->token, 0, false);
		$this->ci->db->truncate('size_enum');
		foreach ($sizes as $size) {
			$this->ci->db->insert('size_enum', array('size_id'=>$size->id, 'width'=>$size->width, 'height'=>$size->height, 'description'=>$size->description));
		}
	}
	public function download_offer_types()
	{
		$offer_types = $this->dictionary_client()->getOfferTypes($this->token);
		$this->ci->db->truncate('offer_types');
		foreach ($offer_types as $offer_type) {
			$this->ci->db->set('id', $offer_type->id)
				->set('description', $offer_type->description);
			if (isset($offer_type->parent_id)) {
				$this->ci->db->set('parent_id', $offer_type->parent_id);
			}
			$this->ci->db->insert('offer_types');
		}
	}
	public function download_creative_tags()
	{
		$creative_tags = $this->dictionary_client()->getCreativeTagList($this->token);
		$this->ci->db->truncate('creative_tags');
		foreach ($creative_tags as $creative_tag) {
			$this->ci->db->insert('creative_tags', $creative_tag);
		}
	}
	public function download_languages()
	{
		$languages = $this->dictionary_client()->getLanguages($this->token);
		$this->ci->db->truncate('languages');
		foreach ($languages as $language) {
			$this->ci->db->insert('languages', $language);
		}
	}
	
}