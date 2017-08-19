<?php

	function send_to_websocket_server($action)
	{
		$token = 'secretKey';
		$ws_http_URL = 'http://localhost:3000';

		$options = array(
			'http' => array(
				'header' =>	"Content-Type: application/json\r\n"."token: ".$token."\r\n",
				'method' => 'POST',
				'content' => json_encode(array('action' => $action), JSON_UNESCAPED_UNICODE)
			)
		);

		$context = stream_context_create($options);
		$result = file_get_contents($ws_http_URL, false, $context);
	}
