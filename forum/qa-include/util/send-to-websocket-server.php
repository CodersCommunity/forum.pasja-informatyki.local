<?php

	function send_to_websocket_server($type, $data)
	{
		$url = 'http://localhost:3000/';
		$options = array(
			'http' => array(
				'header'  => "Content-Type: application/json\r\n",
				'method'  => 'POST',
				'content' => json_encode($data, JSON_UNESCAPED_UNICODE)
			)
		);

		$context = stream_context_create($options);
		$result = file_get_contents($url.$type, false, $context);
	}
