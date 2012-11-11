<?php
/**
* uses european centralbank for exchangerates
*
* this class returns exchange rates from since 1999
*
* following currencies are supported by ecb:
* USD
* JPY
* BGN
* CZK
* DKK
* GBP
* HUF
* LTL
* LVL
* PLN
* RON
* SEK
* CHF
* NOK
* HRK
* RUB
* TRY
* AUD
* BRL
* CAD
* CNY
* HKD
* IDR
* ILS
* INR
* KRW
* MXN
* MYR
* NZD
* PHP
* SGD
* THB
* ZAR
* EUR = 1.0
*/

namespace helper\fetcher\ERProviders;

class ECB extends Rate{
	/**
	* cache for caching requests
	*/
	private $cache;	
	
	private $daily = 'http://www.ecb.europa.eu/stats/eurofxref/eurofxref-daily.xml';
	
	/**
	* for last 90 days, much faster than historical
	*/
	private $ninety = 'http://www.ecb.europa.eu/stats/eurofxref/eurofxref-hist-90d.xml';
	
	/**
	* this is rates since 1999.
	*
	* this is used if requested rate is not today, and the rate does not exist
	* in the cache
	*/
	private $all = 'http://www.ecb.europa.eu/stats/eurofxref/eurofxref-hist.xml';
	
	function __construct(){
		$this->cache = \helper\cache::getInstance('File', 'exchangeRatesECB');
	}
	
	/**
	* get exhange rate from one to another, provis date if needen (not earlier
	* than 1999)
	*
	* @param $date timestamp, this is later formattet as 'dmY'
	*/
	function getRate($from, $to, $date){
		return $this->calcRate($from, $to, $date);
	}
	
	function convert($from, $to, $amount, $date){
		return $this->calcRate($from, $to, $date) * $amount;
	}
	
	
	private function getAllFromDate($date = null){
		//return current
		if(is_null($date) || date('dmY') <= date('dmY', $date))
			return $this->getCurrentRates();
		
		//fetch some 90 days
		if(time()-6912000 < $date)
			return $this->get90Rates($date);
		if(915573600 < $date)
			return $this->getHistRates($date);
		
		//too old for this database
		return null;
	}
	
	/**
	* returns current (newest) rates
	*/
	private function getCurrentRates(){
		//check cache
		if($c = $this->cache->get(date('dmY')))
			return $c;
			
		$XML = new \SimpleXMLElement($this->fetchXML($this->daily));
		$rates = array();
		foreach($XML->Cube->Cube->Cube as $rate){
			$rates[(string)$rate['currency']] = (float)$rate['rate'];
			
		}
		$rates['EUR'] = 1.0;
		
		//do the cache, for very long time ;)
		$this->cache->set(date('dmY'), $rates, 40000000);
		
		return $rates;
	}
	
	/**
	* this fetches from 90 days back
	*/
	private function get90Rates($date){
		//check cache
		if($c = $this->cache->get(date('dmY', $date)))
			return $c;
		
		//fetch the whole shit :/
		$XML = new \SimpleXMLElement($this->fetchXML($this->ninety));
		$rates = array();
		
		foreach($XML->Cube->Cube as $cube){
			foreach($cube as $rate){
				$rates[(string)$rate['currency']] = (float)$rate['rate'];
			
			}
			$rates['EUR'] = 1.0;
			//do the cache, for very long time ;)
			//strtotime is not ambigious, accoring to php note: To avoid potential ambiguity, it's best to use ISO 8601 (YYYY-MM-DD) dates or DateTime::createFromFormat() when possible.
			$this->cache->set(date('dmY', strtotime($cube['time'])), $rates, 40000000);
			unset($rates);
		}
		return $this->cache->get(date('dmY', $date));
	}
	
	/**
	* this fetches historical data, and should only be used very seldom
	*/
	private function getHistRates($date){
		//check cache
		if($c = $this->cache->get(date('dmY', $date)))
			return $c;
		
		//fetch the whole shit :/
		$XML = new \SimpleXMLElement($this->fetchXML($this->all));
		$rates = array();
		
		foreach($XML->Cube->Cube as $cube){
			foreach($cube as $rate){
				$rates[(string)$rate['currency']] = (float)$rate['rate'];
			
			}
			$rates['EUR'] = 1.0;
			//do the cache, for very long time ;)
			//strtotime is not ambigious, accoring to php note: To avoid potential ambiguity, it's best to use ISO 8601 (YYYY-MM-DD) dates or DateTime::createFromFormat() when possible.
			$this->cache->set(date('dmY', strtotime($cube['time'])), $rates, 40000000);
			unset($rates);
		}
		return $this->cache->get(date('dmY', $date));
	}
	
	/**
	* calcuates rate from one currency to another
	*
	* all rates are given to/from EUR
	*/
	private function calcRate($from, $to, $date){
		$rates = $this->getAllFromDate($date);
		
		//check that both currencies exists
		if(!isset($rates[$from]) || !isset($rates[$to]))
			return false;
		
		return $rates[$to] / $rates[$from];
	}
}

?>
