<?php
/**
 * Utility class to convert audio to compatible format for
 * posting to facebook wall.
 * 
 * Abstract - do not try to instantiate, use statis methods
 * 
 * @author james
 */
abstract class Converter
{
	/**
	 * General definition of FFMPEG command line used.
	 * @var string
	 */
	//private static $_ffmpeg_command	= '/usr/local/bin/ffmpeg -loop_input -shortest -y -i  %1$s -i %2$s -r 1 %3$s';
	private static $_ffmpeg_command1	= 'ffmpeg -loop 1 -i %1$s -i %2$s -s 160x100 -r 1 -vcodec mpeg4 -acodec copy -shortest -qscale 1 %3$s';
//	private static $_ffmpeg_command2	= 'ffmpeg -loop 1 -i %1$s -i %2$s -s 320x200 -r 1 -vcodec h264 -acodec copy -shortest %3$s';

	/**
	 * General definition of FFMPEG audio command line
	 * used for audio only conversion.
	 * @var string
	 */
	private static $_ffmpeg_audio	= '/usr/local/bin/ffmpeg -vn -i %1$s -acodec %2$s -ar 16000 -ac 1 %3$s';

	/**
	 * Convert a file to AVI format and return the resulting
	 * file path.
	 * 
	 * @param string $file_uri
	 * @return string|bool
	 */
	public static function convert_to_avi($file_uri)
	{
		if (filesize($file_uri) == 0)
		{
			return 'empty';
		}

		$placeholder_image	= '/home/playground/tango/images/video_placeholder.png';
		$output_filename	= Yii::app()->basePath.'/cache/'.md5(microtime(true)).'.avi';
		$convert_command	= sprintf(
			self::$_ffmpeg_command1,
			$placeholder_image,
			$file_uri,
			$output_filename
		);
		
		$result	= Shell::run_command($convert_command, null, true);
        
		if( $result ) {
			return $output_filename;
		}
		return $result;
	}

	/**
	 * Convert a file to FLV format and return the resulting
	 * file path.
	 * 
	 * @param string $file_uri
	 * @return string|bool
	 */
	public static function convert_to_flv($file_uri)
	{
		$placeholder_image	= '/home/playground/tango/images/video_placeholder.png';
		$output_filename	= Yii::app()->basePath.'/cache/'.microtime(true).'.flv';
		$convert_command	= sprintf(
			self::$_ffmpeg_command,
			$placeholder_image,
			$file_uri,
			$output_filename
		);
		
		$result	= Shell::run_command($convert_command, null, true);
		if( $result ) {
			return $output_filename;
		}
		return $result;
	}

	/**
	 * Convert a file to MP4 format and return the resulting
	 * file path.
	 * 
	 * @param string $file_uri
	 * @return string|bool
	 */
	public static function convert_to_mp4($file_uri)
	{
		$placeholder_image	= '/home/playground/tango/images/video_placeholder.png';
		$output_filename	= Yii::app()->basePath.'/cache/'.microtime(true).'.mp4';
		$convert_command	= sprintf(
			self::$_ffmpeg_command,
			$placeholder_image,
			$file_uri,
			$output_filename
		);
		
		$result	= Shell::run_command($convert_command, null, true);
		if( $result ) {
			return $output_filename;
		}
		return $result;
	}

	/**
	 * Convert a file to MP3 format and return the resulting
	 * file path.
	 *
	 * @param string $file_uri
	 * @return string|bool
	 */
	public static function convert_to_audio_mp3($file_uri)
	{
        
        if (filesize($file_uri) == 0)
		{
			return 'empty';
		}
        @chmod(Yii::app()->basePath.'/cache/', 0777);
		$output_filename	= Yii::app()->basePath.'/cache/'.md5(microtime(true)).'.mp3';
		$convert_command        = sprintf(
			self::$_ffmpeg_audio,
			$file_uri,
			'libmp3lame',
			$output_filename
		);
        
		Yii::log($convert_command, 'info', 'CController');
		$result = Shell::run_command($convert_command, null, true);
		if( $result ) {
			return $output_filename;
		}
		return $result;
	}

	/**
	 * Convert a file to MP3 format and return the resulting
	 * file path.
	 *
	 * @param string $file_uri
	 * @return string|bool
	 */
	public static function convert_to_audio_ogg($file_uri)
	{
		$output_filename	= Yii::app()->basePath.'/cache/'.microtime(true).'.ogg';
		$convert_command        = sprintf(
			self::$_ffmpeg_audio,
			$file_uri,
			'vorbis',
			$output_filename
		);
		
		$result = Shell::run_command($convert_command, null, true);
		if( $result ) {
			return $output_filename;
		}
		return $result;
	}
}
