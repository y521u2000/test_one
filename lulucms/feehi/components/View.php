<?php
/**
 * Author: lf
 * Blog: https://blog.feehi.com
 * Email: job@feehi.com
 * Created at: 2016-12-25 21:58
 */

namespace feehi\components;

use feehi\assets\JqueryAsset;

class View extends \yii\web\View
{

    public function registerJs($js, $position = self::POS_READY, $key = null)
    {
        var_dump($js);
        $key = $key ? : md5($js);
        $this->js[$position][$key] = $js;
        var_dump($key);
        if ($position === self::POS_READY || $position === self::POS_LOAD) {
            JqueryAsset::register($this);
        }
    }

}