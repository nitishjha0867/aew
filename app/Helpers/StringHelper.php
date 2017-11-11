<?php

	namespace App\Helpers;

	// todo: developer@yogesh
	class StringHelper{

		/**
	     * Sanitize a string
	     * @param string $string - the input string
	     * @return string $string - the sanitized version of $string
	     */
		public static function sanitize($string, $exclude = ''){
			return preg_replace('/[^A-Za-z0-9'.$exclude.'\- ]/', '', $string);
		}

		public static function stringReplacecommabyunderscore($string)
		{
			return str_replace(',', '_', $string);
		}

	}