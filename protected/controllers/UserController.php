<?php
Yii::import('ext.logger.CPSLiveLogRoute');
class UserController extends Controller
{
    
    /**
     * @return user information
     * 
     */
    public function actionIndex()
	{
    	$person	= self::$user->person;
    	foreach( self::$user->attributes as $key => $value ) {
    		if( !in_array($key, array('passwd', 'id', 'person_id')) && !is_array($value) ) {
    			$this->setOutput($key, $value);
    		}
    	}
    	foreach( $person->attributes as $key => $value ) {
    		if( !in_array($key, array('id', 'email_id', 'phone_id', 'address_id')) && !is_array($value) ) {
    			$this->setOutput($key, $value);
    		}
    	}
    	if( $person->email ) {
    		$this->setOutput('email_type',		$person->email->email_type);
    		$this->setOutput('email_address',	$person->email->email_address);
    	}
    	if( $person->phone ) {
    		$this->setOutput('phone_type',		$person->phone->phone_type);
    		$this->setOutput('phone_number',	$person->phone->phone_number);
    	}
    	$this->setOutput('user_id',		self::$user->id);
	}
    
    /**
     *
     * @return user information
     * @todo perform login action
     */
    public function actionLogin()
    {
        $data = $this->_request_data;
        if (!sizeof($data)) $data = $_REQUEST;
        
        if( array_key_exists('facebook_access_token', $data) && $data['facebook_access_token'] != '' ) {
            
            $user = Users::model()->find("facebook_access_token='" . $data["facebook_access_token"]."'");
            
            $inputs =  array_merge(array("isUsingSocialNetworking"=>true),$data);
            $person = $this->create_person($data);
            
            if( $this->getInput('email_address') ) {
            
                $person_email = $this->create_person_email($inputs, $person);
                if($person_email === false){
                    $person->delete();
                    $this->_sendResponse(200, array("code"=>'0','message'=>'This email address is already taken, please try a different email address.'));

                }
                $person->email_id   = $person_email->id;
                
                $person->save();
            }
            // Process phone but do not require it.
            if( $this->getInput('phone') ) {
                $person_phone = $this->create_person_phone($inputs);

                $person->phone_id   = $person_phone->id;
                $person->save();
            }
            
            if (!$user && isset($data['facebook_id'])) {
                
                $command = Yii::app()->db->createCommand()
                        ->select('users.id')
                        ->from('users')
                        ->join('people', 'users.person_id=people.id')
                        ->where('people.facebook_id=:fId', array(':fId'=>$data['facebook_id']));
                
                $userId = $command->queryRow();
                
                if ($userId) {
                    
                    $user = Users::model()->findByPk($userId['id']);
                    $user->facebook_access_token = $data["facebook_access_token"];
                    $user->save();
                    
                } else {
                    /*
                     * @todo registering new facebook user
                     */
                    $user = new Users;
                    $user->setAttributes($data);
                    
                    $user->person_id = $person->id;
                    
                    $user->save();
                    if(isset($data['facebook_id']) && $data['facebook_id'] != '') {
                        $this->_inform_my_friends($data['facebook_id'], $user);
                    }
                    if(isset($data['email_address']) && $data['email_address'] != '') {
                        $phoneNumber = (isset($person_phone) && $person_phone)  ? $person_phone->phone_number : null; 
                        $this->_inform_address_book_contacts($data['email_address'], $phoneNumber, $user);
                        
                        if (!empty($data['email_address'])) {

                            $body = $this->render( 'email/welcome', array(
                                'content' => '',
                                'email' => $data['email_address'],
                            ), true );

                            $email = Yii::app()->email;
                            $email->to = $data['email_address']; 
                            $email->from = 'no-reply@tonguetango.com'; 
                            $email->subject = 'Welcome to Tongue Tango';
                            $email->message = $body;
                            $email->send();
                        }
                    }
                }
                
            }
            
        
        }  elseif((array_key_exists('username', $data) && !empty($data['username']))
                && (array_key_exists('passwd', $data) && !empty($data['passwd'])) ) {
            
            $user = Users::model()->find('username= "' . $data['username'] . '"');
            
            if( !$user ) {
                $email = PersonEmails::model()->find( 'email_address=:email', array( ':email' => $data['username'] ) );
                
                if($email) {
                    foreach ( $email->person->users as $tmpUser ) {
                        if( $tmpUser->passwd == md5( $data['passwd'] ) ) {
                            $user = $tmpUser; break;
                        }
                    }
                }     
            }
            
            if (!$user)
            {
               $this->_sendResponse(200, array("code"=>'0','message'=>'Invalid username'));
            } else             
            if ($user->passwd != md5( $data['passwd']))
            {
                $this->_sendResponse(200, array("i_want" => $user->passwd, "you_said" => md5( $data['passwd']),
	                "code"=>'0','message'=>'Incorrect password'));
            }
            
        } else {
            $this->_sendResponse(200, array("data" => $data, "code"=>'0',
                                            'message'=>'No usable account identification available, please pass facebook token or username and password!'));
        }
        if( !$user ){
            $this->_sendResponse(200, array("code"=>'0','message'=>'Incorrect username / password combination'));
        }
        
        self::$user = $user;
        
        $data = array_merge($data,array(
            "user_id"   => self::$user->id
        ));
        
        //check if auth token is already generated
        if (!isset($data['unique_id'])) {
            $this->_sendResponse(200, array("code"=>'0','message'=>'Device unique_id is required.'));
        }
        
        $device = Devices::model()->find("unique_id= '" . $data["unique_id"] . "'");
        
        if($device && $device instanceof CActiveRecord) {
            $device->setAttributes($data);
            $device->user_id = self::$user->id;
            if(isset($data['push_token']))
            $device->push_token = $data['push_token'];
            try {
                $device->save();
            } catch( Exception $e ) {
                $this->_sendResponse(200, array("code"=>'0','message'=>$e->getMessage()));
            }
        } else {
            $device = new Devices;
            $device->user_id = self::$user->id;
            $device->setAttributes($data);
            if(isset($data['push_token']))
                $device->push_token = $data['push_token'];
            $device->save();
        }
        
        self::$device = $device;
        
        $friends = self::$user->get_facebook_friends($data);
        self::$user->save_facebook_friends($friends);
        $friends = self::$user->get_tongue_tango_friends($friends);
        
        if(self::$device && self::$device instanceof CActiveRecord) {
            $this->setOutput("token",           self::$device->auth_token);
        }
        $this->setOutput("user_id",         self::$user->id);
        $this->setOutput("photo",           self::$user->person->photo);
        
        foreach(self::$user as $key=>$value) $this->setOutput($key,$value);
        foreach(self::$user->person as $key=>$value) {
            if( !in_array($key, array('id', 'email_id', 'phone_id', 'address_id')) && !is_array($value) ) {
                $this->setOutput($key, $value);
            }
        }
        $this->setOutput('fb_friends',      $friends['fb_friends']);
        $this->setOutput('tt_friends',      $friends['tt_friends']);
        
        $person = self::$user->person;
        if( $person->email ) {
            $this->setOutput('email_type',      $person->email->email_type);
            $this->setOutput('email_address',   $person->email->email_address);
        }
        if( $person->phone ) {
            $this->setOutput('phone_type',      $person->phone->phone_type);
            $this->setOutput('phone_number',    $person->phone->phone_number);
        }
        
        $this->setOutput('user_id',     self::$user->id);
        
    }
    
