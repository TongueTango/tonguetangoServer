<?php
/*
 * This controller is publicly accessible for playing the 
 * */
class  PlayerController  extends CController
{
    public $layout = '//layout/template';
    
	public function actionIndex()
	{
		$uri     = Yii::app()->request->getParam('uri');
        $uri     = str_replace('#', '', $uri);
        
		$message = SocialMessages::model()->findByAttributes(array('message_body'=>$uri));
        
        $photo = ($message) ? $message->user->person->photo : null;
        $first = ($message) ? ucfirst($message->user->person->first_name) : null;
        $last  = ($message) ? ucfirst($message->user->person->last_name[0].".") : null;
        
        $detect = new MobileDetect;
        $deviceType = ($detect->isMobile() ? ($detect->isTablet() ? 'tablet' : 'phone') : 'computer');
        
		if ( isset($uri) && $uri != '' ) {
            
            if(!is_null($photo)) {
                if($this->detect_fb_image($photo)) {
                    $photo = $photo."?type=large";
                } else {
                    $photo = $photo;
                }
                
                $photo = $this->_image_resize($photo);
            }
            
            if($detect->isMobile()) {
               $this->render('mobile',array('uri'=>$uri,'photo'=>$photo,'firstname'=>$first,'lastname'=>$last));
            } else {
                $this->render('play2',array('uri'=>$uri,'photo'=>$photo,'firstname'=>$first,'lastname'=>$last));
            }
            
		}else{
			die('Message not found');
		}
		
	}
    
    public function actionPlayer()
    {
        $uri     = Yii::app()->request->getParam('uri');
        $uri     = str_replace('#', '', $uri);
        
		$message = SocialMessages::model()->findByAttributes(array('message_body'=>$uri));
        
        $photo = ($message) ? $message->user->person->photo : null;
        $first = ($message) ? ucfirst($message->user->person->first_name) : null;
        $last  = ($message) ? ucfirst($message->user->person->last_name[0].".") : null;
        
        $detect = new MobileDetect;
        $deviceType = ($detect->isMobile() ? ($detect->isTablet() ? 'tablet' : 'phone') : 'computer');
        
		if ( isset($uri) && $uri != '' ) {
            
            if(!is_null($photo)) {
                $photo = $photo."&type=large";
                $photo = $this->_image_resize($photo);
            }
            
//            if($detect->isMobile()) {
                $this->render('mobile',array('uri'=>$uri,'photo'=>$photo,'firstname'=>$first,'lastname'=>$last));
//            } else {
               // $this->render('play2',array('uri'=>$uri,'photo'=>$photo,'firstname'=>$first,'lastname'=>$last));
//            }
            
		}else{
			die('Message not found');
		}
    }
    
