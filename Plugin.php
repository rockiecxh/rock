<?php
/**
 * 魔改看板娘
 *
 * @package Rock
 * @author Rockie
 * @version 0.1.0
 * @link
 */

class Rock_Plugin implements Typecho_Plugin_Interface
{
    /**
     * 激活插件方法,如果激活失败,直接抛出异常
     *
     * @access public
     * @return void
     * @throws Typecho_Plugin_Exception
     */
    public static function activate() {
        Typecho_Plugin::factory('Widget_Archive')->header = array('Rock_Plugin', 'header');
        Typecho_Plugin::factory('Widget_Archive')->footer = array('Rock_Plugin', 'footer');
    }

    /**
     * 禁用插件方法,如果禁用失败,直接抛出异常
     *
     * @static
     * @access public
     * @return void
     * @throws Typecho_Plugin_Exception
     */
    public static function deactivate() {

    }

    /**
     * 获取插件配置面板
     *
     * @access public
     * @param Typecho_Widget_Helper_Form $form 配置面板
     * @return void
     */
    public static function config(Typecho_Widget_Helper_Form $form) {

        // 选择外链模型API
        $custom_live2d_api = new Typecho_Widget_Helper_Form_Element_Text('custom_live2d_api', NULL, NULL, _t('选择外链模型API'), _t('在这里填入一个live2d模型API的地址，可供使用外链模型，不填则使用默认API'));
        $form -> addInput($custom_live2d_api);

        // 自定义宽高
        $custom_width = new Typecho_Widget_Helper_Form_Element_Text('custom_width', NULL, NULL, _t('自定义宽度'), _t('在这里填入自定义宽度，部分模型需要修改'));
        $form -> addInput($custom_width);

        $custom_height = new Typecho_Widget_Helper_Form_Element_Text('custom_height', NULL, NULL, _t('自定义高度'), _t('在这里填入自定义高度，部分模型需要修改'));
        $form -> addInput($custom_height);

    }

    /**
     * 个人用户的配置面板
     *
     * @access public
     * @param Typecho_Widget_Helper_Form $form
     * @return void
     */
    public static function personalConfig(Typecho_Widget_Helper_Form $form){

    }


    /**
     * 页头输出相关代码
     *
     * @access public
     * @param unknown header
     * @return unknown
     */
    public static function header() {
        if(!self::isMobile()) {
            $Path = Helper::options()->pluginUrl . '/Rock/';
            // echo '<link rel="stylesheet" type="text/css" href="' . $Path . 'css/szRock.css" />';
            echo '<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/font-awesome/css/font-awesome.min.css">';
            echo '<script src="https://cdn.jsdelivr.net/npm/jquery/dist/jquery.min.js"></script>';
            echo '<script type="text/javascript" src="' . $Path . 'autoload.js"></script>';
        }
    }


    /**
     * 页脚输出相关代码
     *
     * @access public
     * @param unknown footer
     * @return unknown
     */
    public static function footer() {
        $width  = Typecho_Widget::widget('Widget_Options') -> Plugin('Rock') -> custom_width;
        $height = Typecho_Widget::widget('Widget_Options') -> Plugin('Rock') -> custom_height;
        $width = ($width == null || $width == 0) ? 300 : $width;
        $height = ($height == null || $height == 0) ? 300 : $height;
        $custom_live2d_api = Typecho_Widget::widget('Widget_Options') -> Plugin('Rock') -> custom_live2d_api;
        $live2d_api = $custom_live2d_api == null ? "https://live2d.fghrsh.net/api" : $custom_live2d_api;
        if(!self::isMobile()) {
            echo '<script>$(window).on("load", function() {
            console.log( "' . $live2d_api . ' ")
            initWidget(live2d_path + "waifu-tips.json", "' . $live2d_api . '", ' . $width . ',' . $height .');
        })</script>';
        }
    }


    /**
     * 移动设备识别
     *
     * @return boolean
     */
    private static function isMobile(){
        $user_agent = $_SERVER['HTTP_USER_AGENT'];
        $mobile_browser = Array(
            "mqqbrowser", // 手机QQ浏览器
            "opera mobi", // 手机opera
            "juc","iuc", 'ucbrowser', // uc浏览器
            "fennec","ios","applewebKit/420","applewebkit/525","applewebkit/532","ipad","iphone","ipaq","ipod",
            "iemobile", "windows ce", // windows phone
            "240x320","480x640","acer","android","anywhereyougo.com","asus","audio","blackberry",
            "blazer","coolpad" ,"dopod", "etouch", "hitachi","htc","huawei", "jbrowser", "lenovo",
            "lg","lg-","lge-","lge", "mobi","moto","nokia","phone","samsung","sony",
            "symbian","tablet","tianyu","wap","xda","xde","zte"
        );
        $is_mobile = false;
        foreach ($mobile_browser as $device) {
            if (stristr($user_agent, $device)) {
                $is_mobile = true;
                break;
            }
        }
        return $is_mobile;
    }
}
