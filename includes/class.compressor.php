<?
// ======================================================================================
// This class is free software; you can redistribute it and/or
// modify it under the terms of the GNU Lesser General Public
// License as published by the Free Software Foundation; either
// version 2.1 of the License, or (at your option) any later version.
// 
// This class is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
// Lesser General Public License for more details.
// ======================================================================================
// @author     Leon Chevalier (http://www.aciddrop.com)
// @version    0.2
// @copyright  Copyright &copy; 2008 Leon Chevalier, All Rights Reserved
// ======================================================================================
// 0.1  - Released first version
// 0.2  - Added support for different CSS media types
//		- Fixed bug in relative file paths. Thanks to Jeff Badger
//		- Improved old file cleanup function
//		- Changed file versioning to by date instead of by file size
//		- Added check for trailing slash on cachedir
// 0.3  - Added check for gzip compression compatibilty. Thanks to aNTRaX.
//		- Added check for external javascript files. Thanks to aNTRaX.
//		- Fixed bug in head grab function
// ======================================================================================


/**
 * Gzips and minifies the JavaScript and CSS within the head tags of a page. 
 * Can also gzip and minify the page itself
 *
 **/
class compressor {

	/**
	* Constructor
	* Sets the options and defines the gzip headers
	**/
	function compressor($options=null) {

		//Set options
		$this->set_options($options);
		
		//Define the gzip headers
		$this->set_gzip_headers();
		
		//Start things off
		$this->start();
	
	}
	
	/**
	* Options can be set with a simple comma separated string or a full array
	* String e.g : ('css,javascript,page')
	* Array e.g: array("javascript"=>array("cachedir"=>"/minify/",
										   "gzip"=>true,
										   "minify"=>true,													   
										   ),
							  "css"=>array("cachedir"=>"/minify/",
										   "gzip"=>true,
										   "minify"=>true,													   
										   ),
							   "page"=>array("gzip"=>true,
											 "minify"=>true
											)													   
					   );
	**/
	function set_options($options) {
	

	if(is_array($options)) {
	
		$this->options = $options;
	
	} else {
		
	   //Get current directory
	   $current_dir = $this->get_current_path(true);
	
	   $full_options = array("javascript"=>array("cachedir"=>$current_dir,
										   "gzip"=>true,
										   "minify"=>true,													   
										   ),
							  "css"=>array("cachedir"=>$current_dir,
										   "gzip"=>true,
										   "minify"=>true,													   
										   ),
							   "page"=>array("gzip"=>true,
											 "minify"=>true
											)													   
					          );	
							  	
		$options_array = explode(",",$options);
		asort($options_array); //Make sure page last
		
		foreach($options_array AS $key=>$value) {
		$this->options[$value] = $full_options[$value];						
		}									   

	}
	
		//Make sure cachedir does not have trailing slash
		foreach($this->options AS $key=>$option) {			
			   if(substr($option['cachedir'],-1,1) == "/") {
			   $cachedir = substr($option['cachedir'],0,-1); 
			   $option['cachedir'] = $cachedir;
			   }			   
			   $this->options[$key] = $option;			
		}
		
	$this->options['show_timer'] = false; //time the javascript and css compression?
			
							   
	}	

	
	/**
	* Start saving the output buffer
	* 
	**/	
	function start() {
		
	ob_start();	
	
	}	

	/**
	* Do work and return output buffer
	*
	**/	
	function finish() {
	
	$this->runtime = $this->startTimer();
	$this->times['start_compress'] = $this->returnTime($this->runtime);
	
	$this->content = ob_get_clean();
	
	//Run the functions specified in options
	foreach($this->options AS $func=>$option) {
		if(method_exists($this,$func)) {
		$this->$func($option,$func);
		}
	}
		
	//Delete old cache files
	if(is_array($this->compressed_files)) {
	$this->compressed_files_string = implode("",$this->compressed_files); //Make a string with the names of the compressed files
	}
	$this->do_cleanup(); //Delete any files that don't match the string
	
	$this->times['end'] = $this->returnTime($this->runtime);
	
	//Echo content to the browser
	echo $this->content;
	
	//Show compress time
	if($this->options['show_timer'] && !$this->options['page']['gzip']) {
	echo "<span style='background-color:#999999;color:#cccccc;font-size:9px'>Compress took " . number_format($this->times['end'],2) . " seconds</span>";
	}
	
	
	}

