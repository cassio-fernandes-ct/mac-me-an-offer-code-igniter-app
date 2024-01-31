<?php
  if(isset($_POST['submit'])){
      $file_name = $_FILES['image']['name'];
      $file_size = $_FILES['image']['size'];
      $file_tmp = $_FILES['image']['tmp_name'];
      $file_type = $_FILES['image']['type'];
      move_uploaded_file($file_tmp,$file_name);  
      header ("Content-type: image/jpeg");
 
      $string = $_POST['caption'];
       
      $font = 12;
       
      $width = imagefontwidth($font) * strlen($string) ;
       
      $height = imagefontheight($font) ;
       
      $im = imagecreatefromjpeg($file_name);
       
      $x = imagesx($im) - $width ;
       
      $y = imagesy($im) - $height;
       
      $backgroundColor = imagecolorallocate ($im, 255, 255, 255);
       
      $textColor = imagecolorallocate ($im, 0, 0,0);
       
      imagestring ($im, $font, $x, $y, $string, $textColor);
      
      $a=imagejpeg($im);
    
  }
?>
<!DOCTYPE html>
<html>
<head>
      <title>PHP : Add Custom Text to Image</title>
</head>
<body>
<form enctype="multipart/form-data" method="POST" action="">
    <input type="file" name="image"> 
    <input type="text" name="caption">
    <input type="submit" name="submit">
</form>
</body>
</html>