    /**
     *
     * @return user facebook and Tongue Tango friends
     * @todo Syncronise facebook friends while regular login process
     * @since v1.7
     */
    public  function actionFbSync()
    {
        $facebook_token = $this->getInput('facebook_access_token');
        
        if(!$facebook_token) {
            $this->_sendResponse(400, array("code"=>'0','message'=>"Facebook access token is missing"));
        }
        self::$user->facebook_access_token = $facebook_token;
        self::$user->save();
        
        $friends = self::$user->get_facebook_friends();
        
        
        self::$user->save_facebook_friends($friends);
        $friends = self::$user->get_tongue_tango_friends($friends);
        $this->setOutput('fb_friends',      $friends['fb_friends']);
        $this->setOutput('tt_friends',      $friends['tt_friends']);
        
    }
    
    /**
     *
     * @return user information
     * @todo Perform new user registration
     */
    public function actionRegistration()
    { 
        if( !is_null(self::$user) ) {
            $this->actionUpdate();
            return;
        }

        $inputs = $this->getInputs();
        
        $isUsingSocialNetworking = (isset($inputs["facebook_access_token"]) && $inputs["facebook_access_token"] != '') || 
                                   (isset($inputs["twitter_auth_token"]) && $inputs["twitter_auth_token"] != '');
        
        if($isUsingSocialNetworking ) {
        
            if( isset($inputs["facebook_access_token"]) && $inputs["facebook_access_token"] != ''
                && $this->does_user_exist($inputs["facebook_access_token"], null)){
                
                
                $this->actionLogin();
                
                
                return;
            }
        }
        else { //regular Tt account
            $username   = $this->getInput('username', false);
            
            $user   = Users::model()->findByAttributes(array( 'username' => $username ));
            if($user) {
                $this->_sendResponse(200, array("code"=>'0','message'=>'This username is already taken, please try a different username.'));
            }
        }
        
        $person = $this->create_person($inputs);
        if(!$person) return;
        

        $inputs = array_merge(array("person_id"=>$person->id),$inputs);
        $inputs = array_merge(array("isUsingSocialNetworking"=>$isUsingSocialNetworking),$inputs);
        
        if( $this->getInput('email_address') ) {
            
            $person_email = $this->create_person_email($inputs);
            if($person_email === false){
                $person->delete();
                $this->_sendResponse(200, array("code"=>'0','message'=>'This email address is already taken, please try a different email address.'));
                
            }
            $person->email_id   = $person_email->id;
            $person->save();
        }
        // Process phone but do not require it.
        if( $this->getInput('phone') ) {
            $person_phone = $this->create_person_phone($inputs);
            
            $person->phone_id   = $person_phone->id;
            $person->save();
        }
        

        $inputs = array_merge($inputs, array('person_id' => $person->id));
        $user = $this->create_user($inputs);

        if(!$user){
            $person_email->delete();
            $person->delete();
            return;
        }
        if (!empty($inputs['email_address'])) {
            $phoneNumber = (isset($person_phone) && $person_phone)  ? $person_phone->phone_number : null; 
            $this->_inform_address_book_contacts($inputs['email_address'], $phoneNumber, $user);
            
            $body = $this->render( 'email/welcome', array(
                'content' => '',
                'email' => $inputs['email_address'],
            ), true );

            $email = Yii::app()->email;
            $email->to = $inputs['email_address']; //, $inputs['first_name'].' '.$inputs['last_name'];
            $email->from = 'no-reply@tonguetango.com'; //, 'Tongue Tango';
            $email->subject = 'Welcome to Tongue Tango';
            $email->message = $body;
            $email->send();
        }
        
        $this->actionLogin();
        
        $this->setOutput("user_id",         $user->id);  
    }

