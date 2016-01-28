<?php
$allFormFields = $hook->getValues();
$res_id = $allFormFields['res_id'];
//$modx->log(xPDO::LOG_LEVEL_ERROR,print_r($res_id, true));
//return true;

$work = $modx->getObject('modResource', $res_id);
$work->set('published', '0');
$work->set('hidemenu', '1');
if ($work->save()) {
    return true;
} else {
    $errMsg = 'Произошла ошибка при запрете на публикацию работы';
    $hook->addError('error_message', $errMsg);
}