	/**
	* GZIP and minify the javascript as required
	*
	**/	
	function javascript($options,$type) {
				
	$this->content = $this->do_compress(array('cachedir'=>$options['cachedir'],
					 						  'tag'=>'script',
										      'type'=>'text/javascript',
										      'ext'=>'js',
										      'src'=>'src',
										      'self_close'=>false,
											  'gzip'=>$options['gzip'],
											  'minify'=>$options['minify'],											  
											  'header'=>$type,
											  'save_name'=>$type),$this->content);	
	
	
	}

	/**
	* GZIP and minify the CSS as required
	*
	**/	
	function css($options,$type) {
	
	//Add default media type
	$media_types[] = array("name"=>"",
						   "type"=>"text/css"
						   );	
		
	//Get any media types in the document
	$head = $this->get_head($this->content);			
	if($head) {
	preg_match_all("!<link[^>]+media=[\"'\s](.*?)[\"'\s][^>]+>!is", $head, $matches);
	}		
	
	//Run through the media types and sub in markers
	if(is_array($matches)) {
		foreach($matches[0] AS $key=>$value) {
			if(strstr($value,"stylesheet")) { //make sure it's a style sheet
			$thevalues = array("code"=>$matches[0][$key],
							   "marker"=>str_replace("text/css","marker%%%".$matches[1][$key],$matches[0][$key]),			
							   "name"=>$matches[1][$key],
							   "type"=>"marker%%%".$matches[1][$key]
							   );
			$media_types[$matches[1][$key]] = $thevalues;
			//Add in marker
			$this->content = str_replace($thevalues['code'],$thevalues['marker'],$this->content);
			}		
		}
	}
		
	//Compress separately for each media type	
	foreach($media_types AS $key=>$value) {
			
		$this->content = $this->do_compress(array('cachedir'=>$options['cachedir'],
												  'tag'=>'link',
												  'type'=>$value['type'],
												  'ext'=>'css',
												  'src'=>'href',
												  'rel'=>'stylesheet',	
												  'media'=>$value['name'],
												  'self_close'=>true,
												  'gzip'=>$options['gzip'],	
												  'minify'=>$options['minify'],
												  'header'=>$type,
												  'save_name'=>$type.$value['name']),$this->content);	
												  
		//Replace out the markers
		$this->content = str_replace($value['type'],'text/css',$this->content);												  
											  
	}
	
		
	
	
	}
	
