<?php
namespace ez\core;

/**
 * 框架控制器
 * 
 * @author lxj
 */
class Controller
{
    /**
	 * 模板文件路径
	 */
	public $templateDir;
	
	/**
	 * 模板后缀名
	 */
	public $suffix;
    
    /**
     * 模板变量
     */
    public $templateVariable = [];
    
    
    /**
     * 构造函数
     * 
     * @access public
     */
    public function __construct()
    {
        $this->suffix = config('templateSuffix');
		$this->templateDir = '../view/' . strtolower(CONTROLLER_NAME) . '/';
    }
    
    /**
     * 添加模板变量
     * 
     * @param mixed $name 变量名/模板变量键值对
     * @param mixed $value 变量值
     * @access public
     */
    public function assign($name, $value = '')
    {
        if (is_array($name)) {
            $this->templateVariable = array_merge($this->templateVariable, $name);
            return;
        } elseif (is_string($name) && !empty($name)) {
            $this->templateVariable = array_merge($this->templateVariable, ["$name" => $value]);
            return;
        }
    }
    
    /**
     * 显示模板
     * 
     * @param string $view 模板名称，小写，为空则根据方法名自动定位
     * @param array $data 传递到模板的变量数组
     * 
     * @access public
     */
    public function display($view = '', $data = [])
    {
        /* 未指定模板，在默认位置寻找模板加载 */
        if (is_array($view)) {
            $this->templateVariable = array_merge($this->templateVariable, $view);
            extract($this->templateVariable);
            $template = '../view/' . strtolower(CONTROLLER_NAME) . '/' . strtolower(ACTION_NAME) . '.php';
            if(!is_file($template)) {
                throw new \Exception('template not exists');
            }
            include $template;
            
        } else if (is_string($view)) {
            if (is_array($data) && !empty($data)) {
                $this->templateVariable = array_merge($this->templateVariable, $data);
                extract($this->templateVariable);
            }
            if (empty($view)) {
                $view = ACTION_NAME;
            }
            if (is_file($view)) {
                include $view;
            } else {
                $template = '../view/' . strtolower(CONTROLLER_NAME) . '/' . strtolower($view) . '.php';
                if(!is_file($template)) {
                    throw new \Exception('template not exists');
                }
                include $template;
            }
        }
    }
    
    /**
     * Action跳转(URL重定向)
     * 
     * @param string $url 跳转的URL表达式
     * @param array $params 其它URL参数
     * @param integer $delay 延时跳转的时间 单位为秒
     * @param string $msg 跳转提示信息
     * @return void
     * 
     * @access public
     */
    public function redirect($url, $params = [], $delay = 0, $msg='')
    {
        $url = Route::createUrl($url, $params);
        Route::redirect($url, $delay, $msg);
    }
    
    /**
     * 成功跳转
     * 
     * @access public
     */
    public function success($msg = '', $delay = 1) {
        $url = filter_input(INPUT_SERVER, 'HTTP_REFERER');
        Route::redirect($url, $delay, $msg);
    }
    
    /**
     * 失败跳转
     * 
     * @access public
     */
    public function error($msg = "", $delay = 5) {
        $html = '<!DOCTYPE html PUBLIC "-//WAPFORUM//DTD XHTML Mobile 1.0//EN" "http://www.wapforum.org/DTD/xhtml-mobile10.dtd">
                <html xmlns="http://www.w3.org/1999/xhtml">
                <head>
                <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
                <meta name="viewport" content="width=device-width,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no" />
                <meta name="robots" content="all" />
                <title>错误</title>
                <script>
                    function Jump() { window.location.href = "javascript:history.back(-1);"; }
                    document.onload = setTimeout("Jump()" , ' . $delay . ' * 1000);
                </script>
                </head>
                <body>';
        $html .= "错误：$msg<br>$delay"."秒后跳转，<a href='javascript:history.back(-1);'>立即返回</a>";
        $html .= "</body></html>";
        die($html);
    }
    
    /**
     * ajax返回
     * 
     * @param array 数据数组
     * @param string JSON、STRING
     */
    public function ajaxReturn($data, $type = 'JSON')
    {
        switch (strtoupper($type)) {
            case 'JSON':
                is_array($data) && die(json_encode($data, JSON_UNESCAPED_UNICODE));
                break;
            case 'STRING':
                is_string($data) && die($data);
                break;
        }
    }
    
}



