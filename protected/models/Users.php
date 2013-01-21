<?php

Yii::import('application.models._base.BaseUsers');

class Users extends BaseUsers
{
	public static function model($className=__CLASS__) {
		return parent::model($className);
	}
	
    /*
	 *  @todo This will check to see if a given user already has a contact with a specific
	 *        user.   The specific user should be from facebook
     */
    public function checkAndCreateContact($contact_id)
    {
	    $result = 0;
	    	    
		$contact = Contacts::model()->find("user_id=" . $this->id . " and contact_user_id=" . $contact_id);
		if (!$contact)
		{			
		    $contact = new Contacts;
		    $contact->user_id = $this->id;
	        $contact->contact_user_id = $contact_id;
	        $contact->create_date = date("Y-m-d H:i:s");
	        $contact->update_date = date("Y-m-d H:i:s");
	        $contact->accepted = 1;
	        $contact->save();	   	    
		    $result = 1;
        }
        
		$contact = Contacts::model()->find("user_id=" . $contact_id. " and contact_user_id=" . $this->id);
		if (!$contact)
		{			
		    $contact = new Contacts;
		    $contact->user_id = $contact_id;
	        $contact->contact_user_id = $this->id;
	        $contact->create_date = date("Y-m-d H:i:s");
	        $contact->update_date = date("Y-m-d H:i:s");
	        $contact->accepted = 1;
	        $contact->save();	   	    
    	    $result = 1;
        }
        
        return $result;
	}
    
    
    /**
     * @todo Get a users friends from facebook using facebook graph api
     * @param $data
     * @return array of facebook friends 
     */
    public function get_facebook_friends( $data = null ) {

        if (isset($this->facebook_access_token) && $this->facebook_access_token != '' )
        {        
            $url = 'https://graph.facebook.com/me/friends?access_token=' . $this->facebook_access_token; 

            $response = Yii::app()->CURL->run($url);
            
            $friends_json = $response;

            if(strlen($friends_json) <= 0) return false;
            $friends = json_decode($friends_json,true);

            if(isset($friends["error"])){
                throw new Exception("Facebook Error: " . $friends["error"]["message"]);
            }

            $friend_data = $friends["data"];

            $friend_arr = array();
            $my_friend = array();
            if (is_array($friend_data) ) 
            {                   	              
                foreach($friend_data as $friend)
                {
                   $name = preg_split("/\s+/",$friend["name"]);
                   $my_friend["facebook_id"] = $friend["id"];
                   $my_friend["first_name"] = isset($name[0]) ? $name[0] : "";
                   $my_friend["last_name"] = isset($name[1]) ? $name[1] : "";
                   $my_friend["photo"] = "http://graph.facebook.com/".$friend["id"]."/picture";
                   $friend_arr[] = $my_friend;
                }
            }
            return $friend_arr;
        } 

        return false;

    }

