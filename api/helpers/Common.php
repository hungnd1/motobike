<?php
/**
 * Created by PhpStorm.
 * User: HungChelsea
 * Date: 30-Jul-17
 * Time: 2:09 PM
 */

namespace api\helpers;


use Yii;
use yii\helpers\Url;

class Common
{

    const MUA_NHO = 1;
    const MUA_NHO_KHONG_DANG_KE = 9;
    const MUA_VUA = 2;
    const MUA_TO = 3;
    const MUA_RAT_TO = 4;
    const NANG_KHONG_MAY = 5;
    const NANG_NHIEU_MAY = 6;
    const NANG_IT_MAY = 7;
    const MUA_SET = 8;

    //ham lay huong gio
    public static function windir($windir)
    {
        if ($windir >= 337.5 || $windir < 22.5) {
            return 'Hướng Bắc (N)';
        } elseif (22.5 <= $windir && $windir < 67.5) {
            return 'Hướng Đông Bắc (NE)';
        } elseif (67.5 <= $windir && $windir < 112.5) {
            return 'Hướng Đông (E)';
        } elseif (112.5 <= $windir && $windir < 157.5) {
            return 'Hướng Đông Nam (SE)';
        } elseif (157.5 <= $windir && $windir < 202.5) {
            return 'Hướng Nam (S)';
        } elseif (202.5 <= $windir && $windir < 247.5) {
            return 'Hướng Tây Nam (SW)';
        } elseif (247.5 <= $windir && $windir < 292.5) {
            return 'Hướng Tây (W)';
        } elseif (292.5 <= $windir && $windir < 337.5) {
            return 'Hướng Tây Bắc (NW)';
        }
        return 'Hướng Bắc (N)';
    }

    //ham lay luong mua
    public static function precipitation($precipitation,$tmax)
    {
        if ($precipitation > 0 && $precipitation <= 0.3) {
            return [
                'code' => Common::MUA_NHO_KHONG_DANG_KE,
                'message' => 'Mưa nhỏ lượng mưa không đáng kể',
                'image' => Common::getImageLink(Common::MUA_NHO_KHONG_DANG_KE)
            ];
        } elseif ($precipitation > 0.3 && $precipitation <= 3) {
            return [
                'code' => Common::MUA_NHO,
                'message' =>  'Mưa nhỏ',
                'image' => Common::getImageLink(Common::MUA_NHO)
            ];
        } elseif ($precipitation > 3 && $precipitation <= 8) {
            return [
                'code' => Common::MUA_VUA,
                'message' =>  'Mưa',
                'image' => Common::getImageLink(Common::MUA_VUA)
            ];
        } elseif ($precipitation > 8 && $precipitation <= 25) {
            return [
                'code' => Common::MUA_VUA,
                'message' =>  'Mưa vừa',
                'image' => Common::getImageLink(Common::MUA_VUA)
            ];
        } elseif ($precipitation > 25 && $precipitation <= 50) {
            return [
                'code' => Common::MUA_TO,
                'message' =>  'Mưa to',
                'image' => Common::getImageLink(Common::MUA_TO)
            ];
        } elseif ($precipitation > 50) {
            return [
                'code' => Common::MUA_RAT_TO,
                'message' =>  'Mưa rất to',
                'image' => Common::getImageLink(Common::MUA_RAT_TO)
            ];
        }elseif($tmax >= 33){
            return [
                'code' => Common::NANG_KHONG_MAY,
                'message' =>  'Nắng',
                'image' => Common::getImageLink(Common::NANG_KHONG_MAY)
            ];
        }elseif($tmax < 33 && $tmax >= 23){
            return [
                'code' => Common::NANG_IT_MAY,
                'message' =>  'Nắng',
                'image' => Common::getImageLink(Common::NANG_IT_MAY)
            ];
        }
        return [
            'code' => Common::NANG_NHIEU_MAY,
            'message' =>  'Nắng',
            'image' => Common::getImageLink(Common::NANG_NHIEU_MAY)
        ];
    }

    public function getImageLink($code)
    {
        if($code == Common::MUA_NHO_KHONG_DANG_KE){
            return Url::to(Yii::getAlias('@web') . DIRECTORY_SEPARATOR . Yii::getAlias('@weather') . DIRECTORY_SEPARATOR .'muanhokhongdangke.png', true);
        }elseif($code == Common::MUA_NHO){
            return Url::to(Yii::getAlias('@web') . DIRECTORY_SEPARATOR . Yii::getAlias('@weather') . DIRECTORY_SEPARATOR .'muanho.png', true);
        }elseif($code == Common::MUA_VUA){
            return Url::to(Yii::getAlias('@web') . DIRECTORY_SEPARATOR . Yii::getAlias('@weather') . DIRECTORY_SEPARATOR .'muavua.png', true);
        }elseif($code == Common::MUA_TO){
            return Url::to(Yii::getAlias('@web') . DIRECTORY_SEPARATOR . Yii::getAlias('@weather') . DIRECTORY_SEPARATOR .'muato.png', true);
        }elseif($code == Common::MUA_RAT_TO){
            return Url::to(Yii::getAlias('@web') . DIRECTORY_SEPARATOR . Yii::getAlias('@weather') . DIRECTORY_SEPARATOR .'muarato.png', true);
        }elseif($code == Common::NANG_KHONG_MAY){
            return Url::to(Yii::getAlias('@web') . DIRECTORY_SEPARATOR . Yii::getAlias('@weather') . DIRECTORY_SEPARATOR .'nangkhongmay.png', true);
        }elseif($code == Common::NANG_IT_MAY){
            return Url::to(Yii::getAlias('@web') . DIRECTORY_SEPARATOR . Yii::getAlias('@weather') . DIRECTORY_SEPARATOR .'nangitmay.png', true);
        }elseif($code == Common::NANG_NHIEU_MAY){
            return Url::to(Yii::getAlias('@web') . DIRECTORY_SEPARATOR . Yii::getAlias('@weather') . DIRECTORY_SEPARATOR .'nangnhieumay.png', true);
        }
    }
}