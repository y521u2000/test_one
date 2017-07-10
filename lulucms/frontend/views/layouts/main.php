<?php
/**
 * Author: lf
 * Blog: https://blog.feehi.com
 * Email: job@feehi.com
 * Created at: 2017-03-15 21:16
 */

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use frontend\assets\AppAsset;
use yii\helpers\Url;
use frontend\widgets\MenuView;

AppAsset::register($this);
if (yii::$app->controller->action->id != 'view' && yii::$app->controller->id != 'page') {
    $this->registerMetaTag(['keywords' => yii::$app->feehi->seo_keywords]);
    $this->registerMetaTag(['description' => yii::$app->feehi->seo_description]);
}
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <title><?= Html::encode($this->title) ?></title>
    
    <?php $this->head() ?>
    
    <meta charset="<?= yii::$app->charset ?>">
    <?= Html::csrfMetaTags() ?>
    <meta http-equiv="X-UA-Compatible" content="IE=10,IE=9,IE=8">
    <meta name="viewport"
          content="width=device-width, initial-scale=1.0, user-scalable=0, minimum-scale=1.0, maximum-scale=1.0">
    <script>
        window._deel = {
            name: '<?=yii::$app->feehi->website_title?>',
            url: '<?=yii::$app->homeUrl?>',
            comment_url: '<?=Url::to(['article/comment'])?>',
            ajaxpager: '',
            commenton: 0,
            roll: [4,]
        }
    </script>
    <style>p.help-block {
            color: red
        }</style>
</head>
<?php $this->beginBody() ?>
<body class="home blog">
<?= $this->render('/widgets/_flash') ?>
<header id="masthead" class="site-header">
    <nav id="top-header">
        <div class="top-nav">
            <div id="user-profile">
            <span class="nav-set">
                <span class="nav-login">
                    <?php
                    if (yii::$app->user->isGuest) {
                        ?>
                        <a href="<?= Url::to(['site/login']) ?>"
                           class="signin-loader"><?= yii::t('frontend', 'Hi, Log in') ?></a>&nbsp; &nbsp;
                    <a href="<?= Url::to(['site/signup']) ?>"
                       class="signup-loader"><?= yii::t('frontend', 'Sign up') ?></a>
                    <?php } else { ?>
                        Welcome, <?= Html::encode(yii::$app->user->identity->username) ?>
                        <a href="<?= Url::to(['site/logout']) ?>"
                           class="signup-loader"><?= yii::t('frontend', 'Log out') ?></a>
                    <?php } ?>
              </span>
            </span>
            </div>
            <div class="menu-container">
                <ul id="menu-page" class="top-menu">
                    <a target="_blank" href="/about"><?= yii::t('frontend', 'About us') ?></a> | <a target="_blank"
                                                                                                    href="/contact"><?= yii::t('frontend', 'Contact us') ?></a>
                </ul>
            </div>
        </div>
    </nav>
    <div id="nav-header" class="">
        <div id="top-menu">
            <div id="top-menu_1">
                <span class="nav-search"><i class="fa fa-search"></i></span>
                <span class="nav-search_1"><i class="fa fa-navicon"></i></span>
                <hgroup class="logo-site" style="margin-top: 10px;">
                    <h1 class="site-title"><a href="<?= yii::$app->homeUrl ?>"><img src="/static/images/logo.png"
                                                                                    alt="<?= yii::$app->feehi->website_title ?>"></a>
                    </h1>
                </hgroup>
                <div id="site-nav-wrap">
                    <nav id="site-nav" class="main-nav">
                        <div>
                            <?= MenuView::widget() ?>
                        </div>
                    </nav>
                </div>
            </div>
        </div>
    </div>
    <?= MenuView::widget([
        'template' => '<nav><ul class="nav_sj" id="nav-search_1">{lis}</ul></nav>',
        'liTemplate' => "<li class='menu-item menu-item-type-custom menu-item-object-custom current-menu-item current_page_item menu-item-home menu-item-{menu_id}'><a href='{url}'>{title}</a>{sub_menu}</li>"
    ]) ?>
</header>

<div id="search-main">
    <div id="searchbar">
        <form id="searchform" action="<?= Url::to('/search') ?>" method="get">
            <input id="s" type="text" name="q" value="<?= Html::encode(yii::$app->request->get('q')) ?>" required=""
                   placeholder="<?= yii::t('frontend', 'Please input keywords') ?>" name="s" value="">
            <button id="searchsubmit" type="submit"><?= yii::t('frontend', 'Search') ?></button>
        </form>
    </div>
    <div id="searchbar">
        <form id="searchform" target="_blank" action="https://www.baidu.com/s" method="get">
            <input type="hidden" name="entry" value="1">
            <input class="swap_value" name="w" placeholder="<?= yii::t('frontend', 'Please input keywords') ?>">
            <button id="searchsubmit" type="submit"><?= yii::t('frontend', 'Baidu') ?></button>
        </form>
    </div>
    <div class="clear"></div>
</div>
<section class="container">
    <div class="speedbar"></div>
    <?= $content ?>
</section>

<div class="branding branding-black">
    <div class="container_f">
        <h2><?= yii::t('frontend', 'Effective,Professional,Conform to SEO') ?></h2><a class="btn btn-lg"
                                                                                      href="http://www.feehi.com/contact"
                                                                                      target="_blank"><?= yii::t('frontend', 'Contact us') ?></a>
    </div>
</div>

<footer class="footer">
    <div class="footer-inner">
        <p>
            <a href="http://www.feehi.com/" title="Feehi CMS">Feehi
                CMS</a> <?= yii::t('frontend', 'Copyright, all rights reserved') ?> © 2010-2016&nbsp;&nbsp;
            <select onchange="location.href=this.options[this.selectedIndex].value;" style="height: 30px">
                <option <?php if (yii::$app->language == 'zh-CN') {
                    echo 'selected';
                } ?> value="<?= Url::to(['site/language', 'lang' => 'zh-CN']) ?>">简体中文
                </option>
                <option <?php if (yii::$app->language == 'en-US') {
                    echo "selected";
                } ?> value="<?= Url::to(['site/language', 'lang' => 'en-US']) ?>">English
                </option>
            </select>
        </p>
        <p>Powered by Feehi CMS · Theme by <a title="飞嗨" href="http://blog.feehi.com">飞嗨</a></p>
    </div>
</footer>
<div class="rollto" style="display: none;">
    <button class="btn btn-inverse" data-type="totop" title="回顶部"><i class="fa fa-arrow-up"></i></button>
</div>

</body>
<?php $this->endBody() ?>
<?php
if (yii::$app->feehi->website_statics_script) {
    echo yii::$app->feehi->website_statics_script;
}
?>
</html>
<?php $this->endPage() ?>
