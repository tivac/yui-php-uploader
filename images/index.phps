<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
<head>
<head>
	<title>Image Uploading</title>
	<link rel="stylesheet" href="../upload.css" type="text/css" media="screen" />
</head>
<body>
<div id='header'>
	<form id='uploadForm' method='post' action='http://tivac.com/upload/index.php' enctype='multipart/form-data'>
	<input id='file' type='file' name='image' size='85' /><input id='submit' type='submit' value='Upload' />
	</form>
</div>

<?php

if ($handle = opendir('.'))
{
	while (false !== ($file = readdir($handle)))
	{
		if ($file != "." && $file != "..")
		{
			if(preg_match("/\.[png|gif|jpg]/", $file))
			{
				if(file_exists("thumbs/th_".$file))
					echo "<a href='$file'><img src='thumbs/th_".$file."' /></a> ";
				else
					echo "<img src='$file' /> ";
			}
		}
	}
	
	closedir($handle);
}
?>