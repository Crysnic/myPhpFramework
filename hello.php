<?php

$name = $request->get('name', 'World');
$ip = $request->getClientIp();

$response->setContent(sprintf('Hello %s, with ip %s!', htmlspecialchars($name, ENT_QUOTES, 'UTF-8'), $ip));