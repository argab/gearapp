<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace backend\assets;

use yii\web\AssetBundle;
use yii\web\View;
use yii\web\JqueryAsset;
use Yii;


/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class AdminAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        '//cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css',
        '_admin/assets/css/bootstrap.css',
//        '_admin/assets/js/jquery-ui.min.css',
        '_admin/assets/js/jquery-ui/css/no-theme/jquery-ui-1.10.3.custom.min.css',
        '//fonts.googleapis.com/css?family=Noto+Sans:400,700,400italic',
        '_admin/assets/css/font-icons/entypo/css/entypo.css',
        '_admin/assets/css/neon-core.css',
        '_admin/assets/css/neon-theme.css',
        '_admin/assets/css/neon-forms.css',
        '_admin/assets/js/zurb-responsive-tables/responsive-tables.css',
        '_admin/assets/js/jvectormap/jquery-jvectormap-1.2.2.css',
        '_admin/assets/js/rickshaw/rickshaw.min.css',
        '_admin/assets/js/growl/jquery.growl.css',
        'jslib/rateyo/jquery.rateyo.min.css',
//        'jslib/timepicker/jquery.datetimepicker.css',
        '_admin/css/admin.css',
        '_admin/assets/js/pace/pace.css',
    ];

    public $js = [
        '_admin/assets/js/bootstrap.js',
        '_admin/assets/js/gsap/main-gsap.js',
        '_admin/assets/js/joinable.js',
        '_admin/assets/js/resizeable.js',
        '_admin/assets/js/neon-api.js',
        '_admin/assets/js/jvectormap/jquery-jvectormap-1.2.2.min.js',
        '_admin/assets/js/jvectormap/jquery-jvectormap-europe-merc-en.js',
        '_admin/assets/js/jquery.sparkline.min.js',
        '_admin/assets/js/rickshaw/vendor/d3.v3.js',
        '_admin/assets/js/rickshaw/rickshaw.min.js',
        '_admin/assets/js/raphael-min.js',
        '_admin/assets/js/morris.min.js',
        '_admin/assets/js/toastr.js',
        '_admin/assets/js/neon-chat.js',
        '_admin/assets/js/zurb-responsive-tables/responsive-tables.js',
        '_admin/assets/js/neon-custom.js',
        '_admin/assets/js/neon-demo.js',
//        '_admin/assets/js/tinymce4.7.9/tinymce.min.js',
        '_admin/assets/js/cookies.min.js',
        '_admin/assets/js/growl/jquery.growl.js', // http://ksylvest.github.io/jquery-growl/
        '_admin/assets/js/ion/ion.sound.min.js', // http://ionden.com/a/plugins/ion.sound/demo_advanced.html
        'jslib/visible/jquery.visible.min.js', // https://github.com/customd/jquery-visible
        'jslib/rateyo/jquery.rateyo.min.js',
//        'jslib/timepicker/jquery.datetimepicker.full.js',
        '_admin/js/common.js',
        '_admin/js/admin.js',
        '_admin/assets/js/pace/pace.min.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
    ];
    public $jsOptions = ['position' => View::POS_END];

    public function init()
    {
        Yii::$app->view->registerJsFile(fileTouch('/_admin/assets/js/jquery-1.11.0.min.js'), ['position' => yii\web\View::POS_HEAD]);
        Yii::$app->view->registerJsFile(fileTouch('/_admin/assets/js/jquery-ui.min.js'), ['position' => yii\web\View::POS_HEAD]);

        append_timestamps($this->css);
        append_timestamps($this->js);

        parent::init();
    }
}
