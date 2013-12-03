<?php
set_time_limit(300);

header('Content-type: text/json');

include('upload_functions.php');
error_reporting(E_ALL);

if(isset($_FILES['Filedata'])) {
    $out = array();
    $x = 0;
    
    $file = $_FILES['Filedata'];
    
    $error = false;
    
    $temp_name = $file['tmp_name'];
    
    if(is_uploaded_file($temp_name)) {
        
        // get image information
        list($image_width, $image_height, $image_type) = getimagesize($temp_name);
        
        if(isset($image_type)) {
            if($image_type == IMAGETYPE_GIF || $image_type == IMAGETYPE_PNG || $image_type == IMAGETYPE_JPEG) {
                $name = $file['name'];
                $save_name = substr(basename($temp_name), 2)."_".make_safe_name($name);
                
                if(move_uploaded_file($temp_name, "images/$save_name")) {
                    $thumb = image_resize($save_name);
                    
                    $image_url = "http://tivac.com/upload/images/$save_name";
                    $thumb_url = "http://tivac.com/upload/images/" . (($thumb) ? "thumbs/{$thumb['name']}" : $save_name);
                    
                    if(isset($_POST['raw'])) {
                        exit($image_url);
                    } 
                    else {
                        $out['url']   = $image_url;
                        $out['thb']   = $thumb_url;
                        $out['name']  = $name;
                        $out['width'] = ($thumb) ? $thumb["w"] : $image_width;
                        
                        $out['links']['thb']  = "<a href='{$image_url}'><img src='{$out['thb']}' alt='{$name}'/></a>";
                        $out['links']['fimg'] = "[img]{$image_url}[/img]";
                        $out['links']['fthb'] = "[url={$image_url}][img]{$out['thb']}[/img][/url]";
                    }
                }
                else {
                    $error = true;
                }
            }
            else { 
                $error = true;
            }
        }
    }
    
    if($error) {
        $out['error'] = true;
        $out['name'] = $name;
    }
    
    echo json_encode($out);
}