    /**
     * @todo Updates user and person information
     * @return Updated info of user
     */
    public function actionUpdate()
    {
        $person = self::$user->person;
        $file   = $this->getInput('file');
        if( is_array($file) && array_key_exists('name', $file) ) { 
            $bucketName = 'tonguetangofiles';
            $fileName = md5((string)microtime(true)).$file['name'];
            if( Yii::app()->s3->upload($file['tmp_name']  , $fileName, $bucketName ) ) {
                    $public_uri = "http://".$bucketName.".s3.amazonaws.com/".$fileName;
                    $person->photo	= $public_uri;
                    $person->save();
            
            }
        }
        $inputs = $this->getInputs();
        

        if ( $person->facebook_id == null && isset($inputs['facebook_id']) 
            && $inputs['facebook_id'] != '' ) {
                        
            // try to find an existing person record
            $other_person = People::model()->find('facebook_id="'.$inputs['facebook_id'] .'" AND  id!='.self::$user->person_id);

            if ( $other_person ) {
                // if we found a person with a user record, throw the error
                $other_user = Users::model()->findByAttributes( array( 'person_id' => $other_person->id ) );
                if ( $other_user ) {
                    
                    $this->_sendResponse(200, array("code"=>'0','message'=>'Facebook Error: This FB account belongs to another person/user'));
                } else {
                    // otherwise, update the pre-existing person record
                    $other_person->facebook_id = null;
                    $other_person->save();
                    // then update the current person record
                    $person->facebook_id = $inputs['facebook_id'];
                    $person->save();
                    // then update the current user record with the FB access token
                    self::$user->facebook_access_token = $inputs['facebook_access_token'];
                    self::$user->save();
                }
            }
        }
        
        $person->attributes = $inputs;
        $person->save();

        if( isset( $inputs['passwd'] ) ) { 
            self::$user->passwd = md5( $inputs['passwd'] );
            unset( $inputs['passwd'] );
        }
        
        self::$user->attributes = $inputs;
        self::$user->save();
        
        if( isset( $inputs['email_address'] ) && $inputs['email_address'] != '' ) {
                $email= PersonEmails::model()->findByAttributes( array( 'email_address' => $inputs['email_address'] ) );
              
            if( !$email ) {
                $email = new PersonEmails;
                $email->email_address   = $inputs['email_address'];
                $email->email_type      = ( isset( $inputs['email_type'] ) ) ? $inputs['email_type'] : 'home';
                $email->person_id       = $person->id;
                $email->save();

            } else {
                $email->email_address   = $inputs['email_address'];
                $email->email_type      = ( isset( $inputs['email_type'] ) ) ? $inputs['email_type'] : 'home';
                $email->person_id       = $person->id;
                $email->save();
            }

            $person->email_id       = $email->id;
            $person->save();
            $person->refresh();

        }
        if( isset( $inputs['phone'] ) &&  $inputs['phone'] != '') {
            $phone = $person->phone;
            
            if( $phone ) {
                $phone->phone_number    = $inputs['phone'];
                $phone->phone_type      = ( isset( $inputs['phone_type'] ) ) ? $inputs['phone_type'] : 'home';
                $phone->person_id       = $person->id;
                $phone->save();
            } else {
                $phone = new PersonPhones;
                $phone->phone_number    = $inputs['phone'];
                $phone->phone_type      = ( isset( $inputs['phone_type'] ) ) ? $inputs['phone_type'] : 'home';
                $phone->person_id       = $person->id;
                $phone->save();
            }
            
            $person->phone_id       = $phone->id;
            $person->save();
            
            $person->refresh();
        }
        foreach( self::$user->attributes as $key => $value ) {
            if( !in_array($key, array('passwd', 'id', 'person_id')) && !is_array($value) ) {
                $this->setOutput($key, $value);
            }
        }
        foreach( $person->attributes as $key => $value ) {
            if( !in_array($key, array('id', 'email_id', 'phone_id', 'address_id')) && !is_array($value) ) {
                $this->setOutput($key, $value);
            }
        }
        if( count( $person->email ) ) {
            $this->setOutput('email_type',      $person->email->email_type);
            $this->setOutput('email_address',   $person->email->email_address);
        }
        if( count( $person->phone ) ) {
            $this->setOutput('phone_type',      $person->phone->phone_type);
            $this->setOutput('phone_number',    $person->phone->phone_number);
        }
        $this->setOutput('user_id',     self::$user->id);
        
    }

