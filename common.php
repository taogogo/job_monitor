<?php
function jsonResult($message,$errorCode = 0, $data = array()){
    $result = array(
        'message' => $message,
        'error_code' => $errorCode,
        'data' => $data,
        );
    echo json_encode($result);
    die;
}
function getConfig($configName = 'module'){
    return $moduleConfig = parse_ini_file("{$configName}.ini", true);
}

function getDb(){
    //db
    $DbConnection=mysql_connect(SAE_MYSQL_HOST_M.':'. SAE_MYSQL_PORT, SAE_MYSQL_USER, SAE_MYSQL_PASS);
    mysql_select_db(SAE_MYSQL_DB);
    mysql_query("set names 'utf8'");
    return $DbConnection;
}
function query($sql){
    //echo $sql;
    $Db = getDb();
    $status = mysql_query($sql,$Db);
    return $status;
}
function insert($table,$data){
    $sql = "INSERT INTO `{$table}` ( `".implode('`,`',array_keys($data))."`) VALUES ('".implode("','",$data)."');";
    $status = query($sql);
    if($status){
        $insertId = mysql_insert_id();
        return $insertId;
    }
    return false;
}
function select($table,$where='',$orderby = '',$groupby=''){
    $groupbySql = $groupby == '' ? '' : "group by {$groupby}";
    $orderbySql = $orderby == '' ? '' : "order by {$orderby}";
    $sql = "SELECT * from  `{$table}` WHERE {$where} {$groupbySql} {$orderbySql}";
    $query  = query($sql);
    $result = array();
    while ($row = mysql_fetch_assoc($query)) {
        $result[] = $row;
    }
    return $result;
}
function parseLink($link){
    if(false !== strpos($link,'{yestoday_date}')){
        $link = str_replace('{yestoday_date}',date('Y-m-d',time() - 3600*24),$link);
    }
    return $link;
}
function getClientIp(){
    return $_SERVER["REMOTE_ADDR"];
}
