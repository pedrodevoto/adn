<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Rightmedia {
	
	const SOAP_BASE = 'https://api-test.yieldmanager.com/api-1.37/';
	const KEY = "22]o<2IL20IqoE9k:0T32ZXDcmHn]6";
	
	const PUB_TEST_ID = 711311;
	const PUB_TEST_IO = 1620223;
	const PUB_TEST_SITE = 1633268;
	const TEST_SECTION_CHANNEL = 15;

	// services
	private $__contact_client = NULL;
	private $__creative_client = NULL;
	private $__dictionary_client = NULL;
	private $__lineitem_client = NULL;
	private $__io_client = NULL;
	private $__pixel_client = NULL;
	private $__target_profile_client = NULL;
	private $__site_client = NULL;
	private $__section_client = NULL;
	private $__entity_client = NULL;
	
	private $token;
	
	private $ci;
	
	var $last_error;
	var $errors = array();
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
	
	private function target_profile_client()
	{
		$this->__target_profile_client = $this->__target_profile_client?$this->__target_profile_client:new SoapClient($this::SOAP_BASE . 'target_profile.php?wsdl');
		return $this->__target_profile_client;
	}
	
	private function io_client()
	{
		$this->__io_client = $this->__io_client?$this->__io_client:new SoapClient($this::SOAP_BASE . 'insertion_order.php?wsdl');
		return $this->__io_client;
	}
	
	private function pixel_client()
	{
		$this->__pixel_client = $this->__pixel_client?$this->__pixel_client:new SoapClient($this::SOAP_BASE . 'pixel.php?wsdl');
		return $this->__pixel_client;
	}
	
	private function site_client()
	{
		$this->__site_client = $this->__site_client?$this->__site_client:new SoapClient($this::SOAP_BASE . 'site.php?wsdl');
		return $this->__site_client;
	}
	
	private function section_client()
	{
		$this->__section_client = $this->__section_client?$this->__section_client:new SoapClient($this::SOAP_BASE . 'section.php?wsdl');
		return $this->__section_client;
	}
	
	private function entity_client()
	{
		$this->__entity_client = $this->__entity_client?$this->__entity_client:new SoapClient($this::SOAP_BASE . 'entity.php?wsdl');
		return $this->__entity_client;
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
	
	public function get_pixels($io_id)
	{
		try {
			$io = $this->io_client()->get($this->token, $io_id);
			$pixels = $this->pixel_client()->getByEntity($this->token, $io->buyer_entity_id, 999999, 1);
			return $pixels['pixel'];
		}
		catch (Exception $e) {
			$this->last_error = $e->getMessage();
			return FALSE;
		}
	}
	
	public function create_test_tag(&$test_tag)
	{
		if (!$test_tag->adv_line) {	
			$test_tag->adv_line = $this->add_test_line('adv', $test_tag->io, 'Test Line Item '.microtime(true), $test_tag->pixel);
		}
		$insertion_order = $this->get_io($test_tag->io);
		$advertiser = $this->get_entity($insertion_order->buyer_entity_id);
		$test_tag->adv_id = $advertiser->id;
		$test_tag->adv_name = $advertiser->name;
		
		$test_tag->pub_line = $this->add_test_line('pub', $this::PUB_TEST_IO, $test_tag->adv_name.' Test '.microtime(true));
		
		$test_tag->site = $this::PUB_TEST_SITE;
		$test_tag->section = $this->add_test_section($test_tag->site, $test_tag->adv_name.' Test '.microtime(true));

		$this->include_section($test_tag->adv_line, array($test_tag->section));
		$this->include_section($test_tag->pub_line, array($test_tag->section));
		
		$this->include_advertisers($test_tag->pub_line, array($test_tag->adv_line));
		
	}
	
	private function add_test_line($type, $io, $description, $pixel = NULL)
	{
		$line_item = new StdClass();
		$line_item->insertion_order_id = $io;
		$line_item->description = $description;
		if ($type=='adv') {
			$line_item->pricing_type = 'CPA';
			$line_item->amount = 0.01;
			$line_item->conversion_id = $pixel;
		}
		$line_item->active = true;
		try {
			$result = $this->lineitem_client()->add($this->token, $line_item);
			return $result;
		}
		catch (Exception $e) {
			$this->last_error = $e->getMessage();
			return FALSE;
		}
	}
	
	private function add_test_site($description)
	{
		$site = new StdClass();
		$site->publisher_entity_id = $this::PUB_TEST_ID;
		$site->description = 'Site '.$description;
		
		try {
			$result = $this->site_client()->add($this->token, $site);
			return $result;
		}
		catch (Exception $e) {
			$this->last_error = $e.getMessage();
			return FALSE;
		}
	}
	
	private function add_test_section($site, $description)
	{
		$section = new StdClass();
		$section->site_id = $site;
		$section->description = 'Section '.$description;
		$section->channels = array($this::TEST_SECTION_CHANNEL);
		
		try {
			$result = $this->section_client()->add($this->token, $section);
			return $result;
		}
		catch (Exception $e) {
			$this->last_error = $e.getMessage();
			return FALSE;
		}
	}
	
	private function set_site_default_section($site_id, $section_id)
	{
		try {
			$site = $this->site_client()->get($this->token, $site_id);
			$site->default_section_id = $section_id;
			$this->site_client()->update($this->token, $site);
			return TRUE;
		}
		catch (Exception $e) {
			$this->last_error = $e.getMessage();
			return FALSE;
		}
	}
	
	private function include_section($line, $sections)
	{
		try {
			$this->target_profile_client()->setTargetSections($this->token, 'line_item', $line, false, $sections, false);
			return TRUE;
		}
		catch (Exception $e) {
			$this->last_error = $e.getMessage();
			return FALSE;
		}
	}
	
	private function include_advertisers($line, $advertisers)
	{
		try {
			$this->target_profile_client()->setTargetBuyerLineItems($this->token, 'line_item', $line, false, $advertisers, false);
			return TRUE;
		}
		catch (Exception $e) {
			$this->last_error = $e.getMessage();
			return FALSE;
		}
	}
	
	private function get_entity($id)
	{
		try {
			$result = $this->entity_client()->get($this->token, $id);
			return $result;
		}
		catch (Exception $e) {
			$this->last_error = $e.getMessage();
			return FALSE;
		}
	}
	
	private function get_io($id)
	{
		try {
			$result = $this->io_client()->get($this->token, $id);
			return $result;
		}
		catch (Exception $e) {
			$this->last_error = $e.getMessage();
			return FALSE;
		}
	}
	
	public function exclude_publishers($entity_type0, $lines, $entity_type, $entity_ids, $default)
	{
		foreach ($lines as $line) {
			switch ($entity_type) {
				case 'entity':
					try {
						switch ($entity_type0) {
							case 'adv':
								$this->target_profile_client()->setTargetPublishers($this->token, 'line_item', $line, $default, $entity_ids, true);
								break;
							case 'pub':
								$this->target_profile_client()->setTargetAdvertisers($this->token, 'line_item', $line, $default, $entity_ids, true);
								break;
						}
					}
					catch (Exception $e) {
						$this->errors[] = $e->getMessage();
					}
					break;
				case 'line':
					try {
						switch ($entity_type0) {
							case 'adv':
								$this->target_profile_client()->setTargetSellerLineItems($this->token, 'line_item', $line, $default, $entity_ids, true);
								break;
							case 'pub':
								$this->target_profile_client()->setTargetBuyerLineItems($this->token, 'line_item', $line, $default, $entity_ids, true);
								break;
						}
					}
					catch (Exception $e) {
						$this->errors[] = $e->getMessage();
					}
					break;
			}
		}
	}
	
	public function arbitrage($adv_lines)
	{
		foreach ($adv_lines as $adv_line) {
			try {
				$line = $this->lineitem_client()->get($this->token, $adv_line);
				$new_line_id = $this->lineitem_client()->duplicate($this->token, $adv_line, $line->description . ' (Arbitrage)');
				$new_line = $this->lineitem_client()->get($this->token, $new_line_id);
				$new_line->allow_convert_cpx_to_dcpm = TRUE;
				$new_line->active = TRUE;
				$this->lineitem_client()->update($this->token, $new_line);
			}
			catch (Exception $e) {
				$this->errors[] = $e->getMessage();
				continue;
			}
		}
	}

	public function copy_targeting($from_line, $to_lines)
	{
		foreach ($to_lines as $to_line) {
			try {
				$this->target_profile_client()->copyTargetProfile($this->token, 'line_item', $from_line, $to_line);
			}
			catch (Exception $e) {
				$this->errors[] = $e.getMessage();
				continue;
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