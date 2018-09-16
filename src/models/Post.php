<?php

/**
 * @package   Yii2-Post
 * @author    José Peña <joepa37@gmail.com>
 * @link https://plus.google.com/+joepa37/
 * @copyright Copyright (c) 2018 José Peña
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @version   1.0.0
 */

namespace gearsoftware\post\models;

use gearsoftware\behaviors\MultilingualBehavior;
use gearsoftware\models\OwnerAccess;
use gearsoftware\models\User;
use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\SluggableBehavior;
use yii\behaviors\TimestampBehavior;
use gearsoftware\db\ActiveRecord;
use yii\helpers\Html;

/**
 * This is the model class for table "post".
 *
 * @property integer $id
 * @property string $slug
 * @property string $view
 * @property string $layout
 * @property integer $category_id
 * @property integer $status
 * @property integer $comment_status
 * @property string $thumbnail
 * @property integer $published_at
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $revision
 *
 * @property PostCategory $category
 * @property User $createdBy
 * @property User $updatedBy
 * @property PostLang[] $postLangs
 * @property Tag[] $tags
 */
class Post extends ActiveRecord implements OwnerAccess
{
	
    const STATUS_PENDING = 0;
    const STATUS_PUBLISHED = 1;
    const COMMENT_STATUS_CLOSED = 0;
    const COMMENT_STATUS_OPEN = 1;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%post}}';
    }

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        if ($this->isNewRecord && $this->className() == Post::className()) {
            $this->published_at = time();
        }

        $this->on(self::EVENT_BEFORE_UPDATE, [$this, 'updateRevision']);
        $this->on(self::EVENT_AFTER_UPDATE, [$this, 'saveTags']);
        $this->on(self::EVENT_AFTER_INSERT, [$this, 'saveTags']);
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
            BlameableBehavior::className(),
            'sluggable' => [
                'class' => SluggableBehavior::className(),
                'attribute' => 'title',
            ],
            'multilingual' => [
                'class' => MultilingualBehavior::className(),
                'langForeignKey' => 'post_id',
                'tableName' => "{{%post_lang}}",
                'attributes' => [
                    'title', 'content',
                ]
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title'], 'required'],
            [['created_by', 'updated_by', 'status', 'comment_status', 'revision', 'category_id'], 'integer'],
            [['title', 'content', 'view', 'layout'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['slug'], 'string', 'max' => 127],
            [['thumbnail'], 'string', 'max' => 255],
            ['published_at', 'date', 'timestampAttribute' => 'published_at', 'format' => 'yyyy-MM-dd'],
            ['published_at', 'default', 'value' => time()],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('core', 'ID'),
            'created_by' => Yii::t('core', 'Author'),
            'updated_by' => Yii::t('core', 'Updated By'),
            'slug' => Yii::t('core', 'Slug'),
            'view' => Yii::t('core', 'View'),
            'layout' => Yii::t('core', 'Layout'),
            'title' => Yii::t('core', 'Title'),
            'status' => Yii::t('core', 'Status'),
            'comment_status' => Yii::t('core', 'Comment Status'),
            'content' => Yii::t('core', 'Content'),
            'category_id' => Yii::t('core', 'Category'),
            'thumbnail' => Yii::t('core/post', 'Thumbnail'),
            'published_at' => Yii::t('core', 'Published'),
            'created_at' => Yii::t('core', 'Created'),
            'updated_at' => Yii::t('core', 'Updated'),
            'revision' => Yii::t('core', 'Revision'),
            'tagValues' => Yii::t('core', 'Tags'),
        ];
    }

    /**
     * @inheritdoc
     * @return PostQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new PostQuery(get_called_class());
    }

	/**
	 * Prevent save default TinyMce value to opposite language
	 *
	 * @inheritdoc
	 */
	public function beforeSave($insert)
	{
		if (Yii::$app->core->isMultilingual){
			$defaultHtml = <<<HTML
<!DOCTYPE html>
<html>
<head>
</head>
<body>

</body>
</html>
HTML;
			$attribute = $this->getLangAttribute('content_en_us');
			if(strcmp($attribute, $defaultHtml) == 0){
				$this->setLangAttribute('content_en_us', '');
			}
			$attribute = $this->getLangAttribute('content_es_es');
			if(strcmp($attribute, $defaultHtml) == 0){
				$this->setLangAttribute('content_es_es', '');
			}
		}
		return parent::beforeSave($insert);
	}

	/**
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(Category::className(), ['id' => 'category_id'])->multilingual();
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTagValues()
    {
        $ids = [];
        $tags = $this->tags;
        foreach ($tags as $tag) {
            $ids[] = $tag->id;
        }

        return json_encode($ids);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTags()
    {
        return $this->hasMany(Tag::className(), ['id' => 'tag_id'])
                    ->viaTable('{{%post_tag_post}}', ['post_id' => 'id'])->multilingual();
    }

    /**
     * Handle save tags event of the owner.
     */
    public function saveTags()
    {
        /** @var Post $owner */
        $owner = $this->owner;

        $post = Yii::$app->getRequest()->post('Post');
        $tags = (isset($post['tagValues'])) ? $post['tagValues'] : null;

        if (is_array($tags)) {
            $owner->unlinkAll('tags', true);

            foreach ($tags as $tag) {
                if (!ctype_digit($tag) || !$linkTag = Tag::findOne($tag)) {
                    $linkTag = new Tag(['title' => (string) $tag]);
                    $linkTag->setScenario(Tag::SCENARIO_AUTOGENERATED);
                    $linkTag->save();
                }

                $owner->link('tags', $linkTag);
            }
        }
    }

    public function getAuthor()
    {
        return $this->hasOne(User::className(), ['id' => 'created_by']);
    }

    public function getUpdatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'updated_by']);
    }

    public function getPublishedDate()
    {
        return Yii::$app->formatter->asDate(($this->isNewRecord) ? time() : $this->published_at);
    }

    public function getCreatedDate()
    {
        return Yii::$app->formatter->asDate(($this->isNewRecord) ? time() : $this->created_at);
    }

    public function getUpdatedDate()
    {
        return Yii::$app->formatter->asDate(($this->isNewRecord) ? time() : $this->updated_at);
    }

    public function getPublishedTime()
    {
        return Yii::$app->formatter->asTime(($this->isNewRecord) ? time() : $this->published_at);
    }

    public function getCreatedTime()
    {
        return Yii::$app->formatter->asTime(($this->isNewRecord) ? time() : $this->created_at);
    }

    public function getUpdatedTime()
    {
        return Yii::$app->formatter->asTime(($this->isNewRecord) ? time() : $this->updated_at);
    }

    public function getPublishedDatetime()
    {
        return "{$this->publishedDate} {$this->publishedTime}";
    }

    public function getCreatedDatetime()
    {
        return "{$this->createdDate} {$this->createdTime}";
    }

    public function getUpdatedDatetime()
    {
        return "{$this->updatedDate} {$this->updatedTime}";
    }

    public function getStatusText()
    {
        return $this->getStatusList()[$this->status];
    }

    public function getCommentStatusText()
    {
        return $this->getCommentStatusList()[$this->comment_status];
    }

    public function getRevision()
    {
        return ($this->isNewRecord) ? 1 : $this->revision;
    }

    public function updateRevision()
    {
        $this->updateCounters(['revision' => 1]);
    }

    public function getShortContent($delimiter = '<!-- pagebreak -->', $allowableTags = '<a>')
    {
        $content = explode($delimiter, $this->content);
        return strip_tags($content[0], $allowableTags);
    }

    public function getThumbnail($options = ['class' => 'thumbnail pull-left', 'style' => 'width: 240px'])
    {
        if (!empty($this->thumbnail)) {
            return Html::img($this->thumbnail, $options);
        }

        return;
    }

    /**
     * getTypeList
     * @return array
     */
    public static function getStatusList()
    {
        return [
            self::STATUS_PENDING => Yii::t('core', 'Pending'),
            self::STATUS_PUBLISHED => Yii::t('core', 'Published'),
        ];
    }

    /**
     * getStatusOptionsList
     * @return array
     */
    public static function getStatusOptionsList()
    {
        return [
            [self::STATUS_PENDING, Yii::t('core', 'Pending'), 'default'],
            [self::STATUS_PUBLISHED, Yii::t('core', 'Published'), 'primary']
        ];
    }

    /**
     * getCommentStatusList
     * @return array
     */
    public static function getCommentStatusList()
    {
        return [
            self::COMMENT_STATUS_OPEN => Yii::t('core', 'Open'),
            self::COMMENT_STATUS_CLOSED => Yii::t('core', 'Closed')
        ];
    }

    /**
     *
     * @inheritdoc
     */
    public static function getFullAccessPermission()
    {
        return 'fullPostAccess';
    }

    /**
     *
     * @inheritdoc
     */
    public static function getOwnerField()
    {
        return 'created_by';
    }

}
