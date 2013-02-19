<?php
namespace core;
class localization{
	
	/** fieldvariables: */
	private static $dictionary = array();
	/*
	eg:
	array(
		'da-hippie' => array(
			'md5-0' => 'hai!',
			'md5-1' => 'det går skiiiide godt!',
			//md5-2 goes down to the da edition
		)
		'da' => array(
			'md5-0' => 'hej med dig!',
			'md5-1' => 'det går godt!',
			'md5-2' => 'det er skide godt!',
		)
		'en' => array(
			'md5-0' => 'hi there!',
			'md5-1' => 'I am well!',
			'md5-2' => 'That is very good!',
		)
		'changed' => array('da' => 'merge')
	)
	
	*/
	
	/**
	* whether a dict is addable
	*/
	private static $addable = array();
	
	/* the language the programmer writes in */
	private static $defaultLan = 'en_EN';
	
	private static $changes = false;
	
	/** public readable settings **/
	
	/**
	* we do it the american way at first
	*
	* already used in template system
	*/
	public static $commaSeparator = ',';
	public static $thousandsSeparator = '.';
	public static $localization = 'da_DK';
	
	
	/** magic methods: **/

	/**
	 * this method looks up in the table, to see if there is a translation of
	 * the string
	 *
	 * for now, we are using crc32 for hashing. it is fast, and takes up only 32
	 * bit. if to many collisions, we might consider changing to md4/md5
	 * @param $str
	 * @param $args
	 * @return string
	 */
	static function lookup($str, $args){
		$gotSomething = false;
		
		//get the key for the string:
		$key = (string) self::getKey($str);
		foreach(self::$dictionary as $d => &$dict){
			if(isset($dict[$key])){
				$str = $dict[$key];
				$gotSomething = true;
				break;
			}
			elseif(self::$addable[$d]){
				$dict[$key] = $str;
				self::$changes = true;
			}
		}
		//if we didn't get anything, upload the string to the database as a non-
		//languaged string, for later translation, and procede with the inputted
		//string
		if(!$gotSomething){
			;//...
		}
		
		//return the result, with substituted
		array_unshift($args, $str);
		return call_user_func_array('\sprintf', $args);
	}
	
	static function addDict($name, $addAble = false){
		if(file_exists(LANDIR.$name.'.lan')){
			self::$dictionary[$name] = (array) json_decode(file_get_contents(LANDIR.$name.'.lan'));
		}
		else{
			self::$dictionary[$name] = array();
		}
		self::$addable[$name] = $addAble;
	}
	
	/**
	* static due to efficiency
	*
	* sets locale and stuff
	*/
	static function initialize(){
		//set from header first:
		//$locale = \Locale::acceptFromHttp($_SERVER['HTTP_ACCEPT_LANGUAGE']);
		//var_dump($locale);
		//get information from session:
		$locale = self::$localization;
		$jargon = 'novice';
		//$jargon = 'accounting';
		
		//sets locale, start trying to use utf8 one
		setlocale(LC_ALL, $locale.'.utf8', $locale);
		
		//include the jargon
		self::addDict($locale.$jargon);
		self::addDict($locale, true);
	}
	
	/**
	* the destructer
	*/
	static function cleanup(){
		if(!self::$changes)
			return;
		foreach(self::$dictionary as $name => $struct){
			file_put_contents(LANDIR.$name.'.lan', json_encode($struct));
		}
	}
	
	/**
	* set language and jargon of the object
	*
	* Jargon is used to define anoher jargon, for the same language. Jargon
	* always has fallback to the default jargon
	*
	* @param $lan language string in the form of eg en, da, ect...
	* @param $jargon unique string (relative to language).
	*/
	/*function __construct($lan, $jargon=null){
		$this->addLanguage($lan, $jargon);
	}*/
	
	/**
	* add language to the priority.
	*
	* if a string does not excist in the first language, the second is used, if
	* the phrase doesn't excist in second language it moves on to third and so
	* on.
	* if none language are to be found, the original string is returned 
	*/
	public function addLanguage($lan, $jargon=null){
		//fetch file
		$mongoCollection = core_db::getInstance(config_config::$coreConfig, 'mongo')->getCollection('library');
		
		if(is_null($jargon))
			$jargon = '';
		else
			$jargon = '-'.$jargon;
		
		$this->disctionary[$lan] = $mongoCollection->findOne(array('lanID' => $lan . $jargon));
		//locally cached serialised array (for not loading mongoDB to hard)
	}

    /**
     * takes a string, and returns the associate key
     *
     * @param $str
     * @return string
     */
    static function getKey($str){
	    //k is prepended to make sure it is treaded as a string (PHP's fucking type system)
        return 'k'.base_convert(crc32($str), 10, 36);
    }
	
	
}
?>
