<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
<head>
<head>
	<title>Image Manager</title>
	<link rel="stylesheet" type="text/css" href="http://yui.yahooapis.com/2.5.0/build/reset-fonts/reset-fonts.css" />
    <link rel="stylesheet" type="text/css" href="images.css" />
</head>
<body>

<ul id="images">
<?php
if ($handle = opendir('.'))
{
    $files = array();
    
    while (false !== ($file = readdir($handle)))
	{
		if ($file != "." && $file != "..")
		{
			if(preg_match("/\.(png|gif|jpg)/i", $file))
			{
                $files[] = array('name' => $file, 'stat' => stat($file));
			}
		}
	}
	
	closedir($handle);
    
    function cmp($a, $b)
    {
        if($a['stat']['mtime'] == $b['stat']['mtime'])
        {
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
    
    if($o > $images)
    {
        $o = 0;
    }
    
    $files = array_slice($files, $o, $perpage, true);
    
    foreach($files as $file)
    {
        $name = $file['name'];
        $thumb = "thumbs/th_" . str_replace(".gif", ".png", $name);
        
        echo "
        <li>
            <div class='bd'>
                <a href='{$name}' class='img'>";
        
        if(file_exists($thumb))
        {
            echo "<img src='{$thumb}' />";
        }
        else if($file['stat']['size'] < 102400)
        {
            echo "<img src='{$name}' /> ";
        }
        else
        {
            echo $name;
        }
        
        echo "
                </a>
                <p>
                    <a class='del' href='delete.php?img={$name}'>Delete</a>
                </p>
            </div>
        </li>";
    }

    echo "
    </ul>
    
    <ul class='pagination'>";
    
    $totalpages = ceil($images / $perpage);
    for($i = 0; $i < $totalpages; $i++)
    {
        echo "<li>";
        
        $page = $i + 1;
        
        if(($i * $perpage) <= $o && ($page * $perpage) > $o)
        {
            echo "<strong>{$page}</strong>";
        }
        else
        {
            $href = ($i == 0) ? '/upload/images/' : '?o=' . ($i * $perpage);
            
            echo "<a href='{$href}'>{$page}</a>";
        }
        
        echo "</li>";
    }
    
    echo "
    </ul>";
}
?>

</body>
</html>
