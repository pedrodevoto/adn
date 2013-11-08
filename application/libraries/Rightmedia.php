<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Rightmedia {
	
	const SOAP_BASE = 'https://api-test.yieldmanager.com/api-1.37/';
	const KEY = "22]o<2IL20IqoE9k:0T32ZXDcmHn]6";
	
	const PUB_TEST_ID = 711311;
	const PUB_TEST_IO = 1620223;
	const PUB_TEST_SITE = 1633268;
	const TEST_SECTION_CHANNEL = 15;
	const ENTITY_ID = 117472;
	const TEST_IMP_BUDGET = 100000;

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
	private $__campaign_client = NULL;
	
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
	
	private function contact_client()
	{
		$this->__contact_client = $this->__contact_client?$this->__contact_client:new SoapClient($this::SOAP_BASE . 'contact.php?wsdl');
		return $this->__contact_client;
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
	
	private function campaign_client()
	{
		$this->__campaign_client = $this->__campaign_client?$this->__campaign_client:new SoapClient($this::SOAP_BASE . 'campaign.php?wsdl');
		return $this->__campaign_client;
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
		$timestamp = date('d/m/y');
		$insertion_order = $this->get_io($test_tag->io);
		$advertiser = $this->get_entity($insertion_order->buyer_entity_id);
		$test_tag->adv_id = $advertiser->id;
		$test_tag->adv_name = $advertiser->name;
		
		if (!$test_tag->adv_line) {	
			$test_tag->adv_line = $this->add_test_line('adv', $test_tag->io,  $test_tag->adv_name.' Test '.$timestamp, $test_tag->pixel);
		}
		
		$test_tag->pub_line = $this->add_test_line('pub', $this::PUB_TEST_IO, $test_tag->adv_name.' Test '.$timestamp);
		
		$test_tag->site = $this::PUB_TEST_SITE;
		$test_tag->section = $this->add_test_section($test_tag->site, $test_tag->adv_name.' Test '.$timestamp);

		$this->include_section($test_tag->adv_line, array($test_tag->section));
		$this->include_section($test_tag->pub_line, array($test_tag->section));
		
		$this->include_advertisers($test_tag->pub_line, array($test_tag->adv_line));
		
	}
	
	private function add_test_line($type, $io, $description, $pixel = NULL)
	{
		$line_item = new StdClass();
		$line_item->insertion_order_id = $io;
		$line_item->description = $description;
		switch ($type) {
			case 'adv':
				$line_item->pricing_type = 'CPA';
				$line_item->amount = 0.01;
				$line_item->conversion_id = $pixel;
				$line_item->imp_budget = $this::TEST_IMP_BUDGET;
				break;
			case 'pub':
				$line_item->pricing_type = 'Revenue Share';
				$line_item->amount = 100;
				break;
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
			$this->last_error = $e->getMessage();
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
			$this->last_error = $e->getMessage();
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
			$this->last_error = $e->getMessage();
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
			$this->last_error = $e->getMessage();
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
			$this->last_error = $e->getMessage();
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
			$this->last_error = $e->getMessage();
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
			$this->last_error = $e->getMessage();
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

	public function copy_targeting($from_line, $to_lines, $options)
	{
		foreach ($to_lines as $to_line) {
			if ($options == 'copyall') {
				try {
					$this->target_profile_client()->copyTargetProfile($this->token, 'line_item', $from_line, $to_line);
				}
				catch (Exception $e) {
					$this->errors[] = $e->getMessage();
					continue;
				}
			}
			else {
				foreach ($options as $name=>$flag) {
					if ($flag) {
						$this->{'copy_targeting_'.$name}($from_line, $to_line);
					}
				}
			}
		}
	}

	private function copy_targeting_techno($from_line, $to_line)
	{
		try {
			$result = $this->target_profile_client()->getTargetTechno($this->token, 'line_item', $from_line);
			$included_techno_ids = $result['included_techno_ids']; 
			$excluded_techno_ids = $result['excluded_techno_ids'];
			$this->target_profile_client()->setTargetTechno($this->token, 'line_item', $to_line, $included_techno_ids, $excluded_techno_ids);
			return TRUE;
		}
		catch (Exception $e) {
			$this->errors[] = $e->getMessage();
			return FALSE;
		}
	}
	
	private function copy_targeting_geo($from_line, $to_line)
	{
		try {
			$result = $this->target_profile_client()->getTargetGeographyV2($this->token, 'line_item', $from_line);
			$woeids = $result['woeids']; 
			$custom_geo_area_ids = $result['custom_geo_area_ids'];
			$this->target_profile_client()->setTargetGeographyV2($this->token, 'line_item', $to_line, $woeids, $custom_geo_area_ids, FALSE);
			return TRUE;
		}
		catch (Exception $e) {
			$this->errors[] = $e->getMessage();
			return FALSE;
		}
	}
	
	private function copy_targeting_freq($from_line, $to_line)
	{
		try {
			$result = $this->target_profile_client()->getTargetFrequency($this->token, 'line_item', $from_line);
			$frequency = $result['frequency']; 
			$period = $result['period'];
			$this->target_profile_client()->setTargetFrequency($this->token, 'line_item', $to_line, $frequency, $period);
			return TRUE;
		}
		catch (Exception $e) {
			$this->errors[] = $e->getMessage();
			return FALSE;
		}
	}
	
	private function copy_targeting_urls($from_line, $to_line)
	{
		try {
			$result = $this->target_profile_client()->getTargetUrls($this->token, 'line_item', $from_line);
			$url_default = $result['url_default']; 
			$urls = $result['urls'];
			$this->target_profile_client()->setTargetUrls($this->token, 'line_item', $to_line, $url_default, $urls, FALSE);
			return TRUE;
		}
		catch (Exception $e) {
			$this->errors[] = $e->getMessage();
			return FALSE;
		}
	}
	
	private function copy_targeting_channels($from_line, $to_line)
	{
		try {
			$result = $this->target_profile_client()->getTargetChannels($this->token, 'line_item', $from_line);
			$channel_default = $result['channel_default']; 
			$include_channel_ids = $result['include_channel_ids'];
			$exclude_channel_ids = $result['exclude_channel_ids'];
			$this->target_profile_client()->setTargetChannels($this->token, 'line_item', $to_line, $channel_default, $include_channel_ids, $exclude_channel_ids, FALSE);
			return TRUE;
		}
		catch (Exception $e) {
			$this->errors[] = $e->getMessage();
			return FALSE;
		}
	}
	
	private function copy_targeting_publishers($from_line, $to_line)
	{
		try {
			$result = $this->target_profile_client()->getTargetPublishers($this->token, 'line_item', $from_line);
			$publisher_default = $result['publisher_default']; 
			$publisher_entity_ids = $result['publisher_entity_ids'];
			$this->target_profile_client()->setTargetPublishers($this->token, 'line_item', $to_line, $publisher_default, $publisher_entity_ids, FALSE);
			return TRUE;
		}
		catch (Exception $e) {
			$this->errors[] = $e->getMessage();
			return FALSE;
		}
	}
	
	private function copy_targeting_vurls($from_line, $to_line)
	{
		try {
			$result = $this->target_profile_client()->getTargetValidatedUrls($this->token, 'line_item', $from_line);
			$options = $result['options']; 
			$vurl_ids = $result['vurl_ids'];
			$this->target_profile_client()->setTargetValidatedUrls($this->token, 'line_item', $to_line, $options, $vurl_ids, FALSE);
			return TRUE;
		}
		catch (Exception $e) {
			$this->errors[] = $e->getMessage();
			return FALSE;
		}
	}
	
	public function assign_manager_trafficker($entity_type, $entity_ids, $contact, $trafficker)
	{
		try {
			$method = $entity_type=='pub'?'getBySellers':'getByBuyers';
			$insertion_orders = $this->io_client()->$method($this->token, $entity_ids);
		}
		catch (Exception $e) {
			$this->last_error = $e->getMessage();
			return FALSE;
		}
		foreach ($insertion_orders as $insertion_order) {
			$prop = $entity_type=='pub'?'buyer_contact_id':'seller_contact_id';
			$change = FALSE;
			if ($contact and $insertion_order->$prop != $contact) {
				$insertion_order->$prop = $contact;
				$change = TRUE;
			}
			if ($entity_type == "adv" and $trafficker and $trafficker != $insertion_order->buyer_trafficker_id) {
				$insertion_order->buyer_trafficker_id = $trafficker;
				$change = TRUE;
			}
			if ($change) {
				try {
					$this->io_client()->update($this->token, $insertion_order);
				}
				catch (Exception $e) {
					$this->errors[] = $e->getMessage();
				}
			}
		}
	}

	public function deactivate($entity_type, $entity_ids)
	{
		foreach ($entity_ids as $entity_id) {
			$line_ids = array($entity_id);
			if ($entity_type=='io') {
				try {
					$line_ids = $this->lineitem_client()->listByInsertionOrder($this->token, $entity_id);
				}
				catch (Exception $e) {
					$this->errors[] = $e->getMessage();
					continue;
				}
			}
			foreach ($line_ids as $line_id) {
				try {
					$line = $this->lineitem_client()->get($this->token, $line_id);
					$line->active = FALSE;
					$this->lineitem_client()->update($this->token, $line);
				}
				catch (Exception $e) {
					$this->errors[] = $e->getMessage();
				}
			}
		}
	}

	public function get_ios_line_items($entity_type, $entity_id)
	{
		ini_set('memory_limit', -1);
		$method = $entity_type=='adv'?'getByBuyer':'get';
		$ios = array();
		try {
			$ios[] = $this->io_client()->$method($this->token, $entity_id);
			$ios = is_array($ios[0])?$ios[0]['insertion_orders']:$ios;
		}
		catch (Exception $e) {
			$this->last_error = $e->getMessage();
			return FALSE;
		}
		foreach ($ios as $io) {
			try {
				$line_items = $this->lineitem_client()->getByInsertionOrder($this->token, $io->id, 1000, 1);
				$io->line_items = $line_items['line_items'];
			}
			catch (Exception $e) {
				$this->last_error = $e->getMessage();
				continue;
			}
			foreach ($io->line_items as $line) {
				try {
					$campaigns = $this->campaign_client()->getByLineItem($this->token, $line->id);
					$line->campaigns = $campaigns['campaigns'];
				}
				catch (Exception $e) {
					$this->last_error = $e->getMessage();
					continue;
				}
			}
		}
		return $ios;
	}
	
	public function update_line_items($line_items)
	{
		foreach ($line_items as $line_item) {
			$line_updates = array();
			
			list($delivery_unit, $delivery_type) = explode('-', $line_item['delivery_units_type']);
			$line_updates[($delivery_unit=='Imps'?'imp_':'').'delivery_type'] = $delivery_type;
			
			if ($line_item['url']) {
				$this->update_line_item_url($line_item['id'], $line_item['url']);
			}
			if ($line_item['amount']) {
				$line_updates['amount'] = $line_item['amount'];
			}
			if ($line_item['desc']) {
				$line_updates['desc'] = $line_item['desc'];
			}
			if ($line_item['budget']) {
				$line_updates[($delivery_unit=='Imps'?'imp_':'').'budget'] = $line_item['budget'];
			}
			if ($line_item['cap'] and $delivery_type != 'ASAP' and $delivery_type != 'Even') {
				$line_updates[($delivery_unit=='Imps'?'imp_':'').'delivery_cap'] = $line_item['cap'];
			}
			if (count($line_updates)) {
				$this->update_line_item($line_item['id'], $line_updates);
			}
		}
	}

	private function update_line_item_url($line_item_id, $url)
	{
		try {
			$campaign = $this->campaign_client()->getByLineItem($this->token, $line_item_id);
			$campaign['campaigns'][0]->click_url_override = $url;
			$this->campaign_client()->update($this->token, $campaign['campaigns'][0]);
			return TRUE;
		}
		catch (Exception $e) {
			$this->errors[] = $e->getMessage();
			return FALSE;
		}
	}

	private function update_line_item($line_item_id, $data)
	{
		try {
			$line_item = $this->lineitem_client()->get($this->token, $line_item_id);
			$changed = FALSE;
			foreach($data as $key=>$val) {
				switch ($key) {
					case 'amount':
						$changed = floatval($line_item->amount) != floatval($val)?TRUE:$changed;
						$line_item->amount = floatval($val);
						break;
					case 'desc':
						$changed = $line_item->description != $val?TRUE:$changed;
						$line_item->description = $val;
						break;
					case 'budget':
						$changed = $line_item->budget != $val?TRUE:$changed;
						$line_item->budget = $val;
						$line_item->imp_budget = NULL;
						$line_item->delivery_clicks = NULL;
						break;
					case 'imp_budget':
						$changed = $line_item->imp_budget != $val?TRUE:$changed;
						$line_item->imp_budget = $val;
						$line_item->budget = NULL;
						$line_item->delivery_clicks = NULL;
						break;
					case 'delivery_type':
						$changed = $line_item->delivery_type != $val?TRUE:$changed;
						$line_item->delivery_type = $val;
						$line_item->imp_delivery_type = NULL;
						break;
					case 'imp_delivery_type':
						$changed = $line_item->imp_delivery_type != $val?TRUE:$changed;
						$line_item->imp_delivery_type = $val;
						$line_item->delivery_type = NULL;
						break;
					case 'delivery_cap':
						$changed = $line_item->delivery_cap != $val?TRUE:$changed;
						$line_item->delivery_cap = $val;
						$line_item->imp_delivery_cap = NULL;
						$line_item->delivery_clicks = NULL;
						break;
					case 'imp_delivery_cap':
						$changed = $line_item->imp_delivery_cap != $val?TRUE:$changed;
						$line_item->imp_delivery_cap = $val;
						$line_item->delivery_cap = NULL;
						$line_item->delivery_clicks = NULL;
						break;
				}
			}
			if ($changed) {
				$this->lineitem_client()->update($this->token, $line_item);
			}
			return TRUE;
		}
		catch (Exception $e) {
			$this->errors[] = $e->getMessage();
			return FALSE;
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
		$languages = $this->dictionary_client()->getEnumValues($this->token, 'creative_tag_language');
		$this->ci->db->truncate('languages');
		foreach ($languages as $language) {
			$this->ci->db->insert('languages', $language);
		}
	}	
	public function download_contacts()
	{
		try {
			$contacts = $this->contact_client()->getByEntity($this->token, $this::ENTITY_ID, 1000, 1);
		}
		catch (Exception $e) {
			$this->last_error = $e->getMessage();
			return FALSE;
		}
		$this->ci->db->truncate('contacts');
		foreach ($contacts['contacts'] as $contact) {
			if (!$contact->id or !$contact->first_name or !$contact->last_name) {
				continue;
			}
			$this->ci->db->insert('contacts', array('id'=>$contact->id, 'name'=>$contact->first_name . " " . $contact->last_name));
		}
	}
	
}