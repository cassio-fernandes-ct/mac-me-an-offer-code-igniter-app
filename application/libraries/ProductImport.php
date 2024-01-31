<?php 


// use PHPMailer\PHPMailer\PHPMailer;
// use PHPMailer\PHPMailer\Exception;


defined( 'BASEPATH' ) || exit( 'No direct script access allowed.' );


class ProductImport 
{
	private $log_file = APPPATH . 'logs/product-import.log';
	private $flag_file = APPPATH . 'third_party/flags/product-import.flag';
	private $hash = 'f06691038d85a6de710b38ba80291fa38f076cc827159d453aa1c4abe8345166';

	private $pid;
	private $process_time_start;
	private $process_time_total;


	/**
	 * Sets up class
	 * 
	 * Switched from PHPMailer to CodeIgniter's native mail library (didn't know it existed previously)
	 *
	 */
	public function __construct()
	{
		// require_once( APPPATH . 'third_party/phpmailer/src/Exception.php' );
		// require_once( APPPATH . 'third_party/phpmailer/src/PHPMailer.php' );
		// require_once( APPPATH . 'third_party/phpmailer/src/SMTP.php' );
	}


	/**
	 * Validate hash
	 *
	 * @param	string 	$hash
	 * @return 	bool 	True, if hash matches
	 */
	public function validate_hash( string $hash ): bool
	{
		return $hash === $this->hash;
	}


	/**
	 * Check if importer is running
	 *
	 * For now, we're simply going to check if the flag file exists. If file is present, then import is already running.
	 * 
	 * @todo 	Delete flag if flag creation date is over three days old (assume something went wrong with import)
	 *  
	 * @return 	bool
	 */
	public function is_running(): bool
	{
		return is_file( $this->flag_file );
	}


	/**
	 * Kicks off importer
	 *
	 * @return 	void
	 */
	public function start(): void
	{
		$this->pid = getmypid();
		$this->process_time_start = microtime( true ); // hrtime not available in PHP 7.2

		$this->create_flag();
		$this->log_start();
	}


	/**
	 * Shut down actions
	 *
	 * @return 	void
	 */
	public function finish(): void
	{
		if( !$this->pid ) {
			throw new Exception( 'You must run "start" before running "finish"!' );
		}

		$this->delete_flag();
		$this->log_end();
		$this->notify();
	}	


	/**
	 * Create basic flag file to prevent simultaneous imports 
	 * 
	 * @todo 	Maybe move to DB
	 *
	 * @return	void
	 */
	private function create_flag(): void
	{
		file_put_contents( $this->flag_file, $this->pid );
	}


	/**
	 * Remove basic flag file to unlock for next import to run
	 * 
	 * @todo 	Maybe move to DB
	 *
	 * @return	void
	 */
	private function delete_flag(): void
	{
		if( is_file( $this->flag_file ) ) {
			unlink( $this->flag_file );
		}
	}


	/**
	 * Log start of cronjob
	 *
	 * @return	void
	 */
	private function log_start(): void
	{
		$data = [
			'event'		=> 'start',
			'pid'		=> $this->pid,
			'time' 		=> date( 'Y-m-d H:i:s' ),
			'mem' 		=> $this->get_formatted_mem_usage(),
			'server'	=> $_SERVER,
		];

		$this->write_to_log( $data );
	}


	/**
	 * Log end of cronjob
	 *
	 * @return	void
	 */
	private function log_end(): void
	{
		// hrtime not available in PHP 7.2
		$this->process_time_total = microtime( true ) - $this->process_time_start;

		$data = [
			'event'		=> 'end',
			'pid'		=> $this->pid,
			'time' 		=> date( 'Y-m-d H:i:s' ),
			'mem' 		=> $this->get_formatted_mem_usage(),
			'proc_time'	=> $this->process_time_total,
		];

		$this->write_to_log( $data );
	}


	/**
	 * Log failed attempts to run the importer
	 *
	 * @param	string 	$reason
	 * @return	void
	 */
	public function log_failed_attempt( string $reason ): void
	{
		$data = [
			'event'		=> 'fail',
			'time' 		=> date( 'Y-m-d H:i:s' ),
			'reason'	=> $reason,
			'server'	=> $_SERVER,
		];

		$this->write_to_log( $data );
	}


	/**
	 * Write data to log
	 *
	 * @param	mixed 	$data
	 * @return	void
	 */
	public function write_to_log( $data ): void
	{
		$data = json_encode( $data, JSON_PRETTY_PRINT ) . PHP_EOL;

		file_put_contents( $this->log_file, $data, FILE_APPEND );
	}


	/**
	 * Notify WebFX that importer successfully completed
	 * 
	 * @return	void
	 */
	private function notify(): void
	{
		$ci_instance = get_instance();
		$ci_instance->load->library( 'email' );
		$mailer = $ci_instance->email;

		$query = $ci_instance->db->query(
			'SELECT protocol, smtp_user, smtp_port, smtp_host, smtp_pass
			FROM setting'
		);
		$smtp_config = $query->row_array();

		$mailer->initialize(
			[
				'protocol'		=> $smtp_config['protocol'],
				'smtp_user'		=> $smtp_config['smtp_user'],
				'smtp_port'		=> $smtp_config['smtp_port'],
				'smtp_host'		=> $smtp_config['smtp_host'],
				'smtp_pass'		=> $smtp_config['smtp_pass'],
				'smtp_crypto'	=> 'ssl',
				'charset'		=> 'iso-8859-1',
			]
		);

		$mailer->to( 'rob+appmmao@webfx.com' );
		$mailer->from( $smtp_config['smtp_user'] );
		$mailer->subject( sprintf( '[MMAO] Product Import - %s', date( 'Y-m-d H:i:s' ) ) );
		$mailer->message(
			json_encode(
			[
				'PID' => $this->pid,
				'Memory Usage' => $this->get_formatted_mem_usage(),
				'Processing Time' => $this->process_time_total,
			],
			JSON_PRETTY_PRINT
			)
		);

		$mailer->send();
	}


	/**
	 * Get memory usage in pretty format
	 * 
	 * @todo 	Move to helper?
	 *
	 * @param 	int|string 	$bytes 
	 * @param 	int 		$precision
	 * 
	 * @return 	string
	 */
	private function get_formatted_mem_usage( $bytes = null, int $precision = 2 ): string {
		if( !$bytes || !is_numeric( $bytes ) ) {
			$bytes = memory_get_usage( true );
		}

		$base = log( $bytes, 1024 );
		$suffixes = [ '', 'K', 'M', 'G', 'T' ];

		return sprintf(
			'%s%s',
			round( pow( 1024, $base - floor( $base ) ), $precision ),
			$suffixes[ floor( $base ) ]
		);
	}	
}