<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */
namespace app\commands;


use Yii;
use yii\console\Controller;
use yii\easyii\modules\catalog\models\Item;
/**
 * This command echoes the first argument that you have entered.
 *
 * This command is provided as an example for you to learn how to create console commands.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class DeleteController extends Controller
{

    public function actionIndex($message = 'hello world')
    {
        echo $message . "\n";
    }

    //php /var/www/vhosts/admin.tangolino.com/docroot/yii delete/job-delete
    public function actionJobDelete()
    {
        $time = strtotime('now');
        $items = Item::find()->andFilterWhere(['=',  Item::tableName().'.deleted', 0])->
                               andFilterWhere(['=',  Item::tableName().'.ready_to_delete', 1])->
                               andFilterWhere(['<=', Item::tableName().'.to_be_deleted', $time])->all();
        foreach($items as $item) {
            Yii::$app->db->createCommand()->update('easyii_catalog_items', ['deleted' => Item::STATUS_ON], ['item_id' => $item->item_id])->execute();
        }

        $rootyii  = realpath(dirname(__FILE__).'/../../');
        $time     = date('Y-m-d h:i:sa',strtotime('now')) . '   \n';
        $filename =  'cron_jobs_update.txt';
        $file   = $rootyii.'/cronjob/'.$filename;
        $myfile = file_put_contents($file, $time.PHP_EOL , FILE_APPEND | LOCK_EX);
    }

    public function printData()
    {
        $rootyii  = realpath(dirname(__FILE__).'/../../');
        $time     = date('Y-m-d h:i:sa',strtotime('now')) . '   \n';
        $filename =  'cron_jobs_update.txt';
        $file   = $rootyii.'/cronjob/'.$filename;
        $myfile = file_put_contents($file, $time.PHP_EOL , FILE_APPEND | LOCK_EX);
    }
}