    /**
     * Get a users friends by facebook_id and returns an array
     * of friends ordered by user status (1 = TT user, 0 = not TT user)
     *
     * @param $friends
     * @return array of facebook friends with TT id and user status
     */
    public function get_tongue_tango_friends($friends)
    {
		$results = array();
		$connection = Yii::app()->db;
    	if( is_array($friends) ) 
    	{
	        $fb_ids = array();
	        foreach($friends as $friend){
	            $fb_ids[] = $friend["facebook_id"];
	        }
	        $fb_ids = implode(",",$fb_ids);
			
	        
			if ( $fb_ids != '' ) {
				
		        $sql = "
		            SELECT
		              p.id,
		              p.first_name,
		              p.last_name,
		              p.facebook_id,
		              p.photo,
		              u.id AS user_id
		            FROM people p
		            LEFT JOIN users u
		            ON u.person_id = p.id
		            WHERE (p.facebook_id IN($fb_ids) AND (u.id NOT IN (SELECT blocked_user_id 
                                                                      FROM blocks 
                                                                      WHERE(user_id=".$this->id." AND blocked = 1)) OR u.id IS NULL ))
                    ORDER BY p.first_name, p.last_name
		        ";	
		        
                $results = $connection->createCommand($sql)->queryAll();
			}
    	}
        
        $tt_friends	= array();
        $fb_friends	= array();
        foreach($results as $result)
        {
        	//|
        	//|  This is the list of friends that we have from facebook
        	//|  that also have a matching account on Tango.   
        	$tt_friend					= array();
            $tt_friend["person_id"]		= intval($result["id"]);
            $tt_friend["facebook_id"]	= $result["facebook_id"];
            $tt_friend["first_name"]	= $result["first_name"];
            $tt_friend["last_name"]		= $result["last_name"];
            $tt_friend["photo"]			= $result["photo"];
            $tt_friend["user_id"]		= intval($result["user_id"]);
            $tt_friend["on_tt"]			= ($result["user_id"]>0?1:0);
            $tt_friend["accepted"]		= 0;
			$tt_friend["initiator_id"]	= null;
			        			
            if ($tt_friend["user_id"] > 0) 
            {
            	$tt_friends[$result["user_id"]]	= $tt_friend;
                
				if($this->checkAndCreateContact(intval($result["user_id"]))){
                    /*
                    * @todo send push notification to paired user
                    */
                    $devices = Devices::model()->findAll( array( 'condition' => 
                                                                'push_token!="" AND push_token IS NOT NULL
                                                                AND user_id=' . $result["user_id"] ) );

                    $alert = $this->person->first_name." is now your friend.";

                    $extra = array(
                                "action"        => 'friend',
                            );

                    $badge = Messages::get_unread_message_count($result["user_id"]);
                    PUSH::send($devices, $alert, $extra, $badge);
                }
			} else {
				$fb_friends[] 	= $tt_friend;
			}
        }
        
        $sql = "
        	SELECT
	            p.id,
	            p.first_name,
	            p.last_name,
	            p.facebook_id,
	            p.photo,
	            u.id AS user_id,
        		c.accepted,
        		c.contact_user_id,
        		c.user_id AS initiator_id
        	FROM contacts c
        	LEFT JOIN users u
        		ON IF( c.user_id = ".$this->id.", c.contact_user_id, c.user_id ) = u.id
        	LEFT JOIN people p
        		ON p.id = u.person_id
        	WHERE ((user_id =".$this->id." OR contact_user_id =".$this->id.") 
                AND u.id NOT IN (SELECT blocked_user_id FROM blocks WHERE(user_id=".$this->id." AND blocked = 1)))
        ";
        
        $results = $connection->createCommand($sql)->queryAll();
		foreach($results as $result) {

				$user_id	= intval( $result['contact_user_id'] == $this->id ? $result['initiator_id'] : $result['contact_user_id'] );
				if( !array_key_exists($user_id, $tt_friends) ) {
					$tt_friends[$user_id]	= array(
						"person_id"		=> intval($result["id"]),
						"facebook_id"	=> null,
						"first_name"	=> $result["first_name"],
						"last_name"		=> $result["last_name"],
						"photo"			=> $result["photo"],
						"user_id"		=> intval($user_id),
						"on_tt"			=> 1,
						"accepted"		=> intval($result["accepted"]),
						"initiator_id"	=> intval($result["initiator_id"]),
					);
				} else {
					$tt_friends[$user_id]["accepted"]		= intval($result["accepted"]);
					$tt_friends[$user_id]["initiator_id"]	= intval($result["initiator_id"]);
					// Move pending users to pending array
				}

		}
        
        return array(
        	"tt_friends"=>array_values($tt_friends),
        	"fb_friends"=>array_values($fb_friends),
        );
    }

    /**
     * Bulk inserts friends into the people table if they aren't already there
     *
     * @param $friends
     * @return bool / Database_Query
     */
    public function save_facebook_friends($friends){
        if(!$friends) return false;
        
        $connection = Yii::app()->db;   
        
        $sql = "INSERT INTO `people` (`facebook_id`, `first_name`, `last_name`, `photo`) VALUES "; 
        
        foreach ($friends as $data)
        {
            
            $sql .=  "('" . $data['facebook_id'] . "',
                     '" . addcslashes($data['first_name'],"'") . "',
                     '" . addcslashes($data['last_name'],"'") . "',
                     '" . $data['photo'] . "'),";
        }

        $sql = rtrim($sql, ",");
        $sql .=   "
            ON DUPLICATE KEY update facebook_id=facebook_id;
        ";
        
        $sql .= "INSERT INTO `facebook_friends` (`facebook_id`, `first_name`, `last_name`, `user_id`, `create_date`) VALUES ";
        
        foreach ($friends as $data)
        {
            
            $sql .=  "('" . $data['facebook_id'] . "',
                     '" . addcslashes($data['first_name'],"'") . "',
                     '" . addcslashes($data['last_name'],"'") . "',
                     '" . Controller::$user->id . "',
                     '" . date("Y-m-d H:i:s") . "'),";
        }
        
        $sql = rtrim($sql, ",");
        $sql .=   "
            ON DUPLICATE KEY update facebook_id=facebook_id, user_id=user_id , update_date='".date("Y-m-d H:i:s")."';
        ";
        $res = $connection->createCommand($sql)->execute();
        
        return $res;
    }

