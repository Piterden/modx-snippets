<?php
$user_id = $modx->user->get('id');
$allFormFields = $hook->getValues();
$res_id = $allFormFields['res_id'];
//$modx->log(xPDO::LOG_LEVEL_ERROR,print_r($res_id, true));
//return true;

$work = $modx->getObject('modResource', $res_id);
$created_by = $work->get('createdby');
if ($user_id == $created_by) {
    $work->set('deleted', 1);
    $work->set('deletedby', $user_id);
    if ($work->save() == false) {
        $errorMsg = 'Во время помещения работы в корзину возникла ошибка!';
        $hook->addError('error_message',$errorMsg);
    } else {
        return true;
    }
} else {
    $errorMsg = 'Вы не можете поместить в корзину чужую работу.';
    $hook->addError('error_message',$errorMsg);
    return false;
}