    /**
     * @todo Deletes a user and all associated records
     * @deprecated from version 1.7
     */
    public function actionDelete()
    {
        $id     = $this->getInput('id');
        $user = User::model()->findByPk($id);
        if( !$user->loaded() ) {
            $this->_sendResponse(200, array("code"=>'0','message'=>'Invalid user specified or user does not exist!'));
        }
        $queries    = array(
            'DELETE FROM devices WHERE user_id = %1$s',
            'DELETE FROM message_recipients WHERE message_id IN (SELECT id FROM messages WHERE user_id = %1$s)',
            'DELETE FROM message_recipients WHERE user_id = %1$s',
            'DELETE FROM message_favorites WHERE user_id = %1$s OR message_id IN (SELECT id FROM messages WHERE user_id = %1$s)',
            'DELETE FROM messages WHERE user_id = %1$s',
            'DELETE FROM contacts WHERE user_id = %1$s',
            'DELETE FROM contacts WHERE contact_user_id = %1$s',
            'UPDATE people SET email_id = null WHERE id = %1$s',
            'DELETE FROM person_emails WHERE person_id IN ( SELECT person_id FROM users WHERE id = %1$s)',
            'DELETE FROM person_phones WHERE person_id IN ( SELECT person_id FROM users WHERE id = %1$s)',
            'DELETE FROM person_addresses WHERE person_id IN ( SELECT person_id FROM users WHERE id = %1$s)',
            'DELETE FROM users WHERE id = %1$s',
            'DELETE FROM people WHERE id = ( SELECT person_id FROM users WHERE id = %1$s)',
        );
        foreach( $queries as $query ) {
            Database::instance()->query(Database::DELETE, sprintf($query, $id));
        }
        $this->setOutput('deleted',1);
    }

