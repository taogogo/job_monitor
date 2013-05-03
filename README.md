job_monitor
===========


##说明
用来查看记录任务状态、报警信息的工具,支持手机浏览器访问。
![截图演示](http://ww4.sinaimg.cn/large/736a8bf2jw1e4b7p5geijj20fp0d3myx.jpg)


##运行环境
目前它只能跑在SAE上

##安装说明
在sae新建应用，将代码提交到应用，然后在mysql内执行如下建表语句：
```
CREATE TABLE IF NOT EXISTS `log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `key` varchar(64) NOT NULL COMMENT '任务标示',
  `type` tinyint(3) unsigned NOT NULL COMMENT '性质，1，开始，2结束',
  `content` text NOT NULL COMMENT '日志内容',
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '日志生成时间',
  PRIMARY KEY (`id`),
  KEY `create_time` (`create_time`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=148 ;
```

##使用说明
###常规设置
在config.ini内设置查看白名单ip和管理密码，不在ip白名单的用户需要输入管理密码才能进入查看页面。在此文件内也可以设定type类型（在调用接口的时候会传递type参数）
###任务设置

在module.ini内设置任务，比如默认为：
```
[flush_log_day_merge]
name="三方日志融合脚本"
time_monitor=On
approximate_start_time="14:00"
approximate_stop_time="16:00"
color="#00"

[mq_accumulation]
name="系统MQ队列堆积"
time_monitor=Off
color="red"
```

###调用接口

任务执行开始时调用

http://项目地址/api.php?key=flush_log_day_merge&type=1&content=开始

结束时调用

http://项目地址/api.php?key=flush_log_day_merge&type=2&content=结束


###查看任务
在http://项目地址/就可以看到相应的调用信息。

如果time_monitor=On且设置了approximate_start_time和approximate_stop_time参数，在设置的时间内没有调用接口，在查看页面就会有红色警告显示。
###定制导航
在links.ini内设置查看页面的展示链接，定制你的导航页面。

