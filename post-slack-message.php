<?php
// Post slack message by GET request.
$messenger = new PostSlackMessenger();

?><html><head>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/css/bootstrap.min.css" integrity="sha384-rwoIResjU2yc3z8GV/NPeZWAv56rSmLldC3R/AZzGRnGxQQKnKkoFVhFQhNUwEyJ" crossorigin="anonymous">
    <?php $messenger->the_touch_icon(); ?>
	<script src="https://code.jquery.com/jquery-3.1.1.slim.min.js" integrity="sha384-A7FZj7v+d/sdmMqp/nOQwliLvUsJfDHW+k9Omg/a/EheAdgtzNs3hpfag6Ed950n" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.0/js/tether.min.js" integrity="sha384-DztdAPBWPRXSA/3eYEEUWrWCy7G5KFbe8fFjk5JAIxUYHKkDx6Qin1DkWx51bBrb" crossorigin="anonymous"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/js/bootstrap.min.js" integrity="sha384-vBWWzlZJ8ea9aCX4pEW3rVHjgjt7zpkNpZk+02D9phzyeVkE+jo0ieGizqPLForn" crossorigin="anonymous"></script>
</head><body>
<table class="table table-condensed"><?php
$messenger->post();
?>
</table></body></html>
<?php

/**
 * Controllers here
 *
 * Class PostSlackMessage
 */
class PostSlackMessenger {

	// url with token
	private $webhook_url = '';

	private $touch_icon = '';

	private $silent = false;

	// options to post slack message
	private $payload = array(
		'channel'    => '#random',
		'username'   => 'post-slack-message',
		'text'       => 'ko-ni-chi-wa！',
		'icon_emoji' => ':ghost:',
	);

	public function __construct() {
        $this->receive_query();
    }

	/**
	 *
	 */
	public function the_touch_icon() {
	    if ( isset($this->touch_icon) && !empty($this->touch_icon)) {
            ?>
            <link rel="apple-touch-icon" href="<?php echo $this->touch_icon; ?>" />
		    <?php
        }
    }

	/**
	 * Sets GET values into payloads
	 */
	private function receive_query() {
		foreach ( $_GET as $key => $value ) {
			// Allows empty string to set empty
            switch ( $key ) {
                case 'webhook_url':
	                $this->webhook_url = $_GET[$key];
	                break;
                case 'touch-icon':
                    $this->touch_icon = $_GET[$key];
                    break;
                default:
                    if ( isset($this->payload[$key])) {
                        $this->payload[$key] = $_GET[$key];
                    }
                    break;
			}

		}
	}

	public function post() {
		// sent message formatted as plain text.
		// e.g.) payload={"channel":"#random",...}
		$request_body = 'payload=' . json_encode( $this->payload );

		$ch = curl_init( $this->webhook_url );

		curl_setopt( $ch, CURLOPT_POST, true );
		curl_setopt( $ch, CURLOPT_POSTFIELDS, $request_body );

		curl_setopt( $ch, CURLINFO_HEADER_OUT, true );
        if ( !$this->silent ) { // output
	        ?>
            <tr>
                <td>webhook url</td>
                <td><?php echo $this->webhook_url; ?></td>
            </tr>
            <tr>
                <td>touch-icon</td>
                <td><img src="<?php echo $this->touch_icon; ?>" width="32" height="32">
	                <?php echo $this->touch_icon; ?></td>
            </tr>
	        <?php
	        foreach ( $this->payload as $key => $value ) {
		        ?>
                <tr>
                    <td><?php echo $key; ?></td>
                    <td><?php echo $value; ?></td>
                </tr>
	        <?php } ?>
            <tr>
                <td>Slack response:</td>
                <td style="font-weight: bold;"><?php $content = curl_exec( $ch ); ?></td>
            </tr>
        <?php }
		// @link
		// $header_info = curl_getinfo($ch,CURLINFO_HEADER_OUT); //Where $header_info contains the HTTP Request information
		// echo $header_info;

		curl_close( $ch );
	}
}