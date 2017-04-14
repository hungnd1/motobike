<?php

namespace common\models;

use Yii;
use yii\base\InvalidParamException;
use yii\helpers\Url;

/**
 * This is the model class for table "product".
 *
 * @property integer $id
 * @property string $display_name
 * @property string $ascii_name
 * @property string $code
 * @property integer $type
 * @property string $short_description
 * @property string $description
 * @property string $images
 * @property integer $status
 * @property integer $price
 * @property integer $price_promotion
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $approved_at
 * @property integer $like_count
 * @property integer $comment_count
 * @property integer $is_free
 *
 * @property ProductCategoryAsm[] $productCategoryAsm
 */
class Product extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */

    const STATUS_ACTIVE = 10; // Đã duyệt
    const STATUS_INACTIVE = 0; // khóa
    const STATUS_DELETE = 2; // Xóa
    const STATUS_PENDING = 3; // CHỜ DUYỆT

    const IMAGE_TYPE_THUMBNAIL = 1;
    const IMAGE_TYPE_SCREENSHOOT = 2;
    public $shopbikeProductAsm;

    public static function tableName()
    {
        return 'product';
    }

    public $thumbnail;
    public $screenshoot;
    public $list_cat_id;
    public $viewAttr = [];
    const MAX_SIZE_UPLOAD = 10485760; // 10 * 1024 * 1024

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_merge([
            [['display_name', 'code', 'status', 'list_cat_id', 'price','thumbnail'], 'required', 'on' => 'adminModify', 'message' => '{attribute} không được để trống'],
            [['display_name', 'code'], 'required', 'message' => Yii::t('app', '{attribute} không được để trống')],
            [
                [
                    'type',
                    'price',
                    'price_promotion',
                    'like_count',
                    'comment_count',
                    'status',
                    'created_at',
                    'updated_at',
                    'is_free',
                    'approved_at',
                ], 'integer',
            ],
            [['description', 'short_description', 'images'], 'string'],
            [['display_name', 'ascii_name'], 'string', 'max' => 255],
            [['code'], 'string', 'max' => 20],
            [['code'], 'unique', 'message' => Yii::t('app', '{attribute} đã tồn tại trên hệ thống. Vui lòng thử lại')],
            [['thumbnail', 'screenshoot'],
                'file',
                'tooBig' => Yii::t('app', '{attribute} vượt quá dung lượng cho phép. Vui lòng thử lại'),
                'wrongExtension' => Yii::t('app', '{attribute} không đúng định dạng'),
                'extensions' => 'png, jpg, jpeg, gif, PNG, JPG, JPEG, GIF',
                'maxSize' => self::MAX_SIZE_UPLOAD],
            [['thumbnail'], 'validateThumb', 'on' => ['adminModify', 'adminModifyLiveContent']],
            [['screenshoot'], 'validateScreen', 'on' => 'adminModify'],
            [['thumbnail', 'screenshoot'], 'image', 'extensions' => 'png,jpg,jpeg,gif,PNG,JPG,JPEG,GIF',
//                'minWidth' => 1, 'maxWidth' => 512,
//                'minHeight' => 1, 'maxHeight' => 512,
                'maxSize' => 1024 * 1024 * 10, 'tooBig' => Yii::t('app', 'Ảnh show  vượt quá dung lượng cho phép. Vui lòng thử lại'),
            ],
            [[ 'list_cat_id','shopbikeProductAsm'], 'safe'],
        ], $this->getValidAttr());
    }

    public function getValidAttr()
    {
        return [];

    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'display_name' => 'Tên sản phẩm',
            'ascii_name' => 'Ascii Name',
            'code' => 'Mã code',
            'type' => 'Type',
            'short_description' => 'Mô tả ngắn',
            'description' => 'Mô tả',
            'images' => 'Ảnh đại diện',
            'status' => 'Trạng thái',
            'price' => 'Price',
            'price_promotion' => 'Price Promotion',
            'created_at' => 'Ngày tạo',
            'updated_at' => 'Updated At',
            'approved_at' => 'Approved At',
            'like_count' => 'Like Count',
            'comment_count' => 'Comment Count',
            'is_free' => 'Is Free',
            'shopbikeProductAsm'=>'Hãng xe'
        ];
    }

    public function getFirstImageLink()
    {
        // var_dump(Url::base());die;
        $link = '';
        if (!$this->images) {
            return;
        }
        $listImages = self::convertJsonToArray($this->images);
        foreach ($listImages as $key => $row) {
            if ($row['type'] == self::IMAGE_TYPE_THUMBNAIL) {
                $link = Url::to(Url::base() . DIRECTORY_SEPARATOR . Yii::getAlias('@content_images') . DIRECTORY_SEPARATOR . $row['name'], true);
            }
        }

        return $link;
    }

    public static function convertJsonToArray($input)
    {
        $listImage = json_decode($input, true);
        // var_dump($listImage);die;
        $result = [];
        if (is_array($listImage)) {
            foreach ($listImage as $item) {
                $item = is_array($item) ? $item : json_decode($item, true);

                $row['name'] = $item['name'];
                $row['type'] = $item['type'];
                $row['size'] = $item['size'];
                $result[] = $row;
            }
        }

        return $result;
    }

    public static function getListStatus($type = 'all')
    {
        return ['all' => [
            self::STATUS_ACTIVE => 'Hoạt động',
            self::STATUS_PENDING => 'Chờ duyệt',
            self::STATUS_INACTIVE => 'Khóa',
        ],
            'filter' => [
                self::STATUS_ACTIVE => 'Hoạt động',
                self::STATUS_PENDING => 'Chờ duyệt',
                self::STATUS_INACTIVE => 'Khóa',
            ],
        ][$type];
    }

    public function validateThumb($attribute, $params)
    {
        if (empty($this->images)) {
            $this->addError($attribute, str_replace('(*)', '', $this->attributeLabels()[$attribute]) . ' không được để trống');
            return false;
        }
        $images = $this->convertJsonToArray($this->images, true);

        $thumb = array_filter($images, function ($v) {
            return $v['type'] == self::IMAGE_TYPE_THUMBNAIL;
        });

        if (count($thumb) === 0) {
            $this->addError($attribute, str_replace('(*)', '', $this->attributeLabels()[$attribute]) . ' không được để trống');
            return false;
        }
    }

    public function validateScreen($attribute, $params)
    {
        if (empty($this->images)) {
            $this->addError($attribute, str_replace('(*)', '', $this->attributeLabels()[$attribute]) . ' không được để trống');
            return false;
        }

        $images = $this->convertJsonToArray($this->images, true);

        $screenshoot = array_filter($images, function ($v) {
            return $v['type'] == self::IMAGE_TYPE_SCREENSHOOT;
        });

        if (count($screenshoot) === 0) {
            $this->addError($attribute, str_replace('(*)', '', $this->attributeLabels()[$attribute]) . ' không được để trống');
            return false;
        }
    }

    public function createCategoryAsm()
    {
        ProductCategoryAsm::deleteAll(['product_id' => $this->id]);
        if ($this->list_cat_id) {
            $listCatIds = explode(',', $this->list_cat_id);
            if (is_array($listCatIds) && count($listCatIds) > 0) {
                foreach ($listCatIds as $catId) {
                    $catAsm = new ProductCategoryAsm();
                    $catAsm->product_id = $this->id;
                    $catAsm->category_id = $catId;
                    $catAsm->save();
                }
            }

            return true;
        }

        return true;
    }
    public function createShopbikeProductAsm(){
        if($this->shopbikeProductAsm){
            if(is_array($this->shopbikeProductAsm) && count($this->shopbikeProductAsm) > 0){
                foreach($this->shopbikeProductAsm as $cat){
                    $productShopbikeAsm = ProductShopbikeAsm::findOne(['product_id'=>$this->id,'shopbike_id'=>$cat]);
                    if(!$productShopbikeAsm){
                        $shopbikeasm = new ProductShopbikeAsm();
                        $shopbikeasm->product_id = $this->id;
                        $shopbikeasm->shopbike_id = $cat;
                        $shopbikeasm->created_at = time();
                        $shopbikeasm->updated_at = time();
                        $shopbikeasm->save();
                    }
                }
            }
        }
    }

    public function spUpdateStatus($newStatus)
    {
        $oldStatus = $this->status;
        $listStatusNew = self::getListStatus('filter');
        $this->status = $newStatus;
        if($newStatus == Product::STATUS_ACTIVE){
            $this->approved_at = time();
        }
        $this->updated_at = time();
        return $this->update(false);
    }

    public function getListCatIds()
    {
        $listCat = $this->productCategoryAsm;
        $listCatId = [];
        foreach ($listCat as $catAsm) {
            $listCatId[] = $catAsm->category_id;
        }

        return $listCatId;
    }

    public function getProductCategoryAsm()
    {
        return $this->hasMany(ProductCategoryAsm::className(), ['product_id' => 'id']);
    }

    public static function getListImageType()
    {
        return [
            self::IMAGE_TYPE_SCREENSHOOT => 'Screenshoot',
            self::IMAGE_TYPE_THUMBNAIL => 'Thumbnail',
        ];
    }

    public function getImages()
    {
        try {
            $res = [];
            $images = $this->convertJsonToArray($this->images);
            $maxThumb = 0;
            if ($images) {
                for ($i = 0; $i < count($images); ++$i) {
                    $item = $images[$i];
                    if ($item['type'] == self::IMAGE_TYPE_THUMBNAIL) {
                        $maxThumb = $i;
                    }
                    $image = new \backend\models\Image();
                    $image->type = $item['type'];
                    $image->name = $item['name'];
                    $image->size = $item['size'];
                    array_push($res, $image);
                }

                return $res;
            }
        } catch (InvalidParamException $ex) {
            $images = null;
        }

        return $images;
    }

    public function getCssStatus()
    {
        switch ($this->status) {
            case self::STATUS_ACTIVE:
                return 'label label-primary';
            case self::STATUS_INACTIVE:
                return 'label label-warning';
            case self::STATUS_DELETE:
                return 'label label-danger';
            case self::STATUS_PENDING:
                return 'label label-info';
            default:
                return 'label label-primary';
        }
    }

    public function getStatusName()
    {
        $listStatus = self::getListStatus();
        if (isset($listStatus[$this->status])) {
            return $listStatus[$this->status];
        }

        return '';
    }
}
