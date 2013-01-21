<?php
Yii::import('ext.logger.CPSLiveLogRoute');
class GroupController extends Controller
{
    /**
	 * @todo list all groups    * 
	 */
    public function actionList()
	{   
		$groups = array();
        
        $sql = "SELECT `group`.* FROM `groups` AS `group` 
                JOIN `group_members` ON (`group_members`.`group_id` = `group`.`id`) 
                WHERE `group_members`.`user_id` = " . self::$user->id . " 
                AND `group`.`delete_date` IS NULL 
                AND `group_members`.`removed` = 0
				AND `group_members`.`accepted`=1  
                AND `group`.`id` NOT IN (SELECT group_id FROM group_blocks WHERE(user_id = " . self::$user->id . " AND blocked = 1))";
        
        $groupModels = Groups::model()->with(array('memberusers'=>array('select'=>'*',
                                                                        'condition'=>'groupMembers.removed = 0'),
                                                   'memberpersons'=>array('select'=>'photo',
                                                                          'joinType'=>'JOIN')))
                                      ->findAllBySql($sql);
        
        
		foreach($groupModels  as$key=> $group) {
			$members = array();
			foreach( $group->memberusers as $member) {
                
				$members[] = array(
					'user'	=> $member->id,
					'photo'	=> $member->person->photo,
				);
                
			}
            $groups[$key] = $group->attributes;
			$groups[$key]['members'] =  $members; 
		}
        
		$this->setOutput('groups',$groups);
	}
    
    /**
	 * @todo create group
     * 
	 */
    public function actionCreate()
    {	
		$group 			= new Groups;
		$group->user_id = self::$user->id;
        /*if(is_null($group->name)) {
            
        }*/
		$group->name 	= $this->getInput('name');
        if($group->save()) {
		
            $groupMember = new GroupMembers;
            $groupMember->group_id = $group->id;
            $groupMember->user_id = self::$user->id;
			$groupMember->accepted = 1;
            $groupMember->save();
        } else {
            $this->_sendResponse(404, array("code"=>0, 'message'=>$group->errors));
        }
		
		$this->setOutput('group', $group->attributes);
    }
    
