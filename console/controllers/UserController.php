<?php

/**
 * Swiss army knife to work with user and rbac in command line
 * @author: Nguyen Chi Thuc
 * @email: gthuc.nguyen@gmail.com
 */

namespace console\controllers;

use backend\controllers\CategoryController;
use common\auth\helpers\AuthHelper;
use common\helpers\CUtils;
use common\helpers\StringUtils;
use common\models\AuthItem;
use common\models\QuestionAnswer;
use common\models\Site;
use common\models\Subscriber;
use common\models\User;
use common\models\WeatherDetail;
use ReflectionClass;
use Yii;
use yii\console\Controller;
use yii\console\Exception;
use yii\helpers\StringHelper;
use yii\helpers\VarDumper;
use yii\rbac\DbManager;
use yii\rbac\Item;

/**
 * UserController create user in commandline
 */
class UserController extends Controller
{


    /**
     * Sample: ./yii be-user/create-admin-user "thucnc@vivas.vn" "123456"
     * @param $email
     * @param $password
     * @throws Exception
     */
    public function actionCreateAdminUser($email, $password)
    {
        $this->actionCreateUser('admin', $email, $password);
    }


    /**
     * Sample: ./yii be-user/create-sp-user "huydq" "huydq@vivas.vn" "123456" 1
     * @param $username
     * @param $email
     * @param $password
     * @param $sp_id
     * @throws Exception
     */
    public function actionCreateSpUser($user, $email, $password, $sp_id)
    {
        $sp_user = $this->actionCreateUser($user, $email, $password);
        $sp_user->site_id = $sp_id;
        $sp_user->type = User::USER_TYPE_SP;
        $sp_user->update();
        return $sp_user;
    }

    public function actionSetPassword($user, $password)
    {
        $user = User::findByUsername($user);
        if ($user) {
            $user->setPassword($password);
            if ($user->save()) {
                echo 'Password changed!\n';
                return 0;
            } else {
                Yii::error($user->getErrors());
                VarDumper::dump($user->getErrors());
                throw new Exception("Cannot change password!");
            }
        } else {
            echo "User not found!\n";
            return 1;
        }
    }

    /**
     * @param $username
     * @param $email
     * @param $password
     * @param string $full_name
     * @return $user User
     * @throws Exception
     */
    public function actionCreateUser($username, $email, $password, $full_name = "")
    {
        $user = new User();
        $user->username = $username;
        $user->status = User::STATUS_ACTIVE;
//        $user->full_name = $full_name;
        $user->email = $email;
//        $user->type = $type;
        $user->setPassword($password);
        $user->generateAuthKey();

        if ($user->save()) {
            echo 'User created!\n';
            return $user;
        } else {
            Yii::error($user->getErrors());
            VarDumper::dump($user->getErrors());
            throw new Exception("Cannot create User!");
        }
    }

    /**
     * Add permission.
     * Sample: ./yii be-user/add-permission createUser "Create backend user" "be-user/create" UserManager
     * @param $name
     * @param $description
     * @param $route
     * @param null $parent
     */
    public function actionAddPermission($name, $description, $route, $parent = null)
    {
        $this->addAuthItem($name, $description, $route, AuthItem::TYPE_PERMISSION, $parent);

    }

    public function actionAddRole($name, $description, $route = null, $parent = null)
    {
        $this->addAuthItem($name, $description, $route, AuthItem::TYPE_ROLE, $parent);
    }

    /**
     * Assign permission/role to user
     * Sample: ./yii be-user/assign admin createUser
     * @param $username
     * @param $auth_item
     */
    public function actionAssign($username, $auth_item)
    {
        /* @var $auth DbManager */
        $auth = Yii::$app->authManager;
        $user = User::findByUsername($username);
        if (!$user) {
            echo "User not found!\n";
            return 1;
        }

        $item = $auth->getPermission($auth_item);
        if (!empty($item)) {
            echo "Permission with name `$auth_item` found\n";
        } else {
            $item = $auth->getRole($auth_item);
            if (!empty($item)) {
                echo "Role with name `$auth_item` found\n";
            } else {
                echo "No auth_item named `$auth_item` found\n";
                return 1;
            }
        }

        if (!$auth->getAssignment($auth_item, $user->id)) {
            $auth->assign($item, $user->id);
            echo "Auth_item `$auth_item` has been assigned to `$username`\n";
        } else {
            echo "Assignment existed!\n";
        }
    }

