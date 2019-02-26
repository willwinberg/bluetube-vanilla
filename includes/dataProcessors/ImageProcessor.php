<?php

class ImageProcessor {

   private $db;
   public $errors, $message;

   public function __construct($db) {
      $this->db = $db;
      $this->finalPath = NULL;
      $this->errors = array();
      $this->message = "";
   }

   public function validateImage() {
      $targetDir = "assets/images/profilePictures/";
      $targetFile = $targetDir . uniqid() . basename($_FILES["image"]["name"]);
      $imageFileType = strtolower(pathinfo($targetFile,PATHINFO_EXTENSION));
      
      if (isset($_POST["imageUpdate"])) {
         $check = getimagesize($_FILES["image"]["tmp_name"]);
         if (!$check) {
            $this->errors[] = Error::$notImage;
            return;
         }
      }

      if ($_FILES["image"]["size"] > 5000000) { // 5mb
         $this->errors[] = Error::$imageSize;
         return;
      }

      $allowedTypes = array("jpg", "png", "jpeg", "gif");

      if (!in_array($imageFileType, $allowedTypes)) {
         $this->errors[] = Error::$imageType;
         return;
      }
  
      if (!move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile)) {
         $this->errors[] = Error::$imageInvalid;
         return;
      }

      $this->resize_crop_image(300, 300, $targetFile, $targetFile);
      $this->finalPath = $targetFile;
   }

   //resize and crop image by center and make square
   // credit: https://polyetilen.lt/en/author/polyetilen
   private function resize_crop_image(
      $max_width,
      $max_height,
      $source_file,
      $dst_dir,
      $quality = 80
      ) {
      $imgsize = getimagesize($source_file);
      $width = $imgsize[0];
      $height = $imgsize[1];
      $mime = $imgsize['mime'];

      switch($mime){
         case 'image/gif':
               $image_create = "imagecreatefromgif";
               $image = "imagegif";
               break;

         case 'image/png':
               $image_create = "imagecreatefrompng";
               $image = "imagepng";
               $quality = 7;
               break;

         case 'image/jpeg':
               $image_create = "imagecreatefromjpeg";
               $image = "imagejpeg";
               $quality = 80;
               break;

         default:
               return false;
               break;
      }
      
      $dst_img = imagecreatetruecolor($max_width, $max_height);
      $src_img = $image_create($source_file);
      
      $width_new = $height * $max_width / $max_height;
      $height_new = $width * $max_height / $max_width;
      //if the new width is greater than the actual width of the image, then the height is too large and the rest cut off, or vice versa
      if($width_new > $width){
         //cut point by height
         $h_point = (($height - $height_new) / 2);
         //copy image
         imagecopyresampled($dst_img, $src_img, 0, 0, 0, $h_point, $max_width, $max_height, $width, $height_new);
      }else{
         //cut point by width
         $w_point = (($width - $width_new) / 2);
         imagecopyresampled($dst_img, $src_img, 0, 0, $w_point, 0, $max_width, $max_height, $width_new, $height);
      }
      
      $image($dst_img, $dst_dir, $quality);

      if($dst_img)imagedestroy($dst_img);
      if($src_img)imagedestroy($src_img);
   }

   public function updateImagePath($username) {
      $query = $this->db->prepare(
         "UPDATE users SET image=:image
         WHERE username=:username"
      );
      $query->bindParam(":image", $this->finalPath);
      $query->bindParam(":username", $username);
      $query->execute();

      if ($query->rowCount() === 1) {
         $this->message = Success::$image;
      } else {
         $this->message = Error::$image;
      }
      return;
   }

   public function errors() {
      if (!empty($this->errors)) {
         $html = "<ul>";

         foreach ($this->errors as $error) {
            $html .= "<li><span class='errorMessage'>$error</span></li>";
         }
         $html .= "</ul>";
      }

      return $html;
   }

}
?>