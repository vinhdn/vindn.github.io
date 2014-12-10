<?php
/**
 * 	This class allows you to get the download links from any youtube video
 * 
 * 	@author Vinhblue
 */
class Youtube{
	
	/**
	 * The video map for the results
	 * 
	 * @var array
	 */ 
	private $videoMap = array(
		"13" => array("3GP", "LQ - 176x144"),
		"17" => array("3GP", "MQ - 176x144"),
		"36" => array("3GP", "HQ - 320x240"),
		"5" => array("FLV", "LQ - 400x226"),
		"6" => array("FLV", "MQ - 640x360"),
		"34" => array("FLV", "MQ - 640x360"),
		"35" => array("FLV", "HQ - 854x480"),
		"43" => array("WEBM", "Low - 640x360"),
		"44" => array("WEBM", "MQ - 854x480"),
		"45" => array("WEBM", "HQ - 1280x720"),
		"18" => array("MP4", "MQ - 480x360"),
		"22" => array("MP4", "HQ - 1280x720"),
		"37" => array("MP4", "HQ - 1920x1080"),
		"38" => array("MP4", "HQ - 4096x230")
	);
	
	/**
	 * The page that will be used for requests
	 * 
	 * @var string
	 */ 
	private $videoPageUrl = 'http://www.youtube.com/watch?v=';
	
	/**
	 * Returns the video page content
	 * 
	 * @param string  The video id
	 * @return string The video page content 
	 */
	protected function getPageContent($id){
		$page = $this->videoPageUrl.$id;
		include_once('Snoopy.class.php');
		$snoop = new Snoopy();
		$snoop->fetch($page);
		$content = $snoop->results;
		//$content = file_get_contents($page);
		echo $content;
		return $content;
	}
	
	/**
	 * Return the download links
	 * 
	 * @param string The video id
	 * @return array The download links
	 */ 
	function getDownloadLinks($id){
		$content = $this->getPageContent($id);
		$videos = array('MP4' => array(), 'FLV' => array(), '3GP' => array(), 'WEBM' => array());
		
		if(preg_match('/\"url_encoded_fmt_stream_map\": \"(.*)\"/iUm', $content, $r)){
			$data = $r[1];
			$data = explode(',', $data);
			
			foreach($data As $cdata){
				$cdata = str_replace('\u0026', '&', $cdata);
				$cdata = explode('&', $cdata);
				
				foreach($cdata As $xdata){
					if(preg_match('/^sig/', $xdata)){
						$sig = substr($xdata, 4);
					}
					
					if(preg_match('/^url/', $xdata)){
						$url = substr($xdata, 4);
					}
					
					if(preg_match('/^itag/', $xdata)){
						$type = substr($xdata, 5);
					}
				}
				$url = urldecode($url).'&signature='.$sig;
				$videos[$this->videoMap[$type][0]][$this->videoMap[$type][1]] = $url;
			}
		}
		
		return $videos;
	}
}