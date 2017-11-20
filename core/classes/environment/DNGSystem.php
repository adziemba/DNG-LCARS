<?php
/**
 * LCARS is a framework for the RPG - ST - DNG
*
* @package    LCARS
* @version    0.1
* @author     Andreas Dziemba
* @license    DNG-Bluegreen License
* @copyright  2016 - 2017 Star Trek - Die neue Grenze
* @link       http://www.die-neue-grenze.de
*/
namespace core\classes\environment;



use core\dto\Character;

class DNGSystem {

	public static function getStartdate() {
		return "";
	}
	
	public static function validateSFNumber(string $sfnumber) : bool {		
		$split = explode('-', $sfnumber);
		
		$sfnum = $split[0];
		$checksum = $split[1];
		
		$checkedSum = self::calcChecksum($sfnum);
		if($checkedSum==$checksum) {
			return true;
		}
		return false;
		
	}
	
	public static function createSFNumber(Character $char) : string {
		$number 			= 'SF';
		$dateYear 			= $char->getYearOfBirth();
		if($char->getName() == null | $char=='') {
			$vornameInitial		= random_int(0, 9);
		}else {
			$vornameInitial 	= strtoupper($char->getName()[0]);
		}
		strtoupper($char->getSurname()[0]);
		$dateMonth			= $char->getMonthOfBirth();
		
		//TODO Check if random with this Charpart existes, if first part is identical
		$duorandom			= random_int(1, 9).random_int(1, 9);
		
		$partnumber = 'SF'.$dateYear.$vornameInitial.$nachnameInitial.$dateMonth.$duorandom;		
		
		$checksum			= self::calcChecksum($partnumber);
		return $partnumber. "-". $checksum;
	}

	private  static function calcChecksum(string $testString) : string{
		// SF2359AK0588
		$fixedString 	= substr($testString, 0 , 10);
		$randValue		= substr($testString, 10);
		
		$monthFillZero	= substr($fixedString, 9);
		
		$hashedFixedString 	= md5($fixedString);
		$dechash 			= "".hexdec($hashedFixedString);
		
		$intCrossfoot = 0;
		for( $i = 0; $i < strlen($dechash); $i++ )
		{
				$intCrossfoot +=$dechash{$i};
		}
		
		$checksum = abs(($intCrossfoot)+$randValue*3);	
		return str_pad($checksum, 3, $monthFillZero, STR_PAD_LEFT);
	}
}