<?php
Yii::import('ext.logger.CPSLiveLogRoute');
class ContactController extends Controller
{
    /**
     * @return json contacts
     */
    public function actionIndex() 
    { 
        $friends = self::$user->get_facebook_friends();
        self::$user->save_facebook_friends($friends);
        $friends = self::$user->get_tongue_tango_friends($friends);
        $this->setOutput('fb_friends',      $friends['fb_friends']);
        $this->setOutput('tt_friends',      $friends['tt_friends']);
    }

    /**
     * @todo create  user contact
     */
    public function actionCreate() {
        $inputs = $this->getInputs();
        $contact_user = $this->get_contact_user($inputs);

        if( !isset( $contact_user ) ) {
            $this->_sendResponse(200, array("code"=>'0','message'=>'Person not found.'));
        }
        
        if(!$contact_user->id){ //not a TT user
           $this->invite_contact($inputs);
           $this->setOutput("status","invited");
           $this->_sendResponse();
           return;
        }
        
        if(self::$user->id == $contact_user->id){
            $this->_sendResponse(200, array("code"=>'0','message'=>"You, adding you, who's adding you. You can't add yourself sillygoose."));
        }

        $inputs = array_merge( array(
            "user_id"=>self::$user->id,
            "contact_user_id"=>$contact_user->id
        ), $inputs);
        
        $this->already_contacts($inputs);
        
        $contact = Contacts::model()->findByAttributes( array( "user_id" => $contact_user->id, "contact_user_id" => self::$user->id ) );
        
        if(isset($contact->id)){ //if they requested you first
            
            $data = array(
                "user_contact_id"=>self::$user->id,
                "contact_id"=>$contact->id,
                "user_id"=>$contact_user->id,
                "accepted"=>1
            );

            $this->confirm_contact($data); //confirm you for them
            $this->confirm_contact($inputs); //confirm them for you
            $this->setOutput("status","confirmed");
            $this->_sendResponse();
        }
        else { //if they haven't requested you then request them
            $this->request_contact($inputs);
            $this->setOutput("status","requested");
            $this->_sendResponse();
        }
    }
    
    /**
     * @todo block  user contact | returned blocked contatcs list
     * @param int contact id
     */
    public function actionBlock( $id = 0 ) {
        if( $id != 0 ) {
            $block = Blocks::model()->find('user_id=:user_id AND blocked_user_id=:blocked_user_id', array( ':user_id' => self::$user->id, ':blocked_user_id' => intval( $id ) ) );

            if( $block && $block->create_date != NULL && $block->blocked == 1 ) {
                $this->_sendResponse(200, array("code"=>'0','message'=>"Already blocked"));
            }

			if ($block && $block->update_date != NULL && $block->blocked == 0) {
				$block->blocked = 1;
				$block->update_date = date('Y-m-d H:i:s');
			} else {
				$block = new Blocks;
				$block->user_id = self::$user->id;
				$block->blocked_user_id = $id;
				$block->blocked = 1;
			}
            if( $block->save() ) {
                $this->setOutput("status", "blocked");
            } else {
                $this->_sendResponse(200, array("code"=>'0','message'=>"Database error, please try it later"));
            }
        } else {
            $block_list = Blocks::model()->findAllByAttributes( array( 'user_id' => self::$user->id, 'blocked' => 1 ) );

            $full_block_list = array();
            foreach ($block_list as$key=> $item) {                
                $full_block_list[$key] = $item->attributes;
                $full_block_list[$key]['firstname'] = $item->contactUser->person->first_name;
                $full_block_list[$key]['lastname']  = $item->contactUser->person->last_name;
                $full_block_list[$key]['photo'] = $item->contactUser->person->photo;
            } 

			$block_group_list = GroupBlocks::model()->findAllByAttributes(array('user_id' => self::$user->id, 'blocked' => 1));
			
			$full_group_block_list = array();
			foreach($block_group_list as $key => $item) {
				$full_group_block_list[$key] = $item->attributes;
                $full_group_block_list[$key]['name'] = $item->group->name;
                $full_group_block_list[$key]['photo'] = $item->group->photo;
			}
            
			$this->setOutput('block_list', $full_block_list);
            $this->setOutput('block_group_list', $full_group_block_list);
        }
    }

