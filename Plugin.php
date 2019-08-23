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
        $form->addInput($custom_live2d_api);

        // 自定义宽高
        $custom_width = new Typecho_Widget_Helper_Form_Element_Text('custom_width', NULL, NULL, _t('自定义宽度'), _t('在这里填入自定义宽度，部分模型需要修改'));
        $form->addInput($custom_width);

        $custom_height = new Typecho_Widget_Helper_Form_Element_Text('custom_height', NULL, NULL, _t('自定义高度'), _t('在这里填入自定义高度，部分模型需要修改'));
        $form->addInput($custom_height);

        $exclude_jquery = new Typecho_Widget_Helper_Form_Element_Checkbox('exclude_jquery', array('exclude_jquery' => '禁止加载jQuery'), false, _t('Jquery设置'), _t('插件需要加载jQuery，如果主题模板已经引用加载JQuery，则可以勾选。'));
        $form->addInput($exclude_jquery);

        $enable_cdn = new Typecho_Widget_Helper_Form_Element_Checkbox('enable_cdn', array('enable_cdn' => '启用CDN加载部分js库'), false, _t('CDN开启'), _t('插件需要通过CDN加载部分js库，默认从本地文件读取如jQuery等文件。'));
        $form->addInput($enable_cdn);
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
            $plugin_path = Helper::options()->pluginUrl . '/Rock/';
            $options = Helper::options()->plugin('Rock');
            // echo '<link rel="stylesheet" type="text/css" href="' . $Path . 'css/szRock.css" />';
            if ($options->enable_cdn) {
                echo '<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/font-awesome/css/font-awesome.min.css" />';
            } else {
                echo '<link rel="stylesheet" type="text/css" href="' . $plugin_path . 'css/font-awesome.min.css" />';
            }
            echo '<link rel="stylesheet" type="text/css" href="' . $plugin_path . 'css/waifu.css" />';
            if (!$options->exclude_jquery) {
                echo '<script type="text/javascript" src="' . $plugin_path . 'js/jquery.min.js"></script>';
//                echo '<script src="https://cdn.jsdelivr.net/npm/jquery/dist/jquery.min.js"></script>';
            }
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
            $plugin_path = Helper::options()->pluginUrl . '/Rock/';
            echo '<script type="text/javascript" src="' . $plugin_path . 'js/live2d.min.js"></script>';
            echo '<script type="text/javascript" src="' . $plugin_path . 'js/waifu-tips.js"></script>';
            echo '<script>$(window).on("load", function() {
            console.log( "' . $live2d_api . ' ")
            initWidget("' . $plugin_path .'waifu-tips.json", "' . $live2d_api . '", ' . $width . ',' . $height .');
        });
        console.log(`
        く__,.ヘヽ.        /  ,ー､ 〉
               ＼ \', !-─‐-i  /  /´
               ／｀ｰ\'       L/／｀ヽ､
             /   ／,   /|   ,   ,       \',
           ｲ   / /-‐/  ｉ  L_ ﾊ ヽ!   i
            ﾚ ﾍ 7ｲ｀ﾄ   ﾚ\'ｧ-ﾄ､!ハ|   |
              !,/7 \'0\'     ´0iソ|    |
              |.从"    _     ,,,, / |./    |
              ﾚ\'| i＞.､,,__  _,.イ /   .i   |
                ﾚ\'| | / k_７_/ﾚ\'ヽ,  ﾊ.  |
                  | |/i 〈|/   i  ,.ﾍ |  i  |
                 .|/ /  ｉ：    ﾍ!    ＼  |
                  kヽ>､ﾊ    _,.ﾍ､    /､!
                  !\'〈//｀Ｔ´\', ＼ ｀\'7\'ｰr\'
                  ﾚ\'ヽL__|___i,___,ンﾚ|ノ
                      ﾄ-,/  |___./
                      \'ｰ\'    !_,.:
        `);

        </script>';
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