    private function addAuthItem($name, $description, $route, $type, $parent)
    {
        /* @var $auth DbManager */
        $auth = Yii::$app->authManager;

        $item = $auth->getRole($name);
        $newItem = false;
        if (!empty($item)) {
            echo "Role with name `$name` existed, update it...\n";
        } else {
            $item = $auth->getPermission($name);
            if (!empty($item)) {
                echo "Permission with name `$name` existed, update it...\n";
            } else {
                $newItem = true;
                if ($type == AuthItem::TYPE_ROLE) {
                    $item = $auth->createRole($name);
                } else {
                    $item = $auth->createPermission($name);
                }
            }
        }

        if ($route) {
            $item->data = $route;
        }

        $item->description = $description;
        if (!empty($parent)) {
            /* @var $parentItem Item */
            $parentItem = $auth->getRole($parent);
            if (empty($parentItem)) {
                $parentItem = $auth->getPermission($parent);
            }
            if (empty($parentItem)) {
                echo "Parent item not found\n";
                return 1;
            }

            if ($auth->hasChild($parentItem, $item)) {
                echo "Parent-child asm already exited\n";
            } else {
                $auth->addChild($parentItem, $item);
            }
        }

        if ($newItem) {
            $auth->add($item);
        }
        return 0;
    }

    public function actionListActions($alias = '@app')
    {
        $actionAuth = AuthHelper::listActions(@$alias);
        VarDumper::dump($actionAuth);
    }

    public function actionMigrateAnswer()
    {
        $lstAnswer = QuestionAnswer::find()
            ->andWhere('answer_string is null')
            ->all();
        foreach ($lstAnswer as $answer) {
            /** @var $answer QuestionAnswer */
            $answer->answer_string = strip_tags(html_entity_decode($answer->answer, ENT_NOQUOTES, "UTF-8"));
            $answer->save();
        }
    }


    public function actionMigrateSubscriber()
    {
        $lstSubscriber = Subscriber::find()
            ->andWhere('weather_detail_id is not null')
            ->all();
        foreach ($lstSubscriber as $subscriber) {
            /** @var $subscriber  Subscriber */
            /** @var  $weatherDetail WeatherDetail */
            if ($subscriber->weather_detail_id) {
                $weatherDetail = WeatherDetail::find()
                    ->andWhere(['id' => $subscriber->weather_detail_id])
                    ->one();
                if ($weatherDetail) {
                    $subscriber->weather_detail_id = $weatherDetail->station_code;
                    $subscriber->save(false);
                }
            }
        }
    }

    public function actionTest()
    {
        $listUser = Subscriber::find()
            ->andWhere(['status' => Subscriber::STATUS_ACTIVE])
            ->all();
        $arr = ['0162', '0163', '0164', '0165', '0166', '0167', '0168', '0169', '0128', '0126', '0122', '0121', '0120', '0123', '0125', '0129', '0127', '0124'];
        $arrKeyValue = [
            '0162' => '032',
            '0163' => '033',
            '0164' => '034',
            '0165' => '035',
            '0166' => '036',
            '0167' => '037',
            '0168' => '038',
            '0169' => '039',
            '0128' => '078',
            '0126' => '076',
            '0122' => '077',
            '0121' => '079',
            '0120' => '070',
            '0125' => '085',
            '0123' => '083',
            '0129' => '082',
            '0127' => '081',
            '0124' => '084'
        ];
        foreach ($listUser as $user) {
            /** @var $user Subscriber */
            $firstStr = substr($user->username, 0, 2);
            if ($firstStr == '84') {
                $user->username = CUtils::validateMobile($user->username);
                $user->save(false);
            }
            $subStr = CUtils::validateMobile($user->username);
            $str = substr($subStr, 0, 4);
            $strSub = substr($subStr, 4);
            if (in_array($str, $arr)) {
                $strReplace = $arrKeyValue[$str];
                $user->username = $strReplace . $strSub;
                $user->save(false);
            }
        }
    }
}