    /**
     * @todo unblock  user contact
     * @param int contact id
     */
    public function actionUnblock( $id = 0 ) {
        if( $id != 0 ) {
            $block = Blocks::model()->find('user_id=:user_id AND blocked_user_id=:blocked_user_id', array( ':user_id' => self::$user->id, ':blocked_user_id' => intval( $id ) ) );

            if( !$block ) {
                $this->_sendResponse(200, array("code"=>'0','message'=>"This user not blocked for you"));
            }

            if( $block->blocked == 0 ) {
                $this->_sendResponse(200, array("code"=>'0','message'=>"Already unblocked"));
            }

            $block->blocked = 0;
            if( $block->save() ) {
                $this->setOutput("status", "unblocked");
            } else {
                $this->_sendResponse(200, array("code"=>'0','message'=>"Database error, please try it later"));
            }
        }
    }
    
    /**
     * @todo delete  user contact
     * @param int contact id
     */
    public function actionDelete( $id = 0 ) {
        if( $id != 0 ) {
            $friendship = Contacts::model()->find('user_id=:user_id AND contact_user_id=:contact_user_id', array( ':user_id' => self::$user->id, ':contact_user_id' => intval( $id ) ) );

            if( !$friendship ) {
                $this->_sendResponse(200, array("code"=>'0','message'=>"Friendship not found"));
            }

            if( $friendship->delete_date != NULL ) {
                $this->_sendResponse(200, array("code"=>'0','message'=>"Already removed from friends"));
            }

            $friendship->delete_date = date('Y-m-d H:i:s');

            if( $friendship->save() ) {
                $this->setOutput("status", "deleted");
            } else {
                $this->_sendResponse(200, array("code"=>'0','message'=>"Database error, please try it later"));
            }            

        } else {
            //$this->_sendResponse(200, array("code"=>'0','message'=>"Please select a user to delete."));
            $deleted_friendships = Contacts::model()->findAll('user_id=:user_id AND delete_date!="" AND delete_date IS NOT NULL AND accepted=1 ', array( ':user_id' => self::$user->id ) );

            if( !$deleted_friendships ) {
                $this->_sendResponse(200, array("code"=>'0','message'=>"Deleted friends not found"));
            }

            $full_deleted_list = array();
            foreach ($deleted_friendships as$key=> $item) {
                $full_deleted_list[$key] = $item->attributes;
                $full_deleted_list[$key]['username'] = $item->user->username;
            }

            $this->setOutput('deleted_friend_list', $full_deleted_list);
            $this->_sendResponse();
        }
    }
    
    /**
     * @todo undelete  user contact
     * @param int contact id
     */
    public function actionUndelete( $id = 0 ) {
        if( $id != 0 ) {
            $contact = Contacts::model()->find('user_id=:user_id AND contact_user_id=:contact_user_id AND delete_date IS NOT NULL', 
                                               array( ':user_id' => self::$user->id, ':contact_user_id' => intval( $id ) ) );

            if( !$contact ) {
                $this->setOutput("status", "failure");
                $this->_sendResponse();
            }

            $contact->delete_date = null;
            if( $contact->save() ) {
                $this->setOutput("status", "success");
                $this->_sendResponse();
            } else {
                $this->_sendResponse(200, array("code"=>'0','message'=>"Database error, please try it later"));
            }
        } else {
            $this->_sendResponse(200, array("code"=>'0','message'=>"Please set variable ID"));
        }
    }
    
    /**
     * @todo search user contact and create auto friendship
     * @param array address book contacts
     */
    public function actionSearch() {
        ini_set('max_execution_time', 0);
        $contacts   = $this->getInputs();
        
        $all  = count($contacts);
        $result   = $this->_find_contacts($contacts);
        
        $this->saveAddressBookContacts($contacts);
        $this->_sendResponse(200, array("code"=>'0','message'=>$result." of your ".$all." contacts have been paired"));
        
    }
    