    /**
	 * @todo edit group info
     * @param array group data
     * 
	 */
    public function actionUpdate($id)
	{
        $id = intval($id);   
		if( is_null($id) ) {
			$this->_sendResponse(400, array("code"=>0, 'message'=>"Invalid group specified"));
		}
		$group = Groups::model()->with(array('groupMembers'=>array('select'=>'id,user_id')))->findByPk($id);
        if(!$group) {
            $this->_sendResponse(400, array("code"=>0, 'message'=>"Invalid group specified"));
        }
        
		$group->attributes = $this->getInputs();
		$group->save();
        $file = $this->getInput('file');
        if( is_array($file) && array_key_exists('name', $file) ) { 
            $bucketName = 'tonguetangofiles';
            $fileName = md5(microtime(true)).$file['name'];
            if( Yii::app()->s3->upload($file['tmp_name']  , $fileName, $bucketName ) ) {
                    $public_uri = "http://".$bucketName.".s3.amazonaws.com/".$fileName;
                    $group->photo	= $public_uri;
                    $group->save();
            
            }
        }
		$members = $this->getInput('members', '');
		
		if ($members != '') {

			$members = explode(",", $this->getInput('members'));
            
            $criteria = new CDbCriteria;
            $criteria->condition = 'group_id='.$id . ' AND ' .
                                   'user_id !='.self::$user->id  . ' AND ' .
                                   'user_id NOT IN ('.implode(',', $members).')';
            
            
            $removed_members = GroupMembers::model()->findAll($criteria);
            
			// remove folks as necessary
			
			foreach ($removed_members as $removed) {
                $removedUserDevices = $removed->user->devices;
                $alert = "You've been removed from the group: " .$group->name;
                $extra = array(
                            "action"		=> 'group',
                        );

                $badge = Messages::get_unread_message_count($removed->user->id);
                PUSH::send($removedUserDevices, $alert, $extra, $badge);
				$removed->delete();
			}
            
			if (is_array($members) && count($members) > 0) {
				$existing_members = $group->groupMembers;
                $existing_members = CHtml::listData($existing_members,'id','user_id');
                
				foreach($members as $member) {
					if ($member != self::$user->id && $member > 0 && !in_array($member, $existing_members)) {
						
                        $newMember = new GroupMembers;
                        $newMember->user_id  = $member;
                        $newMember->group_id = $group->id;
                        
                        $newMember->save();
                        
						$member_user = Users::model()->with(array('devices'=>array(   // skip results with empty push token
                                            'condition'=>'devices.push_token != "" AND devices.push_token IS NOT NULL',
                                        )))->findByPk($member);
                        
                        if(!$member_user) continue;
                        
						$devices = $member_user->devices;
                        
						$alert = "You've been added to the group: " .$group->name;
						$extra = array(
									"action"		=> 'group',
								);
                        
						$badge = Messages::get_unread_message_count($member_user->id);
                                                PUSH::send($devices, $alert, $extra, $badge);
                        
					}
				}
			}
		} else {
            $criteria = new CDbCriteria;
            $criteria->condition = 'group_id='.$id . ' AND ' .
                                   'user_id !='.self::$user->id  . ' ';
            
            $removed_members = GroupMembers::model()->findAll($criteria);
            
			// remove all users
			foreach ($removed_members as $removed) {
                $removedUserDevices = $removed->user->devices;
                $alert = "You've been removed from the group: " .$group->name;
                $extra = array(
                            "action"		=> 'group',
                        );

                $badge = Messages::get_unread_message_count($removed->user->id);
                PUSH::send($removedUserDevices, $alert, $extra, $badge);
				$removed->delete();
			}
            
        }
        
		$members = array();
        $group = Groups::model()->with(array('memberusers'=>array('select'=>'*',
                                                                    'joinType'=>'JOIN',),
                                             'memberpersons'=>array('select'=>'photo',
                                                                    'joinType'=>'JOIN')))->findByPk($id);
        
		foreach( $group->memberusers as $member ) {
			$members[]	= array(
				'user_id'	=> $member->id,
				'photo'		=> $member->person->photo,
			);
		}
        
		$this->setOutput('group', array_merge($group->attributes, array(
			'members'	=> $members,
		)));
	}
    
    /**
	 * @todo delete group
     * @param int group id
     * 
	 */
    public function actionDelete($id)
	{ 
		$group 	= Groups::model()->findbyPk($id);
		
		if ( $group) {
			$group->delete_date = date('Y-m-d H:i:s');
			$group->save();
			$this->setOutput('deleted', 1);
		} else {
            $this->_sendResponse(400, array("code"=>0, 'message'=>"Invalid group specified"));
        }
		
	}
    
