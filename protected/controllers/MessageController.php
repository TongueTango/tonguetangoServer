<?php

Yii::import('ext.logger.CPSLiveLogRoute');

class MessageController extends Controller
{
    /**
	 * @todo Mark message as fav
     * @return string success|failure info
     * 
	 */
    public function actionIndex()
    {
        $data = $this->getInputs();
        
        $id = intval($_REQUEST['id']);   
		if( is_null($id) ) {
			$this->_sendResponse(400, array("code"=>0, 'message'=>"Invalid message specified"));
		}
        
		$message = Messages::model()->findByPk($id);
        if(!$message) {
            $this->_sendResponse(400, array("code"=>0, 'message'=>"Invalid message specified"));
        }
        
        if($data['favorite'] == 1) {
            $fav = MessageFavorites::model()->findByAttributes(array('message_id'=>$id,'user_id'=>self::$user->id)); 
            if($fav) {
                $this->_sendResponse(200, array("code"=>0, 'message'=>"This message is already in your favorite list"));
            }
            $fav = new MessageFavorites;
            $fav->message_id = $id;
            $fav->user_id    = self::$user->id;
            $fav->create_date = date("Y:m:d H:i:s");
            $fav->update_date = date("Y:m:d H:i:s");
            if($fav->save()) {
                $this->setOutput('message', 'success');
            } else {
                $this->_sendResponse(200, array("code"=>0, 'message'=>"failure"));
            }
        } elseif($data['favorite'] == 0) {
            $fav = MessageFavorites::model()->findByAttributes(array('message_id'=>$id,'user_id'=>self::$user->id)); 
            if(!$fav) {
                $this->_sendResponse(200, array("code"=>0, 'message'=>"This message is not in your favorite list"));
            }
            if($fav->delete()) {
                $this->setOutput('message', 'success');
            } else {
                $this->_sendResponse(200, array("code"=>0, 'message'=>"failure"));
            }
        }
        
    }
    
    /*
     * @todo get all favotite messages
     * @return array
     */
	public function actionFavorites()
	{
        $tz = new DateTimeZone('Europe/London');
		$messages	= array();
		foreach( self::$user->favmessages as $message ) {
            $data = $message->attributes;
            $data['first_name'] = $message->user->person->first_name;
            $data['last_name']  = $message->user->person->last_name;
			$messages[$message->id]	= $data;
            $date = new DateTime($data['create_date'].' EST');
            $date->setTimeZone($tz);
			$messages[$message->id]['create_date']	= $date->format('Y-m-d H:i:s');
            
		}
		$this->setOutput('messages', array_values($messages));
	}
    
    /**
	 * @todo create message
     * @return json message information
     * 
	 */
    public function actionCreate() 
    {
        if(isset($_REQUEST['id'])) {
            $id = $_REQUEST['id'];
        } else {
            $id = 'regular';
        }
        
        if( $id == 'facebook' ) 
        {   
            ini_set('max_execution_time', 0);
            $file   = $this->getInput('file');
            
            $fileName = md5(microtime(true)).$file['name'];
            $uploadPath = Yii::app()->basePath.'/cache/';
            
            $audio  = Converter::convert_to_audio_mp3($file['tmp_name']);
            
            if($audio == 'empty') {
                $this->_sendResponse(400, array("code"=>0, 'message'=>"File is empty!"));
            }
            
            if( !$audio || empty($audio) ) {
                $this->_sendResponse(400, array("code"=>0, 'message'=>"Unable to encode !"));
            }
            
            $cloudFile = explode('.', $fileName);
            $bucketName = 'tonguetangofiles';
            
            if( Yii::app()->s3->upload($audio ,$cloudFile[0].".mp3", $bucketName)) {
                    $public_uri = "http://".$bucketName.".s3.amazonaws.com/".$cloudFile[0].".mp3";
            
            } else {
                $this->_sendResponse(400, array("code"=>0, 'message'=>"Failed to host file!"));
            }
            
            $message = new SocialMessages;
            $message->user_id = self::$user->id;
            $message->message_type = 'facebook';
            $message->message_body    = $public_uri;
            $message->save(false);
            
            
            $public_uri = json_decode(Yii::app()->bitly->shorten('http://apiv2.tonguetango.com/player?uri='.$public_uri)
                                                       ->getResponseData(), true);
            $public_uri = $public_uri['data']['url'];
            
            $this->setOutput('public_url', $public_uri);
            
        } elseif( $id == 'twitter' ) {
            
            $file       = $this->getInput('file');
            $fileName   = md5(microtime(true)).$file['name'];
            $uploadPath = Yii::app()->basePath.'/cache/';
            
            $audio  = Converter::convert_to_audio_mp3($file['tmp_name']);
            
            if( !$audio || empty($audio) ) {
                $this->_sendResponse(400, array("code"=>0, 'message'=>"Unable to encode audio!"));
            }
            
            $cloudFile = explode('.',$fileName);
            $bucketName = 'tonguetangofiles';
            
            
            if( Yii::app()->s3->upload($audio ,$cloudFile[0].".mp3", $bucketName ) ) {
                    $public_uri = "http://".$bucketName.".s3.amazonaws.com/".$cloudFile[0].".mp3";
            
            } else {
                $this->_sendResponse(400, array("code"=>0, 'message'=>"Failed to host file!"));
            }
            
            $message = new SocialMessages;
            $message->user_id = self::$user->id;
            $message->message_type = 'twitter';
            $message->message_body    = $public_uri;
            $message->save(false);
            
            $public_uri = json_decode(Yii::app()->bitly->shorten('http://apiv2.tonguetango.com/player?uri='.$public_uri)
                                                       ->getResponseData(), true);
            $public_uri = $public_uri['data']['url'];
            
            $this->setOutput('public_url', $public_uri);
            
            
        } else {
            
            $data       = $this->getInputs();
            $message    = $this->_create_message(array_merge($data,array(
                                                                'user_id'   => self::$user->id,
                                                            )));
            
            $this->setOutput('message', $message->attributes);
        }
    }
    
