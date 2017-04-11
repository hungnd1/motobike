<?php

namespace common\models;

use api\helpers\Message;
use api\models\ListContent;
use common\helpers\CUtils;
use common\helpers\CVietnameseTools;
use sp\models\Image;
use Yii;
use yii\base\InvalidParamException;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;


/**
 * This is the model class for table "content".
 *
 * @property int $id
 * @property string $display_name
 * @property string $code
 * @property string $ascii_name
 * @property int $type
 * @property string $tags
 * @property string $short_description
 * @property string $description
 * @property string $content
 * @property int $duration
 * @property string $urls
 * @property int $version_code
 * @property string $version
 * @property int $view_count
 * @property int $download_count
 * @property int $like_count
 * @property int $dislike_count
 * @property float $rating
 * @property int $rating_count
 * @property int $comment_count
 * @property int $favorite_count
 * @property int $is_catchup
 * @property string $images
 * @property int $status
 * @property int $created_at
 * @property int $updated_at
 * @property int $honor
 * @property int $approved_at
 * @property string $admin_note
 * @property int $is_series
 * @property int $episode_count
 * @property int $episode_order
 * @property int $parent_id
 * @property Content $parent
 * @property int $created_user_id
 * @property int $day_download
 * @property string $author
 * @property string $director
 * @property string $actor
 * @property string $country
 * @property string $language
 * @property string $origin_url
 * @property string $en_name
 * @property int $view_date
 * @property int $tvod1_id
 * @property int $updated_tvod1
 * @property int $catchup_id
 * @property int $default_site_id
 * @property int $default_category_id
 * @property int $order
 *
 * @property ContentActorDirectorAsm[] $contentActorDirectorAsms
 * @property ContentAttributeValue[] $contentAttributeValues
 * @property ContentCategoryAsm[] $contentCategoryAsms
 * @property ContentFeedback[] $contentFeedbacks
 * @property ContentLog[] $contentLogs
 * @property ContentProfile[] $contentProfiles
 * @property ContentRelatedAsm[] $contentRelatedAsms
 * @property ContentRelatedAsm[] $contentRelatedAsms0
 * @property ContentSiteAsm[] $contentSiteAsms
 * @property ContentViewLog[] $contentViewLogs
 * @property LiveProgram[] $livePrograms
 * @property LiveProgram[] $livePrograms0
 * @property SubscriberContentAsm[] $subscriberContentAsms
 * @property SubscriberFavorite[] $subscriberFavorites
 * @property SubscriberTransaction[] $subscriberTransactions
 * @property SumContentDownload[] $sumContentDownloads
 * @property SumContentView[] $sumContentViews
 */
class Content extends \yii\db\ActiveRecord
{
    const IMAGE_TYPE_LOGO          = 1;
    const IMAGE_TYPE_THUMBNAIL     = 2;
    const IMAGE_TYPE_SCREENSHOOT   = 3;
    const IMAGE_TYPE_SLIDE         = 4;
    const IMAGE_TYPE_THUMBNAIL_EPG = 5;

    public $logo;
    public $thumbnail_epg;
    public $thumbnail;
    public $screenshoot;
    public $slide;
    public $image_tmp;
    public $live_channel;
    public $started_at;
    public $ended_at;
    public $content_related_asm;
    public $channel_name;
    public $channel_id;
    public $pricing_content;
    public $related_content = [];
    public $related_name;
    public $contentAttr = [];
    public $viewAttr    = [];
    public $validAttr   = [];
    public $live_status;
    public $content_actors;
    public $content_directors;
    public $site_name;
    public $content_site_asm_status;
    public $site_id;
    public $pricing_id;
    public $is_free;
    public $epg_status;
    public $time_sync_sent;
    public $time_sync_received;

    public $price_coin;
    public $price_sms;
    public $watching_period;

    const STATUS_ACTIVE         = 10; // Đã duyệt
    const STATUS_INACTIVE       = 0; // khóa
    const STATUS_REJECTED       = 1; // Từ chối
    const STATUS_DELETE         = 2; // Xóa
    const STATUS_PENDING        = 3; // CHỜ DUYỆT
    const STATUS_INVISIBLE      = 4; // ẨN
    const STATUS_DRAFT          = 5;
    const STATUS_WAIT_TRANSCODE = 6;
    const STATUS_WAIT_TRANSFER  = 7;

    const DEFAULT_SITE_ID = 5; // Viet Nam

    const HONOR_NOTHING  = 0;
    const HONOR_FEATURED = 1;
    const HONOR_HOT      = 2;
    const HONOR_ESPECIAL = 3;

    const ORDER_NEWEST   = 0;
    const ORDER_MOSTVIEW = 1;
    const ORDER_EPISODE  = 2; //phim bộ
    const ORDER_ORDER    = 3; //order
    const ORDER_ID       = 4; //id
    const ORDER_TITLE    = 5; //title

    const IS_MOVIES = 0;
    const IS_SERIES = 1;

    const NOT_FREE = 0;
    const IS_FREE  = 1;

    const NOT_CATCHUP = 0;
    const IS_CATCHUP = 1;

    const TYPE_VIDEO        = 1;
    const TYPE_LIVE         = 2;
    const TYPE_MUSIC        = 3;
    const TYPE_NEWS         = 4;
    const TYPE_CLIP         = 5;
    const TYPE_KARAOKE      = 6;
    const TYPE_RADIO        = 7;
    const TYPE_LIVE_CONTENT = 8;

    const NEXT_VIDEO = 1;
    const PREVIOUS_VIDEO = 2;

    const IS_SINGER = "singer";
    const IS_ACTOR = "actor";

    const MAX_SIZE_UPLOAD = 10485760; // 10 * 1024 * 1024
    public static function getListHonor()
    {
        $lst = [
            self::HONOR_NOTHING  => Yii::t('app','All'),
            self::HONOR_FEATURED => Yii::t('app','Đặc sắc'),
            self::HONOR_HOT      => Yii::t('app','Hot'),
            self::HONOR_ESPECIAL => Yii::t('app','Đặc biệt'),
        ];
        return $lst;
    }

    /**
     * @return int
     */
    public function getHonorName()
    {
        $lst = self::getListHonor();
        if (array_key_exists($this->honor, $lst)) {
            return $lst[$this->honor];
        }
        return $this->honor;
    }


    public static function getListFilmType()
    {
        $lst = [
            self::IS_MOVIES => Yii::t('app','Phim lẻ'),
            self::IS_SERIES => Yii::t('app','Phim bộ'),
        ];
        return $lst;
    }

    public static $list_honor = [
        self::HONOR_NOTHING => 'All',
        self::HONOR_FEATURED => 'Đặc sắc',
        self::HONOR_HOT => 'Hot',
        self::HONOR_ESPECIAL => 'Đặc biệt',
    ];


    /**
     * @return int
     */
    public function getTypeFilmName()
    {
        $lst = self::getListFilmType();
        if (array_key_exists($this->is_series, $lst)) {
            return $lst[$this->is_series];
        }
        return $this->is_series;
    }

    public $list_cat_id;
    public $subtitles;
    public $assignment_sites;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'content';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return array_merge([
            [['display_name', 'code', 'created_user_id', 'is_series', 'status', 'assignment_sites', 'list_cat_id'], 'required', 'on' => 'adminModify', 'message' => Yii::t('app','{attribute} không được để trống')],
            [['started_at', 'ended_at'], 'required', 'message' => Yii::t('app','{attribute} không được để trống'), 'on' => 'adminModifyLiveContent'],
            [['ended_at'], 'validEnded', 'on' => 'adminModifyLiveContent'],
            [['display_name', 'code', 'created_user_id'], 'required', 'message' => Yii::t('app','{attribute} không được để trống')],
            [
                [
                    'type',
                    'duration',
                    'version_code',
                    'view_count',
                    'download_count',
                    'like_count',
                    'dislike_count',
                    'rating_count',
                    'comment_count',
                    'favorite_count',
                    'is_catchup',
                    'status',
                    'created_at',
                    'updated_at',
                    'honor',
                    'approved_at',
                    'is_series',
                    'episode_count',
                    'episode_order',
                    'parent_id',
                    'created_user_id',
                    'day_download',
                    'view_date',
                    'tvod1_id',
                    'updated_tvod1',
                    'catchup_id',
                    'default_site_id',
                    'default_category_id',
                    'order',
                ], 'integer',
            ],
            [['description', 'content', 'urls', 'images', 'short_description', 'images', 'en_name'], 'string'],
            ['display_name', 'unique', 'targetAttribute' => 'display_name', 'filter' => ['type' => self::TYPE_LIVE], 'message' => Yii::t('app','Tên kênh đã tồn tại'), 'when' => function ($model) {
                return $model->type == self::TYPE_LIVE;
            }],
            [['rating'], 'number'],
            [['display_name', 'ascii_name', 'author', 'director', 'actor', 'country', 'origin_url'], 'string', 'max' => 128],
            [['code'], 'string', 'max' => 20],
            [['content_actors', 'content_directors', 'assignment_sites'], 'safe'],
            [['tags'], 'string', 'max' => 500],
            [['version'], 'string', 'max' => 64],
            [['admin_note'], 'string', 'max' => 4000],
            [['language'], 'string', 'max' => 10],
            [['code'], 'unique', 'message' => Yii::t('app','{attribute} đã tồn tại trên hệ thống. Vui lòng thử lại')],
            [['thumbnail', 'thumbnail_epg', 'screenshoot'],
                'file',
                'tooBig'         => Yii::t('app','{attribute} vượt quá dung lượng cho phép. Vui lòng thử lại'),
                'wrongExtension' => Yii::t('app','{attribute} không đúng định dạng'),
                'uploadRequired' => Yii::t('app','{attribute} không được để trống'),
                'extensions'     => 'png, jpg, jpeg, gif',
                'maxSize'        => self::MAX_SIZE_UPLOAD],
            [['thumbnail'], 'validateThumb', 'on' => ['adminModify', 'adminModifyLiveContent']],
            [['screenshoot'], 'validateScreen', 'on' => 'adminModify'],
            [['thumbnail'], 'image', 'extensions' => 'png,jpg,jpeg,gif',
                'minWidth'  => 1, 'maxWidth'              => 512,
                'minHeight' => 1, 'maxHeight'             => 512,
                'maxSize'   => 1024 * 1024 * 10, 'tooBig' => Yii::t('app','Ảnh poster dọc vượt quá dung lượng cho phép. Vui lòng thử lại'),
            ],
            [['image_tmp', 'list_cat_id'], 'safe'],
            // [['subtitles'], 'file', 'extensions' => ['txt', 'smi', 'srt', 'ssa', 'sub', 'ass', 'style'], 'maxSize' => 1024 * 1024 * 10],
        ], $this->getValidAttr());
    }

    public function validEnded($attribute, $params)
    {
        if (strtotime($this->ended_at) < strtotime($this->started_at)) {
            $this->addError($attribute, $this->attributeLabels()[$attribute] .Yii::t('app', ' phải lớn hơn ') . $this->attributeLabels()['started_at']);
            return false;
        }
    }

    public function validateThumb($attribute, $params)
    {
        if (empty($this->images)) {
            $this->addError($attribute, str_replace('(*)', '', $this->attributeLabels()[$attribute]) .Yii::t('app', ' không được để trống'));
            return false;
        }
        $images = $this->convertJsonToArray($this->images, true);

        $thumb = array_filter($images, function ($v) {
            return $v['type'] == self::IMAGE_TYPE_THUMBNAIL;
        });

        if (count($thumb) === 0) {
            $this->addError($attribute, str_replace('(*)', '', $this->attributeLabels()[$attribute]) . Yii::t('app',' không được để trống'));
            return false;
        }
    }

    public function validateScreen($attribute, $params)
    {
        if ($this->type == self::TYPE_LIVE_CONTENT) {
            return true;
        }

        if (empty($this->images)) {
            $this->addError($attribute, str_replace('(*)', '', $this->attributeLabels()[$attribute]) .Yii::t('app', ' không được để trống'));
            return false;
        }

        $images = $this->convertJsonToArray($this->images, true);

        $screenshoot = array_filter($images, function ($v) {
            return $v['type'] == self::IMAGE_TYPE_SCREENSHOOT;
        });

        if (count($screenshoot) === 0) {
            $this->addError($attribute, str_replace('(*)', '', $this->attributeLabels()[$attribute]) .Yii::t('app', ' không được để trống'));
            return false;
        }
    }

    /** Không dùng thằng này mà phải tự add bằng tay */
    /**
     * {@inheritdoc}
     */
//    public function behaviors()
    //    {
    //        return [
    //            [
    //                'class'              => TimestampBehavior::className(),
    //                'createdAtAttribute' => 'created_at',
    //                'updatedAtAttribute' => 'updated_at',
    //            ],
    //        ];
    //    }

    /**
     * @param bool $insert
     * @return bool
     */
