<?php
include('/home/tivac/tivac.com/shared/RSS.php');

if($handle = opendir('.')) {
    $root = "http://tivac.com/upload/images/";
    
    //set up RSS feed object
    $feed = new RSS();
    $feed->title       = "Recent Uploads";
    $feed->link        = $root;
    $feed->description = "Recent uploads to tivac.com";
    
    //find files
    $files = array();
    
    while (false !== ($file = readdir($handle))) {
		if ($file != "." && $file != "..") {
			if(preg_match("/\.(png|gif|jpg|jpeg)$/i", $file)) {
                $files[] = array('name' => $file, 'stat' => stat($file), 'size' => getimagesize($file));
			}
		}
	}
	
	closedir($handle);
    
    //sort files by modified time
    function cmp($a, $b) {
        if($a['stat']['mtime'] == $b['stat']['mtime']) {
            return 0;
        }
        
        //output in reverse order
        return ($a['stat']['mtime'] < $b['stat']['mtime']) ? 1 : -1;
    }
    
    usort($files, 'cmp');
    
    $images = count($files);
    
    //now that we're all sorted, handle pagination!
    $perpage = 50;
    $o = (isset($_REQUEST['o'])) ? filter_input(INPUT_GET, 'o', FILTER_VALIDATE_INT) : 0;
    
    if($o > $images) {
        $o = 0;
    }
    
    $files = array_slice($files, $o, $perpage, true);
    
    foreach($files as $file) {
        $img = $root . $file['name'];
        
        $item = new RSSItem();
        $item->title = $file['name'];
        $item->link  = $img;
        $item->setPubDate($file['stat']['mtime']); 
        
        
        $media = new RSSMedia();
        
        //figure out the mimetype
        $len = strlen($file['name']) - 4;
        if(strpos($file['name'], 'gif', $len)) {
            $mime = 'gif';
        } else if (strpos($file['name'], 'png', $len)) {
            $mime = 'png';
        } else if (strpos($file['name'], 'jpg', $len) || strpos($file['name'], 'jpeg', $len)) {
            $mime = 'jpeg';
        }
        
        //add main image
        $media->content($img, 'image/' . $mime, $file['size'][1], $file['size'][0]);
        $media->title($file['name']);
        
        //add thumbnail
        $thumb_path = "thumbs/th_" . str_replace(".gif", ".png", $file['name']);
        
        if(file_exists($thumb_path)) {
            $size = getimagesize($thumb_path);
            $img = $root . $thumb_path;
            
            $media->thumbnail($img, $size[1], $size[0]);
        }
        
        $item->description = "<![CDATA[ <a href='{$root}{$file['name']}'><img src='{$img}' /></a><br /><br /><a class='del' href='{$root}delete.php?img={$file['name']}'>Delete {$file['name']}</a> ]]>";
        
        $item->addMedia($media);
        $feed->addItem($item);
    }
    
    echo $feed->serve();
}