    public function actionDeleteAll()
    {
        $inputs = $this->getInputs();
        $messages = $inputs['messages'];
        if(empty($messages)) {
            $this->_sendResponse(400, array("code"=>0, 'message'=>"No messages defined!"));
        }
        
        $ids = implode(",",$messages);
        $model = Messages::model();
        $result = $model->updateAll(array('delete_date'=>date("Y-m-d H:i:s")), 'id IN ('.$ids.')');
        if($result) {
            $this->setOutput('message', 'success');
        } else {
            $this->setOutput('message', 'failure');
        }
    }
    
    public function actionDeleteThread()
    {
        $inputs = $this->getInputs();
        
        $userId   = explode("-",$inputs['thread_id']);
        $userId   = $userId[0];
        $groupId  = intval($inputs['group_id']);
        $friendId = intval($inputs['friend_id']);
        $connection = Yii::app()->db;
        if($groupId != 0) {
            
            $sql = "UPDATE messages m INNER JOIN message_recipients mr
                        ON mr.message_id=m.id
                        AND mr.user_id = ".$userId." SET m.delete_date = '".date("Y-m-d H:i:s")."'
                    WHERE  mr.group_id IN (SELECT DISTINCT group_id FROM group_members WHERE user_id = ".$userId.")
                        AND m.delete_date IS NULL";
            
            $command = $connection->createCommand($sql);
            $res = $command->execute();
            
        } elseif($friendId != 0) {
     
            $sql = "UPDATE messages m INNER JOIN message_recipients mr
                        ON mr.message_id=m.id
                        AND mr.group_id IS NULL SET m.delete_date = '".date("Y-m-d H:i:s")."'
                    WHERE (mr.user_id = ".$friendId." AND m.user_id = ".$userId.") OR (m.user_id = ".$friendId." AND mr.user_id = ".$userId.")
                        AND m.delete_date IS NULL";
            
            
            $command = $connection->createCommand($sql);
            $res = $command->execute();
        }
        
        
        if($res) {
            $this->setOutput('message', 'success');
        } else {
            $this->setOutput('message', 'No massage to mark as deleted');
        }
    }
    
