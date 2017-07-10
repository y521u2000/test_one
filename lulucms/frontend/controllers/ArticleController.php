<?php
/**
 * Author: lf
 * Blog: https://blog.feehi.com
 * Email: job@feehi.com
 * Created at: 2016-04-02 22:48
 */

namespace frontend\controllers;

use yii;
use yii\web\Controller;
use frontend\models\Article;
use common\models\Category;
use frontend\models\Comment;
use yii\data\ActiveDataProvider;
use common\models\ArticleMetaLike;
use yii\web\NotFoundHttpException;

class ArticleController extends Controller
{


    public function behaviors()
    {
        return [
            [
                'class' => 'yii\filters\HttpCache',
                'only' => ['view'],
                'lastModified' => function ($action, $params) {
                    //var_dump($params);
                    //var_dump($action);
                    $article = Article::findOne(['id' => yii::$app->request->get('id')]);
                    //var_dump($article);
                    //return $article->updated_at;
                    return time();
                },
            ],
        ];
    }

    public function actionIndex($cat = '')
    {
        //var_dump(123);
        if ($cat == '') {
            $cat = yii::$app->getRequest()->getPathInfo();
        }
        $where = ['type' => Article::ARTICLE, 'status' => Article::ARTICLE_PUBLISHED];
        if ($cat != '' && $cat != 'index') {
            if ($cat == yii::t('app', 'uncategoried')) {
                $where['cid'] = 0;
            } else {
                if (! $category = Category::findOne(['name' => $cat])) {
                    throw new NotFoundHttpException('None category named ' . $cat);
                }
                $where['cid'] = $category['id'];
            }
        }
        $query = Article::find()->select([])->where($where);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'sort' => SORT_ASC,
                    'created_at' => SORT_DESC,
                    'id' => SORT_DESC,
                ]
            ]
        ]);
        return $this->render('index', [
            'dataProvider' => $dataProvider
        ]);
    }

    public function actionView($id)
    {
//         var_dump($_POST);
//         var_dump($_GET);
//         var_dump($id);
//         echo "<hr><hr><hr><hr><hr><hr><hr>";
        
        $model = Article::findOne(['id' => $id]);
        Article::updateAllCounters(['scan_count' => 1], ['id' => $id]);
        $prev = Article::find()
        ->where(['cid' => $model->cid])
        ->andWhere(['>', 'id', $id])
        ->orderBy("sort asc,created_at asc,id desc")
        ->limit(1)
        ->createCommand()
        ->getRawSql();
        //var_dump($prev);
        $prev = Article::find()
            ->where(['cid' => $model->cid])
            ->andWhere(['>', 'id', $id])
            ->orderBy("sort asc,created_at asc,id desc")
            ->limit(1)
            ->one();
        $next = Article::find()
            ->where(['cid' => $model->cid])
            ->andWhere(['<', 'id', $id])
            ->orderBy("sort desc,created_at desc,id asc")
            ->limit(1)
            ->one();//->createCommand()->getRawSql();
        
            
        $commentModel = new Comment();
        $commentList = $commentModel->getCommentByAid($id);
        
        $recommends = Article::find()
        ->where(['type' => Article::ARTICLE, 'status' => Article::ARTICLE_PUBLISHED])
        ->andWhere(['<>', 'thumb', ''])
        ->orderBy("rand()")
        ->limit(8)->createCommand()->getRawSql();
        echo $recommends;
        
        $recommends = Article::find()
            ->where(['type' => Article::ARTICLE, 'status' => Article::ARTICLE_PUBLISHED])
            ->andWhere(['<>', 'thumb', ''])
            ->orderBy("rand()")
            ->limit(8)
            ->all();
        
        $likeModel = new ArticleMetaLike();
        return $this->render('view', [
            'model' => $model,
            'likeCount' => $likeModel->getLikeCount($id),
            'prev' => $prev,
            'next' => $next,
            'recommends' => $recommends,
            'commentModel' => $commentModel,
            'commentList' => $commentList,
        ]);
    }

    public function actionComment()
    {
        if (yii::$app->getRequest()->getIsPost()) {
            $commentModel = new Comment();
            if ($commentModel->load(yii::$app->getRequest()->post()) && $commentModel->save()) {
                $avatar = 'https://secure.gravatar.com/avatar?s=50';
                if ($commentModel->email != '') {
                    $avatar = "https://secure.gravatar.com/avatar/" . md5($commentModel->email) . "?s=50";
                }
                $tips = '';
                if (yii::$app->feehi->website_comment_need_verify) {
                    $tips = "<span class='c-approved'>" . yii::t('frontend', 'Comment waiting for approved.') . "</span><br />";
                }
                $commentModel->content = str_replace([
                    ':mrgreen:',
                    ':razz:',
                    ':sad:',
                    ':smile:',
                    ':oops:',
                    ':grin:',
                    ':eek:',
                    ':???:',
                    ':cool:',
                    ':lol:',
                    ':mad:',
                    ':twisted:',
                    ':roll:',
                    ':wink:',
                    ':idea:',
                    ':arrow:',
                    ':neutral:',
                    ':cry:',
                    ':?:',
                    ':evil:',
                    ':shock:',
                    ':!:'
                ], [
                        "<img src='{%URL%}mrgreen{%EXT%}'>",
                        "<img src='{%URL%}razz{%EXT%}'>",
                        "<img src='{%URL%}sad{%EXT%}'>",
                        "<img src='{%URL%}smile{%EXT%}'>",
                        "<img src='{%URL%}redface{%EXT%}'>",
                        "<img src='{%URL%}biggrin{%EXT%}'>",
                        "<img src='{%URL%}surprised{%EXT%}'>",
                        "<img src='{%URL%}confused{%EXT%}'>",
                        "<img src='{%URL%}cool{%EXT%}'>",
                        "<img src='{%URL%}lol{%EXT%}'>",
                        "<img src='{%URL%}mad{%EXT%}'>",
                        "<img src='{%URL%}twisted{%EXT%}'>",
                        "<img src='{%URL%}rolleyes{%EXT%}'>",
                        "<img src='{%URL%}wink{%EXT%}'>",
                        "<img src='{%URL%}idea{%EXT%}'>",
                        "<img src='{%URL%}arrow{%EXT%}'>",
                        "<img src='{%URL%}neutral{%EXT%}'>",
                        "<img src='{%URL%}cry{%EXT%}'>",
                        "<img src='{%URL%}question{%EXT%}'>",
                        "<img src='{%URL%}evil{%EXT%}'>",
                        "<img src='{%URL%}eek{%EXT%}'>",
                        "<img src='{%URL%}exclaim{%EXT%}'>"
                    ], $commentModel->content);
                $commentModel->content = str_replace([
                    '{%URL%}',
                    '{%EXT%}'
                ], [yii::$app->homeUrl . 'static/images/smilies/icon_', '.gif'], $commentModel->content);
                echo "
                <li class='comment even thread-even depth-1' id='comment-{$commentModel->id}'>
                    <div class='c-avatar'><img src='{$avatar}' class='avatar avatar-108' height='50' width='50'>
                        <div class='c-main' id='div-comment-{$commentModel->id}'><p>{$commentModel->content}</p>
                            {$tips}
                            <div class='c-meta'><span class='c-author'><a href='{$commentModel->website_url}' rel='external nofollow' class='url'>{$commentModel->nickname}</a></span>  (" . yii::t('frontend', 'a minutes ago') . ")</div>
                        </div>
                    </div>";
            } else {
                $temp = $commentModel->getErrors();
                $str = '';
                foreach ($temp as $v) {
                    $str .= $v[0] . "<br>";
                }
                echo "<font color='red'>" . $str . "</font>";
            }
        }
    }

    public function actionLike()
    {
        $aid = yii::$app->getRequest()->post("um_id");
        $model = new ArticleMetaLike();
        $model->setLike($aid);
        return $model->getLikeCount($aid);

    }

}