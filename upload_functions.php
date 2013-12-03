<?php
function image_resize($name)
{
	$file = "images/$name";

	// Set a maximum height and width
	$target = 200;

	// Get new dimensions
	list($width_orig, $height_orig, $image_type) = getimagesize($file);

    if($width_orig <= $target && $height_orig <= $target)
    {
        return false;
    }
    
    //figure transform percentage
    if($width_orig > $height_orig)
    {
        $percentage = ($target / $width_orig);
    }
    else
    {
        $percentage = ($target / $height_orig);
    }
    
    //get new width/height values
    $width = round($width_orig * $percentage);
    $height = round($height_orig * $percentage);

	// Create empty new image at target width/height
	$thumb = imagecreatetruecolor($width, $height);
    
    //create an image resource of the original img
	switch($image_type)
	{
		case IMAGETYPE_GIF:
			$image = imagecreatefromgif($file);
			break;
		
        case IMAGETYPE_PNG:
			$image = imagecreatefrompng($file);
			break;
		
        case IMAGETYPE_JPEG:
			$image = imagecreatefromjpeg($file);
			break;
	}
	
    //create overlay resource
	$overlay = imagecreatefrompng('thumb_overlay.png');

    //resize original image (with resampling)
    //resource $dst_image, resource $src_image, int $dst_x, int $dst_y, int $src_x, int $src_y, int $dst_w, int $dst_h, int $src_w, int $src_h  )
	imagecopyresampled($thumb, $image, 0, 0, 0, 0, $width, $height, $width_orig, $height_orig);
	
    //copy the overlay on top of the thumb
    //resource $dst_im, resource $src_im, int $dst_x, int $dst_y, int $src_x, int $src_y, int $src_w, int $src_h
    imagecopy($thumb, $overlay, 2, $height - 18, 0, 0, 16, 16);

    //save the image
	switch($image_type)
	{
		//GIF thumbnails are output as a png, watch out for that
        case IMAGETYPE_GIF:
			$name = str_replace('gif', 'png', $name);
			imagepng($thumb, "images/thumbs/th_{$name}");
			break;
		
        case IMAGETYPE_PNG:
			imagepng($thumb, "images/thumbs/th_{$name}");
			break;
		
        case IMAGETYPE_JPEG:
			imagejpeg($thumb, "images/thumbs/th_{$name}", 70);
			break;
	}
    
	return array('w' => $width, 'h' => $height, 'name' => "th_{$name}");
}

function make_safe_name($name)
{
	$matches = array();
	if(!preg_match("/\.\w{3,4}$/", $name, $matches)) {
		exit("Couldn't figure out file type!");
    }
	
	$extension = $matches[0];

	//make the name safe
	$name = strip_tags($name);
	$name = str_replace($extension, '', $name);
	// Preserve escaped octets.
	$name = preg_replace('|%([a-fA-F0-9][a-fA-F0-9])|', '---$1---', $name);
	// Remove percent signs that are not part of an octet.
	$name = str_replace('%', '', $name);
	// Restore octets.
	$name = preg_replace('|---([a-fA-F0-9][a-fA-F0-9])---|', '%$1', $name);

	$name = strtolower($name);
	$name = preg_replace('/&.+?;/', '', $name); // kill entities
	$name = preg_replace('/[^%a-z0-9 _-]/', '', $name);
	$name = preg_replace('/\s+/', '-', $name);
	$name = preg_replace('|-+|', '-', $name);
	$name = trim($name, '-');
	$name = $name.$extension;

	return $name;
}