    public function does_user_exist($fb_access_token=null, $twitter_auth_token=null){
        if ( !is_null($fb_access_token) ) {
            $user = Users::model()->findByAttributes( array( "facebook_access_token" => $fb_access_token ) );
            // we may have a new access_token.  if no match search by facebook UID and update the token
            $facebook_id = $this->getInput('facebook_id');
            if (!$user && isset($facebook_id)) {

                $user = Users::model()->with(array(
                    'people' => array(
                        'join' => 'people',
                        'on' => 'user.person_id=people.id',
                        'condition' => 'facebook_id=' . $this->getInput('facebook_id'),
                    )
                ));

                if ( count( $user ) ) {
                    $user = Users::model()->findByPk( $user->id );
                    $user->facebook_access_token = $fb_access_token;
                    $user->save();
                }
            }
        }
        if (!is_null($twitter_auth_token) ) {
            $user = Users::model()->findByAttributes( array( "twitter_auth_token" => $twitter_auth_token ) );
        }
        return $user->id;
    }

    /**
     * Get a users friends requires a facebook access_token, does
     * nothing if not supplied a token
     *
     * @param $data
     * @return array|bool an array of FB friends or false
     */
    public function get_facebook_friends($data){

        if(isset($data["facebook_access_token"]) && $data["facebook_access_token"] != ''){
            $url = 'https://graph.facebook.com/me/friends?access_token='.$data["facebook_access_token"];

            $request = Request::factory($url)
                ->method('GET');

            $response = $request->execute();

            $friends_json = $response->body();

            if(strlen($friends_json) <= 0) return false;
            $friends = json_decode($friends_json,true);
            if(isset($friends["error"])){
               $this->_sendResponse(200, array("code"=>'0','message'=>'Facebook Error: ' . $friends["error"]["message"]));
            }

            $friend_data = $friends["data"];

            $friend_arr = array();
            $my_friend = array();
            foreach($friend_data as $friend){
               $name = preg_split("/\s+/",$friend["name"]);
               $my_friend["facebook_id"] = $friend["id"];
               $my_friend["first_name"] = isset($name[0]) ? $name[0] : "";
               $my_friend["last_name"] = isset($name[1]) ? $name[1] : "";
               $my_friend["photo"] = "http://graph.facebook.com/".$friend["id"]."/picture";
               $friend_arr[] = $my_friend;

            }
            return $friend_arr;
        }

        return false;

    }
    
    /**
     * Validates and creates a person record or returns false if invalid
     *
     * @param $data
     * @return bool|ORM
     */
    public function create_person($data)
    {
        //check if person already exists via facebook id, if so then update that shiz
        if(isset($data["facebook_id"]) && $data["facebook_id"] != '')
        {
            if ( isset($data['facebook_id']) && $data['facebook_id'] != '') {
                $data['photo'] = 'http://graph.facebook.com/'.$data["facebook_id"].'/picture';
            }elseif( !isset($data['photo']) ) {
                $data['photo'] = '';
            }

            $person = People::model()->findByAttributes( array( 'facebook_id' => $data['facebook_id'] ) );
            if( count( $person ) ) {
                $person->attributes = $data;
                $person->save();
                return $person;
            }
        }
        
        $person = new People;
        $person->attributes = $data;
        $person->save();
        return $person;
    }

