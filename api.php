<?php
include 'common.php';
$moduleConfig = getConfig('module');
$config = getConfig('config');
//var_dump($moduleConfig);
//获取变量
$key = addslashes($_REQUEST['key']);
if(!isset($moduleConfig[$key])){
    jsonResult('key is not exists', 10);
}
$type = intval($_REQUEST['type']);
//if(!in_array($type, $moduleConfig[$key]['type'])){
if(!isset($config['type_config']['type'][$type])){
    jsonResult('type error', 10);
}
$content = empty($_REQUEST['content']) ? '' : addslashes(trim($_REQUEST['content']));
$data = array('key' => $key, 'type' => $type, 'content' => $content);
$status = insert('log',$data);
if($status){
    jsonResult('insert success',0,array('id' => $status));
}
else{
    jsonResult('insert failed',1);
}
