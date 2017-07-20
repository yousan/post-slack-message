<?php
// Post slack message by GET request.
$messenger = new PostSlackMessenger();
$messenger->post();
?><html><head>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/css/bootstrap.min.css" integrity="sha384-rwoIResjU2yc3z8GV/NPeZWAv56rSmLldC3R/AZzGRnGxQQKnKkoFVhFQhNUwEyJ" crossorigin="anonymous">
    <?php $messenger->the_touch_icon(); ?>
	<script src="https://code.jquery.com/jquery-3.1.1.slim.min.js" integrity="sha384-A7FZj7v+d/sdmMqp/nOQwliLvUsJfDHW+k9Omg/a/EheAdgtzNs3hpfag6Ed950n" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.0/js/tether.min.js" integrity="sha384-DztdAPBWPRXSA/3eYEEUWrWCy7G5KFbe8fFjk5JAIxUYHKkDx6Qin1DkWx51bBrb" crossorigin="anonymous"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/js/bootstrap.min.js" integrity="sha384-vBWWzlZJ8ea9aCX4pEW3rVHjgjt7zpkNpZk+02D9phzyeVkE+jo0ieGizqPLForn" crossorigin="anonymous"></script>
    <style>
        /** {*/
            /*font-size:15px;*/
        /*}*/
    </style>
</head><body>
<form action="" method="get">
<div class="table-responsive">
    <table class="table table-condensed">
        <thead>
        <tr>
            <th></th>
            <th  class="col-xs-3 col-ms-3 col-md-4 col-lg-4"></th>
        </tr>
        </thead>
        <tbody>
        <?php $messenger->the_results(); ?>
        </tbody>
    </table></div>
    <button type="submit">Submit</button>
</form>
</body></html>
<?php

/**
 * Controllers here
 *
 * Class PostSlackMessage
 */
class PostSlackMessenger {

    private $curl_handler = null;

    private $webhook_url = '';

	private $touch_icon = '';

	private $slack_response = false;

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
	 * Show icon-url link tag.
	 */
	public function the_touch_icon() {
	    if ( isset($this->touch_icon) && !empty($this->touch_icon)) {
            ?>
            <link rel="apple-touch-icon" href="<?php echo $this->touch_icon; ?>">
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
                case 'touch_icon':
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

	public function the_results() {
        ?>
        <tr>
            <td><label for="webhook_url">webhook url</label></td>
            <td><input type="text" name="webhook_url" id="webhook_url"
                       value="<?php echo $this->webhook_url; ?>"></td>
        </tr>
        <tr>
            <td><label for="touch_icon">touch-icon</label></td>
            <td><input type="text" name="touch_icon" id="touch_icon"
                       value="<?php echo $this->touch_icon; ?>">
                <img src="<?php echo $this->touch_icon; ?>" width="32" height="32">
                <img class="preset" src="https://a.slack-edge.com/bc86/marketing/img/meta/app-256.png" width="32" height="32">

            </td>
        </tr>
        <?php foreach ( $this->payload as $key => $value ) { ?>
            <tr>
                <td><label for="<?php echo $key; ?>"><?php echo $key; ?></label></td>
                <td><input type="text" name="<?php echo $key; ?>" id="<?php echo $key; ?>"
                           value="<?php echo $value; ?>"></td>
            </tr>
        <?php } ?>
        <tr>
            <td>Slack response:</td>
            <td style="font-weight: bold;"><?php $this->slack_response;  ?></td>
        </tr>
        <?php
    }

	public function post() {

		// @link
		// $header_info = curl_getinfo($ch,CURLINFO_HEADER_OUT); //Where $header_info contains the HTTP Request information
		// echo $header_info;
		// sent message formatted as plain text.
		// e.g.) payload={"channel":"#random",...}
		$request_body = 'payload=' . json_encode( $this->payload );

		$this->curl_handler = curl_init( $this->webhook_url );

		curl_setopt( $this->curl_handler, CURLOPT_POST, true );
		curl_setopt( $this->curl_handler, CURLOPT_POSTFIELDS, $request_body );

		curl_setopt( $this->curl_handler, CURLINFO_HEADER_OUT, true );
		$this->slack_response = curl_exec( $this->curl_handler );
		curl_close( $this->curl_handler );
	}
}