    /**
	 * @todo accept group invitation
     * @param int group id
     * 
	 */
    public function actionAccept($id)
    {
        $id = intval($id);   
		if( is_null($id) ) {
			$this->_sendResponse(400, array("code"=>0, 'message'=>"Invalid group specified"));
		}
        
        $connection = Yii::app()->db;
        
        $sql = "UPDATE `group_members` SET `accepted`=1 WHERE `user_id`=".self::$user->id." AND `group_id`=".$id."";
        
        $results = $connection->createCommand($sql)->execute();
        if($results) {
            $group   = Groups::model()->findByPk($id);
            $devices = Devices::model()->findAll( array( 'condition' => 
                                                         'push_token!="" AND push_token IS NOT NULL
                                                          AND user_id=' . $group->user_id ) );
            
            $alert = self::$user->person->first_name." has accepted your group request for ".$group->name." group";

            $extra = array(
                        "action"        => 'group',
                    );

            $badge = Messages::get_unread_message_count($group->user_id);
            PUSH::send($devices, $alert, $extra, $badge);
            $this->_sendResponse(400, array('message'=>"success"));
        } else {
            $this->_sendResponse(400, array('message'=>"failure"));
        }
    }
    
    /**
	 * @todo decline group invitation
     * @param int group id
     * 
	 */
    public function actionReject($id)
    { 
        $id = intval($id);   
		if( is_null($id) ) {
			$this->_sendResponse(400, array("code"=>0, 'message'=>"Invalid group specified"));
		}
        $connection = Yii::app()->db;
        
        $sql = "UPDATE `group_members` SET `removed`=1 WHERE `user_id`=".self::$user->id." AND `group_id`=".$id."";
        
        $results = $connection->createCommand($sql)->execute();
        
        if($results) {
            $group   = Groups::model()->findByPk($id);
            $devices = Devices::model()->findAll( array( 'condition' => 
                                                         'push_token!="" AND push_token IS NOT NULL
                                                          AND user_id=' . $group->user_id ) );
            
            $alert = self::$user->person->first_name." has rejected your group request for ".$group->name." group";

            $extra = array(
                        "action"        => 'group',
                    );

            $badge = Messages::get_unread_message_count($group->user_id);
            PUSH::send($devices, $alert, $extra, $badge);
            $this->setOutput('status', 'success');
        } else {
            $this->setOutput('status', 'failure');
        }
    }
    
    /**
	 * @todo block group
     * @param int group id (if !$id do list all blocked groups)
     * 
	 */
    public function actionBlock( $id = 0 ) {
		if( $id != 0 ) {
			$member = GroupMembers::model()->findByAttributes( array( 'group_id' => intval( $id ), 
                                                                      'user_id' => self::$user->id, 
                                                                      'accepted' => 1 ) );

			if( !$member ) {
				$this->_sendResponse(400, array("code"=>0, 'message'=>"You are not joined to group"));
			}

			$block_group = GroupBlocks::model()->findByAttributes( array( 'user_id' => self::$user->id, 
                                                                          'group_id' => $member->group_id ) );
			
			if( $block_group && $block_group->blocked == 1) {
				$this->_sendResponse(400, array("code"=>0, 'message'=>"You have already blocked group"));
			}

			if ($block_group && $block_group->update_date != NULL && $block_group->blocked == 0) {
				$block_group->blocked = 1;
			} else {
				$block_group = new GroupBlocks;
				$block_group->user_id = self::$user->id;
				$block_group->group_id = $member->group_id;
				$block_group->blocked = 1;
			}
			if( $block_group->save() ) {
				$this->setOutput('status', 'success');
			} else {
				$this->setOutput('status', 'failure');
				$this->_sendResponse();
			}

		} else { $this->_sendResponse(400, array("code"=>0, 'message'=>"Please enter group id")); }
	}
	
    /**
	 * @todo unblock group
     * @param int group id 
     * 
	 */
	public function actionUnblock( $id = 0 ) {
        if( $id != 0 ) {
			$member = GroupMembers::model()->findByAttributes( array( 'group_id' => intval( $id ), 
                                                                      'user_id' => self::$user->id, 
                                                                      'accepted' => 1 ) );

			if( !$member ) {
				$this->_sendResponse(400, array("code"=>0, 'message'=>"You are not joined to group"));
			}
			
            $block_group = GroupBlocks::model()->findByAttributes( array( 'user_id' => self::$user->id, 
                                                                          'group_id' => $member->group_id ) );

            if( !$block_group ) {
                $this->_sendResponse(200, array("code"=>'0','message'=>"This group is not blocked for you"));
            }

            if( $block_group->blocked == 0 ) {
                $this->_sendResponse(200, array("code"=>'0','message'=>"Already unblocked"));
            }

            $block_group->blocked = 0;
            if( $block_group->save() ) {
                $this->setOutput("status", "unblocked");
            } else {
                $this->_sendResponse(200, array("code"=>'0','message'=>"Database error, please try it later"));
            }
        }
	}
    
}