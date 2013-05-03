<?php
error_reporting(9999);
include 'common.php';
$config = getConfig('config');
$ipWhiteList = isset($config['ip_white_list']['ip']) ? $config['ip_white_list']['ip'] : array();
$date = isset($_GET['date'] ) ? addslashes($_GET['date']) : date('Y-m-d');
$ip=getClientIp();
if(!in_array($ip,$ipWhiteList)){
    header('myip:'.urlencode($ip));
    if( empty($_SERVER['PHP_AUTH_USER'])
        || empty($_SERVER['PHP_AUTH_PW'] )
        || $_SERVER['PHP_AUTH_USER'] != $config['manage_account']['username']
        || $_SERVER['PHP_AUTH_PW'] != $config['manage_account']['password']
      )
    {
        header('HTTP/1.1 401 Unauthorized');
        header('WWW-Authenticate: Basic realm="CS"');
        die();
    }
    //die('404');
}
?>
<title>[<?php echo $date;?>]任务执行日志</title>
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<style>
body{background:url('http://ww2.sinaimg.cn/large/736a8bf2jw1e3stijyyhtg.gif')}
.notice{color:red;}
fieldset{ border-radius:0.4em;opacity:0.7;background:#fff}
a:hover{color:#B42518}
#apDiv {
    position:fixed;
    width:200px;
    text-indent:20px;
    padding:3px;
    z-index:1;
    -moz-border-radius: 5px;
    -webkit-border-radius: 5px;
    border:double gray 2px;
    background-color:#D5D5D5;
    display:none;
}

</style>
<script type="text/javascript">
function showMessage(yourmsg){
    if(yourmsg == ''){
    return false;
    }
    var posx=event.clientX;
    var posy=event.clientY;
    var mydiv=document.getElementById('apDiv');
    mydiv.style.display="block";
    mydiv.style.left=posx+'px';
    mydiv.style.top=posy+15+'px';
    mydiv.innerHTML=yourmsg;   
}
function hiddenmsg(){
    document.getElementById('apDiv').style.display="none";
}
</script>
<div id="apDiv"></div>
<fieldset id="fieldset"> 
<legend> <?php echo date('Y-m-d')?>执行日志  </legend> 
<?php
$result = select('log', "create_time between '{$date} 00:00:00'  and '{$date} 23:59:59' ",'id desc');
$moduleConfig = getConfig('module');

foreach($result as $value){
    $running[$value['key']][$value['type']] = 1;
?>
<p style="color:<?php echo $moduleConfig[$value['key']]['color'];?>" onMouseOver="showMessage('<?php echo htmlentities($value['content']);?>');" onMouseOut="hiddenmsg();" >
<?php echo $value['create_time'];?>: 任务[ <?php echo $moduleConfig[$value['key']]['name'];?> ] <span style="color:<?php echo $config['type_config']['color'][$value['type']];?>"><?php echo $config['type_config']['type'][$value['type']];?></span> </p>

<?php } ?>
<p class="notice">
<?php foreach($moduleConfig as $key => $value){?>
<?php if($value['time_monitor']&&!isset($running[$key][1]) && time() > strtotime($value['approximate_start_time'])) {?>
[ <?php echo $value['name'];?> ]应该在<?php echo $value['approximate_start_time'];?>开始执行，但是竟然没有开始执行！！  <br />
<?php  }?>
<?php if($value['time_monitor']&&!isset($running[$key][2]) && time() > strtotime($value['approximate_stop_time']) ) {?>
[ <?php echo $value['name'];?> ]应该在<?php echo $value['approximate_stop_time'];?>执行结束，但是竟然没有执行完成！！  <br />
<?php } ?>
<?php }?>
</p>
</fieldset>
<?php
$links = getConfig('links');
?>
<?php foreach($links as $value){?>
<fieldset id="fieldset"> 
<legend> <?php echo $value['name']; ?></legend> 

<?php foreach($value['link'] as $linkString){ $linkArray = explode('|',$linkString);?>
[<a target="_blank" href="<?php echo parseLink($linkArray[1]);?>" ><?php echo $linkArray[0];?></a>] 
<?php }?>
</fieldset>
<?php }?>
