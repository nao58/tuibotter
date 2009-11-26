<?php

/**
 * TuiBotter - PHP Framework for twitter bot
 *
 * PHP versions 5
 * This framework depends on Tuitter (http://github.com/nao58/tuitter)
 *
 * @author    Naohiko MORI <naohiko.mori@gmail.com>
 * @copyright 2009 Naohiko MORI <naohiko.mori@gmail.com>
 * @license   Dual licensed under the MIT and GPL licenses.
 */

/**
 * TuiBotter Main Class
 */
class TuiBotter
{
	/**
	 * Private members
	 */
	private $_config=array();
	private $_tuitter;
	private $_bh=array();

	/**
	 * Load related modules
	 *
	 * @access public
	 * @param  string $file file name
	 */
	public static function load($file)
	{
		require_once self::getPath($file);
	}

	/**
	 * Create absolute path
	 *
	 * @access public
	 * @param  string $file file name
	 * @return string full path
	 */
	public static function getPath($file)
	{
		static $dir=null;
		if($dir===null) $dir=dirname(__FILE__);
		return "{$dir}/{$file}";
	}

	/**
	 * Constructor
	 *
	 * @access public
	 * @param  string $config config file name
	 */
	public function __construct($config)
	{
		if(!class_exists('Tuitter'))
			self::load('../Tuitter/Tuitter.php');
		self::load('Events.php');

		$config_file = realpath($config);
		if(file_exists($config_file)){
			$this->_config = parse_ini_file($config_file, true);
		}else{
			throw new Exception('Config file "'.$config_file.'" does not exists.');
		}

		$account = $this->_config['Account'];
		$user = $account['user'];
		$pass = $account['pass'];
		$this->_tuitter = new Tuitter($user, $pass);

		if($env = $this->_config['Environment']){
			if($env['cache']){
				if($cacheDir = $env['cacheDir']){
					Tuitter::load('Cache/File.php');
					$this->_tuitter->setCache(new Tuitter_Cache_File($cacheDir));
				}
			}
			if($env['cacheHttp']){
				if($cacheDir = $env['cacheHttpDir']){
					Tuitter::load('Cache/File.php');
					$this->_tuitter->setHttpCache(new Tuitter_Cache_File($cacheDir));
				}
			}
		}
	}

	/**
	 * Returns Tuitter object automatically created in constructor
	 *
	 * @access public
	 * @return object tuitter object
	 */
	public function getTuitter()
	{
		return $this->_tuitter;
	}

	/**
	 * Takes behaviour object
	 * The behaviour object has to implement TuiBotter_Event_xx interfaces.
	 *
	 * @access public
	 * @param  object $bh behaviour object
	 */
	public function applyBehaviour($bh)
	{
		$this->_bh[] = $bh;
	}

	/**
	 * Triggers
	 * You have to call this method after all.
	 *
	 * @access public
	 */
	public function heartbeat()
	{
		$this->checkFollowers();
		$this->checkFriendsTL();
		$this->checkMentions();
		$this->checkReplies();
	}

	/**
	 * Checks friends' timeline and push behaviour objects' method.
	 *
	 * @access public
	 */
	public function checkFriendsTL()
	{
		$this->_checkTweets('UpdatedFriendsTL', 'eventUpdatedFriendsTL', 'getFriendsTL');
	}

	/**
	 * Checks mentions and push behaviour objects' method.
	 *
	 * @access public
	 */
	public function checkMentions()
	{
		$this->_checkTweets('Mentioned', 'eventMentioned', 'getMentions');
	}

	/**
	 * Checks replies and push behaviour objects' method.
	 * Note: replies api has been deplicated now. you should use mentions api instead.
	 *
	 * @access public
	 */
	public function checkReplies()
	{
		$this->_checkTweets('Replied', 'eventReplied', 'getReplies');
	}

	/**
	 * Checks followers and push behaviour objects' method.
	 *
	 * @access public
	 */
	public function checkFollowers()
	{
		$ifName = 'Followed';
		$applyer = 'eventFollowed';
		$getter = 'getFollowers';
		if($bhs = $this->_getBehaviours($ifName)){
			$users = $this->_tuitter->$getter();
			$users->reverse();
			foreach($users as $user){
				foreach($bhs as $bh){
					$bh->$applyer($user, $this->_tuitter);
				}
			}
		}
	}

	/**
	 * Takes care about incremental checking process.
	 *
	 * @access private
	 * @param  string $ifName name of interface
	 * @param  string $applyer name of behaviours method
	 * @param  string $getter name of tuitter object's method
	 */
	private function _checkTweets($ifName, $applyer, $getter)
	{
		if($bhs = $this->_getBehaviours($ifName)){
			$tweets = $this->_tuitter->$getter(array('count'=>200));
			$tweets->reverse();
			foreach($tweets as $tweet){
				foreach($bhs as $bh){
					$bh->$applyer($tweet, $this->_tuitter);
				}
			}
		}
	}

	/**
	 * Returns all behaviours for the event.
	 *
	 * @access private
	 * @param  string $eventName name of event
	 * @return array behaviours
	 */
	private function _getBehaviours($eventName)
	{
		$en = "TuiBotter_Event_{$eventName}";
		$ret = array();
		foreach($this->_bh as $bh){
			if($bh instanceof $en){
				$ret[] = &$bh;
			}
		}
		return $ret;
	}
}
