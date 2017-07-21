<?php
// Post slack message by GET request.
$messenger = new PostSlackMessenger();
$messenger->post();
?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>Slack Post Messenger</title>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/css/bootstrap.min.css"
          integrity="sha384-rwoIResjU2yc3z8GV/NPeZWAv56rSmLldC3R/AZzGRnGxQQKnKkoFVhFQhNUwEyJ" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.1.1.slim.min.js"
            integrity="sha384-A7FZj7v+d/sdmMqp/nOQwliLvUsJfDHW+k9Omg/a/EheAdgtzNs3hpfag6Ed950n"
            crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.0/js/tether.min.js"
            integrity="sha384-DztdAPBWPRXSA/3eYEEUWrWCy7G5KFbe8fFjk5JAIxUYHKkDx6Qin1DkWx51bBrb"
            crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/js/bootstrap.min.js"
            integrity="sha384-vBWWzlZJ8ea9aCX4pEW3rVHjgjt7zpkNpZk+02D9phzyeVkE+jo0ieGizqPLForn"
            crossorigin="anonymous"></script>
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>

    <!-- FontAwesome -->
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
	<?php $messenger->the_touch_icon(); ?>
    <style>
        .fa-info-circle .webhook_url {
            color: blue;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Slack Post Messenger</h2>
    <?php $messenger->the_results(); ?>
</div>
</body>
<footer>
    <script src="js/scripts.js"></script>
    <script>
        $(function() {
            $('img.preset').on('click', function() {
                $('#touch_icon').val($(this).attr('src'));
            });
        });
    </script>
</footer>
</html>
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
		'text'       => 'ko-ni-chi-wa！',
		'channel'    => '#bot',
		'username'   => 'post-slack-message',
		'icon_emoji' => ':ghost:',
	);

	public function __construct() {
		$this->receive_query();
	}

	/**
	 * Show HTML title.
     * Displays the text message for homescreen title.
     */
	public function the_html_title() {
        if ( !empty($_GET['text']) ) {
            echo $_GET['text'];
        } else {
            echo 'Slack Post Message';
        }
    }

	/**
	 * Show icon-url link tag.
	 */
	public function the_touch_icon() {
		if ( isset( $this->touch_icon ) && ! empty( $this->touch_icon ) ) {
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
					$this->webhook_url = $_GET[ $key ];
					break;
				case 'touch_icon':
					$this->touch_icon = $_GET[ $key ];
					break;
				default:
					if ( isset( $this->payload[ $key ] ) ) {
						$this->payload[ $key ] = $_GET[ $key ];
					}
					break;
			}

		}
	}

	public function the_results() {
		?>
        <form action="" method="get">
            <?php foreach ( $this->payload as $key => $value ) { ?>
                <label for="<?php echo $key; ?>"><?php echo $key; ?></label>
                <input type="text" name="<?php echo $key; ?>" id="<?php echo $key; ?>"
                       class="form-control" value="<?php echo $value; ?>">
            <?php } ?>
            <div class="form-group">
                <label for="touch_icon">Icon for homescreen</label>
                <img src="<?php echo $this->touch_icon; ?>" width="32" height="32">
                <input type="text" name="touch_icon" id="touch_icon"
                       class="form-control" value="<?php echo $this->touch_icon; ?>">
            </div>

            <div class="form-group">
                Presets
                <span class="glyphicon glyphicon-envelope"></span>
                <img class="preset" src="https://a.slack-edge.com/bc86/marketing/img/meta/app-256.png" height="32" width="32">
                <img class="preset" src="https://2017.l2tp.org/wp-content/uploads/2017/07/359uanTp_200x200.jpg" height="32" width="32">
            </div>
            <div class="form-group">
                <label for="webhook_url">webhook url</label>
                <a href="https://my.slack.com/services/new/incoming-webhook/"><span class="fa fa-info-circle webhook_url" aria-hidden="true"></span></a>

                <input type="text" name="webhook_url" id="webhook_url"
                       class="form-control col-xs-12" value="<?php echo $this->webhook_url; ?>">
            </div>
            <button class="btn btn-primary" type="submit">Submit</button>
        </form>
        <label for="slack_response">Slack response:</label>
        <textarea id="slack_response" class="form-control"><?php echo $this->slack_response; ?></textarea>
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
		curl_setopt( $this->curl_handler, CURLOPT_RETURNTRANSFER, true );

		curl_setopt( $this->curl_handler, CURLINFO_HEADER_OUT, true );
		$this->slack_response = curl_exec( $this->curl_handler );
		curl_close( $this->curl_handler );
	}
}