<?php
if ($modx->event->name != 'OnPageNotFound') {return false;}
$alias = $modx->context->getOption('request_param_alias', 'q');
if (!isset($_REQUEST[$alias])) {return false;}

$request = $_REQUEST[$alias];
$tmp = explode('/', $request);
if ($tmp[0] == 'user' && count($tmp) >= 2) {
	$id = str_replace(array('.html', '.php'), '', $tmp[1]);
	if ($tmp[1] != $id || (isset($tmp[2]) && $tmp[2] == '')) {
		$modx->sendRedirect($tmp[0] . '/' . $id);
	}

	if ($user = $modx->getObject('modUser', array('id' => $id))) {
        if ($user->isMember('Конкурсанты')) {
            //echo '<pre>';
    		$fields = array_merge($user->toArray(), $user->Profile->toArray());
    		$modx->setPlaceholders($fields, 'us_');
    		$modx->sendForward(498);

        }
	}
}