//    public function beforeSave($action)
    //    {
    //        if (parent::beforeSave($action)) {
    //            // ...custom code here...
    //            if($this->type == Content::TYPE_KARAOKE && $this->status == Content::STATUS_ACTIVE){
    //                $site_id = Yii::$app->user->identity->site_id;
    //                $items = \api\models\Content::find()
    //                    ->select(['content.id','display_name','ascii_name','short_description'])
    //                    ->andWhere(['type'=>$this->type,'status'=>Content::STATUS_ACTIVE,'is_series'=>Content::IS_MOVIES])
    //                    ->joinWith('contentSiteAsms')->andWhere(['site_id' =>$site_id])
    //                    ->all();
    ////                    ->limit(1)->all();
    //                $lst = [];
    //                foreach($items as $item){
    //                    $group_tmp = $item->getAttributes(['id','display_name','ascii_name','short_description'], ['created_user_id']);
    //                    $temp = "";
    //
    //                    $categoryAsms = $item->contentCategoryAsms;
    //                    if($categoryAsms){
    //                        foreach ($categoryAsms as $asm) {
    //                            /** @var $asm ContentCategoryAsm */
    //                            $temp .= $asm->category->id.',';
    //                        }
    //                        if(strlen($temp) > 2){
    //                            $temp = substr($temp,0,-1);
    //                        }
    //                    }
    //
    //                    $group_tmp['categories'] = $temp;
    //                    $tempA = "";
    //                    $tempD = "";
    //                    $contentActorDirectorAsms = $item->contentActorDirectorAsms;
    //                    if($contentActorDirectorAsms){
    //                        foreach ($contentActorDirectorAsms as $asm) {
    //                            if ($asm->actorDirector->type == ActorDirector::TYPE_ACTOR) {
    //                                /** @var $asm ContentCategoryAsm */
    //                                $tempA .= $asm->actorDirector->id . ',';
    //                            }
    //                            if ($asm->actorDirector->type == ActorDirector::TYPE_DIRECTOR) {
    //                                /** @var $asm ContentCategoryAsm */
    //                                $tempD .= $asm->actorDirector->id . ',';
    //                            }
    //                        }
    //                        if(strlen($temp) > 2){
    //                            $tempA = substr($tempA,0,-1);
    //                        }
    //                        if(strlen($temp) > 2){
    //                            $tempD = substr($tempD,0,-1);
    //                        }
    //                    }
    //                    $group_tmp['actors'] = $tempA;
    //                    $group_tmp['directors'] = $tempD;
    //                    $group_tmp['shortname'] = CUtils::parseTitleToKeyword($item->display_name);
    //
    //                    array_push($lst,$group_tmp);
    //                }
    //
    //                $res = [
    //                    'success' => true,
    //                    'message' => Message::MSG_SUCCESS,
    //                    'totalCount' => count($lst),
    //                    'time_update' => time(),
    //                    "date_expired" =>"01/01/2018",
    //                ];
    //                $res['items'] = $lst;
    //                $resJson = json_encode($res);
    //                $path = 'staticdata/data'.$site_id.'.json';
    //                $save2File = CUtils::writeFile($resJson,$path);
    //                if($save2File){
    //                    Yii::info("########CUONGVM success");
    //                }else{
    //                    Yii::info("########CUONGVM false");
    //                }
    //
    //            }
    //            return true;
    //        } else {
    //            return false;
    //        }
    //    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {

            if ($this->status == self::STATUS_ACTIVE) {
                $this->approved_at = time();
            }

            return true;
        } else {
            return false;
        }
    }

    public function getValidAttr()
    {
        return [];

        // $this->getContentAttr();
        // // var_dump($this->validAttr);die;
        // return $this->validAttr;
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id'                  => Yii::t('app','ID'),
            'display_name'        => Yii::t('app', 'Tên hiển thị'),
            'code'                => Yii::t('app','Code'),
            'ascii_name'          => Yii::t('app','Ascii Name'),
            'type'                => Yii::t('app','Type'),
            'tags'                => Yii::t('app','Đánh dấu'),
            'short_description'   => Yii::t('app','Mô tả ngắn'),
            'description'         => Yii::t('app','Mô tả'),
            'content'             => Yii::t('app','Mô tả'),
            'duration'            =>Yii::t('app', 'Duration'),
            'urls'                => Yii::t('app','Urls'),
            'version_code'        => Yii::t('app','Version Code'),
            'version'             => Yii::t('app','Version'),
            'view_count'          => Yii::t('app','View Count'),
            'download_count'      => Yii::t('app','Download Count'),
            'like_count'          => Yii::t('app','Like Count'),
            'dislike_count'       => Yii::t('app','Dislike Count'),
            'rating'              => Yii::t('app','Rating'),
            'rating_count'        => Yii::t('app','Rating Count'),
            'comment_count'       => Yii::t('app','Comment Count'),
            'favorite_count'      => Yii::t('app','Favorite Count'),
            'is_catchup'          => Yii::t('app', 'Truyền hình xem lại'),
            'images'              => Yii::t('app','Images'),
            'status'              => Yii::t('app', 'Trạng thái'),
            'created_at'          => Yii::t('app','Ngày tạo'),
            'updated_at'          => Yii::t('app','Ngày cập nhật'),
            'honor'               => Yii::t('app','Honor'),
            'approved_at'         => Yii::t('app','Ngày phê duyệt'),
            'admin_note'          => Yii::t('app','Admin Note'),
            'is_series'           => Yii::t('app','Thể loại'),
            'episode_count'       =>Yii::t('app', 'Episode Count'),
            'episode_order'       => Yii::t('app','Sắp xếp'),
            'parent_id'           => Yii::t('app','Parent ID'),
            'created_user_id'     => Yii::t('app','Created User ID'),
            'day_download'        => Yii::t('app','Day Download'),
            'author'              => Yii::t('app','Author'),
            'director'            => Yii::t('app','Director'),
            'actor'               => Yii::t('app','Actor'),
            'country'             => Yii::t('app','Country'),
            'language'            => Yii::t('app','Language'),
            'view_date'           => Yii::t('app','View Date'),
            'tvod1_id'            => Yii::t('app','Tvod1 ID'),
            'assignment_sites'    => Yii::t('app','Nhà cung cấp dịch vụ'),
            'thumbnail_epg'       => Yii::t('app','Ảnh Poster dọc'),
            'thumbnail'           => Yii::t('app','Ảnh Poster dọc (*)'),
            'screenshoot'         => Yii::t('app','Ảnh Slide show (*)'),
            'list_cat_id'         => Yii::t('app','Danh mục  nội dung'),
            'started_at'          => Yii::t('app','Thời gian bắt đầu'),
            'ended_at'            => Yii::t('app','Thời gian kết thúc'),
            'live_channel'        => Yii::t('app','Kênh Live'),
            'default_site_id'     => Yii::t('app','Nhà cung cấp dịch vụ gốc'),
            'default_category_id' => Yii::t('app','Danh mục'),
            'content_related_asm' => Yii::t('app','Nội dung liên quan'),
            'order'               => Yii::t('app','Sắp xếp'),
            'content_directors'   => $this->type == self::TYPE_VIDEO ? Yii::t('app','Đạo diễn') : Yii::t('app','Nhạc sĩ'),
            'content_actors'      => $this->type == self::TYPE_VIDEO ? Yii::t('app','Diễn viên') : Yii::t('app','Ca sĩ'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getParent()
    {
        return $this->hasOne(Content::className(), ['id' => 'parent_id']);
    }

    public function getContentActorDirectorAsms($type = ActorDirector::TYPE_DIRECTOR)
    {
        return $this->hasMany(ContentActorDirectorAsm::className(), ['content_id' => 'id']);
    }

    public function getContentAttributeValues()
    {
        return $this->hasMany(ContentAttributeValue::className(), ['content_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getContentCategoryAsms()
    {
        return $this->hasMany(ContentCategoryAsm::className(), ['content_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getContentFeedbacks()
    {
        return $this->hasMany(ContentFeedback::className(), ['content_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getContentLogs()
    {
        return $this->hasMany(ContentLog::className(), ['content_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getContentProfiles()
    {
        return $this->hasMany(ContentProfile::className(), ['content_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getContentRelatedAsms()
    {
        return $this->hasMany(ContentRelatedAsm::className(), ['content_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getContentRelatedAsms0()
    {
        return $this->hasMany(ContentRelatedAsm::className(), ['content_related_id' => 'id']);
    }

    /**
     * @author *
     * @return $this
     */
    public function getRelatedContent()
    {
        /** return a query hasMany */
        return $this->hasMany(Content::className(), ['id' => 'content_related_id'])->viaTable('{{%content_related_asm}}', ['content_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
//    public function getContentSiteAsms($site_id)
    //    {
    //        return $this->hasMany(ContentSiteAsm::className(), ['content_id' => 'id'])->where('site_id > :site_id', [':site_id' => $site_id]);
    //    }

    public function getContentSiteAsms()
    {
        return $this->hasMany(ContentSiteAsm::className(), ['content_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getContentViewLogs()
    {
        return $this->hasMany(ContentViewLog::className(), ['content_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLivePrograms()
    {
        return $this->hasMany(LiveProgram::className(), ['channel_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLivePrograms0()
    {
        return $this->hasMany(LiveProgram::className(), ['content_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSubscriberContentAsms()
    {
        return $this->hasMany(SubscriberContentAsm::className(), ['content_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSubscriberFavorites()
    {
        return $this->hasMany(SubscriberFavorite::className(), ['content_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSubscriberTransactions()
    {
        return $this->hasMany(SubscriberTransaction::className(), ['content_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSumContentDownloads()
    {
        return $this->hasMany(SumContentDownload::className(), ['content_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSumContentViews()
    {
        return $this->hasMany(SumContentView::className(), ['content_id' => 'id']);
    }

    public static function getListStatus($type = 'all')
    {
        return ['all' => [
            self::STATUS_ACTIVE         => Yii::t('app','Đã duyệt'),
            self::STATUS_INVISIBLE      => Yii::t('app','Ẩn'),
            self::STATUS_DRAFT          =>Yii::t('app', 'Nháp'),
            self::STATUS_DELETE         => Yii::t('app','Xóa'),
            self::STATUS_INACTIVE       => Yii::t('app','Khóa'),
            self::STATUS_PENDING        => Yii::t('app','Chờ duyệt'),
            self::STATUS_REJECTED       => Yii::t('app','Từ chối'),
            self::STATUS_WAIT_TRANSCODE => Yii::t('app','Chờ transcode'),
            self::STATUS_WAIT_TRANSFER  => Yii::t('app','Đang phân phối'),
        ],
            'filter'      => [
                self::STATUS_ACTIVE    => Yii::t('app','Đã duyệt'),
                self::STATUS_INVISIBLE => Yii::t('app','Ẩn'),
                self::STATUS_DRAFT     => Yii::t('app','Nháp'),
            ],
        ][$type];
    }

    public function getStatusName()
    {
        $listStatus = self::getListStatus();
        if (isset($listStatus[$this->status])) {
            return $listStatus[$this->status];
        }

        return '';
    }
    public static function listType()
    {
        return [
            self::TYPE_VIDEO   => Yii::t('app','Phim'),
            self::TYPE_CLIP    => Yii::t('app','Clip'),
            self::TYPE_LIVE    => Yii::t('app','Live'),
            self::TYPE_MUSIC   => Yii::t('app','Âm nhạc'),
            self::TYPE_NEWS    => Yii::t('app','Tin tức'),
            self::TYPE_KARAOKE => Yii::t('app','Karaoke'),
            self::TYPE_RADIO   => Yii::t('app','Radio'),
        ];
    }
    public static function listTypeBC()
    {
        return [
            self::TYPE_VIDEO   => Yii::t('app','Phim'),
            self::TYPE_CLIP    => Yii::t('app','Clip'),
            self::TYPE_LIVE    => Yii::t('app','Live'),
            self::TYPE_MUSIC   => Yii::t('app','Âm nhạc'),
            self::TYPE_NEWS    => Yii::t('app','Tin tức'),
            self::TYPE_KARAOKE => Yii::t('app','Karaoke'),
            self::TYPE_RADIO   => Yii::t('app','Radio'),
            self::TYPE_LIVE_CONTENT=> Yii::t('app','Live content'),
        ];
    }
    public function getTypeName()
    {
        $lst = self::listType();
        if (array_key_exists($this->type, $lst)) {
            return $lst[$this->type];
        }
        return $this->type;
    }
    public static  function getTypeNameById($type)
    {
        $lst = self::listType();
        if (array_key_exists($type, $lst)) {
            return $lst[$type];
        }
        return $type;
    }


    public function createCategoryAsm()
    {
        ContentCategoryAsm::deleteAll(['content_id' => $this->id]);
        if ($this->list_cat_id) {
            $listCatIds = explode(',', $this->list_cat_id);
            if (is_array($listCatIds) && count($listCatIds) > 0) {
                foreach ($listCatIds as $catId) {
                    $catAsm = new ContentCategoryAsm();
                    $catAsm->content_id = $this->id;
                    $catAsm->category_id = $catId;
                    $catAsm->save();
                }
            }

            return true;
        }

        return true;
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

    public static function getListImageType()
    {
        return [
            self::IMAGE_TYPE_LOGO          => Yii::t('app','Logo'),
            self::IMAGE_TYPE_SCREENSHOOT   => Yii::t('app','Screenshoot'),
            self::IMAGE_TYPE_THUMBNAIL     => Yii::t('app','Thumbnail'),
            self::IMAGE_TYPE_SLIDE         => Yii::t('app','Slide'),
            self::IMAGE_TYPE_THUMBNAIL_EPG => Yii::t('app','Thumbnail_epg'),
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
                    if ($item['type'] == self::IMAGE_TYPE_THUMBNAIL_EPG) {
                        $maxThumb = $i;
                    }
                    $image = new Image();
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

    public function getListCatIds()
    {
        $listCat = $this->contentCategoryAsms;
        $listCatId = [];
        foreach ($listCat as $catAsm) {
            $listCatId[] = $catAsm->category_id;
        }

        return $listCatId;
    }

    public static function getListContent(
        $sp_id,
        $type,
        $category = 0,
        $filter = 0,
        $keyword = '',
        $order,
        $language = ''
    )
    {
        $query = \api\models\Content::find()->andWhere(['created_user_id' => $sp_id]);
        if ($category > 0) {
            $query->joinWith('contentCategoryAsms');
            $query->andWhere(['category_id' => $category]);
        } else {
            if ($type > 0) {
                $query->andWhere(['`content`.`type`' => $type]);
            }
        }

        if ($filter > 0) {
            $query->andWhere(['`content`.`honor`' => $filter]);
        }

        if ($type > 0) {
            $query->andWhere(['`content`.`type`' => $type]);
        }

        if ($language != '') {
            $query->andWhere(['`content`.`country`' => $language]);
        }

        if ($keyword != '') {
            $keyword = CVietnameseTools::makeSearchableStr($keyword);
            $query->andwhere('`content`.`ascii_name` LIKE :query')
                ->addParams([':query' => '%' . $keyword . '%']);
        }
        $orderDefault = [];
        if ($order == self::ORDER_NEWEST) {
            $orderDefault['created_at'] = SORT_DESC;
        } else {
            $orderDefault['view_count'] = SORT_DESC;
        }
        $query->andWhere(['status' => self::STATUS_ACTIVE]);
        $query->andWhere('parent_id is null or parent_id = 0');
        $provider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => $orderDefault,
            ],
            'pagination' => [
                'defaultPageSize' => 10,
            ],
        ]);

        return $provider;
    }

    public static function getListContentSearch(
        $sp_id,
        $type = 0,
        $category = 0,
        $filter = 0,
        $keyword,
        $order,
        $language = ''
    )
    {
        $query = \api\models\Content::find()->andWhere(['created_user_id' => $sp_id]);
        if ($category > 0) {
            $query->joinWith('contentCategoryAsms');
            $query->andWhere(['category_id' => $category]);
        } else {
            if ($type > 0) {
                $query->andWhere(['`content`.`type`' => $type]);
            }
        }

        if ($filter > 0) {
            $query->andWhere(['`content`.`honor`' => $filter]);
        }

        if ($type > 0) {
            $query->andWhere(['`content`.`type`' => $type]);
        }

        if ($language != '') {
            $query->andWhere(['`content`.`country`' => $language]);
        }

        if ($keyword != '') {
            $keyword = CVietnameseTools::makeSearchableStr($keyword);
            $query->andwhere('`content`.`ascii_name` LIKE :query')
                ->addParams([':query' => '%' . $keyword . '%']);
        }
        $orderDefault = [];
        if ($order == self::ORDER_NEWEST) {
            $orderDefault['created_at'] = SORT_DESC;
        } else {
            $orderDefault['view_count'] = SORT_DESC;
        }

        $query->andWhere(['status' => self::STATUS_ACTIVE]);
        $provider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => $orderDefault,
            ],
            'pagination' => [
                'defaultPageSize' => 10,
            ],
        ]);

        return $provider;
    }

    public static function getListContentDetail(
        $sp_id,
        $id
    )
    {
        $protocol = ContentProfile::STREAMING_HLS;
        $arr = array();
        $i = 0;
        $query = self::find()->andWhere(['created_user_id' => $sp_id]);
        $query->andWhere(['`content`.`status`' => self::STATUS_ACTIVE]);
        $query->andWhere(['`content`.`parent_id`' => $id]);
        $query->orderBy(['episode_order' => SORT_ASC])->all();
        $command = $query->createCommand();
        $data = $command->queryAll();
        if (!$query->count()) {
            return false;
        }
        foreach ($data as $val) {
            $arr[$i] = new \stdClass();
            $arr[$i]->id = $val['id'];
            $video = self::findOne($val['id']);
            if ($video) {
                $arr[$i]->urls = $video->getStreamUrl($protocol, true);
            }
            ++$i;
        }

        return $arr;
    }

    /**
     * @param $sp_id
     * @param $id
     *
     * @return ActiveDataProvider
     */
    public static function getDetail($sp_id, $id)
    {
        $content = ContentSearch::find()
            ->andWhere(['created_user_id' => $sp_id])
            ->andWhere(['id' => $id])
            ->andWhere(['status' => self::STATUS_ACTIVE]);
        $dataProvider = new ActiveDataProvider([
            'query' => $content,
            'sort' => [],
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);

        return $dataProvider;
    }

    public static function getRelated($sp_id, $content_id)
    {
        /** @var  $content_category_asm ContentCategoryAsm */
        $content_category_asm = ContentCategoryAsm::findOne(['content_id' => $content_id]);
        if ($content_category_asm) {
            $category_id = $content_category_asm->category_id;
        } else {
            $category_id = -1;
        }
//        $query = ListContent::find()->andWhere(['created_user_id' => $sp_id]);
        $query = ListContent::find()->andWhere(['created_user_id' => $sp_id]);

        $query->joinWith('contentCategoryAsms');
        $query->andWhere(['category_id' => $category_id]);

        $query->andWhere(['status' => self::STATUS_ACTIVE]);
        $query->andwhere('`content`.`id` <> :query')
            ->addParams([':query' => $content_id]);
        $provider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'updated_at' => SORT_DESC,
                ],
            ],
            'pagination' => [
                'defaultPageSize' => 10,
            ],
        ]);

        return $provider;
    }

    /**
     * @return null|string
     */
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
            if ($row['type'] == self::IMAGE_TYPE_THUMBNAIL_EPG) {
                $link = Url::to(Url::base() . DIRECTORY_SEPARATOR . Yii::getAlias('@content_images') . DIRECTORY_SEPARATOR . $row['name'], true);
            }

        }

        return $link;
    }

    public function createContentLog(
        $type = ContentLog::TYPE_CREATE,
        $user_id = null,
        $ip_address = '',
        $status = ContentLog::STATUS_SUCCESS,
        $description = '',
        $user_agent = '',
        $content_name = ''
    )
    {
        $contentLog = new ContentLog();
        $contentLog->content_id = $this->id;
        // $contentLog->content_provider_id = $this->content_provider_id;
        // $contentLog->created_user_id = $this->created_user_id;
        $contentLog->description = $description;
        $contentLog->type = $type;
        $contentLog->user_id = $user_id;
        $contentLog->user_agent = $user_agent;
        $contentLog->ip_address = $ip_address;
        $contentLog->status = $status;
        $contentLog->content_name = $content_name;
        if ($contentLog->save()) {
            return $contentLog;
        }
        Yii::trace($contentLog->getErrors());

        return;
    }

    public static function getCPStatusAction($current_status)
    {
        switch ($current_status) {
            case self::STATUS_DRAFT:
                return [
                    self::STATUS_DELETE => Yii::t('app','Xóa'),
                    self::STATUS_DRAFT  => Yii::t('app','Nháp'),
                    self::STATUS_ACTIVE => Yii::t('app','Publish'),
                ];
            case self::STATUS_PENDING:
                return [
                    self::STATUS_DRAFT  => Yii::t('app','Nháp'),
                    self::STATUS_ACTIVE => Yii::t('app','Publish'),
                ];
            case self::STATUS_REJECTED:
                return [
                    self::STATUS_DRAFT    => Yii::t('app','Nháp'),
                    self::STATUS_DELETE   => Yii::t('app','Xóa'),
                    self::STATUS_REJECTED => Yii::t('app','Từ chối'),
                ];
            case self::STATUS_ACTIVE:
                return [
                    self::STATUS_DRAFT     => Yii::t('app','Nháp'),
                    self::STATUS_ACTIVE    => Yii::t('app','Đã Duyệt'),
                    self::STATUS_INVISIBLE => Yii::t('app','Ẩn'),
                ];
            case self::STATUS_INVISIBLE:
                return [

                    self::STATUS_ACTIVE    => Yii::t('app','Đã Duyệt'),
                    self::STATUS_INVISIBLE => Yii::t('app','Ẩn'),
                ];
            default:
                return [];
        }
    }

    public static function getSPStatusAction($current_status)
    {
        switch ($current_status) {
            case self::STATUS_DRAFT:
                return [
                    self::STATUS_DRAFT => Yii::t('app','Nháp'),
                ];
            case self::STATUS_PENDING:
                return [
                    self::STATUS_PENDING  => Yii::t('app','Chờ duyệt'),
                    self::STATUS_REJECTED => Yii::t('app','Từ chối'),
                    self::STATUS_ACTIVE   => Yii::t('app','Đã Duyệt'),

                ];
            case self::STATUS_REJECTED:
                return [
                    self::STATUS_REJECTED => Yii::t('app','Từ chối'),
                    self::STATUS_ACTIVE   => Yii::t('app','Đã Duyệt'),
                ];
            case self::STATUS_ACTIVE:
                return [
                    self::STATUS_REJECTED => Yii::t('app','Từ chối'),
                    self::STATUS_ACTIVE   => Yii::t('app','Đã Duyệt'),
                ];
            case self::STATUS_INVISIBLE:
                return [
                    self::STATUS_INVISIBLE => Yii::t('app','Ẩn'),
                ];
            default:
                return [];
        }
    }

    public function cpUpdateStatus($newStatus, $cp_id)
    {
        $oldStatus = $this->status;
        $listStatusNew = self::getListStatus();
        if (isset($listStatusNew[$newStatus]) && ($newStatus != self::STATUS_DELETE || ($newStatus == self::STATUS_DELETE && $oldStatus == self::STATUS_DRAFT))) {
            $this->status = $newStatus;
            // tao log
            $description = 'UPDATE STATUS CONTENT';
            $ip_address = CUtils::clientIP();
            $this->createContentLog(ContentLog::TYPE_EDIT, Yii::$app->user->id, $ip_address, ContentLog::STATUS_SUCCESS, $description, '', $this->display_name);
            return $this->update(false);
        }

        return false;
    }

    public function spUpdateStatus($newStatus, $sp_id)
    {
        $oldStatus = $this->status;
        $listStatusNew = self::getListStatus('filter');
        // $listStatusNew = Content::getSPStatusAction($oldStatus);
        // if ($sp_id != $this->created_user_id) {
        //     return false;
        // }
        // if (isset($listStatusNew[$newStatus])) {
        // var_dump(isset($listStatusNew[$newStatus]));die;
        if (isset($listStatusNew[$newStatus]) || ($newStatus == self::STATUS_DELETE && $oldStatus != self::STATUS_ACTIVE)) {
            $this->status = $newStatus;
            // tao log
            $description = 'UPDATE STATUS CONTENT';
            $ip_address = CUtils::clientIP();
            $this->createContentLog(ContentLog::TYPE_EDIT, Yii::$app->user->id, $ip_address, ContentLog::STATUS_SUCCESS,
                $description, '', $this->display_name);
            /** cuongvm 20160725 - phải insert created_at, updated_at bằng tay, không dùng behaviors - begin */
            $this->updated_at = time();
            /** cuongvm 20160725 - phải insert created_at, updated_at bằng tay, không dùng behaviors - end */
            return $this->update(false);
        }
        return false;
    }

    public function getCssStatus()
    {
        switch ($this->status) {
            case self::STATUS_ACTIVE:
                return 'label label-primary';
            case self::STATUS_INACTIVE:
                return 'label label-warning';
            case self::STATUS_DRAFT:
                return 'label label-default';
            case self::STATUS_DELETE:
                return 'label label-danger';
            case self::STATUS_PENDING:
                return 'label label-info';
            case self::STATUS_REJECTED:
                return 'label label-danger';
            default:
                return 'label label-primary';
        }
    }

    public static function getContentProfileRaw()
    {
        /*
         * @var $dataRaw ContentProfile
         */
        $dataRaw = ContentProfile::find()
            ->where(['type' => ContentProfile::TYPE_RAW])
            ->andWhere(['status' => ContentProfile::STATUS_RAW])
            ->one();
        if (!$dataRaw) {
            return [
                'error' => 1,
                'message' => 'No file raw',
            ];
        }
        $dataRaw->status = ContentProfile::STATUS_TRANCODE_PENDING;
        $dataRaw->update();

        return [
            'content_profile_id' => $dataRaw->id,
            'content_id' => $dataRaw->content_id,
            'cp_id' => 0,
            'url' => $dataRaw->getFilePath(ContentProfile::LOCATION_STORAGE),
//            'sub_path' => $dataRaw->getSubPath(ContentProfile::LOCATION_STORAGE),
        ];
    }

    /**
     * @param $contentProfile
     * @param $site_id
     * @return array
     */
    public static function getUrl($type_check, $contentProfile, $site_id)
    {
        switch ($contentProfile->type) {
            case ContentProfile::TYPE_RAW:
                /** Không xử lí với file RAW */
                $res = [
                    'success' => false,
                    'message' => Message::getNotFoundContentMessage(),
                ];
                return $res;
            case ContentProfile::TYPE_STREAM:
                /** @var  $cpsa ContentProfileSiteAsm */
                $cpsa = ContentProfileSiteAsm::findOne(['content_profile_id' => $contentProfile->id, 'site_id' => $site_id, 'status' => ContentProfileSiteAsm::STATUS_ACTIVE]);
                if (!$cpsa) {
                    $res['success'] = false;
                    $res['message'] = Message::getNotFoungContentProfileMessage();
                    return $res;
                }
                $response = ContentProfile::getStreamUrl($cpsa->url);
                if (!$response['success']) {
                    $res = [
                        'success' => false,
                        'message' => $response['message'],
                    ];
                    return $res;
                } else {
                    /** @var  $contentSiteAsm ContentSiteAsm */
                    $contentSiteAsm = ContentSiteAsm::findOne(['content_id' => $contentProfile->content_id, 'site_id' => $site_id]);
                    $subtitle = Content::getSubtitleUrl($contentSiteAsm->subtitle);
                    $res = [
                        'success' => true,
                        'url' => $response['url'],
                        'subtitle' => $subtitle,
                    ];
                    return $res;
                }

            case ContentProfile::TYPE_CDN;
                /** @var  $cpsa ContentProfileSiteAsm */
                $cpsa = ContentProfileSiteAsm::findOne(['content_profile_id' => $contentProfile->id, 'site_id' => $site_id, 'status' => ContentProfileSiteAsm::STATUS_ACTIVE]);

                if (!$cpsa) {
                    $res['success'] = false;
                    $res['message'] = Message::getNotFoungContentProfileMessage();
                    return $res;
                }
                $response = ContentProfile::getCdnUrl((int)$cpsa->url);
                /** Nếu CDN trả về false thì return kèm message */
                if (!$response['success']) {
                    $res = [
                        'success' => false,
                        'message' => $response['reason'],
                        'code' => $response['errorCode'],
                    ];
                } else {
                    /** Trường hợp CDN trả về true */
                    /** @var  $contentSiteAsm ContentSiteAsm */
                    $contentSiteAsm = ContentSiteAsm::findOne(['content_id' => $contentProfile->content_id, 'site_id' => $site_id]);
                    $subtitle = Content::getSubtitleUrl($contentSiteAsm->subtitle);
                    $url = $response['url'];
                    /**
                     * Nếu không phải site VN thì makeLink đúng vào con serverCache của nó
                     */
                    if ($site_id != (int)Yii::getAlias('@default_site_id')) {
                        /** @var  $streamingServer StreamingServer */
                        $streamingServer = SiteStreamingServerAsm::getStreamingServerPriority($site_id);
                        /** Nếu  có server cache thì mới makeLink còn không thì xem ở con serverCache gốc */
                        if ($streamingServer) {
                            $url = Content::makeLink($url, $streamingServer->ip);
                            if($type_check == Content::TYPE_LIVE){// add 13/01/2017
                                $url = Content::replaceUrl($url,$site_id);
                            }
                        }
                    }
                    $res = [
                        'success' => true,
                        'url' => $url,
                        'subtitle' => $subtitle,
                    ];
                }
                return $res;
        }
    }

    // TuanPV add delay to url if site != site_default
    public static  function replaceUrl($url,$site_id){
        $time = Delay::find()->andWhere(['site_id'=>$site_id])->andWhere(['status'=>Delay::STATUS_ACTIVE])->one();
        if(!isset($time)){
            return Yii::t('app','Lỗi! Độ trễ với site_id = '.$site_id.' chưa được cài đặt');
        }
        $ss = ($time->delay)*3600;
        $chars=explode('/',$url);
        $count=0;
        $str_ex = '';
        foreach($chars as $key => &$char)
        {
            if($char == 'e')
            {
                $str_ex = $chars[$key +1];
            }
        }
        $result = str_replace($str_ex, $str_ex.'/d/'.$ss, $url, $count);
        return $result;
    }

    /**
     * HungNV creation: 15/03/16: get list of Drama film without sub drama films.
     *
     * @param $type
     * @param $is_series
     * @param null $parent_id
     *
     * @return ActiveDataProvider
     */
    public static function getLiveDrama($type, $is_series, $parent_id = null)
    {
        $params = Yii::$app->request->queryParams;
        $drama = self::find()
            ->andWhere(['type' => $type]);
        if (isset($params['id'])) {
            $drama->andWhere(['id' => $params['id']]);
        }
        $drama->andWhere(['is_series' => $is_series])
            ->andWhere(['status' => self::STATUS_ACTIVE])
            ->andWhere(['IS', 'parent_id', $parent_id]);
        $dataProvider = new ActiveDataProvider([
            'query' => $drama,
            'sort' => [],
            'pagination' => [
                'defaultPageSize' => 10,
            ],
        ]);

        return $dataProvider;
    }

    /**
     * HungNV edition: 15/03/16.
     * HungNV creation: 15/03/16.
     *
     * @param $name
     *
     * @return array|\yii\db\ActiveRecord[]
     */
    public static function searchByName($name)
    {
        $res = self::find()
            ->orFilterWhere(['LIKE', 'display_name', '%' . $name . '%', false])
            ->orFilterWhere(['LIKE', 'ascii_name', '%' . $name . '%', false]);
        $provider = new ActiveDataProvider([
            'query' => $res,
            'sort' => [],
            'pagination' => [
                'defaultPageSize' => 10,
            ],
        ]);

        return $provider;
    }

    /**
     * HungNV creation: 31/03.
     *
     * @param $type
     * @param null $parent_id
     * @param null $language
     * @param null $order
     *
     * @return ActiveDataProvider
     */
    public static function getLives($type, $parent_id = null, $language = null, $order = null)
    {
        $res = self::find()
            ->andWhere(['type' => $type]);
        if (isset($parent_id) ? $parent_id : null) {
            $res->andWhere(['parent_id' => $parent_id]);
        }
        if ($language != null) {
            $res->andWhere(['language' => $language]);
        }
        $res->andWhere(['status' => self::STATUS_ACTIVE])
            ->orderBy(['created_at' => $order]);
        if (isset($res) ? $res : null) {
            // throw new Exception here
        }
        $provider = new ActiveDataProvider([
            'query' => $res,
            'sort' => [
            ],
            'pagination' => [
                'defaultPageSize' => 10,
            ],
        ]);

        return $provider;
    }

    public static function getTest()
    {
        return $test = self::find()
            ->andWhere(['id' => 307])
            ->all();
    }

    public static function listLive()
    {
        $lives = self::findAll(['type' => self::TYPE_LIVE]);
        $listLives = [];
        foreach ($lives as $live) {
            $listLives[$live->id] = $live->display_name;
        }
        arsort($listLives);

        return $listLives;
    }

    public function getReadonlyAssignment_sites()
    {
        $readOnlySite = ContentSiteAsm::find()->where(['AND', ['content_id' => $this->id], ['!=', 'status', ContentSiteAsm::STATUS_NOT_TRANSFER]])->all();
        return ArrayHelper::map($readOnlySite, 'id', 'site_id');
    }

    public function getSubtitles()
    {
        $this->subtitles = ContentSiteAsm::getSiteList(['content_id' => $this->id], ['id', 'subtitle']);
    }

    public function getAssignment_sites()
    {
        $this->assignment_sites = ContentSiteAsm::getSiteList(['content_id' => $this->id], ['id', 'site_id']);
    }

    public function setAssignment_sites($assignment_sites = null)
    {
        $assignment_sites = $this->assignment_sites;
        if (!empty($assignment_sites)) {
            ContentSiteAsm::deleteAll(['AND', ['content_id' => $this->id], ['NOT IN', 'site_id', $assignment_sites], ['OR', ['status' => ContentSiteAsm::STATUS_NOT_TRANSFER], in_array($this->type, [self::TYPE_LIVE, self::TYPE_NEWS]) ? 'true' : 'false', $this->is_series == self::IS_SERIES ? 'true' : 'false']]);
            foreach ($assignment_sites as $site_id) {
                $this->addSiteEpisode($site_id);

                $checkSiteAsm = ContentSiteAsm::findOne(['content_id' => $this->id, 'site_id' => $site_id]);

                if (!$checkSiteAsm) {
                    $siteAsm = new ContentSiteAsm();
                    $siteAsm->content_id = $this->id;
                    $siteAsm->site_id = $site_id;
                    $siteAsm->status = $site_id == $this->default_site_id || $this->type == self::TYPE_LIVE || $this->type == self::TYPE_NEWS || $this->is_series == self::IS_SERIES ? ContentSiteAsm::STATUS_ACTIVE : ContentSiteAsm::STATUS_NOT_TRANSFER;
                    if($siteAsm->insert()){
                        if($this->type == self::TYPE_LIVE){
                            $listProfiles = ContentProfile::findAll(['content_id' => $this->id]);
                            foreach ($listProfiles as $profile) {
                                if(!ContentProfileSiteAsm::findOne(['site_id' => $this->site_id, 'content_profile_id' => $profile->id])){
                                    $cpsa = new ContentProfileSiteAsm();
                                    $cpsa->site_id = $site_id;
                                    $cpsa->content_profile_id = $profile->id;
                                    $cpsa->url = ContentProfileSiteAsm::findOne(['site_id' => $this->default_site_id, 'content_profile_id' => $profile->id])->url;
                                    $cpsa->status = ContentProfileSiteAsm::STATUS_ACTIVE;
                                    $cpsa->insert();
                                }
                                
                            }
                        }
                    }
                } else {
                    if ($this->subtitles[$site_id]) {
                        // var_dump($this->subtitles[$site_id]);die;
                        $sub = $this->subtitles[$site_id];
                        $subUploadName = $site_id . '.' . $this->id . '.' . time() . '.' . rand(100, 999) . '.' . $sub->extension;
                        $sub->saveAs(Yii::getAlias('@webroot') . '/' . Yii::getAlias('@content_images') . '/subtitle/' . $subUploadName);

                        $checkSiteAsm->subtitle = $subUploadName;
                        $checkSiteAsm->save();
                    }
                }


            }

            // var_dump($assignment_sites);die;
            return true;
        }

        ContentSiteAsm::deleteAll(['content_id' => $this->id]);
        return false;
    }

    public function addSiteEpisode($site_id)
    {
        if ($this->is_series == self::IS_SERIES) {
            $episode = Content::findAll(['status' => self::STATUS_ACTIVE, 'parent_id' => $this->id]);
            foreach ($episode as $content) {
                $checkSiteAsm = ContentSiteAsm::findOne(['content_id' => $content->id, 'site_id' => $site_id]);

                if (!$checkSiteAsm) {
                    $siteAsm = new ContentSiteAsm();
                    $siteAsm->content_id = $content->id;
                    $siteAsm->site_id = $site_id;
                    $siteAsm->status = ContentSiteAsm::STATUS_NOT_TRANSFER;
                    $siteAsm->insert();
                }
            }
        }
    }

    public function saveRelatedContent()
    {
        ContentRelatedAsm::deleteAll(['content_id' => $this->id]);
        // var_dump($this->content_related_asm);die;
        if ($this->content_related_asm) {
            foreach ($this->content_related_asm as $content) {
                $related = new ContentRelatedAsm();
                $related->content_id = $this->id;
                $related->content_related_id = $content;
                $related->insert();
            }
        }
        return true;
    }

    public function getRelatedContents()
    {
        $output = [];
        foreach ($this->contentRelatedAsms as $related) {
            $output[] = $related->id;
        }
        return $this->related_content = $output;
    }

    public function getContentAttr($mode = null)
    {
        $contentAttributeValues = $this->contentAttributeValues;
        $extraAttr = $this->getExtraAttr('view');
        $validData = $this->getExtraAttr('validation');
        $contentAttr = [];
        $viewAttr = [];
        $validAttr = [];

        if ($contentAttributeValues) {
            foreach ($contentAttributeValues as $value) {
                $contentAttr[$value->content_attribute_id] = $value->value;
                $viewAttr[] = [
                    'label' => $extraAttr[$value->content_attribute_id],
                    'value' => $value->value,
                ];
                $validAttr[] = [
                    CVietnameseTools::makeSearchableStr($extraAttr[$value->content_attribute_id]),
                    strtolower(ContentAttribute::getDatatype($validData[$value->content_attribute_id])),
                    'except' => 'updateStatus',
                ];
            }
        }

        $this->validAttr = $validAttr;
        $this->viewAttr = $viewAttr;
        return $this->contentAttr = $contentAttr;
    }

    public function getExtraAttr($mode = null)
    {

        if ($mode === 'view') {
            $out = [];
            foreach (ContentAttribute::findAll(['content_type' => $this->type]) as $value) {
                $out[$value->id] = $value->name;
            }
            return $out;
        }
        if ($mode === 'validation') {
            $out = [];
            foreach (ContentAttribute::findAll(['content_type' => $this->type]) as $value) {
                $out[$value->id] = $value->data_type;
            }
            return $out;
        }

        return ContentAttribute::findAll(['content_type' => $this->type]);
    }

    public function saveAttrValue()
    {
        ContentAttributeValue::deleteAll(['content_id' => $this->id]);
        $contentAttr = $this->contentAttr;
        // var_dump($contentAttr);die;
        if ($contentAttr) {
            foreach ($contentAttr as $k => $value) {
                $cValue = new ContentAttributeValue;

                $cValue->content_id = $this->id;
                $cValue->content_attribute_id = $k;
                $cValue->value = $value;
                $cValue->insert();
                // var_dump($cValue->getErrors());die;
            }
        }
    }

    public function getPriceContent($site_id)
    {
        $price = ContentSiteAsm::find()
            // ->select('pricing.id as pricing_id')
            ->innerJoin('pricing', 'pricing.id = content_site_asm.pricing_id')
            ->andWhere(['content_site_asm.site_id' => $site_id])
            ->andwhere(['content_site_asm.content_id' => $this->id])
            ->one();

        $defaultPrice = Site::findOne($site_id)->default_price_content_id;

        $price = $price === null ? $defaultPrice : $price->pricing_id;

        $this->pricing_content = $price;

        return $price;
    }

    public function getEpisodeOrder()
    {
        $episodes = Content::find()
                ->select('episode_order')
                ->andwhere(['parent_id' => $this->parent_id])
                ->orderBy(['episode_order' => SORT_DESC])->all();
                
        if(count($episodes) > 0){
            return $this->parent && $this->type != self::TYPE_LIVE_CONTENT ?
            $episodes[0]->episode_order + 1
            : null;
        }
        
        return 0;
    }

    /**
     * @param $target_site_id
     * @param $streaming_server_id
     * @return mixed
     */
//    public static function syncDataToSite_new($target_site_id, $streaming_server_id,$force_download =false)
//    {
//        if (!is_numeric($target_site_id)) {
//            $res['success'] = false;
//            $res['message'] = CUtils::replaceParam(Message::getNumberOnlyMessage(), ['site_id']);
//            return $res;
//        }
//        if (!is_numeric($streaming_server_id)) {
//            $res['success'] = false;
//            $res['message'] = CUtils::replaceParam(Message::getNumberOnlyMessage(), ['streaming_server_id']);
//            return $res;
//        }
//        /** @var  $streamAsm SiteStreamingServerAsm */
//        $streamAsm = SiteStreamingServerAsm::findOne(['streaming_server_id' => $streaming_server_id]);
//
//        if (!$streamAsm) {
//            $res['success'] = false;
//            $res['message'] = Message::getNotFoundStreamMessage();
//            return $res;
//        }
//
//        /** @var  $stream StreamingServer */
//        $stream              = $streamAsm->streamingServer;
//        $url                 = $stream->content_api;
//        $streaming_server_ip = $stream->ip;
//        $content_folder      = $stream->content_path;
//
//        /** Bỏ phần check theo trạng thái contentSite mà mình sẽ quét all */
//        /** Update 20160721 check chỉ lấy những thằng Content có trạng thái STATUS_ACTIVE và ContentSiteAsm có trạng thái là  STATUS_NOT_TRANSFER, STATUS_TRANSFER_ERROR theo nội dung cuộc họp */
////        $items = Content::find()
////            ->innerJoin('content_site_streaming_server_asm', 'content.id=content_site_streaming_server_asm.content_id')
////            ->andWhere(['content_site_streaming_server_asm.site_id' => $target_site_id, 'content_site_streaming_server_asm.status' => [ContentSiteStreamingServerAsm::STATUS_NOT_TRANSFER, ContentSiteStreamingServerAsm::STATUS_TRANSFER_ERROR]])
////            ->andWhere(['content.status' => Content::STATUS_ACTIVE, 'content.type' => [
////                Content::TYPE_VIDEO,
////                Content::TYPE_MUSIC,
////                Content::TYPE_CLIP,
////                Content::TYPE_KARAOKE,
////                Content::TYPE_RADIO,
////                Content::TYPE_LIVE_CONTENT,
////            ]])
////            ->all();
//
//        $page = 0;
//        $pageSize = 100;
//        $limit = $pageSize;
//        $offset = $page++ * $pageSize + 1;
//        $items = Content::find()
//            ->innerJoin('content_site_asm', 'content.id=content_site_asm.content_id')
//            ->andWhere(['content_site_asm.site_id' => $target_site_id, 'content_site_asm.status' => [ContentSiteAsm::STATUS_NOT_TRANSFER, ContentSiteAsm::STATUS_TRANSFER_ERROR]])
//            ->andWhere(['content.status' => Content::STATUS_ACTIVE, 'content.type' => [
//                Content::TYPE_VIDEO,
//                Content::TYPE_MUSIC,
//                Content::TYPE_CLIP,
//                Content::TYPE_KARAOKE,
//                Content::TYPE_RADIO,
//                Content::TYPE_LIVE_CONTENT,
//            ]])->limit($limit)
//            ->offset($offset)
//            ->all();
//        while ($items != null) {
//            // xu ly lo item trang dau tien
//
//
//            // xu ly trang tiep theo
//            $offset = $page++ * $pageSize + 1;
//
//            $items = Content::find()
//                ->innerJoin('content_site_asm', 'content.id=content_site_asm.content_id')
//                ->andWhere(['content_site_asm.site_id' => $target_site_id, 'content_site_asm.status' => [ContentSiteAsm::STATUS_NOT_TRANSFER, ContentSiteAsm::STATUS_TRANSFER_ERROR]])
//                ->andWhere(['content.status' => Content::STATUS_ACTIVE, 'content.type' => [
//                    Content::TYPE_VIDEO,
//                    Content::TYPE_MUSIC,
//                    Content::TYPE_CLIP,
//                    Content::TYPE_KARAOKE,
//                    Content::TYPE_RADIO,
//                    Content::TYPE_LIVE_CONTENT,
//                ]])->limit($limit)
//                ->offset($offset)
//                ->all();
//
//        }
//
////        $contentHadDowloaded = ContentSiteStreamingServerAsm::find()->where([
////           'content_id' => $item->id,
////            'site_id' => $target_site_id,
////            'cache_id' => $streaming_server_id
////        ]);
////
////        $contentHadDowloaded == null -> // chua pp den cache cua site nay
////            //make call sang downloader, nhan ack cua downloader -> tạo bản ghi, ghi status là dang pp
////
////        $contentHadDowloaded != null -> //
////            status = (dang pp, pp loi, san sang)
//
//        /** Nếu không có content nào thỏa mãn thì thông báo  */
//        if (count($items) <= 0) {
//            $res['success'] = false;
//            $res['message'] = Message::getNotFoundContentMessage();
//            return $res;
//        }
////        echo count($items);exit;
//        $data                   = [];
//        $data['request_id']     = time();
//        $data['site_id']        = $target_site_id;
//        $data['content_folder'] = $content_folder;
//        $data['token']          = md5($data['request_id'] . $target_site_id);
//        $data['force_download'] = $force_download;
//
//        $arrItems               = [];
//        /** @var  $row */
//        foreach ($items as $row) {
//            $item = $row->getAttributes(['id', 'type', 'default_site_id'], ['tvod1_id']);
//            /** Kiểm tra xem có site_default_id hay không */
//            if (!$item['default_site_id']) {
//                continue;
//            }
//            /** Lấy danh sách content_profile của content không cần map theo site */
//            $contentProfiles = ContentProfile::find()
//                ->andWhere(['content_id' => $row['id'], 'type' => ContentProfile::TYPE_CDN, 'status' => ContentProfile::STATUS_ACTIVE])
//                ->all();
//            /** Nếu tồn tại quality thì mới xử lí */
//            if (!$contentProfiles) {
//                continue;
//            }
////            var_dump($row['id']);exit;
//            $is_check = false;
//            /** @var  $contentProfile ContentProfile */
//            foreach ($contentProfiles as $contentProfile) {
//                /** Chỉ xử lí content_profile thuộc defaultSite */
//                /** @var  $defaultContentProfileSite ContentProfileSiteAsm */
//                $defaultContentProfileSite = ContentProfileSiteAsm::findOne(['content_profile_id' => $contentProfile->id, 'site_id' => $item['default_site_id'], 'status' => ContentProfileSiteAsm::STATUS_ACTIVE]);
//                if (!$defaultContentProfileSite) {
//                    continue;
//                }
//                /** Nếu content_profile này đã có trong targetSite thì thôi không xử lí */
//                $targetContentProfileSite = ContentProfileSiteAsm::findOne(['content_profile_id' => $contentProfile->id, 'site_id' => $target_site_id, 'status' => ContentProfileSiteAsm::STATUS_ACTIVE]);
//                if ($targetContentProfileSite) {
//                    continue;
//                }
//
//                /** Get object content_priofile để xử lí*/
//                $contentProfileDefault = $defaultContentProfileSite->contentProfile;
//                /** Nếu là kiểu CDN thì mới xử lí */
//                if ($contentProfileDefault->type != ContentProfile::TYPE_CDN) {
//                    continue;
//                }
//                $is_check = false;
//                /** @var  $contentProfile ContentProfile */
//                foreach ($contentProfiles as $contentProfile) {
//                    /** Chỉ xử lí content_profile thuộc defaultSite */
//                    /** @var  $defaultContentProfileSite ContentProfileSiteAsm */
//                    $defaultContentProfileSite = ContentProfileSiteAsm::findOne(['content_profile_id' => $contentProfile->id, 'site_id' => $item['default_site_id'], 'status' => ContentProfileSiteAsm::STATUS_ACTIVE]);
//                    if (!$defaultContentProfileSite) {
//                        continue;
//                    }
//                    /** Nếu content_profile này đã có trong targetSite thì thôi không xử lí */
//                    $targetContentProfileSite = ContentProfileSiteAsm::findOne(['content_profile_id' => $contentProfile->id, 'site_id' => $target_site_id, 'status' => ContentProfileSiteAsm::STATUS_ACTIVE]);
//                    if ($targetContentProfileSite) {
//                        continue;
//                    }
//                    /** Get object content_priofile để xử lí*/
//                    $contentProfileDefault = $defaultContentProfileSite->contentProfile;
//                    /** Nếu là kiểu CDN thì mới xử lí */
//                    if ($contentProfileDefault->type != ContentProfile::TYPE_CDN) {
//                        continue;
//                    }
//                    /** get lấy link CDN  */
//                    $res = ContentProfile::getCdnUrl($defaultContentProfileSite->url, $streaming_server_ip);
//
////                        Yii::info('#### syncDataToSite getCdnUrl: ' . $res);echo $res;exit;
//                    if (!$res['success']) {
//                        continue;
//                    }
//                    $arrCP = [];
//                    $arrCP['content_profile_id'] = $contentProfileDefault->id;
//                    $arrCP['content_link'] = $res['success'] ? $res['url'] : "";
//                    $arrCP['cdn_content_id'] = $defaultContentProfileSite->url;
//                    $arrCP['quality'] = $contentProfileDefault->quality;
//                    $item['qualities'][] = $arrCP;
//                    $is_check = true;
//
//                }
//            }
//
////            }
//            /** Nếu không có content_profile thì không truyền sang Downloader */
//            if ($is_check) {
//                /** Nếu chuyển được trạng thái sang STATUS_TRANSFERING thì mới đưa vào mảng */
//                $contentSiteAsm = ContentSiteAsm::findOne(['content_id' => $row->id, 'site_id' => $target_site_id]);
//                if (!$contentSiteAsm) {
//                    continue;
//                }
//                /** Đưa đoạn chuyển trạng thái xuống dưới sau khi có ACK từ phía Downloader: Họp sau ngày 20161013 thống nhất*/
//                //            /** Chuyển trạng thái thành STATUS_TRANSFERING */
//                //            $contentSiteAsm->status = ContentSiteAsm::STATUS_TRANSFERING;
//                //            if (!$contentSiteAsm->save()) {
//                //                continue;
//                //            }
//
//                /** Thỏa mãn mọi điều kiện, đưa vào mảng để truyền sang Downloader */
//                unset($item['default_site_id']);
//                $arrItems[] = $item;
//
//            }
//
//        }
//        /** Nếu không có content nào thỏa mãn thì thông báo  */
//        if (count($arrItems) <= 0) {
//            $res['success'] = false;
//            $res['message'] = Message::getNotFoundContentMessage();
//            return $res;
//        }
//        $data['items'] = $arrItems;
//        /** json_encode data trước khi truyền */
//        $json_data = json_encode($data); //return $json_data;
//
//        $ch = curl_init();
//        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
//        curl_setopt($ch, CURLOPT_URL, $url);
//        curl_setopt($ch, CURLOPT_HEADER, true);
//        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
//        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json;charset=UTF-8"));
//        curl_setopt($ch, CURLOPT_POST, 1);
//        curl_setopt($ch, CURLOPT_POSTFIELDS, $json_data);
//        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
//        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
//        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
//        curl_setopt($ch, CURLOPT_TIMEOUT, 30); // 30s timeout
//        Yii::info('#### Post to Downloader: ' . $json_data);
//        $response = curl_exec($ch);
//        if ($response === false) {
//            CUtils::log('#### Post to Downloader error: ' . curl_error($ch));
//            $return['success'] = false;
//            $return['message'] = Message::getFailMessage();
//        } else {
//            CUtils::log('#### Return from Downloader: ' . $response);
//            $return['success'] = true;
//            $return['message'] = Message::getSysDataMessage();
//            /** save data after sync to Downloader */
//            Content::saveDataReceivedAck($arrItems,$target_site_id,$streaming_server_id);
//
//        }
//        curl_close($ch);
//
//        return $return;
//
//    }

    /**
     * @param $target_site_id
     * @param $streaming_server_id
     * @return mixed
     */
    /**
     * @param $target_site_id
     * @param $streaming_server_id
     * @return mixed
     */
    public static function syncDataToSite($target_site_id, $streaming_server_id, $force_download = false)
    {
        if (!is_numeric($target_site_id)) {
            $res['success'] = false;
            $res['message'] = CUtils::replaceParam(Message::getNumberOnlyMessage(), ['site_id']);
            return $res;
        }
        if (!is_numeric($streaming_server_id)) {
            $res['success'] = false;
            $res['message'] = CUtils::replaceParam(Message::getNumberOnlyMessage(), ['streaming_server_id']);
            return $res;
        }
        /** @var  $streamAsm SiteStreamingServerAsm */
        $streamAsm = SiteStreamingServerAsm::findOne(['streaming_server_id' => $streaming_server_id]);

        if (!$streamAsm) {
            $res['success'] = false;
            $res['message'] = Message::getNotFoundStreamMessage();
            return $res;
        }
        /** @var  $stream StreamingServer */
        $stream = $streamAsm->streamingServer;
        $url = $stream->content_api;
        $streaming_server_ip = $stream->ip;
        $content_folder = $stream->content_path;

        /** Bỏ phần check theo trạng thái contentSite mà mình sẽ quét all */
        /** Update 20160721 check chỉ lấy những thằng Content có trạng thái STATUS_ACTIVE và ContentSiteAsm có trạng thái là  STATUS_NOT_TRANSFER, STATUS_TRANSFER_ERROR theo nội dung cuộc họp */
        $items = Content::find()
            ->innerJoin('content_site_asm', 'content.id=content_site_asm.content_id')
            ->andWhere(['content_site_asm.site_id' => $target_site_id, 'content_site_asm.status' => [ContentSiteAsm::STATUS_NOT_TRANSFER, ContentSiteAsm::STATUS_TRANSFER_ERROR]])
            ->andWhere(['content.status' => Content::STATUS_ACTIVE, 'content.type' => [
                Content::TYPE_VIDEO,
                Content::TYPE_MUSIC,
                Content::TYPE_CLIP,
                Content::TYPE_KARAOKE,
                Content::TYPE_RADIO,
                Content::TYPE_LIVE_CONTENT,
            ]])
            ->all();

        /** Nếu không có content nào thỏa mãn thì thông báo  */
        if (count($items) <= 0) {
            $res['success'] = false;
            $res['message'] = Message::getNotFoundContentMessage();
            return $res;
        }
//        echo count($items);exit;
        $data = [];
        $data['request_id'] = time();
        $data['site_id'] = $target_site_id;
        $data['content_folder'] = $content_folder;
        $data['token'] = md5($data['request_id'] . $target_site_id);
        $data['force_download'] = $force_download;

        $arrItems = [];
        /** @var  $row */
        foreach ($items as $row) {
            $item = $row->getAttributes(['id', 'type', 'default_site_id'], ['tvod1_id']);
            /** Kiểm tra xem có site_default_id hay không */
            if (!$item['default_site_id']) {
                continue;
            }
            /** Lấy danh sách content_profile của content không cần map theo site */
            $contentProfiles = ContentProfile::find()
                ->andWhere(['content_id' => $row['id'], 'type' => ContentProfile::TYPE_CDN, 'status' => ContentProfile::STATUS_ACTIVE])
                ->all();
            /** Nếu tồn tại quality thì mới xử lí */
            if (!$contentProfiles) {
                continue;
            }
//            var_dump($row['id']);exit;
            $is_check = false;
            /** @var  $contentProfile ContentProfile */
            foreach ($contentProfiles as $contentProfile) {
                /** Chỉ xử lí content_profile thuộc defaultSite */
                /** @var  $defaultContentProfileSite ContentProfileSiteAsm */
                $defaultContentProfileSite = ContentProfileSiteAsm::findOne(['content_profile_id' => $contentProfile->id, 'site_id' => $item['default_site_id'], 'status' => ContentProfileSiteAsm::STATUS_ACTIVE]);
                if (!$defaultContentProfileSite) {
                    continue;
                }
                /** Nếu content_profile này đã có trong targetSite thì thôi không xử lí */
                $targetContentProfileSite = ContentProfileSiteAsm::findOne(['content_profile_id' => $contentProfile->id, 'site_id' => $target_site_id, 'status' => ContentProfileSiteAsm::STATUS_ACTIVE]);
                if ($targetContentProfileSite) {
                    continue;
                }

                /** Get object content_priofile để xử lí*/
                $contentProfileDefault = $defaultContentProfileSite->contentProfile;
                /** Nếu là kiểu CDN thì mới xử lí */
                if ($contentProfileDefault->type != ContentProfile::TYPE_CDN) {
                    continue;
                }
                $is_check = false;
                /** @var  $contentProfile ContentProfile */
                foreach ($contentProfiles as $contentProfile) {
                    /** Chỉ xử lí content_profile thuộc defaultSite */
                    /** @var  $defaultContentProfileSite ContentProfileSiteAsm */
                    $defaultContentProfileSite = ContentProfileSiteAsm::findOne(['content_profile_id' => $contentProfile->id, 'site_id' => $item['default_site_id'], 'status' => ContentProfileSiteAsm::STATUS_ACTIVE]);
                    if (!$defaultContentProfileSite) {
                        continue;
                    }
                    /** Nếu content_profile này đã có trong targetSite thì thôi không xử lí */
                    $targetContentProfileSite = ContentProfileSiteAsm::findOne(['content_profile_id' => $contentProfile->id, 'site_id' => $target_site_id, 'status' => ContentProfileSiteAsm::STATUS_ACTIVE]);
                    if ($targetContentProfileSite) {
                        continue;
                    }
                    /** Get object content_priofile để xử lí*/
                    $contentProfileDefault = $defaultContentProfileSite->contentProfile;
                    /** Nếu là kiểu CDN thì mới xử lí */
                    if ($contentProfileDefault->type != ContentProfile::TYPE_CDN) {
                        continue;
                    }
                    /** get lấy link CDN  */
                    $res = ContentProfile::getCdnUrl($defaultContentProfileSite->url, $streaming_server_ip);

//                        Yii::info('#### syncDataToSite getCdnUrl: ' . $res);echo $res;exit;
                    if (!$res['success']) {
                        continue;
                    }
                    $arrCP = [];
                    $arrCP['content_profile_id'] = $contentProfileDefault->id;
                    $arrCP['content_link'] = $res['success'] ? $res['url'] : "";
                    $arrCP['cdn_content_id'] = $defaultContentProfileSite->url;
                    $arrCP['quality'] = $contentProfileDefault->quality;
                    $item['qualities'][] = $arrCP;
                    $is_check = true;

                }
            }

//            }
            /** Nếu không có content_profile thì không truyền sang Downloader */
            if ($is_check) {
                /** Nếu chuyển được trạng thái sang STATUS_TRANSFERING thì mới đưa vào mảng */
                $contentSiteAsm = ContentSiteAsm::findOne(['content_id' => $row->id, 'site_id' => $target_site_id]);
                if (!$contentSiteAsm) {
                    continue;
                }
                /** Đưa đoạn chuyển trạng thái xuống dưới sau khi có ACK từ phía Downloader: Họp sau ngày 20161013 thống nhất*/
                //            /** Chuyển trạng thái thành STATUS_TRANSFERING */
                //            $contentSiteAsm->status = ContentSiteAsm::STATUS_TRANSFERING;
                //            if (!$contentSiteAsm->save()) {
                //                continue;
                //            }

                /** Thỏa mãn mọi điều kiện, đưa vào mảng để truyền sang Downloader */
                unset($item['default_site_id']);
                $arrItems[] = $item;

            }

        }
        /** Nếu không có content nào thỏa mãn thì thông báo  */
        if (count($arrItems) <= 0) {
            $res['success'] = false;
            $res['message'] = Message::getNotFoundContentMessage();
            return $res;
        }
        $data['items'] = $arrItems;
        /** json_encode data trước khi truyền */
        $json_data = json_encode($data); //return $json_data;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json;charset=UTF-8"));
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $json_data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30); // 30s timeout
        Yii::info('#### Post to Downloader: ' . $json_data);
        $response = curl_exec($ch);
        if ($response === false) {
            CUtils::log('#### Post to Downloader error: ' . curl_error($ch));
            $return['success'] = false;
            $return['message'] = Message::getFailMessage();
        } else {
            CUtils::log('#### Return from Downloader: ' . $response);
            $return['success'] = true;
            $return['message'] = Message::getSysDataMessage();
            /** save data after sync to Downloader */
            Content::saveDataReceivedAck($arrItems, $target_site_id);

        }
        curl_close($ch);

        return $return;

    }

    /**
     * @param $target_site_id
     * @param $streaming_server_id
     * @return mixed
     */
    public static function syncDataToSite_bka20161021($target_site_id, $streaming_server_id, $force_download = false)
    {
        if (!is_numeric($target_site_id)) {
            $res['success'] = false;
            $res['message'] = CUtils::replaceParam(Message::getNumberOnlyMessage(), ['site_id']);
            return $res;
        }
        if (!is_numeric($streaming_server_id)) {
            $res['success'] = false;
            $res['message'] = CUtils::replaceParam(Message::getNumberOnlyMessage(), ['streaming_server_id']);
            return $res;
        }
        /** @var  $streamAsm SiteStreamingServerAsm */
        $streamAsm = SiteStreamingServerAsm::findOne(['streaming_server_id' => $streaming_server_id]);

        if (!$streamAsm) {
            $res['success'] = false;
            $res['message'] = Message::getNotFoundStreamMessage();
            return $res;
        }
        /** @var  $stream StreamingServer */
        $stream = $streamAsm->streamingServer;
        $url = $stream->content_api;
        $streaming_server_ip = $stream->ip;
        $content_folder = $stream->content_path;

        /** Bỏ phần check theo trạng thái contentSite mà mình sẽ quét all */
        /** Update 20160721 check chỉ lấy những thằng Content có trạng thái STATUS_ACTIVE và ContentSiteAsm có trạng thái là  STATUS_NOT_TRANSFER, STATUS_TRANSFER_ERROR theo nội dung cuộc họp */
        $items = Content::find()
            ->innerJoin('content_site_asm', 'content.id=content_site_asm.content_id')
            ->andWhere(['content_site_asm.site_id' => $target_site_id, 'content_site_asm.status' => [ContentSiteAsm::STATUS_NOT_TRANSFER, ContentSiteAsm::STATUS_TRANSFER_ERROR]])
            ->andWhere(['content.status' => Content::STATUS_ACTIVE, 'content.type' => [
                Content::TYPE_VIDEO,
                Content::TYPE_MUSIC,
                Content::TYPE_CLIP,
                Content::TYPE_KARAOKE,
                Content::TYPE_RADIO,
                Content::TYPE_LIVE_CONTENT,
            ]])
            ->all();

        /** Nếu không có content nào thỏa mãn thì thông báo  */
        if (count($items) <= 0) {
            $res['success'] = false;
            $res['message'] = Message::getNotFoundContentMessage();
            return $res;
        }
//        echo count($items);exit;
        $data = [];
        $data['request_id'] = time();
        $data['site_id'] = $target_site_id;
        $data['content_folder'] = $content_folder;
        $data['token'] = md5($data['request_id'] . $target_site_id);
        $data['force_download'] = $force_download;

        $arrItems = [];
        /** @var  $row */
        foreach ($items as $row) {
            $item = $row->getAttributes(['id', 'type', 'default_site_id'], ['tvod1_id']);
            /** Kiểm tra xem có site_default_id hay không */
            if (!$item['default_site_id']) {
                continue;
            }
            /** Lấy danh sách content_profile của content không cần map theo site */
            $contentProfiles = ContentProfile::find()
                ->andWhere(['content_id' => $row['id'], 'type' => ContentProfile::TYPE_CDN, 'status' => ContentProfile::STATUS_ACTIVE])
                ->all();
            /** Nếu tồn tại quality thì mới xử lí */
            if (!$contentProfiles) {
                continue;
            }
//            var_dump($row['id']);exit;
            $is_check = false;
            /** @var  $contentProfile ContentProfile */
            foreach ($contentProfiles as $contentProfile) {
                /** Chỉ xử lí content_profile thuộc defaultSite */
                /** @var  $defaultContentProfileSite ContentProfileSiteAsm */
                $defaultContentProfileSite = ContentProfileSiteAsm::findOne(['content_profile_id' => $contentProfile->id, 'site_id' => $item['default_site_id'], 'status' => ContentProfileSiteAsm::STATUS_ACTIVE]);
                if (!$defaultContentProfileSite) {
                    continue;
                }
                /** Nếu content_profile này đã có trong targetSite thì thôi không xử lí */
                $targetContentProfileSite = ContentProfileSiteAsm::findOne(['content_profile_id' => $contentProfile->id, 'site_id' => $target_site_id, 'status' => ContentProfileSiteAsm::STATUS_ACTIVE]);
                if ($targetContentProfileSite) {
                    continue;
                }

                /** Get object content_priofile để xử lí*/
                $contentProfileDefault = $defaultContentProfileSite->contentProfile;
                /** Nếu là kiểu CDN thì mới xử lí */
                if ($contentProfileDefault->type != ContentProfile::TYPE_CDN) {
                    continue;
                }
                $is_check = false;
                /** @var  $contentProfile ContentProfile */
                foreach ($contentProfiles as $contentProfile) {
                    /** Chỉ xử lí content_profile thuộc defaultSite */
                    /** @var  $defaultContentProfileSite ContentProfileSiteAsm */
                    $defaultContentProfileSite = ContentProfileSiteAsm::findOne(['content_profile_id' => $contentProfile->id, 'site_id' => $item['default_site_id'], 'status' => ContentProfileSiteAsm::STATUS_ACTIVE]);
                    if (!$defaultContentProfileSite) {
                        continue;
                    }
                    /** Nếu content_profile này đã có trong targetSite thì thôi không xử lí */
                    $targetContentProfileSite = ContentProfileSiteAsm::findOne(['content_profile_id' => $contentProfile->id, 'site_id' => $target_site_id, 'status' => ContentProfileSiteAsm::STATUS_ACTIVE]);
                    if ($targetContentProfileSite) {
                        continue;
                    }
                    /** Get object content_priofile để xử lí*/
                    $contentProfileDefault = $defaultContentProfileSite->contentProfile;
                    /** Nếu là kiểu CDN thì mới xử lí */
                    if ($contentProfileDefault->type != ContentProfile::TYPE_CDN) {
                        continue;
                    }
                    /** get lấy link CDN  */
                    $res = ContentProfile::getCdnUrl($defaultContentProfileSite->url, $streaming_server_ip);

//                        Yii::info('#### syncDataToSite getCdnUrl: ' . $res);echo $res;exit;
                    if (!$res['success']) {
                        continue;
                    }
                    $arrCP = [];
                    $arrCP['content_profile_id'] = $contentProfileDefault->id;
                    $arrCP['content_link'] = $res['success'] ? $res['url'] : "";
                    $arrCP['cdn_content_id'] = $defaultContentProfileSite->url;
                    $arrCP['quality'] = $contentProfileDefault->quality;
                    $item['qualities'][] = $arrCP;
                    $is_check = true;

                }
            }

//            }
            /** Nếu không có content_profile thì không truyền sang Downloader */
            if ($is_check) {
                /** Nếu chuyển được trạng thái sang STATUS_TRANSFERING thì mới đưa vào mảng */
                $contentSiteAsm = ContentSiteAsm::findOne(['content_id' => $row->id, 'site_id' => $target_site_id]);
                if (!$contentSiteAsm) {
                    continue;
                }
                /** Đưa đoạn chuyển trạng thái xuống dưới sau khi có ACK từ phía Downloader: Họp sau ngày 20161013 thống nhất*/
                //            /** Chuyển trạng thái thành STATUS_TRANSFERING */
                //            $contentSiteAsm->status = ContentSiteAsm::STATUS_TRANSFERING;
                //            if (!$contentSiteAsm->save()) {
                //                continue;
                //            }

                /** Thỏa mãn mọi điều kiện, đưa vào mảng để truyền sang Downloader */
                unset($item['default_site_id']);
                $arrItems[] = $item;

            }

        }
        /** Nếu không có content nào thỏa mãn thì thông báo  */
        if (count($arrItems) <= 0) {
            $res['success'] = false;
            $res['message'] = Message::getNotFoundContentMessage();
            return $res;
        }
        $data['items'] = $arrItems;
        /** json_encode data trước khi truyền */
        $json_data = json_encode($data); //return $json_data;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json;charset=UTF-8"));
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $json_data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30); // 30s timeout
        Yii::info('#### Post to Downloader: ' . $json_data);
        $response = curl_exec($ch);
        if ($response === false) {
            CUtils::log('#### Post to Downloader error: ' . curl_error($ch));
            $return['success'] = false;
            $return['message'] = Message::getFailMessage();
        } else {
            CUtils::log('#### Return from Downloader: ' . $response);
            $return['success'] = true;
            $return['message'] = Message::getSysDataMessage();
            /** save data after sync to Downloader */
            Content::saveDataReceivedAck($arrItems, $target_site_id, $streaming_server_id);

        }
        curl_close($ch);

        return $return;

    }

    /**
     * @param $target_site_id
     * @param $content_id
     * @param $streaming_server_id
     * @return mixed
     */
    public static function syncContentToSite($target_site_id, $content_id, $streaming_server_id, $force_download = false, $log = false)
    {
        if (!is_numeric($target_site_id)) {
            $return['sync_status'] = ContentSiteAsm::STATUS_TRANSFER_ERROR;
            $res['success'] = false;
            $res['message'] = CUtils::replaceParam(Message::getNumberOnlyMessage(), ['site_id']);
            return $res;
        }
        if (!is_numeric($streaming_server_id)) {
            $return['sync_status'] = ContentSiteAsm::STATUS_TRANSFER_ERROR;
            $res['success'] = false;
            $res['message'] = CUtils::replaceParam(Message::getNumberOnlyMessage(), ['streaming_server_id']);
            return $res;
        }
        /** @var  $streamAsm SiteStreamingServerAsm */
        $streamAsm = SiteStreamingServerAsm::findOne(['streaming_server_id' => $streaming_server_id]);

        if (!$streamAsm) {
            $res['success'] = false;
            $res['message'] = Message::getNotFoundStreamMessage();
            $return['sync_status'] = ContentSiteAsm::STATUS_TRANSFER_ERROR;
            return $res;
        }
        /** @var  $stream StreamingServer */
        $stream = $streamAsm->streamingServer;
        $url = $stream->content_api;
        $streaming_server_ip = $stream->ip;
        $content_folder = $stream->content_path;

        /** Bỏ phần check theo trạng thái contentSite mà mình sẽ quét all */
        /** Update 20160721 check chỉ lấy những thằng Content có trạng thái STATUS_ACTIVE và ContentSiteAsm có trạng thái là  STATUS_NOT_TRANSFER, STATUS_TRANSFER_ERROR theo nội dung cuộc họp */
        $item = Content::find()
            ->andWhere(['content.id' => $content_id])
            ->innerJoin('content_site_asm', 'content.id=content_site_asm.content_id')
            ->andWhere(['content_site_asm.site_id' => $target_site_id, 'content_site_asm.status' => [ContentSiteAsm::STATUS_NOT_TRANSFER, ContentSiteAsm::STATUS_TRANSFER_ERROR]])
            ->andWhere(['content.status' => Content::STATUS_ACTIVE, 'content.type' => [
                Content::TYPE_VIDEO,
                Content::TYPE_MUSIC,
                Content::TYPE_NEWS,
                Content::TYPE_CLIP,
                Content::TYPE_KARAOKE,
                Content::TYPE_RADIO,
                Content::TYPE_LIVE_CONTENT,
            ]])
            ->one();
        /** Chỉ xử lí với thằng ở trạng thái STATUS_NOT_TRANSFER, STATUS_TRANSFER_ERROR */
        if (!$item) {
            $res['success'] = false;
            $res['message'] = Message::getNotFoundContentMessage();
            $return['sync_status'] = ContentSiteAsm::STATUS_TRANSFER_ERROR;
            return $res;
        }
        $data = [];
        $data['request_id'] = time();
        $data['site_id'] = $target_site_id;
        $data['content_folder'] = $content_folder;
        $data['token'] = md5(time() . $target_site_id);
        $data['force_download'] = $force_download;

        $arrItems = [];
        /** convert sang mảng*/
        $arrItem = $item->getAttributes(['id', 'type', 'default_site_id'], ['tvod1_id']);
        /** Kiểm tra xem có site_default_id hay không */
        if (!$item->default_site_id) {
            $return['sync_status'] = ContentSiteAsm::STATUS_TRANSFER_ERROR;
            $res['success'] = false;
            $res['message'] = CUtils::replaceParam(Message::getNullValueMessage(), ['default_site_id']);
            return $res;
        }

        /** Lấy danh sách content_profile của content không cần map theo site */
        $contentProfiles = ContentProfile::find()
            ->andWhere(['content_id' => $item['id'], 'type' => ContentProfile::TYPE_CDN, 'status' => Content::STATUS_ACTIVE])
            ->all();
        /** Nếu tồn tại quality thì mới xử lí */
        if (!$contentProfiles) {
            $res['success'] = false;

            $res['message'] = Message::getContentProfileNotFoundMessage();
            $return['sync_status'] = ContentSiteAsm::STATUS_TRANSFER_ERROR;
            $res['content_status'] = LogSyncContent::CONTENT_STATUS_NO_PROFILE;

            return $res;
        }

        $is_check = false;
        /** @var  $contentProfile ContentProfile */
        foreach ($contentProfiles as $contentProfile) {
            /** Chỉ xử lí content_profile thuộc defaultSite */
            /** @var  $defaultContentProfileSite ContentProfileSiteAsm */
            $defaultContentProfileSite = ContentProfileSiteAsm::findOne(['content_profile_id' => $contentProfile->id, 'site_id' => $item['default_site_id'], 'status' => ContentProfileSiteAsm::STATUS_ACTIVE]);
            if (!$defaultContentProfileSite) {
                continue;
            }
            /** Nếu content_profile này đã có trong targetSite thì thôi không xử lí */
            $targetContentProfileSite = ContentProfileSiteAsm::findOne(['content_profile_id' => $contentProfile->id, 'site_id' => $target_site_id, 'status' => ContentProfileSiteAsm::STATUS_ACTIVE]);
            if ($targetContentProfileSite) {
                continue;
            }
            /** Get object content_priofile để xử lí*/
            $contentProfileDefault = $defaultContentProfileSite->contentProfile;

            /** Nếu là kiểu CDN thì mới xử lí */
            if ($contentProfileDefault->type != ContentProfile::TYPE_CDN) {
                continue;
            }
            /** get lấy link CDN  */
            if ($log) {
                $res = ContentProfile::getCdnUrlContent($defaultContentProfileSite->url, $streaming_server_ip);
            } else {
                $res = ContentProfile::getCdnUrl($defaultContentProfileSite->url, $streaming_server_ip);
            }


            $arrCP = [];
            $arrCP['content_profile_id'] = $contentProfileDefault->id;
            $arrCP['content_link'] = $res['success'] ? $res['url'] : "";
            $arrCP['cdn_content_id'] = $defaultContentProfileSite->url;
            $arrCP['quality'] = $contentProfileDefault->quality;
            $arrItem['qualities'][] = $arrCP;
            $is_check = true;

        }
        if ($is_check) {
            /** Nếu chuyển được trạng thái sang STATUS_TRANSFERING thì mới đưa vào mảng */
            $contentSiteAsm = ContentSiteAsm::findOne(['content_id' => $item->id, 'site_id' => $target_site_id]);
            if (!$contentSiteAsm) {
                $return['success'] = false;

                $return['message'] = Message::getNotFoundContentMessage();
                $return['sync_status'] = ContentSiteAsm::STATUS_TRANSFER_ERROR;
            }

            /** Đưa đoạn chuyển trạng thái xuống dưới sau khi có ACK từ phía Downloader: Họp sau ngày 20161013 thống nhất*/
//            /** Chuyển trạng thái thành STATUS_TRANSFERING */
//            $contentSiteAsm->status = ContentSiteAsm::STATUS_TRANSFERING;
//            if (!$contentSiteAsm->save()) {
//                $return['succes']  = false;
//                $return['message'] = Message::getFailMessage();
//            }

            /** Thỏa mãn mọi điều kiện, đưa vào mảng để truyền sang Downloader */
            unset($arrItem['default_site_id']);
            $arrItems[] = $arrItem;
        }
        /** Nếu không có content_profile nào thỏa mãn thì báo lỗi */
        if (count($arrItems) <= 0) {
            $return['success'] = false;

            $return['message'] = Message::getContentProfileNotFoundMessage();
            $return['sync_status'] = ContentSiteAsm::STATUS_TRANSFER_ERROR;
            $res['content_status'] = LogSyncContent::CONTENT_STATUS_NO_PROFILE;

        }
        $data['items'] = $arrItems;
        /** json_encode data trước khi truyền */
        $json_data = json_encode($data); //return $json_data;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json;charset=UTF-8"));
//        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Expect:'));
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $json_data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30); // 30s timeout
        Yii::info('#### Post to Downloader: ' . $json_data);
        $response = curl_exec($ch);
        if ($response === false) {
            CUtils::log('#### Post to Downloader error: ' . curl_error($ch));
            $return['success'] = false;

            $return['message'] = Message::getFailMessage();
            $return['sync_status'] = ContentSiteAsm::STATUS_TRANSFER_ERROR;
        } else {
            CUtils::log('#### Return from Downloader: ' . $response);
            $return['success'] = true;
            $return['message'] = Message::getSysDataMessage();
            /** save data after sync to Downloader */
            CUtils::log('#### saveDataAfterSync changge status content to STATUS_TRANSFERING BEGIN');
            $success = Content::saveDataReceivedAck($arrItems, $target_site_id);
            CUtils::log('#### saveDataAfterSync changge status content to STATUS_TRANSFERING END');
            /** Trường hợp nếu save lỗi thì đưa ra thông báo */
            if (!$success) {
                $return['message'] = Message::getFailChangeStatusMessage();
                $return['success'] = false;
                $return['sync_status'] = ContentSiteAsm::STATUS_TRANSFER_ERROR;
            }
        }
        curl_close($ch);
        return $return;
    }

    /**
     * @param $items
     * @param $target_site_id
     */
    private function saveDataReceivedAck($items, $target_site_id)
    {
        $success = true;
        foreach ($items as $row) {
            /** Nếu chuyển được trạng thái sang STATUS_TRANSFERING thì mới đưa vào mảng */
            $contentSiteAsm = ContentSiteAsm::findOne(['content_id' => $row['id'], 'site_id' => $target_site_id]);
            if (!$contentSiteAsm) {
                $success = false;
                continue;
            }
            /** Chuyển trạng thái thành STATUS_TRANSFERING */
            $contentSiteAsm->status = ContentSiteAsm::STATUS_TRANSFERING;
            $contentSiteAsm->time_sync_sent = time();
            if (!$contentSiteAsm->save()) {
                $success = false;
                CUtils::log('#### saveDataAfterSync error cannot save content: content_id=' . $row['id'] . ' , site_id=' . $target_site_id);
                continue;
            }
            CUtils::log('#### saveDataAfterSync changge status content to STATUS_TRANSFERING success : content_id=' . $row['id'] . ' , site_id=' . $target_site_id);
        }
        return $success;
    }

    public function getActors()
    {
        switch ($this->type) {
            case self::TYPE_VIDEO:
                return ArrayHelper::map(ActorDirector::findAll(['type' => ActorDirector::TYPE_ACTOR, 'content_type' => self::TYPE_VIDEO]), 'id', 'name');
                break;
            case self::TYPE_KARAOKE:
                return ArrayHelper::map(ActorDirector::findAll(['type' => ActorDirector::TYPE_ACTOR, 'content_type' => self::TYPE_KARAOKE]), 'id', 'name');
                break;
            default:
                return [];
                break;
        }
    }

    public function getDirectors()
    {
        switch ($this->type) {
            case self::TYPE_VIDEO:
                return ArrayHelper::map(ActorDirector::findAll(['type' => ActorDirector::TYPE_DIRECTOR, 'content_type' => self::TYPE_VIDEO]), 'id', 'name');
                break;
            case self::TYPE_KARAOKE:
                return ArrayHelper::map(ActorDirector::findAll(['type' => ActorDirector::TYPE_DIRECTOR, 'content_type' => self::TYPE_KARAOKE]), 'id', 'name');
                break;
            default:
                return [];
                break;
        }
    }

    public function saveActorDirectors()
    {
        if ($this->type != self::TYPE_VIDEO && $this->type != self::TYPE_KARAOKE) {
            return false;
        }

        $directorSaved = $actorSaved = false;

        ContentActorDirectorAsm::deleteAll(['content_id' => $this->id]);

        if (!empty($this->content_directors)) {
            foreach ($this->content_directors as $key => $value) {
                $newActorDirectorsAsm = new ContentActorDirectorAsm;
                $newActorDirectorsAsm->content_id = $this->id;
                $newActorDirectorsAsm->actor_director_id = $value;
                $directorSaved = $newActorDirectorsAsm->save();
            }
        }

        if (!empty($this->content_actors)) {
            foreach ($this->content_actors as $key => $value) {
                $newActorDirectorsAsm = new ContentActorDirectorAsm;
                $newActorDirectorsAsm->content_id = $this->id;
                $newActorDirectorsAsm->actor_director_id = $value;
                $actorSaved = $newActorDirectorsAsm->save();
            }
        }

        return $directorSaved && $actorSaved;
    }

    public function getContentDirectors()
    {
        $directors = ActorDirector::find()
            ->innerJoin('content_actor_director_asm', 'content_actor_director_asm.actor_director_id = actor_director.id')
            ->innerJoin('content', 'content_actor_director_asm.content_id = content.id')
            ->where(['content.id' => $this->id])
            ->andwhere(['actor_director.type' => ActorDirector::TYPE_DIRECTOR])
            ->asArray()
            ->all();

        return $this->content_directors = $directors;
    }

    public function getContentActors()
    {
        $actors = ActorDirector::find()
            ->innerJoin('content_actor_director_asm', 'content_actor_director_asm.actor_director_id = actor_director.id')
            ->innerJoin('content', 'content_actor_director_asm.content_id = content.id')
            ->where(['content.id' => $this->id])
            ->andwhere(['actor_director.type' => ActorDirector::TYPE_ACTOR])
            ->asArray()
            ->all();

        return $this->content_actors = $actors;
    }

    public function getContentSiteProvider()
    {
        return self::find()
            ->innerJoin('content_site_asm', 'content_site_asm.content_id = content.id')
            ->innerJoin('site', 'site.id = content_site_asm.site_id')
//            ->select('site.name site_name, site.id site_id, time_sync_sent, time_sync_received, content_site_asm.status content_site_asm_status')
            ->select('site.name site_name, site.id site_id, content_site_asm.status content_site_asm_status')
            ->where(['content.id' => $this->id])
            ->all();
    }

    /**
     * @param $content_id
     * @param $site_id
     * @return int
     */
    public static function countQualityWhenDownload($content_id, $site_id)
    {
        /** Lấy content_profile của thằng gốc */
        $contentProfilesDefault = ContentProfile::find()
            ->andWhere(['content_id' => $content_id, 'type' => ContentProfile::TYPE_CDN, 'status' => Content::STATUS_ACTIVE])
            ->all();
        $totalCountSuccess = 0;
        foreach ($contentProfilesDefault as $contentProfileDefault) {
            $contentProfileSiteAsm = ContentProfileSiteAsm::find()->andWhere(['content_profile_id' => $contentProfileDefault->id, 'site_id' => $site_id, 'status' => ContentProfileSiteAsm::STATUS_ACTIVE])->one();
            if ($contentProfileSiteAsm) {
                $totalCountSuccess++;
            }
        }
        return $totalCountSuccess;

    }


    /**
     * @param $link
     * @return string
     */
    public static function getSubtitleUrl($link)
    {
        return $link?Url::to(Yii::getAlias('@web') . DIRECTORY_SEPARATOR . Yii::getAlias('@subtitle') . DIRECTORY_SEPARATOR . $link, true):null;

    }

    /**
     * @param $site_id
     * @return int
     */
    public function getIsFree($site_id)
    {
        $contentSiteAsm = ContentSiteAsm::findOne(['content_id' => $this->id, 'site_id' => $site_id, 'status' => Content::STATUS_ACTIVE]);
        if (!$contentSiteAsm) {
            return Content::IS_FREE;
        }
        if (empty($contentSiteAsm->pricing_id)) {
            return Content::IS_FREE;
        }
        return Content::NOT_FREE;
    }

    /**
     * @param $site_id
     * @return int
     */
    public function getPriceCoin($site_id)
    {
        /** @var  $contentSiteAsm ContentSiteAsm */
        $contentSiteAsm = ContentSiteAsm::findOne(['content_id' => $this->id, 'site_id' => $site_id, 'status' => Content::STATUS_ACTIVE]);

        if (!$contentSiteAsm) {
            return 0;
        }
        if (!$contentSiteAsm->pricing_id) {
            return 0;
        }

        return $contentSiteAsm->pricing ? $contentSiteAsm->pricing->price_coin : 0;
    }

    /**
     * @param $site_id
     * @return float|int
     */
    public function getPriceSms($site_id)
    {
        /** @var  $contentSiteAsm ContentSiteAsm */
        $contentSiteAsm = ContentSiteAsm::findOne(['content_id' => $this->id, 'site_id' => $site_id, 'status' => Content::STATUS_ACTIVE]);
        if (!$contentSiteAsm) {
            return 0;
        }
        if (!$contentSiteAsm->pricing_id) {
            return 0;
        }
        return $contentSiteAsm->pricing ? $contentSiteAsm->pricing->price_sms : 0;
    }

    /**
     * @param $site_id
     * @return int
     */
    public function getWatchingPriod($site_id)
    {
        /** @var  $contentSiteAsm ContentSiteAsm */
        $contentSiteAsm = ContentSiteAsm::findOne(['content_id' => $this->id, 'site_id' => $site_id, 'status' => Content::STATUS_ACTIVE]);
        if (!$contentSiteAsm) {
            return 0;
        }
        if (!$contentSiteAsm->pricing_id) {
            return 0;
        }
        return $contentSiteAsm->pricing ? $contentSiteAsm->pricing->watching_period : 0;
    }


    /**
     * @description make link, đổi ip server cache
     * @param $link
     * @param $streaming_server_ip
     * @return string
     */
    public function makeLink($link, $streaming_server_ip)
    {
        $lst = array();
        $lst = explode("/", $link);

        $url = "http://" . $streaming_server_ip . "/";
        for ($i = 3; $i < count($lst) - 1; $i++) {
            $url .= $lst[$i] . "/";
        }
        $url .= $lst[count($lst) - 1];
        return $url;
    }
}
