<?php
if(isset($_GET['clear'])) {
    $files = glob("*");
    $now = time();
    
    foreach($files as $file) {
        if(!is_file($file) || stristr($file, ".php") || stristr($file, ".css")) {
            continue;
        }
        
        if($now - filemtime($file) >= 30 * 24 * 60 * 60) { // 30 days
            unlink($file);
            
            if(file_exists("thumbs/th" . $file)) {
                unlink("thumbs/th" . $file);
            }
        }
    }
    
    header("Location: /upload/images/");
    
    exit();
}

if(isset($_GET['img'])) {
    $file = filter_var($_GET['img']);

    if($file) {
        unlink($file);
        
        if(file_exists("thumbs/th" . $file)) {
            unlink("thumbs/th" . $file);
        }
        
?>
<!DOCTYPE html>
<html>
<head>
<head>
	<title>Image Deleted!</title>
	<link rel="stylesheet" type="text/css" href="http://yui.yahooapis.com/2.5.0/build/reset-fonts/reset-fonts.css" />
    <link rel="stylesheet" type="text/css" href="images.css" />
</head>
<body>
    <?php echo $file ?> deleted! <a href="/upload/images/">Go Back</a>
</body>
</html>
<?php
        exit();
    }
}

header("Location: /upload/images/");
