<?php

Yii::import('application.models._base.BaseMessages');

class Messages extends BaseMessages
{
	public static function model($className=__CLASS__) {
		return parent::model($className);
	}
    
    public static function get_conversations($date=0, $target_id=null)
	{
        
		if( is_null($target_id) ) {
			$target_id	= Controller::$user->id;
		}
        
        $connection = Yii::app()->db;
		if( is_null($date) ) {
			$date		= 0;
		}
        
        $sql = sprintf('CALL `returnMessageThread`("%1$s", "%2$s")', $target_id, date('Y-m-d H:i:s', $date));
                
		$results = $connection->createCommand($sql)->queryAll();
        
        
		// Clean up interger values for easier reliability
		foreach( $results as $index=>$result ) {
			foreach( array('friend_id') as $key ) {
				$results[$index][$key]	= intval($result[$key]);
			}
		}
		return $results;
	}

	public static function get_conversation($user_id, $date=0, $target_id=null, $limit = 0, $offset = 0)
	{
		if( is_null($target_id) ) {
			$target_id	= Controller::$user->id;
		}
        
        $connection = Yii::app()->db;
		if( is_null($date) ) {
			$date		= 0;
		}
        
        $sqlLimit = 'LIMIT '.$limit.' OFFSET '.$offset.' ';
        if($limit == 0 && $offset == 0) {
            $sqlLimit = '';
        }
        
		$sql = sprintf('
			SELECT
				m.id,
				m.user_id AS sender_id,
				mr.user_id AS recipient_id,
				m.message_header,
				m.message_body,
				m.message_path,
				m.create_date,
				IF(mf.id IS NULL, 0, 1) AS is_favorite
			FROM messages m
			INNER JOIN message_recipients mr
				ON mr.message_id=m.id
				AND mr.group_id IS NULL
			LEFT OUTER JOIN message_favorites mf
				ON m.id = mf.message_id
				AND mf.user_id = %1$s
			WHERE ((mr.user_id = %1$s AND m.user_id = %2$s) 
				OR (m.user_id = %1$s AND mr.user_id = %2$s))
				AND m.delete_date IS NULL
				AND m.create_date >= "%3$s"
			ORDER BY m.create_date DESC '.$sqlLimit,
			$target_id,
			$user_id,
			date("Y-m-d H:i:s", $date));
        
        
        
		$results = $connection->createCommand($sql)->queryAll();
		
		foreach( $results as $index=>$result ) {
			foreach( array('id','sender_id','recipient_id') as $key ) {
				$results[$index][$key]	= intval($result[$key]);
			}
		}
                
		return $results;
	}

	public static function mark_conversation($user_id, $status=null, $target_id=null)
	{
		if( is_null($target_id) ) {
			$target_id	= Controller::$user->id;
		}
        
        $connection = Yii::app()->db;
		if( is_null($status) ) {
			$status		= 1;
		}
        $results = false;
		$messages		= self::get_conversation($user_id, $target_id);
        
		$message_ids	= array();
		foreach( $messages as $message ) {
			$message_ids[]	= $message['id'];
		}
        if(empty($message_ids)) {
            return $results;
        }
        
		$sql = sprintf('
			UPDATE
				message_recipients
			SET `read`=%2$s
			WHERE message_id IN(%1$s)
			AND user_id=%3$s',
			implode(',',$message_ids),
			$status,
			$target_id);
		$results = $connection->createCommand($sql)->execute();
		return $results;
	}
	
    public static function get_unread_message_count($user_id)
    {
        $connection = Yii::app()->db;
        $sql = sprintf('
                SELECT COUNT(id) AS unread
                FROM message_recipients
                WHERE user_id = %1$s
                AND `read` = 0
                AND delete_date IS NULL 
                AND message_id NOT IN(SELECT id 
                                      FROM messages 
                                      WHERE delete_date IS NOT NULL)',
                $user_id);

        $results = $connection->createCommand($sql)->queryRow();
        
        return $results['unread'];
    }
    
    public function beforeSave()
    {
        if(parent::beforeSave())
        {
            if($this->isNewRecord)
            {
                if(is_null($this->create_date) || $this->create_date == '') {
                    $this->create_date  = date("Y-m-d H:i:s");
                    $this->update_date = date("Y-m-d H:i:s");
                }
                
            }
            else {
                if(is_null($this->update_date) || $this->update_date == '') {
                    $this->update_date = date("Y-m-d H:i:s");
                }
                
            }
                
                
            return true;
        }
        else
            return false;
    }
	
}