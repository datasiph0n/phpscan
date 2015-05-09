# phpscan
Lollipops - Simple yet effective PHP function scanner.

Currently in semi-working order - nothing too heavy.
  $multi_array = array('GET', 'POST', 'COOKIE', 'REQUEST', 'SERVER', 'FILES', 'ENV', 'HTTP_COOKIE_VARS', 'HTTP_ENV_VARS', 'HTTP_GET_VARS', 'HTTP_POST_FILES', 'HTTP_POST_VARS', 'HTTP_SERVER_VARS');

  $secure = array('htmlspecialchars', 'mysql_real_escape_string', 'htmlentities', 'escapeString');
.__         .__  .__  .__                            
|  |   ____ |  | |  | |__|_____   ____ ______  ______
|  |  /  _ \|  | |  | |  \____ \ /  _ \\\\____/  ___/
|  |_(  <_> )  |_|  |_|  |  |_> >  <_> )  |_> >___ \ 
|____/\____/|____/____/__|   __/ \____/|   __/____  >
    php basic fuzzer     |__|  v0.1    |__|       \/ 

		[+] [+] Help [+] [+]
		 Missing Operators Detected;
		 Options:
		        -a (Attack Mode)
		            1 - all (default)
		        -d (Directory)*
		            /tmp/files/
		        -o (Output File)
		            optional
		        -v (Verbose)
		            1 - 3
