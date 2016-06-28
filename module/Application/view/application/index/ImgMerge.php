<?php

/**********************************
BSD - Licence
Feel free to copy or distrubute, just remember to add maztch@gmail.com from www.maztch.es
************************************/

class ImgMerge{

// This functionality can be used to create avatar images based on user choice or whatermarks, buttons, etc.

// give an array with img paths, output is optional and if set it will save the image, if not will output directly

// first image will be the back and last one in array the superior in the merge

// options to position to merge: topleft, topcenter, topright, centerleft, center, centerright, bottomleft, bottomcenter, bottomright
// this will be based in image sizes

		public function ImgMerge($imgfiles, $pos="center", $output="")	{	
					$imgbuff = array ();
						$maxw = 0;
						$maxh = 0;
						foreach ($imgfiles as $filename)
						{
							if(!file_exists($filename)){
								echo "error, file $filename dosen't exists";
							}
							else{
								$size = getimagesize($filename);
							
								$maxw = max($maxw, $size[0]);
								$maxh = max($maxh, $size[1]);
								switch($size['mime']){
									case 'image/png': $tmp = imagecreatefrompng($filename); break;
									case 'image/gif': $tmp = imagecreatefromgif($filename); break;
									case 'image/x-windows-bmp':
									case 'image/bmp' : $tmp = imagecreatefromwbmp($filename); break;
									case 'image/pjpeg';
									case 'image/pjpeg';
									case 'image/jpeg': $tmp = imagecreatefromjpeg($filename); break;               
								}
								$imgbuff[] = $tmp;
							}
						}
						
						//create transparent image base
						$out = imagecreatetruecolor ($maxw,$maxh) ;
						//imagefill($out,0,0,0x7fff0000);						
						$color = imagecolorallocatealpha($out, 255, 255, 255, 127);
						
						imagefill($out, 0, 0, $color);
						//$color = ImageColorResolveAlpha($out, 255, 255, 255, 127);
						// Make the background transparent
						//imagecolortransparent($out, $color);
						
						
						foreach($imgbuff as $imgadd){
							
							imagealphablending($imgadd,true);
							imagesavealpha($imgadd, true);
							imagealphablending($out,true);
							imagesavealpha($out, true);
							
							switch($pos){
								case "topleft": imagecopymerge ($out, $imgadd, 0, 0, 0, 0, imagesx($imgadd), imagesy($imgadd), 100); break;
								case "topcenter" : imagecopymerge ($out, $imgadd, (($maxw/2)-(imagesx($imgadd)/2)), 0, 0, 0, imagesx($imgadd), imagesy($imgadd), 100); break;
								case "topright" : imagecopymerge ($out, $imgadd, ($maxw-imagesx($imgadd)), 0, 0, 0, imagesx($imgadd), imagesy($imgadd), 100);break;
								case "centerleft" : imagecopymerge ($out, $imgadd, 0, (($maxh/2)-(imagesy($imgadd)/2)), 0, 0, imagesx($imgadd), imagesy($imgadd), 100); break;
								case "centermerge" : imagecopymerge ($out, $imgadd, (($maxw/2)-(imagesx($imgadd)/2)), (($maxh/2)-(imagesy($imgadd)/2)), 0, 0, imagesx($imgadd), imagesy($imgadd), 100); break;
								case "center" : imagecopy ($out, $imgadd, (($maxw/2)-(imagesx($imgadd)/2)), (($maxh/2)-(imagesy($imgadd)/2)), 0, 0, imagesx($imgadd), imagesy($imgadd)); break;
								case "centerright" : imagecopymerge ($out, $imgadd, (($maxw/2)-(imagesx($imgadd)/2)), ($maxh-imagesy($imgadd)), 0, 0, imagesx($imgadd), imagesy($imgadd), 100); break;
								case "bottomleft" : imagecopymerge ($out, $imgadd, 0, ($maxh-imagesy($imgadd)), 0, 0, imagesx($imgadd), imagesy($imgadd), 100); break;
								case "bottomcenter" : imagecopymerge ($out, $imgadd, (($maxw/2)-(imagesx($imgadd)/2)), ($maxh-imagesy($imgadd)), 0, 0, imagesx($imgadd), imagesy($imgadd), 100); break;
								case "bottomright" : imagecopymerge ($out, $imgadd, ($maxw-imagesx($imgadd)), ($maxh-imagesy($imgadd)), 0, 0, imagesx($imgadd), imagesy($imgadd), 100); break;
								//default center
								default: imagecopymerge ($out, $imgadd, (($maxw/2)-(imagesx($imgadd)/2)), (($maxh/2)-(imagesy($imgadd)/2)), 0, 0, imagesx($imgadd), imagesy($imgadd), 100);
							}
							
							imagedestroy ($imgadd);
						}
						
						// Output and free from memory
						if($output!="" or $output!=null){
							//save to file
							imagepng($out, $output);
						}
						else{
							//print to browser
							//need header to be a standalone image
							imagepng($out);
						}
						//this to download
						//header('Content-Type: image/png');
						//header("Content-Transfer-Encoding: binary");
						//header("Content-type: image/png");
						//header("Content-Disposition: attachment; filename=image.png");
						
						//this to see
						//header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
						//header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Date in the past
						//Header( "Content-type: image/png");
						imagedestroy($out);
				}
				
}

?>