    /**
     * Validates and creates a person record or returns false if invalid
     *
     * @param $data
     * @return bool|ORM
     * @deprecated v1.7
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


            $person = ORM::factory("person",array("facebook_id"=>$data["facebook_id"]));
            if($person->loaded()){
                $person->values($data);
                $person->save();
                return $person;
            }
        }
    	
        $person = ORM::factory("person")->values($data);
        $person->save();
        return $person;
    }

    /**
     * Validates and creates a person email record or returns false if invalid
     *
     * @param $data
     * @return bool|ORM
     */
    public function create_person_email($data) {

        if ( $data["isUsingSocialNetworking"] )
        {
            $person_email = ORM::factory("person_email")->values($data);
            $person_email->save();
            return $person_email;
        }
        else 
        {
            $address        = $this->getInput('email_address', false);

            $person  = Model_Person::find_by_email($address);
			
			if($person) {
					return;
			}
			else {
					$person_email = ORM::factory("person_email")->values($data);
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
    	
        $person_phone = ORM::factory("person_phone")->values($data);
        $person_phone->save();
        return $person_phone;
    }

    public static function find_by_emails($addresses)
    {
        $email_where    = implode('","',$addresses);
        $sql = sprintf('
            SELECT
                p.id AS person_id,
                u.id AS user_id
            FROM person_emails pe
            LEFT JOIN people p
                ON p.id = pe.person_id
            LEFT JOIN users u
                ON u.person_id = p.id
            WHERE pe.email_address IN ("%1$s")
            ORDER BY u.id DESC
            LIMIT 1',
            $email_where
        );
        
        $result = Yii::app()->db->createCommand($sql)->queryRow();
    
        if( !$result) {
            return false;
        } else {
            return $result;
        }
    }
    
    /**
     * @param   $phones, $emails array
     * @return  array  user info
     * @since v1.7
     */
    public static function find_by_emails_phones($addresses, $numbers)
    {
        if(empty($addresses) && empty($numbers)) return false;
       
        
        $sqlEmails = "";
        if(!empty($addresses)) {
            $sqlEmails = sprintf('
                SELECT
                    p.id AS person_id,
                    u.id AS user_id
                FROM person_emails pe
                LEFT JOIN people p
                    ON p.id = pe.person_id
                LEFT JOIN users u
                    ON u.person_id = p.id
                WHERE pe.email_address IN ("%1$s") 
                ',
                $addresses
            );
        }
        
        $union = "";
        
        if(!empty($addresses) && !empty($numbers))
            $union = " UNION ";
        
        $sqlPhones = "";
        if(!empty($numbers)) {
            $sqlPhones = sprintf('
                    SELECT
                        p.id AS person_id,
                        u.id AS user_id
                    FROM person_phones pp
                    LEFT JOIN people p
                        ON p.id = pp.person_id
                    LEFT JOIN users u
                        ON u.person_id = p.id
                    WHERE pp.phone_number IN ("%1$s") 
                    ORDER BY user_id DESC
                    ',
                    $numbers
                );
        }
        
        $sql = sprintf(' %1$s  %2$s  %3$s', $sqlEmails, $union, $sqlPhones);
        
        
        $result = Yii::app()->db->createCommand($sql)->queryAll();
        
        if(!$result) {
            return false;
        } else {
            return $result;
        }
    }
    
    /**
     * @param   $username  string  username
     * @return  array  user info
     */
    public static function find_by_username($username)
    {
        $user = Users::model()->find( array( 'condition' => 'username="' . $username . '"' ) );
        
        if( count( $user ) ) {
            return true;
        }
        else {
            return false;
        }
    }
    
    /**
     * @param   $numbers  array  user's phone numbers
     * @return  array  user info
     */
    public static function find_by_phones($numbers)
    {
        $clean_numbers = array();
        foreach($numbers as $number) {
            $clean_numbers[] = Users::strip_format($number);
        }
        $phone_where    = implode('","',$clean_numbers);
        $sql = sprintf('
            SELECT
                p.id AS person_id,
                u.id AS user_id
            FROM person_phones pp
            LEFT JOIN people p
                ON p.id = pp.person_id
            LEFT JOIN users u
                ON u.person_id = p.id
            WHERE pp.phone_number IN ("%1$s")
            ORDER BY u.id DESC
            LIMIT 1',
            $phone_where
        );

        $result = Yii::app()->db->createCommand($sql)->queryRow();
        if(!$result) {
            return false;
        } else {
            return $result;
        }
    }

    /**
     * Strips phone number formatting from provided values.
     *
     * @param   $value  string  Numeric string to strip formatting from
     * @return  string  Unformatted string
     */
    public static function strip_format($value)
    {
        return preg_replace('/[^\d]/', '', $value);
    }	
    
    /**
     * @todo check if user exists
     * @param string fb access token , $tw auth token
     * @return int user id|null
     * @deprecated v1.7 
     */
    public function does_user_exist($fb_access_token = null, $twitter_auth_token=null){
    	if ( !is_null($fb_access_token) ) {
        	$user = ORM::factory("user")->where("facebook_access_token",'=',$fb_access_token)->find();
			// we may have a new access_token.  if no match search by facebook UID and update the token
			$facebook_id = $this->getInput('facebook_id');
			if (!$user->loaded() && isset($facebook_id)) {
				$user = ORM::factory("user")
						->join('people')->on('user.person_id','=','people.id')
						->where("facebook_id",'=',$this->getInput('facebook_id'))
						->find();
				if ($user->loaded()) {
					$user = ORM::factory('user', $user->id);
					$user->facebook_access_token = $fb_access_token;
					$user->save();
				}
			}
		}
		if (!is_null($twitter_auth_token) ) {
			$user = ORM::factory("user")->where("twitter_auth_token",'=',$twitter_auth_token)->find();
		}
        return $user->id;
    }
    
    public function beforeSave()
    {
        if(parent::beforeSave())
        {
            if($this->isNewRecord)
            {
                $this->create_date  = date("Y-m-d H:i:s");
                $this->update_date = date("Y-m-d H:i:s");
            }
            else {
                $this->update_date = date("Y-m-d H:i:s");
            }
                
            return true;
        }
        else
            return false;
    }
    
}