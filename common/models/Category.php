<?php

namespace common\models;

use Yii;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;

/**
 * This is the model class for table "category".
 *
 * @property integer $id
 * @property string $display_name
 * @property integer $type
 * @property integer $status
 * @property string $description
 * @property integer $parent_id
 * @property integer $order_number
 * @property string $path
 * @property integer $level
 * @property integer $child_count
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $updated_by
 * @property integer $created_by
 *
 * * @property Category $parent
 */
class Category extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */

    const STATUS_ACTIVE   = 10;
    const STATUS_INACTIVE = 0;
    const CHILD_NODE_PREFIX  = '|--';
    public $path_name;
    private static $catTree  = array();


    public static function tableName()
    {
        return 'category';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['display_name'], 'required', 'message' => '{attribute} không được để trống', 'on' => 'admin_create_update'],
            [['type', 'status', 'parent_id', 'order_number', 'level', 'child_count', 'created_at', 'updated_at', 'updated_by', 'created_by'], 'integer'],
            [['description'], 'string'],
            [['display_name', 'path'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'display_name' => 'Tên danh mục',
            'type' => 'Type',
            'status' => 'Trạng thái',
            'description' => 'Mô tả',
            'parent_id' => 'Danh mục cha',
            'order_number' => 'Order Number',
            'path' => 'Path',
            'level' => 'Level',
            'child_count' => 'Child Count',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
            'created_by' => 'Created By',
        ];
    }
    public function getBECategories()
    {
        return $this->hasMany(Category::className(), ['parent_id' => 'id'])
            ->orderBy(['order_number' => SORT_DESC])->all();

    }
    public static function getAllCategories($cat_id = null, $recursive = true)
    {
        $res = [];
        if ($cat_id != null) {
            $model = Category::findOne(['id' => $cat_id]);

            if ($model === null) {
                throw new NotFoundHttpException(404, "The requested Vod Category (#$cat_id) does not exist.");
            }

            //Thuc: them 'order'=>'order_number ASC' trong ham relations
            $children = $model->getBECategories();

            if ($children) {
                foreach ($children as $child) {
                    $path = "";
                    for ($i = 0; $i < $child->level; $i++) {
                        $path .= Category::CHILD_NODE_PREFIX;
                    }
//                    $child->name = $path . $child->name;
                    $child->path_name = $path . $child->display_name;
                    $res[]            = $child;
                    if ($recursive) {
                        $res = ArrayHelper::merge($res,
                            Category::getAllCategories($child->id, $recursive));
                    }
                }
            }
        } else {
            $root_cats = Category::find()->andWhere(['level' => 0])
                ->orderBy(['order_number' => SORT_DESC])->all();
            if ($root_cats) {
                foreach ($root_cats as $cat) {
                    /* @var $cat Category */
                    $cat->path_name = $cat->display_name;
                    $res[]          = $cat;
                    if ($recursive) {
                        $res = ArrayHelper::merge($res,
                            Category::getAllCategories($cat->id, $recursive));
                    }
                }
            }
        }

        return $res;
    }


    /**
     * return : 1: max, 2: min, 3: middle
     */
    public function checkPositionOnTree()
    {
        if ($this->parent_id == null) {
            $minMaxOrder = Category::find()->select(['max(order_number) as max', 'min(order_number) as min'])
                ->where('parent_id is null')->asArray()->one();
        } else {
            $minMaxOrder = Category::find()->select(['max(order_number) as max', 'min(order_number) as min'])
                ->where('parent_id =:p_parent_id', [':p_parent_id' => $this->parent_id])->asArray()->one();
        }
        if ($minMaxOrder) {
            if ($minMaxOrder['max'] == $minMaxOrder['min']) {
                return 3;
            }
            if ($minMaxOrder['max'] <= $this->order_number) {
                return 1;
            } else if ($minMaxOrder['min'] >= $this->order_number) {
                return 2;
            }

            return 4;
        }
    }

    public static function getListStatus()
    {
        return [
            self::STATUS_ACTIVE   => 'Đang hoạt động',
            self::STATUS_INACTIVE => 'Tạm khóa',
        ];
    }

    public static function getTreeCategories($sp_id = null)
    {
        return ArrayHelper::map(Category::getAllCategories(null, true, $sp_id), 'id', 'path_name');
    }

    public static function getAllChildCats( $parent = null)
    {
        if ($parent === null) {
            return [];
        }

        static $listCat = [];

        $cats = self::findAll([ 'parent_id' => $parent]);

        if (!empty($cats)) {
            foreach ($cats as $cat) {

                $listCat[$cat->id] = ['disabled' => true];
                self::getAllChildCats( $cat->id);
            }
        }

        return $listCat;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getParent()
    {
        return $this->hasOne(Category::className(), ['id' => 'parent_id']);
    }
    public function getStatusName()
    {
        $listStatus = self::getListStatus();
        if (isset($listStatus[$this->status])) {
            return $listStatus[$this->status];
        }
        return '';
    }

    public static function getMenuTree($type)
    {
        if (empty(self::$catTree[$type])) {
            $query = Category::find();
            $query->andWhere(['category.level' => 0]);
            $query->andWhere(['category.status' => self::STATUS_ACTIVE]);
            $query->orderBy(['category.order_number' => SORT_ASC]);
            $rows = $query->all();
            // var_dump($cp_id);die;
            if (count($rows) > 0) {
                foreach ($rows as $item) {
                    /** @var $item Category */
                    self::$catTree[$type][] = self::getMenuItems($item);
                }
            } else {
                self::$catTree[$type] = [];
            }
            Yii::info(self::$catTree[$type]);
        }
        return self::$catTree[$type];

    }

    private static function getMenuItems($modelRow)
    {

        if (!$modelRow) {
            return;
        }

        if (isset($modelRow->categories)) {
            /** @var  $modelRow Category */
            $childCategories = $modelRow->getCategories();

            $chump = self::getMenuItems($childCategories);
            if ($chump != null) {
                $res = array('id' => $modelRow->id, 'label' => $modelRow->display_name, 'items' => $chump);
            } else {
                $res = array('id' => $modelRow->id, 'label' => $modelRow->display_name, 'items' => array());
            }
            return $res;
        } else {
            if (is_array($modelRow)) {
                $arr = array();
                foreach ($modelRow as $leaves) {
                    $arr[] = self::getMenuItems($leaves);
                }
                return $arr;
            } else {
                return array('id' => $modelRow->id, 'label' => ($modelRow->display_name));
            }
        }
    }

    public function getCategories()
    {
        return $this->hasMany(Category::className(), ['parent_id' => 'id'])
            ->andWhere(['category.status' => Category::STATUS_ACTIVE]) // Ai commen phải báo loop vì comment là không đúng
            ->orderBy(['order_number' => SORT_DESC])->all();

    }
}