    /**
	 * 
	 * @todo Update message
     * @return json message info 
     * @param int message id, string message type
	 */
    public function actionUpdate() {
        
        if(!isset($_REQUEST['type']))$this->_sendResponse(400, array("code"=>0, 'message'=>"type is not specified"));
            
        $type = $_REQUEST['type'];
        $id   = $_REQUEST['id'];
        $connection = Yii::app()->db;
        
        $read =  $this->getInput('read');
        if(is_null($read)) {
            $read = 1;
        }
        
        if($type == 'group') {
            $group = Groups::model()->findByPk($id);
            if(!$group) {
                $this->_sendResponse(400, array("code"=>0, 'message'=>"Invalid group specified"));
            }
            $sql = "update message_recipients SET `read`=".$read." WHERE delete_date IS NULL AND group_id =".$id."";
            
            $result = $connection->createCommand($sql)->execute();
            if($result) 
                $this->setOutput('message', 'success');
            else 
                $this->setOutput('message', 'No unread messages for this group!');
            
            
        } elseif($type=='message') {
            
            
        } elseif($type=='friend') {
            $target_id	= $id;
			$status		= $read;
			$result		= Messages::mark_conversation($target_id, $status );
            
            if($result)
                $this->setOutput('message', 'success');
            else
                $this->setOutput('message', 'No messages to mark as read');
        }
        
    }
    
    /**
	 * 
	 * @todo Marks a message as deleted.
     * @return int status 
	 */
	public function actionDelete()
	{
        if(!isset($_REQUEST['id'])) {
            $this->_sendResponse(400, array("code"=>'0','message'=>"Message id is not specified"));
        }
		$id			= $_REQUEST['id'];
		$message	= Messages::model()->findByPk($id);
		if( !$message) {
            $this->_sendResponse(400, array("code"=>'0','message'=>"Invalid message specified!"));
		}
		$message->delete_date	= date('Y-m-d H:i:s');
		$message->save();
		$this->setOutput('deleted', 1);
	}
    
    /**
	 * @todo Return User messages thread
     * 
	 */
    public function actionUser( $user_id = 0, $since = 0 ) 
    {   
        $offset  = $_REQUEST['skip'];
        $limit   = $_REQUEST['limit'];
        $user_id = $_REQUEST['id'];
        $messages   = Messages::get_conversation($user_id , null, null, $limit, $offset);
        $this->setOutput( 'messages', array_values( $messages ) );
        
    }
    
     /**
	 * @todo Return User conversations, groups, friends
	 */
    public function actionConversations( $since = 0 ) 
    {
        $date       = $since;
        $threads    = $this->_get_conversations($date);
        
        
        $membership = GroupMembers::model()->with(array('group'=>array('select'=>'group.id,group.name,group.photo',
                                            'condition'=>'group.delete_date IS NULL','group'=>'group.id'
                                        )))->findAllByAttributes( array('user_id' => self::$user->id, 'accepted'=> 0,'removed'=>0) );
        
        $groups = array();
        foreach($membership as$key=> $mem) {
            $groups[$key]['id'] = $mem->group->id; 
            $groups[$key]['name'] = $mem->group->name; 
            $groups[$key]['photo'] = $mem->group->photo; 
            
        }   
        $friends = self::$user->get_facebook_friends();
        $friends = self::$user->get_tongue_tango_friends($friends);
        
        $this->setOutput('threads', array_values($threads));
        $this->setOutput('group_invitations', array_values($groups));
    }
    
    /**
	 * @todo Return group messages thread
     * 
	 */
    public function actionGroup($id)
    {   
        $offset  = $_REQUEST['skip'];
        $limit   = $_REQUEST['limit'];
        $messages = array();

        $sql = "select DISTINCT messages.* from messages 
                WHERE messages.id IN (SELECT  message_id FROM message_recipients WHERE group_id=".$id." ) AND
                messages.delete_date IS NULL ORDER BY messages.create_date DESC  LIMIT  ".$limit." OFFSET ". $offset;


        $arrMessages = Messages::model()->findAllBySql($sql);

        foreach($arrMessages as $message) {
                
                $messages[$message->id]	= $message->attributes;
        }

        $this->setOutput('messages', array_values($messages));

    }
    
