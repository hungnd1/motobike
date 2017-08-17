<?php
namespace common\widgets;

use common\assets\CKEditorAsset;
use common\assets\KCFinderAsset;
use dosamigos\ckeditor\CKEditorWidgetAsset;
use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\widgets\InputWidget;

class CKEditor extends InputWidget
{
	/**
	 * Whether add configuration that enables KCFinder. Defaults to TRUE.
	 * @see http://kcfinder.sunhater.com/
	 * @var bool
	 */
	public $enabledKCFinder = true;

	/**
	 * KCFinder default dynamic settings
	 * @link http://kcfinder.sunhater.com/install#dynamic
	 * @var array
	 */
	public static $kcfDefaultOptions = [
		'disabled' => false,
		'denyZipDownload' => true,
		'denyUpdateCheck' => true,
		'denyExtensionRename' => true,
		'theme' => 'default',
		'access' => [ // @link http://kcfinder.sunhater.com/install#_access
			'files' => [
				'upload' => false,
				'delete' => false,
				'copy' => false,
				'move' => false,
				'rename' => false,
			],
			'dirs' => [
				'create' => false,
				'delete' => false,
				'rename' => false,
			],
		],
		'types' => [  // @link http://kcfinder.sunhater.com/install#_types
			'files' => [
				'type' => '',
			],
		],
		'thumbsDir' => '.thumbs',
		'thumbWidth' => 100,
		'thumbHeight' => 100,
	];

	use CKEditorTrait;

	/**
	 * @inheritdoc
	 */
	public function init()
	{
		parent::init();
		$this->initOptions();
	}

	/**
	 * @inheritdoc
	 */
	public function run()
	{
		if ($this->hasModel()) {
			echo Html::activeTextarea($this->model, $this->attribute, $this->options);
		} else {
			echo Html::textarea($this->name, $this->value, $this->options);
		}
		$this->registerPlugin();
	}

	/**
	 * Registers CKEditor plugin
	 */
	protected function registerPlugin()
	{
		$view = $this->getView();

		CKEditorAsset::register($view);

		$id = $this->options['id'];

		if ($this->enabledKCFinder) {
			$kcFinderBundle = KCFinderAsset::register($view);
			$kcFinderBaseUrl = $kcFinderBundle->baseUrl;
			// Add KCFinder-specific config for CKEditor
			$this->clientOptions = ArrayHelper::merge(
				$this->clientOptions,
				[
					'filebrowserBrowseUrl'      => $kcFinderBaseUrl . '/browse.php?opener=ckeditor&type=files',
					'filebrowserImageBrowseUrl' => $kcFinderBaseUrl . '/browse.php?opener=ckeditor&type=images',
					'filebrowserFlashBrowseUrl' => $kcFinderBaseUrl . '/browse.php?opener=ckeditor&type=flash',
					'filebrowserUploadUrl'      => $kcFinderBaseUrl . '/upload.php?opener=ckeditor&type=files',
					'filebrowserImageUploadUrl' => $kcFinderBaseUrl . '/upload.php?opener=ckeditor&type=images',
					'filebrowserFlashUploadUrl' => $kcFinderBaseUrl . '/upload.php?opener=ckeditor&type=flash',
					'allowedContent'            => true,
				]
			);

		}

		$options = $this->clientOptions !== false && !empty($this->clientOptions)
			? Json::encode($this->clientOptions)
			: '{}';

		$js[] = "CKEDITOR.replace('$id', $options).on('blur', function(){this.updateElement();jQuery(this.element.$).trigger('blur');});";

		$view->registerJs(implode("\n", $js));

	}
} 