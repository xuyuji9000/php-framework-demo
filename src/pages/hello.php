<?php 
$input = $request->get('name', 'World');

echo(sprintf('Hello %s', htmlspecialchars($input, ENT_QUOTES, 'UTF-8')));
