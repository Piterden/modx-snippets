    <?php
$templates = array(
    6 => 3, // Music
    5 => 5, // Movie
    4 => 4  // Photo
);
$allFormFields = $hook->getValues();
$parent = $allFormFields['parent'];

$user_id = $modx->user->get('id');
$user_work_by_category = $modx->getCollection('modResource', array(
    'template'  => $templates[$parent],
    'createdby' => $user_id
));
if (count($user_work_by_category) >= 3) {
    $errorMsg = 'Вы не можете загрузить больше 3 работ.';
    $hook->addError('nomination_file',$errorMsg);
    return false;
}
//$modx->log(xPDO::LOG_LEVEL_ERROR,print_r(count($user_work_by_category), true));
//return;
//if ();

// Count IDs
$stmt = $modx->query("SELECT MAX(id) FROM {$modx->getTableName('modResource')}");
$maxId = (integer) $stmt->fetch(PDO::FETCH_COLUMN);
$stmt->closeCursor();
$id = $maxId + 2;
$aliasId = str_pad($id, 6, "0", STR_PAD_LEFT);

$pagetitle = $allFormFields['pagetitle'];
$tags = $allFormFields['tags'];
$filename = $allFormFields['nomination_file'];

//return;
$doc = $modx->newObject('modResource');
$doc->set('createdby', $user_id);
$doc->set('createdon', time());
$doc->set('menuindex',count($modx->getCollection('modResource',array('parent'=>$parent))));
$doc->set('pagetitle', $pagetitle);
$doc->set('parent', $parent);
$doc->set('alias', $aliasId."_".$doc->cleanAlias($pagetitle));
$doc->set('template', $templates[$parent]);
$doc->save();

switch ($parent) {
	case '6': // music
        if ($tv = $modx->getObject('modTemplateVar', array ('name'=>'musicItem'))) {
            $tv->setValue($doc->get('id'), $path . $filename);
            $tv->save();
        }
        if ($tv = $modx->getObject('modTemplateVar', array ('name'=>'musicTags'))) {
            $tv->setValue($doc->get('id'), $tags);
            $tv->save();
        }
		break;
	case '5': // movie
        if ($tv = $modx->getObject('modTemplateVar', array ('name'=>'videoItem'))) {
            $tv->setValue($doc->get('id'), $path . $filename);
            $tv->save();
        }
        if ($tv = $modx->getObject('modTemplateVar', array ('name'=>'videoTags'))) {
            $tv->setValue($doc->get('id'), $tags);
            $tv->save();
        }
		break;
	case '4': // photo
        if ($tv = $modx->getObject('modTemplateVar', array ('name'=>'photoItem'))) {
            $tv->setValue($doc->get('id'), $path . $filename);
            $tv->save();
        }
        if ($tv = $modx->getObject('modTemplateVar', array ('name'=>'photoTags'))) {
            $tv->setValue($doc->get('id'), $tags);
            $tv->save();
        }
		break;
}


return true;
