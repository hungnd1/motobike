<?php
/**
 * Created by PhpStorm.
 * User: HungChelsea
 * Date: 22-Oct-17
 * Time: 10:42 PM
 */

namespace console\controllers;


use api\helpers\Message;
use api\models\Subscriber;
use common\helpers\CUtils;
use common\models\Exchange;
use common\models\ExchangeBuy;
use common\models\Province;
use common\models\ReportBuySell;
use common\models\ReportSubscriberActivity;
use common\models\SubscriberActivity;
use common\models\SubscriberActivityType;
use common\models\TypeCoffee;
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
            $via_site_daily = $via_android + $via_website + $via_ios;
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

    public function actionReportBuySell($start_day = '')
    {
        // 2/7/2017
        if ($start_day) {
            if (!CUtils::validateDate($start_day)) {
                throw new InvalidValueException(Message::getNotDateMessage());
            }
            for ($i = 0; $i <= 365; $i++) {
                $beginFirst = date('Y-m-d', strtotime($start_day . "+" . $i . " days"));
                $begin = new \DateTime($beginFirst);
                $begin->setTime(0, 0, 0);
                $beginPreDay = $begin->getTimestamp();
                $end = new \DateTime($beginFirst);
                $end->setTime(23, 59, 59);
                $endPreDay = $end->getTimestamp();

                //list province
                $listProvince = Province::find()->all();
                foreach ($listProvince as $province) {
                    /** @var $province Province */
                    //list type coffee
                    $listTypeCoffee = TypeCoffee::find()->all();
                    foreach ($listTypeCoffee as $typeCoffee) {
                        /** @var $typeCoffee TypeCoffee */

                        $total_buy = 0;
                        $total_sell = 0;
                        //list buy
                        $listExchangeBuy = ExchangeBuy::find()
                            ->andWhere(['province_id' => $province->id])
                            ->andWhere(['type_coffee_id' => $typeCoffee->id])
                            ->andWhere('created_at between :beginPreDay and :endPreDay')
                            ->addParams([':beginPreDay' => $beginPreDay, ':endPreDay' => $endPreDay])
                            ->all();
                        foreach ($listExchangeBuy as $exchangeBuy) {
                            /** @var $exchangeBuy ExchangeBuy */
                            $total_buy += $exchangeBuy->total_quantity;
                        }

                        $listExchangeSell = Exchange::find()
                            ->andWhere(['province_id' => $province->id])
                            ->andWhere('total_quantity is not null')
                            ->andWhere(['type_coffee' => $typeCoffee->id])
                            ->andWhere('created_at between :beginPreDay and :endPreDay')
                            ->addParams([':beginPreDay' => $beginPreDay, ':endPreDay' => $endPreDay])
                            ->all();

                        foreach ($listExchangeSell as $exchangeSell) {
                            /** @var $exchangeSell Exchange */
                            $total_sell += $exchangeSell->total_quantity;
                        }
                        if ($total_buy > 0 || $total_sell > 0) {
                            $reportNew = new ReportBuySell();
                            $reportNew->report_date = $beginPreDay;
                            $reportNew->type_coffee = $typeCoffee->id;
                            $reportNew->province_id = $province->id;
                            $reportNew->total_buy = $total_buy;
                            $reportNew->total_sell = $total_sell;
                            $reportNew->save();
                        }
                    }
                }
            }
        } else {
            $beginFirst = strtotime("midnight", time());
            $begin = new \DateTime($beginFirst);
            $begin->setTime(0, 0, 0);
            $beginPreDay = $begin->getTimestamp();
            $end = new \DateTime($beginFirst);
            $end->setTime(23, 59, 59);
            $endPreDay = $end->getTimestamp();
            //list province
            $listProvince = Province::find()->all();
            foreach ($listProvince as $province) {
                /** @var $province Province */
                //list type coffee
                $listTypeCoffee = TypeCoffee::find()->all();
                foreach ($listTypeCoffee as $typeCoffee) {
                    /** @var $typeCoffee TypeCoffee */

                    $total_buy = 0;
                    $total_sell = 0;
                    //list buy
                    $listExchangeBuy = ExchangeBuy::find()
                        ->andWhere(['province_id' => $province->id])
                        ->andWhere(['type_coffee_id' => $typeCoffee->id])
                        ->andWhere('created_at between :beginPreDay and :endPreDay')
                        ->addParams([':beginPreDay' => $beginPreDay, ':endPreDay' => $endPreDay])
                        ->all();
                    foreach ($listExchangeBuy as $exchangeBuy) {
                        /** @var $exchangeBuy ExchangeBuy */
                        $total_buy += $exchangeBuy->total_quantity;
                    }

                    $listExchangeSell = Exchange::find()
                        ->andWhere(['province_id' => $province->id])
                        ->andWhere('total_quantity is not null')
                        ->andWhere(['type_coffee' => $typeCoffee->id])
                        ->andWhere('created_at between :beginPreDay and :endPreDay')
                        ->addParams([':beginPreDay' => $beginPreDay, ':endPreDay' => $endPreDay])
                        ->all();

                    foreach ($listExchangeSell as $exchangeSell) {
                        /** @var $exchangeSell Exchange */
                        $total_sell += $exchangeSell->total_quantity;
                    }
                    if ($total_buy > 0 || $total_sell > 0) {
                        $reportNew = new ReportBuySell();
                        $reportNew->report_date = $beginPreDay;
                        $reportNew->type_coffee = $typeCoffee->id;
                        $reportNew->province_id = $province->id;
                        $reportNew->total_buy = $total_buy;
                        $reportNew->total_sell = $total_sell;
                        $reportNew->save();
                    }
                }
            }
        }
    }

    public function actionSubscriberActivityType($start_day = '')
    {
        //YYYY-mm-dd
        $transaction = Yii::$app->db->beginTransaction();
//        for ($i = 0; $i <= 600; $i++) {
            try {
                if ($start_day) {
                    if (!CUtils::validateDate($start_day)) {
                        throw new InvalidValueException(Message::getNotDateMessage());
                    }
                    $beginFirst = date('Y-m-d', strtotime($start_day . "+" . 1 . " days"));
                    $begin = new \DateTime($beginFirst);
                    $begin->setTime(0, 0, 0);
                    $beginPreDay = $begin->getTimestamp();
                    $end = new \DateTime($beginFirst);
                    $end->setTime(23, 59, 59);
                    $endPreDay = $end->getTimestamp();
                } else {
                    $beginPreDay = strtotime("midnight", time());
                    $endPreDay = strtotime("tomorrow", $beginPreDay) - 1;
                    $to_day_date = (new DateTime('now'))->setTime(0, 0)->format('Y-m-d H:i:s');
                }
                print("Thoi gian bat dau: $beginPreDay : Thoi gian ket thuc: $endPreDay ");
                SubscriberActivityType::deleteAll(['between', 'report_date', $beginPreDay, $endPreDay]);
                echo "Deleted report game daily date:" . date("d-m-Y H:i:s", $beginPreDay) . ' timestamp:' . $beginPreDay;

                $weather = SubscriberActivity::find()
                    ->andWhere(['action' => SubscriberActivity::ACTION_WEATHER])
                    ->andWhere('created_at between :beginPreDay and :endPreDay')->addParams([':beginPreDay' => $beginPreDay, ':endPreDay' => $endPreDay])
                    ->count();

                $price = SubscriberActivity::find()
                    ->andWhere(['action' => SubscriberActivity::ACTION_PRICE])
                    ->andWhere('created_at between :beginPreDay and :endPreDay')->addParams([':beginPreDay' => $beginPreDay, ':endPreDay' => $endPreDay])
                    ->count();
                $gap = SubscriberActivity::find()
                    ->andWhere(['action' => SubscriberActivity::ACTION_GAP])
                    ->andWhere('created_at between :beginPreDay and :endPreDay')->addParams([':beginPreDay' => $beginPreDay, ':endPreDay' => $endPreDay])
                    ->count();

                $buy = SubscriberActivity::find()
                    ->andWhere(['action' => SubscriberActivity::ACTION_BUY])
                    ->andWhere('created_at between :beginPreDay and :endPreDay')->addParams([':beginPreDay' => $beginPreDay, ':endPreDay' => $endPreDay])
                    ->count();
                $gap_disease = SubscriberActivity::find()
                    ->andWhere(['action' => SubscriberActivity::ACTION_GAP_DISEASE])
                    ->andWhere('created_at between :beginPreDay and :endPreDay')->addParams([':beginPreDay' => $beginPreDay, ':endPreDay' => $endPreDay])
                    ->count();
                $qa = SubscriberActivity::find()
                    ->andWhere(['action' => SubscriberActivity::ACTION_ANSWER])
                    ->andWhere('created_at between :beginPreDay and :endPreDay')->addParams([':beginPreDay' => $beginPreDay, ':endPreDay' => $endPreDay])
                    ->count();
                $tracuu = SubscriberActivity::find()
                    ->andWhere(['action' => SubscriberActivity::ACTION_TRA_CUU_SU_CO])
                    ->andWhere('created_at between :beginPreDay and :endPreDay')->addParams([':beginPreDay' => $beginPreDay, ':endPreDay' => $endPreDay])
                    ->count();
                $nongnghiep = SubscriberActivity::find()
                    ->andWhere(['action' => SubscriberActivity::ACTION_NONG_NGHIEP_THONG_MINH])
                    ->andWhere('created_at between :beginPreDay and :endPreDay')->addParams([':beginPreDay' => $beginPreDay, ':endPreDay' => $endPreDay])
                    ->count();
                $biendoi = SubscriberActivity::find()
                    ->andWhere(['action' => SubscriberActivity::ACTION_CLIMATE_CHANGE])
                    ->andWhere('created_at between :beginPreDay and :endPreDay')->addParams([':beginPreDay' => $beginPreDay, ':endPreDay' => $endPreDay])
                    ->count();
                $tuvan = SubscriberActivity::find()
                    ->andWhere(['action' => SubscriberActivity::ACTION_TU_VAN_SU_DUNG])
                    ->andWhere('created_at between :beginPreDay and :endPreDay')->addParams([':beginPreDay' => $beginPreDay, ':endPreDay' => $endPreDay])
                    ->count();

                $r = new SubscriberActivityType();
                $r->report_date = $beginPreDay;
                $r->weather = $weather;
                $r->price = $price;
                $r->gap = $gap;
                $r->gap_disease = $gap_disease;
                $r->buy = $buy;
                $r->qa = $qa;
                $r->tracuusuco = $tracuu;
                $r->nongnghiepthongminh = $nongnghiep;
                $r->biendoikhihau = $biendoi;
                $r->tuvansudungphanbon = $tuvan;
                if (!$r->save()) {
                    echo '****** ERROR! Report Subscriber Activity Type Fail ******';
                    $transaction->rollBack();
                }
                $transaction->commit();
                echo '****** Report Subscriber Activity Done ******';

            } catch (Exception $e) {
                $transaction->rollBack();
                echo '****** ERROR! Report Subscriber Activity Fail Exception ******' . $e->getMessage();
//            }
        }
    }
}