<?php

	namespace App\Helpers;

	// todo: developer@yogesh
	class IntHelper{

		/**
	     * pending
	     * @param pending
	     * @return pending
	     */
		public static function numSuffix($param, $suffix_only = false){
			if($param<10){
				$output = $param==1?$param."st":($param==2?$param."nd":($param==3?$param."rd":$param."th"));
			} else if($param>9&&$param<20){
				$output = $param."th";
			} else if($param>=20&&$param<100){
				$output = preg_match("/[2-9]{1}[1]{1}/", $param)?$param."st":(preg_match("/[2-9]{1}[2]{1}/", $param)?$param."nd":(preg_match("/[2-9]{1}[3]{1}/", $param)?$param."rd":($param."th")));
			} else {
				$output = preg_match("/[0-9]{2}[1]{1}/", $param)?$param."st":(preg_match("/[0-9]{2}[2]{1}/", $param)?$param."nd":(preg_match("/[0-9]{2}[3]{1}/", $param)?$param."rd":($param."th")));
			}
			return $suffix_only ? substr($output, strlen($param)) : $output;
		}

		/**
	     * pending
	     * @param pending
	     * @return pending
	     */
		public static function intoWords($elem){
			$output=""; $this_val_deductor=0;
			$this_val_str = strval($elem);
			$input_array = explode(".", $this_val_str);
			$pre_deci_val = strval($input_array[0]);
			$pre_deci_val = str_replace(",", "", $pre_deci_val);
			$this_val = intval($pre_deci_val);
			$inpval_len = strlen($pre_deci_val);
			if($inpval_len<10){
				if($this_val==""){
					return "";
				} else if($this_val==0){
					return "Zero";
				} else if($this_val==1){
					return "One";
				} else {
					$wordified = ["0" => "", "1" => "One ", "2" => "Two ", "3" => "Three ", "4" => "Four ", "5" => "Five ", "6" => "Six ", "7" => "Seven ", "8" => "Eight ", "9" => "Nine "];
					$teen_suff = ["0" =>"Ten", "1" => "Eleven", "2" => "Twelve", "3" => "Thirteen", "4" => "Fourteen", "5" => "Fifteen", "6" => "Sixteen", "7" => "Seventeen", "8" => "Eighteen", "9" => "Nineteen"];
					$num_suff = ["2" => "Twenty", "3" => "Thirty", "4" => "Forty", "5" => "Fifty", "6" => "Sixty", "7" => "Seventy", "8" => "Eighty", "9" => "Ninety"];
					$zeros = [10000000, 100000, 1000, 100, 1];
					$oo_suff_arr = ["10000000" => "Crore ", "100000" => "Lakh ", "1000" => "Thousand ", "100" => "Hundred ", "1" => ""];
					foreach($zeros as $key => $value){
						$oo_suff = $oo_suff_arr[$value];
						$this_val = $this_val - $this_val_deductor;
						$quot_val = floor($this_val/$value);
						$quot_len = strlen(strval($quot_val));
						$and_preffix = $value==1&&$inpval_len>2?"And ":"";
						switch($quot_len){
							case 1:
								if($quot_val==0){
									break;
								} else {
									$output.= $and_preffix.$wordified[$quot_val].$oo_suff;
								}
								break;
							case 2:
								$tens_p = floor($quot_val/10);
								$unts_p = $quot_val%10;
								$output.= $and_preffix.($tens_p!=1 ? $unts_p==0? $num_suff[$tens_p]." ".$oo_suff : $num_suff[$tens_p]."-".$wordified[$unts_p].$oo_suff : ($tens_p==1 ? $teen_suff[$unts_p]." ".$oo_suff : ("")));
								break;
							default:
								break;
						}
						$this_val_deductor = $quot_val*$value;
					}
					return trim($output);
				}
			} else {
				return "Number too large to be converted into words. Max limit is 99,99,99,999";
			}
		}
		
		/**
	     * pending
	     * @param pending
	     * @return pending
	     */
		public static function intoCurrencyFormat($param, $deci_limit = false){
			$input_array = explode(".", $param);
			$pre_deci_val = $input_array[0];
			$inp_length = strlen($pre_deci_val);
			$char_array = str_split($pre_deci_val);
			$output = $decimals = "";
			$echoed=0;
			if($inp_length>3){
				for($i=0; $i<$inp_length; $i++){
					$output.=$char_array[$i];
					$echoed++;
					if($inp_length-$echoed==3){
						$output.=",";
					} else if($inp_length-$echoed>3){
						$inpair = $inp_length-$echoed;
						$inpair%2==1?$output.=",":"";
					}
				}
			} else {
				$output = trim($input_array[0]);
			}
			if(isset($input_array[1])){
				if($deci_limit!==false && gettype($deci_limit)==="integer"){
					if($deci_limit<=0){
						$deci_limit = 0;
						return $output;
					}
					$decimals = substr(round(floatval("0.".$input_array[1]), $deci_limit), 2);
					for($dc = strlen(strval($decimals)); $dc < $deci_limit; $dc++){
						$decimals.= "0";
					}
				} else {
					$decimals = $input_array[1];
				}
				return $output.".".$decimals;
			} else {
				if($deci_limit!==false && gettype($deci_limit)==="integer"){
					if($deci_limit<=0){
						$deci_limit = 0;
						return $output = trim($output);
					}
					while($deci_limit>0){
						$decimals.= "0";
						$deci_limit--;
					}
					return $output.".".$decimals;
				} else {
					return $output = trim($output);
				}
			}
		}
	}