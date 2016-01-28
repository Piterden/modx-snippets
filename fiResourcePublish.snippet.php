<?php
$allFormFields = $hook->getValues();
$res_id = $allFormFields['res_id'];
//$modx->log(xPDO::LOG_LEVEL_ERROR,print_r($res_id, true));
//return false;

$work = $modx->getObject('modResource', $res_id);
if ($work->get('published') == '0' && $work->get('hidemenu') == '0') {
    $work->set('published', '1');
    $work->set('publishedon', time());
    //$work->set('unpub_date', time() + 48*60*60);
    $work->set('hidemenu', '1');
    if ($work->save()) {
        $modx->getCacheManager()->clearCache();
        return true;
    } else {
        $errMsg = 'Произошла ошибка при публикации работы';
        $hook->addError('error_message', $errMsg);
    }
} elseif ($work->get('published') == '1' && $work->get('hidemenu') == '1') {
    $errMsg = 'Работа была опубликована ранее';
    $hook->addError('error_message', $errMsg);
} elseif ($work->get('published') == '0' && $work->get('hidemenu') == '1') {
    $errMsg = 'Работа была запрещена для публикации ранее';
    $hook->addError('error_message', $errMsg);
}
