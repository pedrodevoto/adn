<?php defined('BASEPATH') or exit('No direct script access allowed');

class Creative extends CI_Model {
	
	private $mediatypes = array(
		'png'=>'Image',
		'jpg'=>'Image',
		'jpeg'=>'Image',
		'gif'=>'Image',
		'swf'=>'Flash',
		'js'=>'Javascript'
	);
		
	var $advertiser;
	var $url;
	var $offertype;
	var $prefix;
	var $clicktag;
	var $suffix;
	var $language;
	var $specs;
	var $themes;
	var $filename;
	var $type;
	var $width;
	var $height;
	var $size_id;
	var $mime;
	var $mediatype;
		
	var $creative_content;
	var $file_markers;
	
	var $id;
	
	function __construct($advertiser = '', $line = '', $url = '', $offertype = '', $prefix = '', $clicktag = '', $suffix = '', $language = '', $specs = '', $themes = '')
	{
		parent::__construct();
		
		$this->advertiser = $advertiser;
		$this->line = $line;
		$this->url = $url;
		$this->offertype = $offertype;
		$this->prefix = $prefix;
		$this->clicktag = $clicktag;
		$this->suffix = $suffix;
		$this->language = $language;
		$this->specs = $specs;
		$this->themes = $themes;
	}
	
	function setFile($file)
	{
		$this->filename = $file;
		$imagesize = getimagesize($this->filename);

		list($this->width, $this->height) = $imagesize;
		$this->mime = isset($imagesize['mime'])?$imagesize['mime']:'';

		$res = $this->db->get_where('size_enum', array('width'=>$this->width, 'height'=>$this->height))->row();
		$this->size_id = $res->size_id?$res->size_id:0;
		
		$ext = pathinfo($this->filename, PATHINFO_EXTENSION);
		$this->mediatype = isset($this->mediatypes[$ext])?$this->mediatypes[$ext]:'';
	}
	
	function getSupportingFile()
	{
		$h = fopen($this->filename, 'rb');
		$file_content = fread($h, filesize($this->filename));
		fclose($h);
		
		// $file_markers is an array of strings - special markers which you put
		// into content or media_url and which are replaced with correct url
		// on add() or update()
		$supporting_file = new stdClass();
		$supporting_file->filename = basename($this->filename);
		$supporting_file->data = $file_content;
		return $supporting_file;
	}
	
	function toUpload()
	{
		$creative = new StdClass();
		$creative_content = new StdClass();
		
		$creative->advertiser_entity_id = $this->advertiser;
		$creative->creative_type = $this->type;
		$description = ($this->prefix?$this->prefix.' - ':'').basename($this->filename).($this->suffix?' - '.$this->suffix:'').' ('.date('d-m-y').')';
		$creative->description = $description;
		$creative->offer_type_id = $this->offertype;
		$creative->language_id = $this->language;
		$creative->size_id = $this->size_id;
		$creative->height = $this->height;
		$creative->width = $this->width;
		if (empty($this->url)) {
			$creative->use_campaign_click_url = true;
		}
		else {
			$creative->click_url = $this->url;
		}
		$creative->can_track_clicks = true;
		
		$creative_content->media_type = $this->mediatype;
		$creative_content->media_url = $this->file_markers;
		$creative->contents = array($creative_content);
		
		return $creative;
	}
	
}