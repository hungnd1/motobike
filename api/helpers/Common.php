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
    const NANG_NHE_MUA_RAO = 10;
    const MUADONG = 11;

    //ham lay huong gio
    public static function windir($wnddtxt)
    {
        if ($wnddtxt == 'N') {
            return 'Hướng Bắc (N)';
        } elseif ($wnddtxt == 'NE') {
            return 'Hướng Đông Bắc (NE)';
        } elseif ($wnddtxt == 'E') {
            return 'Hướng Đông (E)';
        } elseif ($wnddtxt == 'SE') {
            return 'Hướng Đông Nam (SE)';
        } elseif ($wnddtxt == 'S') {
            return 'Hướng Nam (S)';
        } elseif ($wnddtxt == 'SW') {
            return 'Hướng Tây Nam (SW)';
        } elseif ($wnddtxt == 'W') {
            return 'Hướng Tây (W)';
        } elseif ($wnddtxt == 'NW') {
            return 'Hướng Tây Bắc (NW)';
        }
        return 'Hướng Bắc (N)';
    }

    //ham lay luong mua
    public static function precipitation($wtxt)
    {
        $icon = explode('_',$wtxt);
        if(isset($icon['1'])){
            return Url::to(Yii::getAlias('@web') . DIRECTORY_SEPARATOR . Yii::getAlias('@weather') . DIRECTORY_SEPARATOR . $icon['1'].'.png', true);
        }
        return Url::to(Yii::getAlias('@web') . DIRECTORY_SEPARATOR . Yii::getAlias('@weather') . DIRECTORY_SEPARATOR . 'muanhokhongdangke.png', true);
    }

    public function getImageLink($code)
    {
        if ($code == Common::MUA_NHO_KHONG_DANG_KE) {
            return Url::to(Yii::getAlias('@web') . DIRECTORY_SEPARATOR . Yii::getAlias('@weather') . DIRECTORY_SEPARATOR . 'muanhokhongdangke.png', true);
        } elseif ($code == Common::MUA_NHO) {
            return Url::to(Yii::getAlias('@web') . DIRECTORY_SEPARATOR . Yii::getAlias('@weather') . DIRECTORY_SEPARATOR . 'muanho.png', true);
        } elseif ($code == Common::MUA_VUA) {
            return Url::to(Yii::getAlias('@web') . DIRECTORY_SEPARATOR . Yii::getAlias('@weather') . DIRECTORY_SEPARATOR . 'muavua.png', true);
        } elseif ($code == Common::MUA_TO) {
            return Url::to(Yii::getAlias('@web') . DIRECTORY_SEPARATOR . Yii::getAlias('@weather') . DIRECTORY_SEPARATOR . 'muato.png', true);
        } elseif ($code == Common::MUA_RAT_TO) {
            return Url::to(Yii::getAlias('@web') . DIRECTORY_SEPARATOR . Yii::getAlias('@weather') . DIRECTORY_SEPARATOR . 'muarato.png', true);
        } elseif ($code == Common::NANG_KHONG_MAY) {
            return Url::to(Yii::getAlias('@web') . DIRECTORY_SEPARATOR . Yii::getAlias('@weather') . DIRECTORY_SEPARATOR . 'nangkhongmay.png', true);
        } elseif ($code == Common::NANG_IT_MAY) {
            return Url::to(Yii::getAlias('@web') . DIRECTORY_SEPARATOR . Yii::getAlias('@weather') . DIRECTORY_SEPARATOR . 'nangitmay.png', true);
        } elseif ($code == Common::NANG_NHIEU_MAY) {
            return Url::to(Yii::getAlias('@web') . DIRECTORY_SEPARATOR . Yii::getAlias('@weather') . DIRECTORY_SEPARATOR . 'nangnhieumay.png', true);
        } elseif ($code == Common::NANG_NHE_MUA_RAO) {
            return Url::to(Yii::getAlias('@web') . DIRECTORY_SEPARATOR . Yii::getAlias('@weather') . DIRECTORY_SEPARATOR . 'nangnhecomuarao.png', true);
        } elseif ($code == Common::MUADONG) {
            return Url::to(Yii::getAlias('@web') . DIRECTORY_SEPARATOR . Yii::getAlias('@weather') . DIRECTORY_SEPARATOR . 'muadong.png', true);
        }
    }
}