	/**
	* GZIP and minify the page itself as required
	*
	**/		
	function page($options,$type) {


		//Minify page itself
		if($options['minify']) {
		$this->content = $this->trimwhitespace($this->content);		
		}
	
		//Gzip page itself
		if($options['gzip'] && strstr($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip')) {
		
				$Size = strlen( $this->content );
				$Crc = crc32( $this->content );		
			
				header('Content-Encoding: gzip');			 
				$content = "\x1f\x8b\x08\x00\x00\x00\x00\x00";
				
				$this->content = gzcompress( $this->content,3);
				$this->content = substr( $this->content, 0, strlen( $this->content) - 4 );
				
				$content .= ( $this->content );
				$content .= ( pack( 'V', $Crc ) );
				$content .= ( pack( 'V', $Size ) ); 			 
			 
			 $this->content = $content;
		 
		 }
	
	
	}
	
	
	/**
	* Compress JS or CSS and return source
	*
	**/	
	function do_compress($options,$source) {	
		
		//Change the extension
		if($options['gzip']) {
		$options['ext'] = "php";	
		}
		
		$cachedir = $_SERVER['DOCUMENT_ROOT'] . $options['cachedir'];
	

		$head = $this->get_head($source);			
		if($head) {
		$regex = "!<" . $options['tag'] . "[^>]+" . $options['type'] . "[^>]+>(</" . $options['tag'] . ">)?!is";
		preg_match_all($regex, $head, $matches);
		}
		
				
		$script_array = $matches[0];
				
		if(!is_array($script_array)) { //Noting to do
		return $source;
		}
		
		//Make sure src element present
		foreach($script_array AS $key=>$value) {
			if(!strstr($value,$options['src'])) {
			unset($script_array[$key]);
			}
		}
				
		//Remove empty sources and any externally linked files
		foreach($script_array AS $key=>$value) {
		preg_match("!" . $options['src'] . "=\"(.*?)\"!is", $value, $src);
			if(!$src[1]){
			unset($script_array[$key]);
			}
			if(strlen($src[1])> 7 && strcasecmp(substr($src[1],0,7),'https://')==0) {
				if(!strstr($src[1],$_SERVER['HTTP_HOST'])) {
				unset($script_array[$key]);				
				}			
			}
			
		}		
		
		//Get date string for making hash
		$datestring = $this->get_file_dates($script_array,$options);
	
		//Get the cache hash
		$cache_file = '_cmp_' . $options['save_name'] . '_' . md5(implode("_",$script_array).$datestring);
		//echo $cache_file . "\n";
					
		//Check if the cache file exists
		if (file_exists($cachedir . '/' . $cache_file . ".$options[ext]")) {
		$source = $this->_remove_scripts($script_array,$source);
		$source = str_replace("@@marker@@",$this->get_new_file($options,$cache_file),$source);
		return $source;
		}
														
			$script_array = $this->get_file_locations($script_array,$options);	
											
			//Create file
			if(is_array($script_array)) {
				foreach($script_array AS $key=>$info) {	
													
					//Get the code
					if (file_exists($info['src'])) {
					   $contents .= file_get_contents($info['src']) . "\n";				   
					   
					   if($key == count($script_array)-1) { //Remove script 
					   $source = str_replace($info['location'],"@@marker@@",$source); 
					   } else {
					   $source = str_replace($info['location'],"",$source);
					   }
					}				
				
				}	
			}	
		
		//Allow for minification of javascript
		if($options['header'] == "javascript" && $options['minify'] && substr(phpversion(),0,1) == 5) { //Only minify on php5+
		require_once('libs/jsmin.php');
		$jsmin = new JSMin($contents);
		$contents = $jsmin->minify($contents);			
		}
					
		//Allow for minification of CSS
		if($options['header'] == "css" && $options['minify']) { //Minify CSS
		$contents = $this->minify_text($contents);
		}

		
		//Allow for gzipping
		if($options['gzip']) {
		$contents = $this->gzip_header[$options['header']] . $contents;		
		}
		
		//Write to cache and display
		if($contents) {
			if ($fp = fopen($cachedir . '/' . $cache_file . '.' . $options[ext], 'wb')) {
					fwrite($fp, $contents);
					fclose($fp);
					
					//Create the link to the new file
					$newfile = $this->get_new_file($options,$cache_file);						
					
					$source = str_replace("@@marker@@",$newfile,$source);
					
			} 
		}
		
		return $source;
	
	}
	
	/**
	* Replaces the script or css links in the source with a marker
	*
	*/
	function _remove_scripts($script_array,$source) {
	
		foreach($script_array AS $key=>$value) {	   
			   
			   if($key == count($script_array)-1) { //Remove script 
			   $source = str_replace($value,"@@marker@@",$source); 
			   } else {
			   $source = str_replace($value,"",$source);
			   }			
		
		}	
	
		return $source;
	
	}
	
	/**
	* Returns the filename for our new compressed file
	*
	**/
	function get_new_file($options,$cache_file) {
	
	$newfile = "<" . $options['tag'] . " type=\"" . $options['type'] . "\" $options[src]=\"https://" . $_SERVER['HTTP_HOST'] . $options['cachedir'] ."/$cache_file." . $options[ext] . "\"";
						
						if($options['rel']) {
						$newfile .= " rel=\"" . $options['rel'] . "\"";
						}
						
						if($options['media']) {
						$newfile .= " media=\"" . $options['media'] . "\"";
						}						
						
						if($options['self_close']) {
						$newfile .= " />";
						} else {
						$newfile .= "></" . $options['tag'] . ">";
						}
						
		$this->compressed_files[] = $newfile;				
						
		return $newfile;	
	
	
	}
	
	/**
	* Returns the last modified dates of the files being compressed
	* In this way we can see if any changes have been made
	**/	
	function get_file_dates($files,$options) {
	
		$files = $this->get_file_locations($files,$options);	
		
		if(!is_array($files)) {
		return;
		}
				
		foreach($files AS $key=>$value) {
			if(file_exists($value['src'])) {
				$thedate = filemtime($value['src']);
				$dates[] = $thedate;
			}
		}
		
		if(is_array($dates)) {
		return implode(".",$dates);
		}		
	
	
	}
	
	/**
	* Gets the path locations of the scripts being compressed
	* 
	**/		
	function get_file_locations($script_array,$options) {
	
		//Remove empty sources
		foreach($script_array AS $key=>$value) {
		preg_match("!" . $options['src'] . "=\"(.*?)\"!is", $value, $src);
			if(!$src[1]) {
			unset($script_array[$key]);
			}
		}			
		//Create file
		foreach($script_array AS $key=>$value) {
			//Get the src
			preg_match("!" . $options['src'] . "=\"(.*?)\"!is", $value, $src);
			$src[1] = str_replace("https://".$_SERVER['HTTP_HOST'],"",$src[1]);
						
			if(strstr($src[1],"/")) {
			$current_src = $_SERVER['DOCUMENT_ROOT'] . $src[1];
			} else {
			$current_src = $_SERVER['DOCUMENT_ROOT'] . $this->get_current_path() . $src[1];
			}			
			
			$return_array[] = array('src'=>$current_src,
									'location'=>$value);
											
		}
		
		return $return_array;
	
	}

	/**
	* Sets the headers to be sent in the javascript and css files
	* 
	**/	
	function set_gzip_headers() {
	
	$this->gzip_header['javascript'] = '<?	
			ob_start ("ob_gzhandler");
			header("Content-type: text/javascript; charset: UTF-8");
			header("Cache-Control: must-revalidate");
			$offset = 6000000 * 60 ;
			$ExpStr = "Expires: " . 
			gmdate("D, d M Y H:i:s",
			time() + $offset) . " GMT";
			header($ExpStr);
			?>';	
	
	$this->gzip_header['css'] = '<?
			ob_start ("ob_gzhandler");
			header("Content-type: text/css; charset: UTF-8");
			header("Cache-Control: must-revalidate");
			$offset = 6000000 * 60 ;
			$ExpStr = "Expires: " . 
			gmdate("D, d M Y H:i:s",
			time() + $offset) . " GMT";
			header($ExpStr);	
			?>';		
	
	
	}

	/**
	* Strips whitespace and comments from a text string
	* 
	**/	
	function minify_text($txt) {
	
		// Compress whitespace.
		$txt = preg_replace('/\s+/', ' ', $txt);
		// Remove comments.
		$txt = preg_replace('/\/\*.*?\*\//', '', $txt);
		
		return $txt;
	
	}

	/**
	* Safely trim whitespace from an HTML page
	* Adapted from smarty code http://www.smarty.net/
	**/		
	function trimwhitespace($source)
	{
		// Pull out the script blocks
		preg_match_all("!<script[^>]+>.*?</script>!is", $source, $match);
		$_script_blocks = $match[0];
		$source = preg_replace("!<script[^>]+>.*?</script>!is",
							   '@@@COMPRESSOR:TRIM:SCRIPT@@@', $source);
	
		// Pull out the pre blocks
		preg_match_all("!<pre>.*?</pre>!is", $source, $match);
		$_pre_blocks = $match[0];
		$source = preg_replace("!<pre>.*?</pre>!is",
							   '@@@COMPRESSOR:TRIM:PRE@@@', $source);
	
		// Pull out the textarea blocks
		preg_match_all("!<textarea[^>]+>.*?</textarea>!is", $source, $match);
		$_textarea_blocks = $match[0];
		$source = preg_replace("!<textarea[^>]+>.*?</textarea>!is",
							   '@@@COMPRESSOR:TRIM:TEXTAREA@@@', $source);
	
		// remove all leading spaces, tabs and carriage returns NOT
		// preceeded by a php close tag.
		$source = trim(preg_replace('/((?<!\?>)\n)[\s]+/m', '\1', $source));
		
		//Remove comments
		$source =  preg_replace("/<!--.*-->/U","",$source); 	
	
		// replace textarea blocks
		$this->trimwhitespace_replace("@@@COMPRESSOR:TRIM:TEXTAREA@@@",$_textarea_blocks, $source);
	
		// replace pre blocks
		$this->trimwhitespace_replace("@@@COMPRESSOR:TRIM:PRE@@@",$_pre_blocks, $source);
	
		// replace script blocks
		$this->trimwhitespace_replace("@@@COMPRESSOR:TRIM:SCRIPT@@@",$_script_blocks, $source);
	
		return $source;
	}

	/**
	* Helper function for trimwhitespace
	* 
	**/		
	function trimwhitespace_replace($search_str, $replace, &$subject) {
		$_len = strlen($search_str);
		$_pos = 0;
		for ($_i=0, $_count=count($replace); $_i<$_count; $_i++)
			if (($_pos=strpos($subject, $search_str, $_pos))!==false)
				$subject = substr_replace($subject, $replace[$_i], $_pos, $_len);
			else
				break;
	
	}	
	
	/**
	* Gets the directory we are in
	* 
	**/		
	function get_current_path($trailing=false) {
	
	   preg_match("@.*\/@",$_SERVER['REQUEST_URI'],$matches);
	   $current_dir = $matches[0];		
	   
	   //Remove trailing slash
	   if($trailing) {
		   if(substr($current_dir,-1,1) == "/") {
		   $current_dir = substr($current_dir,0,-1); 
		   }
	   }
	
	   return $current_dir;
	
	}

	/**
	* Gets the head part of the $source
	* 
	**/			
	function get_head($source) {
	
		preg_match("!<head([^>]+)?>.*?</head>!is", $source, $matches);
		
		if(is_array($matches)) {
		return $matches[0];	
		}
	
	
	}
	
	/**
	* Removes old cache files
	* 
	**/		
	function do_cleanup() {
	
	//Get all directories
	foreach($this->options AS $key=>$value) {
	$active_dirs[] = $_SERVER['DOCUMENT_ROOT'] . $value['cachedir'];
	}
		
		foreach($active_dirs AS $path) {
		
		$files = $this->get_files_in_dir($path);
				
			foreach($files AS $file) {
					
				if (strstr($file,"_cmp_") && !strstr($this->compressed_files_string,$file)) {
				unlink($path . "/" . $file);
				} // end if
		
			}
		
		}
	

	
	}	
	
	/**
	* Returns list of files in a directory
	* 
	**/	
	function get_files_in_dir($path) {
	
	// open this directory 
	$myDirectory = opendir($path);
	
	// get each entry
	while($entryName = readdir($myDirectory))
	{
		$dirArray[] = $entryName;
	}
	// close directory
	closedir($myDirectory);	
	
	return $dirArray;
	
	
	}
	
	//Start script timing
	function startTimer() {
	   $mtime = microtime();
	   $mtime = explode(" ",$mtime);
	   $mtime = $mtime[1] + $mtime[0];
	   $starttime = $mtime;
	   return $starttime;
	} 
	
	//Return current time
	function returnTime($starttime) {
		$mtime = microtime();
	   $mtime = explode(" ",$mtime);
	   $mtime = $mtime[1] + $mtime[0];
	   $endtime = $mtime;
	   $totaltime = ($endtime - $starttime);
	   return $totaltime;
	}	
	
		

} // end class



?>