    /**
     * @todo return user contacts
     * @param array user data
     */
    public function get_contact_user( $data ) {
        if( isset( $data["person_id"] ) ) {
            return Users::model()->findByAttributes( array( "person_id" => $data["person_id"] ) );
        } else if( isset( $data["facebook_id"] ) ) {
            $contact_person = People::model()->findByAttributes( array( "facebook_id" => $data["facebook_id"] ) );
            return Users::model()->findByAttributes( array( "person_id" => $contact_person->id ) );
        } else {
            $this->_sendResponse(200, array("code"=>'0','message'=>"Please select a person to add or invite."));
        }
    }
    
    /**
     * @todo check if user is already in contacts list
     * @param array user data
     */
    public function already_contacts( $data ) {
        $contact = Contacts::model()->findByAttributes( array( "user_id" => $data["user_id"], "contact_user_id" => $data["contact_user_id"] ) );
        if( isset($contact->id) && !is_null($contact->id) ) {
            if( $contact->accepted == 1 ) {
                $this->_sendResponse(200, array("code"=>'0','message'=>'You are already contacts with this person.'));
            } else if( $contact->accepted == 0 ) {
                $this->_sendResponse(200, array("code"=>'0','message'=>"You've already requested to add this person."));
            }
        }
        
        return false;
    }
    
    /**
     * @todo accept contact invitation
     * @param array user data
     */
    public function confirm_contact( $data ){
        if( isset( $data["contact_id"] ) ) {
            $contact = Contacts::model()->findByPk( $data["contact_id"] );
            $contact->accepted = 1;
            $contact->save();

            
            // get the requestee info
            $requestee = Users::model()->findByPk( $contact->contact_user_id )->person;
            
            // send a push notification
            $requestor  = $contact->user;
            
            $devices = Devices::model()->findAll( array( 'condition' => 'push_token!="" AND push_token IS NOT NULL AND user_id=' . $requestor->id ) );
            $message = 'You are now connected with '.$requestee->first_name.' '.$requestee->last_name;
            $extra = array(
                        "action"    => "requestAccepted",
                        "user_id"   => ''.$contact->contact_user_id,
                        "message"   => $requestee->first_name.' '.$requestee->last_name.' accepted your invite!'
                    );
            
            $badge = Messages::get_unread_message_count($requestor->id);
            PUSH::send($devices, $alert, $extra, $badge);
            
            return $contact;
        }

        $contact = new Contacts;
        $contact->attributes = $data;
        $contact->accepted = 1;
        $contact->save();

        return $contact;
    }
    
    /**
     * @todo send contact request
     * @param array user data
     */
    public function request_contact( $data ) {
        $contact = new Contacts;
        $contact->attributes = $data;
        $contact->accepted = 0;
        $contact->save();

        $user   = $contact->contactUser;
        $requestor  = $contact->user->person;
        $the_photo = $requestor->photo; // Bitly::instance()->shorten($requestor->photo); 

        $devices = Devices::model()->findAll( array( 'condition' => 'push_token!="" AND push_token IS NOT NULL AND user_id=' . $user->id ) );
        $message = $requestor->first_name.' '.$requestor->last_name.' has requested to add you on Tongue Tango!';
        $extra = array(
                    "action"    => "request",
                    "user_id"   => ''.$contact->user_id,
                    "message"   => $requestor->first_name.' '.$requestor->last_name.' added you.',
                    "photo"     => $the_photo
                );
        
        $badge = Messages::get_unread_message_count($user->id);
        PUSH::send($devices, $alert, $extra, $badge);
        
        return $contact;
    }

    
    /**
     * @todo make this actually send something need to know what we're doing with FB people
     * @param array user data
     */
    public function invite_contact( $data ) {

        if( isset( $data["person_id"] ) )
            $person = People::model()->findByPk( $data["person_id"] );
        else if( isset( $data["facebook_id"] ) )
            $person = People::model()->findByPk( array( "facebook_id" => $data["facebook_id"] ) );
        else if( isset( $data["emails"] ) ) {
            
        }
        if( !$person->id ) 
            $this->_sendResponse(200, array("code"=>'0','message'=>"That person does not exist."));

        
        return;
        $this->request_contact( $data );
    }
    
