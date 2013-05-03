job_monitor
===========


##说明
用来查看记录任务状态、报警信息的工具,支持手机浏览器访问。
![截图演示](http://ww4.sinaimg.cn/large/736a8bf2jw1e4b7p5geijj20fp0d3myx.jpg)


##运行环境
目前它只能跑在SAE上

##安装说明
在sae新建应用，将代码提交到应用，然后在mysql内执行如下建表语句：

CREATE TABLE IF NOT EXISTS `log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `key` varchar(64) NOT NULL COMMENT '任务标示',
  `type` tinyint(3) unsigned NOT NULL COMMENT '性质，1，开始，2结束',
  `content` text NOT NULL COMMENT '日志内容',
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '日志生成时间',
  PRIMARY KEY (`id`),
  KEY `create_time` (`create_time`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=148 ;