    protected function _image_resize($imageUrl)
    {
        $image_file    = $this->_cache_image($imageUrl);
        $corner_radius =  90; // The default corner radius is set to 20px
        $angle         =  0; // The default angle is set to 0º
        $topleft       =  true; // Top-left rounded corner is shown by default
        $bottomleft    =  true; // Bottom-left rounded corner is shown by default
        $bottomright   =  true; // Bottom-right rounded corner is shown by default
        $topright      =  true; // Top-right rounded corner is shown by default

        $images_dir    = Yii::app()->basePath.'/cache/';
        $save_dir      = Yii::app()->basePath.'/../images/';
//        $corner_source = imagecreatefrompng($images_dir.'corner40.png');
//
//        $corner_width  = imagesx($corner_source);  
//        $corner_height = imagesy($corner_source);  
        
        
//        $corner_resized = ImageCreateTrueColor($corner_radius, $corner_radius);
//        ImageCopyResampled($corner_resized, $corner_source, 0, 0, 0, 0, $corner_radius, $corner_radius, $corner_width, $corner_height);
//
//        $corner_width  = imagesx($corner_resized);  
//        $corner_height = imagesy($corner_resized);
//        
        $imageSettings = getimagesize($image_file);
        
        
        if($imageSettings['mime'] == "image/jpeg") {
            $image = imagecreatefromjpeg($image_file);
            $ext = ".jpg";
        } elseif($imageSettings['mime'] == "image/png") {
            $image = imagecreatefrompng($image_file);
            $ext = ".png";
        } elseif($imageSettings['mime'] == "image/gif") {
            $image = imagecreatefromgif($image_file);
            $ext = ".gif";
        } 
        
        

        $thumb_width = 200;
        $thumb_height = 200;

        $width = imagesx($image);
        $height = imagesy($image);

        $original_aspect = $width / $height;
        $thumb_aspect = $thumb_width / $thumb_height;

        if ( $original_aspect >= $thumb_aspect )
        {
            $new_height = $thumb_height;
            $new_width = $width / ($height / $thumb_height);
        }
        else
        {
            $new_width  = $thumb_width;
            $new_height = $height / ($width / $thumb_width);
        }

        $thumb = imagecreatetruecolor( $thumb_width, $thumb_height );

        // Resize and crop
        imagecopyresampled($thumb,
                        $image,
                        0 - ($new_width - $thumb_width) / 2, // Center image horizontally
                        0 - ($new_height - $thumb_height) / 2, // Center image vertically
                        0, 0,
                        $new_width, $new_height,
                        $width, $height);
//        $white = ImageColorAllocate($thumb, 255, 255, 255);
//        $black = ImageColorAllocate($thumb ,0 ,0 ,0 );
//
//        // Top-left corner
//        if ($topleft == true) {
//            $dest_x = 0;  
//            $dest_y = 0;  
//            imagecolortransparent($corner_resized, $black); 
//            imagecopymerge($thumb, $corner_resized, $dest_x, $dest_y, 0, 0, $corner_width, $corner_height, 100);
//        } 
//
//        // Bottom-left corner
//        if ($bottomleft == true) {
//            $dest_x = 0;  
//            $dest_y = 200 - $corner_height; 
//            $rotated = imagerotate($corner_resized, 90, 0);
//            imagecolortransparent($rotated, $black); 
//            imagecopymerge($thumb, $rotated, $dest_x, $dest_y, 0, 0, $corner_width, $corner_height, 100);  
//        }
//
//        // Bottom-right corner
//        if ($bottomright == true) {
//            $dest_x = 200 - $corner_width;  
//            $dest_y = 200 - $corner_height;  
//            $rotated = imagerotate($corner_resized, 180, 0);
//            imagecolortransparent($rotated, $black); 
//            imagecopymerge($thumb, $rotated, $dest_x, $dest_y, 0, 0, $corner_width, $corner_height, 100);  
//        }
//
//        // Top-right corner
//        if ($topright == true) {
//            $dest_x = 200 - $corner_width;  
//            $dest_y = 0;  
//            $rotated = imagerotate($corner_resized, 270, 0);
//            imagecolortransparent($rotated, $black); 
//            imagecopymerge($thumb, $rotated, $dest_x, $dest_y, 0, 0, $corner_width, $corner_height, 100);  
//        }
//
//        // Rotate image
//        $image = imagerotate($thumb, $angle, $white);
        
        $name = md5(microtime(true)).$ext;
        $newFile = $save_dir.$name;
        unlink($image_file);
        // Output final image
        imagejpeg($thumb, $newFile);
        imagedestroy($thumb);
        
        return $name;
        
    }
    
    protected function _cache_image($imageUrl) 
    {
        $data = file_get_contents($imageUrl);
        
        $imageSettings = getimagesize($imageUrl);
        
        $ext = ".gif";
        if($imageSettings['mime'] == "image/jpeg") {
            $ext = ".jpg";
        } elseif($imageSettings['mime'] == "image/png") {
            $ext = ".png";
        } 
        
        $output_filename	= Yii::app()->basePath.'/cache/'.md5(microtime(true)).$ext;
        file_put_contents($output_filename, $data);
        
        return $output_filename;
    }
    
    protected function detect_fb_image($image)
    {
        if(strpos($image, "graph.facebook") !== false) {
            return true;
        } else if(strpos($image, "amazon") !== false) {
            return false;
        }
    }
}