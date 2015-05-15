<?php
(PHP_SAPI !== 'cli' || isset($_SERVER['HTTP_USER_AGENT'])) && die('cli only');

$prefix = '$_';

$multi_array = array('GET', 'POST', 'COOKIE', 'REQUEST', 'SERVER', 'FILES', 'ENV', 'HTTP_COOKIE_VARS', 'HTTP_ENV_VARS', 'HTTP_GET_VARS', 'HTTP_POST_FILES', 'HTTP_POST_VARS', 'HTTP_SERVER_VARS');


class phpscan {

	public $searchterm;
	public $verbose;

	public function search_file($file, $variable, $file_write, $outputfile) {
		$line_number = false;
		$secure = array('htmlspecialchars', 'mysql_real_escape_string', 'htmlentities', 'escapeString');
		if($handle = fopen($file, "r")) {
			$count = 0;
			while(($line = fgets($handle, 4096)) !== FALSE and !$line_number) {
				$count++;
				$line_number = (strpos($line, $variable) !== FALSE) ? $count : $line_number;
				$secure_d = 0;
				if($line_number) {
					foreach($secure as $a) {
						if(preg_match("/".$a."/", $line)) {
							$secure_d = 1;
						}
					}
					if(preg_match("/(.*)=(.*)" . ltrim($variable, '$_') . "/", $line)) {
						$potential_variable = 1;
					} else {
						$potential_variable = 0;
					}
				}
			}
			fclose($handle);
		}
		if($line_number) {
			if($file_write == 1) {
				$line = file($file);
				$line = $line[$line_number-1];
				$fh = fopen($outputfile, "a");
				fwrite($fh, "Variable: " . $variable . "\n Line: " . $line_number . " " . $line . "\n File: " . $file . "\n Secure: " . $secure_d . "\n Variable Check: " . $potential_variable . "\n--------------------------------------------------------------------------\n");
				fclose($fh);
			} else { 
				if($this->verbose == 1) {
					if($potential_variable === 1 && $secure_d === 0) {
						$line = file($file);
						$line = $line[$line_number-1];
						echo "Variable: " . $variable . "\n Line: " . $line_number . " " . $line . "\n File: " . $file . "\n Secure: " . $secure_d . "\n Variable Check: " . $potential_variable . "\n--------------------------------------------------------------------------\n";
					}
				} else {
					$line = file($file);
					$line = $line[$line_number-1];
					echo "Variable: " . $variable . "\n Line: " . $line_number . " " . $line . "\n File: " . $file . "\n Secure: " . $secure_d . "\n Variable Check: " . $potential_variable . "\n--------------------------------------------------------------------------\n";
				}
			}
		}
	}

	public function find_files($directory, $write, $where) {
		$path = new RecursiveDirectoryIterator($directory);
		foreach(new RecursiveIteratorIterator($path) as $filename=>$cur) {
			if(strpos($filename, ".php")) {
				$this->search_file($filename, $this->searchterm, $write, $where);
			}
		}
	}
}

class banner {

	public $version = 'v.1';

	public function main_banner() {
		$main = "
.__         .__  .__  .__                            
|  |   ____ |  | |  | |__|_____   ____ ______  ______
|  |  /  _ \|  | |  | |  \____ \ /  _ \\\\____ \/  ___/
|  |_(  <_> )  |_|  |_|  |  |_> >  <_> )  |_> >___ \ 
|____/\____/|____/____/__|   __/ \____/|   __/____  >
    php basic fuzzer     |__|  v0.1    |__|       \/ ";
    	echo $main . "\n\n";
	}
	public function help() {
		$this->main_banner();
		echo "\n[+] [+] Help [+] [+]\n";
		echo " Missing Operators Detected \n";
		echo " Options: \n";
		echo "        -a (Attack Mode)*\n";
		echo "            1 - all (default)\n";
		echo "        -d (Directory)*\n";
		echo "            /tmp/files/\n";
		echo "        -o (Output File)\n";
		echo "            optional\n";
		echo "        -v (Verbose)\n";
		echo "            1 - 3\n";
		echo "\n";
	}
}

$prepare = new banner();

if(empty($argv[1])) {
	die($prepare->help());
} else {
	if($key = array_search('-h', $argv)) {
		die($prepare->help());
	}
	if($key = array_search('-a', $argv)) {
		$attacktype = $argv[$key+1];
	} else {
		$prepare->help();
		die();
	}
	if($key = array_search('-d', $argv)) {
		$directory = $argv[$key+1];
	} else {
		$prepare->help();
		die();
	}
	if($key = array_search('-o', $argv)) {
		$outputfile = $argv[$key+1];
		$file_write = 1;
	} else {
		$outputfile = "";
		$file_write = 0;
	}
	if($key = array_search('-v', $argv)) {
		$verbose = $argv[$key+1];
	} else {
		$verbose = 1;
	}
	if($attacktype != 1) {
		$prepare->help();
		die();
	}
}

if($attacktype == 1 && !empty($directory)) {
	$prepare->main_banner();
	$phpscan = new phpscan();
	foreach($multi_array as $a) {
		$phpscan->searchterm = $prefix . $a;
		$phpscan->verbose = $verbose;
		$phpscan->find_files($directory, $file_write, $outputfile);
	}
}
?>
