<?php
namespace app\controllers;

use  yii\easyii\modules\news\api\News;
use  app\models\EntryForm;
use  Yii;

class NewsController extends \yii\web\Controller
{
    public function actionGetInfo()
    {
        //the URL without the host
        echo ("Yii::app->request->url");
        var_dump(Yii::$app->request->url);
        echo "<br>";
        //the whole URL including the host path
        echo ("Yii::app->request->absoluteUrl");
        var_dump(Yii::$app->request->absoluteUrl);echo "<br>";
        //the host of the URL
        echo ("Yii::app->request->hostInfo");
        var_dump(Yii::$app->request->hostInfo);echo "<br>";
        //the part after the entry script and before the question mark
        echo ("Yii::app->request->pathInfo");
        var_dump(Yii::$app->request->pathInfo);echo "<br>";
        //the part after the question mark
        echo ("Yii::app->request->queryString");
        var_dump(Yii::$app->request->queryString);echo "<br>";
        //the part after the host and before the entry script
        echo ("Yii::app->request->baseUrl");
        var_dump(Yii::$app->request->baseUrl);echo "<br>";
        //the URL without path info and query string
        echo ("Yii::app->request->scriptUrl");
        var_dump(Yii::$app->request->scriptUrl);echo "<br>";
        //the host name in the URL
        echo ("Yii::app->request->serverName");
        var_dump(Yii::$app->request->serverName);echo "<br>";
        //the port used by the web server
        echo ("Yii::app->request->serverPort");
        var_dump(Yii::$app->request->serverPort);echo "<br>";
        echo ("Yii::app->request->headers");
        var_dump(Yii::$app->request->headers);echo "<br>";

    }

    public function actionAdHocValidation()
    {
        $model = DynamicModel::validateData([
            'username' => 'John',
            'email' => 'john@gmail.com'
        ], [
            [['username', 'email'], 'string', 'max' => 12],
            ['email', 'email'],
        ]);
        if ($model->hasErrors()) {
            var_dump($model->errors);
        } else {
            echo "success";
        }
    }

    public function actionTestWidget()
    {
        $model = new EntryForm();
         /// \Yii::$app->view->on(View::EVENT_BEGIN_BODY, function () { echo date('m.d.Y H:i:s'); });

        return $this->render('testwidget', ['model' => $model]);
    }

    public function actionTest()
    {
        $name = "Mostafa Sroor";

        return $this->render('test',['name' => $name]);
    }


    public function actionEntry()
    {
        $model = new EntryForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {

            return $this->render('entry-confirm', ['model' => $model]);
        } else {
            return $this->render('entry', ['model' => $model]);
        }
    }

    public function actionIndex($tag = null)
    {
        return $this->render('index',[
            'news' => News::items(['tags' => $tag, 'pagination' => ['pageSize' => 2]])
        ]);
    }

    public function actionView($slug)
    {
        $news = News::get($slug);
        if(!$news){
            throw new \yii\web\NotFoundHttpException('News not found.');
        }

        return $this->render('view', [
            'news' => $news
        ]);
    }
}
