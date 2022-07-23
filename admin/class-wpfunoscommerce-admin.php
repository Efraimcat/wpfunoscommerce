<?php

/**
* The admin-specific functionality of the plugin.
*
* @link       https://efraim.cat
* @since      1.0.0
*
* @package    Wpfunoscommerce
* @subpackage Wpfunoscommerce/admin
*/

/**
* The admin-specific functionality of the plugin.
*
* Defines the plugin name, version, and two examples hooks for how to
* enqueue the admin-specific stylesheet and JavaScript.
*
* @package    Wpfunoscommerce
* @subpackage Wpfunoscommerce/admin
* @author     Efraim Bayarri <efraim@efraim.cat>
*/
class Wpfunoscommerce_Admin {

	/**
	* The ID of this plugin.
	*
	* @since    1.0.0
	* @access   private
	* @var      string    $plugin_name    The ID of this plugin.
	*/
	private $plugin_name;

	/**
	* The version of this plugin.
	*
	* @since    1.0.0
	* @access   private
	* @var      string    $version    The current version of this plugin.
	*/
	private $version;

	/**
	* Initialize the class and set its properties.
	*
	* @since    1.0.0
	* @param      string    $plugin_name       The name of this plugin.
	* @param      string    $version    The version of this plugin.
	*/
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	* Register the stylesheets for the admin area.
	*
	* @since    1.0.0
	*/
	public function enqueue_styles() {

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/wpfunoscommerce-admin.css', array(), $this->version, 'all' );

	}

	/**
	* Register the JavaScript for the admin area.
	*
	* @since    1.0.0
	*/
	public function enqueue_scripts() {

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/wpfunoscommerce-admin.js', array( 'jquery' ), $this->version, false );

	}

	/*********************************/
	/*****  UTILIDADES          ******/
	/*********************************/
	/**
	* Utility: log files.
	*/
	private function logFiles()
	{
		$upload_dir = wp_upload_dir();
		$files = scandir( $upload_dir['basedir'] . '/' . $this->plugin_name . '-logs');
		?>
		<form action="" method="post">
			<ul>
				<?php foreach ( $files as $file ) { ?>
					<?php if( substr( $file , -4) == '.log'){?>
						<li><input type="radio" id="age[]" name="logfile" value="<?php esc_html_e( $file ); ?>">
							<?php esc_html_e( $file . ' -> ' . date("d-m-Y H:i:s", filemtime( $upload_dir['basedir'] . '/' . $this->plugin_name . '-logs/' . $file  ) ) ); ?>
						</li>
					<?php }?>
				<?php }?>
			</ul>
			<div class="wpfunos-send-logfile">
				<input type="submit" value="<?php _e( 'Ver archivo de registro', 'wpfunos' ); ?>">
			</div>
		</form>
		<?php
	}
	/**
	* Utility: show log file.
	*/
	private function showLogFile()
	{
		$upload_dir = wp_upload_dir();
		if (isset($_POST['logfile'])) {
			?>
			<hr />
			<h3><?php esc_html_e( $_POST['logfile'] ); ?> </h3>
			<textarea id="wpfunoslogfile" name="wpfunoslogfile" rows="30" cols="180" readonly>
				<?php esc_html_e( ( file_get_contents( $upload_dir['basedir'] . '/' . $this->plugin_name . '-logs/' . $_POST['logfile'] ) ) ); ?>
			</textarea>
			<?php
		}
	}
	/**
	* Utility: create entry in the log file.
	*/
	private function custom_logs($message){
		$upload_dir = wp_upload_dir();
		if (is_array($message)) {
			$message = json_encode($message);
		}
		if (!file_exists( $upload_dir['basedir'] . '/' . $this->plugin_name . '-logs') ) {
			mkdir( $upload_dir['basedir'] . '/' . $this->plugin_name . '-logs' );
		}
		$time = current_time("d-M-Y H:i:s");
		$ban = "#$time: $message\r\n";
		$file = $upload_dir['basedir'] . '/' . $this->plugin_name . '-logs/' . $this->plugin_name .'-adminlog-' . current_time("Y-m-d") . '.log';
		$open = fopen($file, "a");
		fputs($open, $ban);
		fclose( $open );
	}

	/**
	* GZIPs a file on disk (appending .gz to the name)
	*
	* From http://stackoverflow.com/questions/6073397/how-do-you-create-a-gz-file-using-php
	* Based on function by Kioob at:
	* http://www.php.net/manual/en/function.gzwrite.php#34955
	*
	* @param string $source Path to file that should be compressed
	* @param integer $level GZIP compression level (default: 9)
	* @return string New filename (with .gz appended) if success, or false if operation fails
	*/
	private function gzCompressFile($source, $level = 9){
		$dest = $source . '.gz';
		$mode = 'wb' . $level;
		$error = false;
		if ($fp_out = gzopen($dest, $mode)) {
			if ($fp_in = fopen($source,'rb')) {
				while (!feof($fp_in))
				gzwrite($fp_out, fread($fp_in, 1024 * 512));
				fclose($fp_in);
			} else {
				$error = true;
			}
			gzclose($fp_out);
		} else {
			$error = true;
		}
		if ($error)
		return false;
		else
		return $dest;
	}
}
