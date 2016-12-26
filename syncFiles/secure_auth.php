<?php

class authentication {
    // declare log file and file pointer as private properties
    private $log_file, $fp;
    var $print_log_enabled=false;
    
    // set log file (path and name)
    public function lfile($path) {
    	if( $this->print_log_enabled ){
			$this->log_file = $path;
		}
    }
    // write message to the log file
    public function lwrite($message) {
    	if( $this->print_log_enabled ){
        	// if file pointer doesn't exist, then open log file
        	if (!is_resource($this->fp)) {
           	 $this->lopen();
        	}
       	 	// define script name
        	$script_name = pathinfo($_SERVER['PHP_SELF'], PATHINFO_FILENAME);
        	// define current time and suppress E_WARNING if using the system TZ settings
        	// (don't forget to set the INI setting date.timezone)
       	 	$time = @date('[d/M/Y:H:i:s]');
        	// write current time, script name and message to the log file
        	fwrite($this->fp, "$time ($script_name) $message" . PHP_EOL);
        }
    }
}
?>