    /**
     * Validates and creates a person email record or returns false if invalid
     *
     * @param $data
     * @return bool|ORM
     */
    public function create_person_email($data, $person = null) {

        if ( $data["isUsingSocialNetworking"] )
        {
            $person_email = PersonEmails::model()->findByAttributes(array('email_address'=>$data['email_address'],'person_id'=>$person->id));
            if(!$person_email) { 
                $person_email = new PersonEmails;
            }
            $person_email->attributes = $data;
            $person_email->person_id  = $person->id;
            $person_email->save();
            return $person_email;
        }
        else 
        {
            $address        = $this->getInput('email_address', false);

            $person_email  = PersonEmails::model()->findByAttributes( array( 'email_address' => $address ) );
            if($person_email) {
                    return false;
            } else {
                    $person_email = new PersonEmails;
                    $person_email->attributes = $data;
                    $person_email->save();
                    return $person_email;
            }
        }
    }

    /**
     * Validates and creates a person phone record or returns false if invalid
     *
     * @param $data
     * @return bool|ORM
     */
    public function create_person_phone($data) {
        
        $person_phone = new PersonPhones;
        $person_phone->attributes = $data;
        $person_phone->save();
        return $person_phone;
    }

    /**
     * Validates and creates a user record or returns false if invalid
     *
     * @param $data
     * @return bool|ORM
     */
    public function create_user($data)
    {
        $user = new Users;
        $user->attributes = $data;
        if( array_key_exists('passwd', $data) ) $user->passwd = md5( $data['passwd'] );
        $user->save();
        return $user;
    }

    /**
     * Perform HMVC
     *
     * @param array $data
     * @deprecated from version 1.7
     */
    protected function _login($data)
    {
        $login  = json_decode( $this->actionLogin() );
        foreach( $login as $key=>$val) {
            $this->setOutput($key, $val);
        }
    }
    
    /**
     * 
     * @todo send push notifications  
     * to facebook friends who ara already in our system
     * @since version 1.7
     */
    protected function _inform_my_friends($facebookId, $user)
    {   
        $friends = FacebookFriends::model()->with(array('devices'=>array('select'=>'push_token,device_type',
                                                                   'joinType'=>'JOIN',
                                                                   'condition'=>'devices.push_token IS NOT NULL AND push_token != ""')))
                                           ->findAllByAttributes(array('facebook_id'=>$facebookId));
        
        
        foreach ($friends as $friend) {
            $user->checkAndCreateContact(intval($friend->user_id));
            $alert = "Your facebook friend ".$user->person->first_name." ".$user->person->last_name." is now on Tongue Tango";

            $extra = array(
                        "action"        => 'friend',
                    );

            $badge = Messages::get_unread_message_count($friend->user_id);
            PUSH::send($friend->devices, $alert, $extra, $badge);
            
        } 
        
        return true;
    }
    
    /**
     * 
     * @todo send push notifications  to address book
     * contacts who ara already in our system
     * @since version 1.7
     */
    protected function _inform_address_book_contacts($email, $phoneNumber , $user)
    {   
        $criteria = 'contact_email="'.$email.'" OR contact_phone"'.$phoneNumber.'"';
        if(is_null($phoneNumber)) {
            $criteria = 'contact_email="'.$email.'"';
        }
        $friends = AddressBook::model()->with(array('devices'=>array('select'=>'push_token,device_type',
                                                                   'joinType'=>'JOIN',
                                                                   'condition'=>'devices.push_token IS NOT NULL AND push_token != ""')))
                                           ->findAll($criteria);
        
        
        foreach ($friends as $friend) {
            $user->checkAndCreateContact(intval($friend->user_id));
            $alert = "Your address book contact ".$user->person->first_name." ".$user->person->last_name." is now on Tongue Tango";

            $extra = array(
                        "action"        => 'friend',
                    );
            
            $badge = Messages::get_unread_message_count($friend->user_id);
            PUSH::send($friend->devices, $alert, $extra, $badge);
        } 
        
        return true;
    }
    
}