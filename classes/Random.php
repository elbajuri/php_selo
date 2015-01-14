<?php

class Random{

	public static function generate($length=6) {
			$seed = str_split('abcdefghijklmnopqrstuvwxyz'
                 .'ABCDEFGHIJKLMNOPQRSTUVWXYZ'
                 .'0123456789!@#$%^&*()'); 
		// and any other characters
			shuffle($seed); // probably optional since array_is randomized; this may be redundant
			$rand = '';
			foreach (array_rand($seed, $length) as $k) $rand .= $seed[$k];

		return  $rand;

	}

	public static function pin($length=6) {
			$num = str_split('0123456789');
		// and any other characters
			shuffle($num); // probably optional since array_is randomized; this may be redundant
			$rand = '';
			foreach (array_rand($num, $length) as $k) $rand .= $num[$k];

		return  $rand;

	}

	public static function huruf($length=6) {
			$num = str_split('ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz');
		// and any other characters
			shuffle($num); // probably optional since array_is randomized; this may be redundant
			$rand = '';
			foreach (array_rand($num, $length) as $k) $rand .= $num[$k];

		return  $rand;

	}


}