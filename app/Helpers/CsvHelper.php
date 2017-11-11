<?php

	namespace App\Helpers;

	// todo: developer@nitish
	class CsvHelper{

		/**
	     * To parse a CSV file and return a processed array of arrays to be stored in DB
	     * @param string $path_to_csv - the path to the input file - csv
	     * @param int $num_of_rows_from_top_to_skip - number of top rows (may include column heading or sheet heading) to be skipped
	     * @return array $result_arr - an array of arrays generated from the input csv file
	     */
		public static function parseCsv($path_to_csv, $num_of_rows_from_top_to_skip){
			// return
		}

		public static function checkCsv($num_columns, $data_header)
		{
			$array_header = array("Client Name", "Plant Name", "Plant State",	"Plant City", "Plant Address", "Person Name", "Person Designation", "Person Phone", "Person Mobile", "Person Email");
			
			$return = false;
			
			if($num_columns == 10)
			{
				if($data_header === $array_header)
				{
					$return = true;
				}
				else
				{
					$return = false;
				}
			}
			else{
					$return = false;
			}
			return $return;
		}

	}