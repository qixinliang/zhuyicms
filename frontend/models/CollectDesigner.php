<?php
/*
 * 收藏设计师
 */
namespace frontend\models;
use yii\db\ActiveRecord;

Class CollectDesigner extends ActiveRecord{
	private $_field = null;

	const STATUS_OK = 1;
	const STATUS_DELETE = 2;
	static COLLECT_STATUS_DICT = array(
		self::STATUS_OK 	=> '收藏成功',
		self::STATUS_DELETE	=> '取消收藏',
	);

	protected $_name = '';
	
	public function __construct(){
		parent::__construct();
		$this->_name = 'zy_collect_designer';
		$this->_field = array(
			'collect_id','user_id','designer_id','status','create_time','update_time',
		);
	}
	
	public static function tableName(){
		return $this->_name;
	}

	/*根据user_id取得designer_id(检查是否收藏)*/
	
	public function getCollectDesignerById($userId){
		if(empty($userId)){
			return false;
		}
		$ret = $this->findBySql("SELECT DISTINCT designer_id FROM {$this->_name} WHERE user_id=:id AND status=1 GROUP BY designer_id",array(':id' => $userId));	
		return $ret;
	}

	public function opCollectDesigner($data){
		if(empty($data) || !is_array($data)){
			return false;
		}
		$updateField = array_intersect($this->_field,array_keys($data));
		foreach($updateField as $k => $v){
			$this->$v = $data[$v];
		}

		$this->save();
	}
}
