<?php

	function send_to_websocket_server($action)
	{
		$options = array(
			'http' => array(
				'header' =>	"Content-Type: application/json\r\n"."token: ".QA_WS_TOKEN."\r\n",
				'method' => 'POST',
				'content' => json_encode(array('action' => $action), JSON_UNESCAPED_UNICODE)
			)
		);

		$context = stream_context_create($options);
		$result = file_get_contents(QA_WS_URL, false, $context);
	}