    /**
     * @todo send invitation
     * @param array user data
     * @deprecated v1.7
     */
    public function send_invite($data){
        $this->_sendResponse(200, array("code"=>'0','message'=>"This functionality is not yet implemented."));
    }

    /**
     * Search by phone number and email address for
     * existing people and users.
     * @param array $data
     * @throws Exception
     * @return array
     */
    protected function _find_contacts( $data )
    {   
        if( !is_array($data) ) {
            $this->_sendResponse(200, array("code"=>'0','data'=>$data,'message'=>"This functionality is not yet implemented."));
        }
        
        $friends    = array();
        $tt_friends = array();
        $emailsString = '';
        $phonesString = '';
        foreach( $data as $contact ) {
            
            if(isset($contact['e']) && !empty ($contact['e'])) {
                $emailsString .= implode('","', $contact['e']) . '","';
            }
            
             $clean_numbers = array();
             foreach($contact['p'] as $number) {
                $clean_numbers[] = Users::strip_format($number);
             }
             $phonesString .= implode('","', $clean_numbers)  . '","';
        }    
        
        $emailsString = rtrim($emailsString, "\"\",");
        $phonesString = rtrim($phonesString, "\"\",");
        
        $result         = array();
        
        $pairedCount = 0;
        $results = Users::find_by_emails_phones( $emailsString , $phonesString);
        
        if($results) 
        foreach($results as $value) {

            if( isset( $value['person_id'] ) ) {
                $result['person_id']    = intval( $value['person_id'] );
            }
            if( isset( $value['user_id'] ) ) {
                $result['user_id']      = intval( $value['user_id'] );
            }

            if( !is_null( $result['user_id'] ) ) 
            {
                if(self::$user->checkAndCreateContact($result["user_id"])) {
                    /*
                     * @todo send push notification to paired user
                     */
                    $devices = Devices::model()->findAll( array('condition' => 
                                                            'push_token!="" AND push_token IS NOT NULL
                                                            AND user_id=' . $result["user_id"] ) );
                
                    $alert = self::$user->person->first_name." is now your friend.";

                    $extra = array(
                                "action"        => 'friend',
                            );

                    $badge = Messages::get_unread_message_count($result["user_id"]);
                    PUSH::send($devices, $alert, $extra, $badge);
                    $pairedCount ++;
                }
                $tt_friends[]   = $result;                
            } else {
                $friends[]      = $result;
            }
        }
        
        return $pairedCount;
    }
    
    /**
     * save logged in user address book data
     * @param array $data
     * @throws Exception
     * @return status
     * @since v1.7
     */
    protected function saveAddressBookContacts($data)
    {
        
        if(!$data || empty($data)) return false;
        
        $insertArray = array();
        $ind = 0;
        foreach($data as $value) {
            foreach($value['e'] as $key2 =>$email) {
                $insertArray[$ind]['user_id'] = self::$user->id;  
                $insertArray[$ind]['email'] =   $email;
                $insertArray[$ind]['phone'] =   isset($value['p'][$key2]) ? $value['p'][$key2] : null;
                $ind ++;
            }
        }
        
        $connection = Yii::app()->db;   
        
        $sql = "INSERT INTO `address_book` (`user_id`, `contact_email`, `contact_phone`,  `create_date`) VALUES ";
        if(!$insertArray OR empty($insertArray)) return;
        foreach ($insertArray as $data)
        {
            
            $sql .=  "('" . $data['user_id'] . "',
                     '" . addcslashes($data['email'],"'") . "',
                     '" . addcslashes(Users::strip_format($data['phone']),"'") . "',
                     '" . date("Y-m-d H:i:s") . "'),";
        }
        
        $sql = rtrim($sql, ",");
        $sql .=   "
            ON DUPLICATE KEY update user_id=user_id, contact_email=contact_email , update_date='".date("Y-m-d H:i:s")."';
        ";
        
        $res = $connection->createCommand($sql)->execute();
        
        return $res;
    }
}