    /**
	 * @todo save message
     * @param array message data
     * 
	 */
    public function _create_message( $data ) 
    {   
        $recipients = $data['recipients'];
        
        if( count($recipients) < 1 ) {
            $this->_sendResponse(400, array("code"=>'0','message'=>'No recipients specified!'));
        }
        unset($data['recipients']);
        
        $message    = new Messages;
        $message->attributes = $data;
        
        if(isset($data['create_date']) && $data['create_date'] != '') {
            
            $message->create_date = trim($data['create_date']);
            $message->update_date = trim($data['create_date']);
        }
        
        $message->save();
        
        
        foreach( $recipients as $recipient ) {
            // individual recipient
            if( isset($recipient['user_id']) ) {
                $target = new MessageRecipients;
                $target->attributes = $recipient;
                $target->message_id = $message->id;
                $target->save();

                $user = Users::model()->findByPk( $recipient['user_id'] );
                
                $devices = Devices::model()->findAll( array( 'condition' => 'push_token!="" 
                                                             AND push_token IS NOT NULL AND user_id=' . $user->id ) );
                if($message->message_header == "Audio Message") {
                    $alert = "You've got a new audio message";
                } else {
                    $alert = $message->message_body;
                }
                $extra = array(
                            "action"        => 'message',
                            "message_id"    => $message->id,
                            "user_id"       => self::$user->id,
                            "group_id"      => 0
                        );
                
                // Add self as recipient for easier organization
                $target = new MessageRecipients;
                
                $target->attributes = array(
                        'user_id'       => self::$user->id,
                        'message_id'    => $message->id,
                        'read'          => 1,
                );
                
                $target->save();
                
                $badge = Messages::get_unread_message_count($recipient['user_id']);
                
                PUSH::send($devices, $alert, $extra, $badge);
                
                
            }
            // handle push notifications to group_members if necessary
            if( isset($recipient['group_id']) ) {
                // check if the group has been deleted
                $group = Groups::model()->findByPk( $recipient['group_id'] );
                
                if ( $group && $group->delete_date != null) {
                    throw new Exception('You cannot post to a deleted group!', 400);
                }else{
                    
                     $members = GroupMembers::model()->findAll(array( 'condition' => 'group_id = '.$recipient['group_id'].' 
                                                                                      AND accepted=1 AND removed = 0 
                                                                                      AND user_id NOT IN (SELECT user_id FROM group_blocks 
                                                                                                          WHERE group_id='.$recipient['group_id'].' AND blocked=1)' ) );
                    
                    foreach ( $members as $member ) {
                        $target = new MessageRecipients;
                        $target->attributes = array_merge($recipient,array(
                                'user_id'       => $member->user_id,
                                'message_id'    => $message->id,
                                'read'          => ($member->user_id == self::$user->id ? 1 : 0),
                        ));

                        $target->save();

                        if ( $member->user_id != self::$user->id ) {
                            $user = Users::model()->findByPk( $member->user_id );
                            
                            $devices = Devices::model()->findAll( array( 'condition' => 'push_token!="" AND push_token IS NOT NULL AND user_id=' . $user->id ) );
                            if($message->message_header == "Audio Message") {
                                $alert = "You've got a new audio message";
                            } else {
                                $alert = $message->message_body;
                            }
                            
                            $extra = array(
                                        "action"        => 'message',
                                        "message_id"    => $message->id,
                                        "user_id"       => 0,
                                        "group_id"      => $recipient['group_id'],
                                    );
                             
                            $badge = Messages::get_unread_message_count($member->user_id);
                            PUSH::send($devices, $alert, $extra, $badge);
                            
                        }
                    }
                }
            }
        }
        if( array_key_exists('file', $data) && is_array($data['file']) ) {
            
            $public_url = $this->_save_attachment($data['file']);
            $message->message_path  = $public_url;
            $message->save();
            
        }
        return $message;
    }

    /**
     * Retrieve all conversations for a given user.
     * @param string $date
     * @throws Exception
     * @return array
     */
    protected function _get_conversations($date = null)
    {
        $threads = Messages::get_conversations($date);
        return $threads;
    }
    
    /**
     * @todo upload file to Amazon cloud control system
     * @param array $_FILES
     * @return array
     */
    protected function _save_attachment($upload)
    {
        $file   = $upload;
            
        $fileName = md5(microtime(true)).$file['name'];
//        $uploadPath = Yii::app()->basePath.'/cache/';
//        @chmod($uploadPath, 0777);
//        
//        move_uploaded_file($file['tmp_name'], $uploadPath.$fileName);

        $bucketName = 'tonguetangofiles';
        if( Yii::app()->s3->upload($file['tmp_name']  , $fileName, $bucketName ) ) {
                $public_uri = "http://".$bucketName.".s3.amazonaws.com/".$fileName;

        }
        
        return $public_uri;
    }

    /**
     * @deprecated from v1.7
     * @param string|null $name
     * @return string
     */
    protected function _generate_upload_filename($name=null)
    {
        $file_name  = microtime(true);
        if( !is_null($name) ) {
            $file_name  .= '-'.$name;
        }
        return $file_name;
    }
}
