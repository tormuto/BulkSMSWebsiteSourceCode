<?php
    class MY_Upload extends CI_Upload {
        public function __construct($config = array()){
            parent::__construct($config);
        }
        
        public function do_upload($field = 'userfile'){
            $successful=parent::do_upload($field);
            if($successful){
                //if image; resize to corrupt bad image.
                $sourceImage=$this->upload_path.$this->file_name;
                $ftype=strtolower($this->file_type);
                $f_ext=strtolower($this->file_ext);
                
                $is_image=true;
                $img_types = array('image/jpeg', 'image/png','image/gif');
                //maybe deceptive mime-type was sent; check the extension too
                if(!in_array($ftype,$img_types)){
                    $jpeg_ext=array('jpg','jpeg','jpe','pjpeg','njpeg','jp2','j2k','jpf','jpx','jpm','mj2');
                    if(in_array($f_ext,$jpeg_ext))$ftype='image/jpeg';
                    elseif($f_ext=='png')$ftype='image/png';
                    elseif($f_ext=='gif')$ftype='image/gif';
                    else $is_image=false;
                }
                
                if($is_image){ //distort the image to prevent exploit
                    $has_alpha=true;
                    if($ftype=='image/png'){
                        if (!$image = @imagecreatefrompng($sourceImage))return false;
                    } elseif($ftype=='image/gif'){
                        if (!$image = @imagecreatefromgif($sourceImage))return false;
                    } else {
                        if (!$image = @imagecreatefromjpeg($sourceImage))return false;
                        $has_alpha=false;
                    }

                    $quality=100; //99%
                    list($origWidth,$origHeight)=getimagesize($sourceImage);
                    $newWidth=$origWidth; $newHeight=$origHeight;
                    
                    //$ratio=0.90; //99%
                    $ratio=1;
                    // Calculate new image dimensions.
                    $newWidth  = (int)$origWidth  * $ratio;
                    $newHeight = (int)$origHeight * $ratio;
                    
                    $newImage = imagecreatetruecolor($newWidth, $newHeight);
                    if($has_alpha)imagesavealpha($newImage , true);
                    imagecopyresampled($newImage, $image, 0, 0, 0, 0, $newWidth, $newHeight, $origWidth, $origHeight);
                    $targetImage=$sourceImage;
                    
                    
                    if($has_alpha){
                        imagealphablending($newImage,false);
                        imagesavealpha($newImage,true);
                        //convert quality to compression. (inverse)
                        if($quality==0||$quality==100)$quality=0;
                        else { $quality=floor((100-$quality)/100); }
                    }

                    if($ftype=='image/png')imagepng($newImage, $targetImage, $quality);
                    elseif($ftype=='image/gif')imagegif($newImage, $targetImage, $quality);
                    else imagejpeg($newImage, $targetImage, $quality);
                    
                    //free memory
                    imagedestroy($image);
                    imagedestroy($newImage);
                }            
            }
            
            return $successful;
        }
    }