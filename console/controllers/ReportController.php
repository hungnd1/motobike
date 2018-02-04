<?php
/**
 * Created by PhpStorm.
 * User: HungChelsea
 * Date: 22-Oct-17
 * Time: 10:42 PM
 */

namespace console\controllers;


use api\helpers\Message;
use common\helpers\CUtils;
use common\models\ReportSubscriberActivity;
use common\models\SubscriberActivity;
use DateTime;
use Exception;
use Yii;
use yii\base\InvalidValueException;
use yii\console\Controller;

class ReportController extends Controller
{

    public function actionSubscriberActivity($start_day = '')
    {
        //YYYY-mm-dd
        $transaction = Yii::$app->db->beginTransaction();

        try {
            if ($start_day) {
                if (!CUtils::validateDate($start_day)) {
                    throw new InvalidValueException(Message::getNotDateMessage());
                }
                $begin = new \DateTime($start_day);
                $begin->setTime(0, 0, 0);
                $beginPreDay = $begin->getTimestamp();
                $end = new \DateTime($start_day);
                $end->setTime(23, 59, 59);
                $endPreDay = $end->getTimestamp();
            } else {
                $beginPreDay = strtotime("midnight", time());
                $endPreDay = strtotime("tomorrow", $beginPreDay) - 1;
                $to_day_date = (new DateTime('now'))->setTime(0, 0)->format('Y-m-d H:i:s');
            }
            print("Thoi gian bat dau: $beginPreDay : Thoi gian ket thuc: $endPreDay ");
            ReportSubscriberActivity::deleteAll(['between', 'report_date', $beginPreDay, $endPreDay]);
            echo "Deleted report game daily date:" . date("d-m-Y H:i:s", $beginPreDay) . ' timestamp:' . $beginPreDay;


            $total_via_site = SubscriberActivity::find()
                ->andWhere(['<=', 'created_at', $endPreDay])
                ->count();
            $via_android = SubscriberActivity::find()
                ->andWhere(['channel' => SubscriberActivity::CHANNEL_APP])
                ->andWhere('created_at between :beginPreDay and :endPreDay')->addParams([':beginPreDay' => $beginPreDay, ':endPreDay' => $endPreDay])
                ->count();
            $via_ios = SubscriberActivity::find()
                ->andWhere(['channel' => SubscriberActivity::CHANNEL_IOS])
                ->andWhere('created_at between :beginPreDay and :endPreDay')->addParams([':beginPreDay' => $beginPreDay, ':endPreDay' => $endPreDay])
                ->count();

            $via_website = SubscriberActivity::find()
                ->andWhere(['channel' => SubscriberActivity::CHANNEL_WEB])
                ->andWhere('created_at between :beginPreDay and :endPreDay')->addParams([':beginPreDay' => $beginPreDay, ':endPreDay' => $endPreDay])
                ->count();
            $via_site_daily = $via_android  + $via_website + $via_ios;
            $r = new ReportSubscriberActivity();
            $r->report_date = $beginPreDay;
            $r->via_site_daily = $via_site_daily;
            $r->total_via_site = $total_via_site;
            $r->via_android = $via_android;
            $r->via_ios = $via_ios;
            $r->via_website = $via_website;
            if (!$r->save()) {
                echo '****** ERROR! Report Subscriber Activity Fail ******';
                $transaction->rollBack();
            }
            $transaction->commit();
            echo '****** Report Subscriber Activity Done ******';

        } catch (Exception $e) {
            $transaction->rollBack();
            echo '****** ERROR! Report Subscriber Activity Fail Exception ******' . $e->getMessage();
        }

    }
}