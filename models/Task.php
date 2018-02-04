<?php

/**
 * This is the model class for table "{{td_task}}".
 *
 * The followings are the available columns in table '{{td_task}}':
 * @property string $id
 * @property integer $owner_id
 * @property string $title
 * @property integer $priority
 * @property integer $order_no
 * @property integer $status
 * @property string $date_created
 * @property string $date_updated
 * @property string $date_deadline
 * @property string $date_completed
 */
class Task extends CActiveRecord
{

	const STATUS_ACTIVE = 1;
	const STATUS_INACTIVE = 2;

	const PRIORITY_HIGH = 1;
	const PRIORITY_MEDIUM = 2;
	const PRIORITY_LOW = 3;
	const PRIORITY_NONE = NULL;

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{td_task}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('title', 'required'),
			array('owner_id, priority, order_no, status', 'numerical', 'integerOnly'=>true),
			array('title', 'length', 'max'=>200),
			array('date_created, date_updated', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, owner_id, title, priority, order_no, status, date_created, date_updated, date_deadline, date_completed', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'taskInfo' => array(self::HAS_ONE, 'TaskInfo', 'task_id')
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'owner_id' => 'Owner',
			'title' => 'Title',
			'priority' => 'Priority',
			'order_no' => 'Order No',
			'status' => 'Status',
			'date_created' => 'Date Created',
			'date_updated' => 'Date Updated',
			'date_deadline' => 'Date Deadline',
			'date_completed' => 'Date Completed',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id,true);
		$criteria->compare('owner_id',$this->owner_id);
		$criteria->compare('title',$this->title,true);
		$criteria->compare('priority',$this->priority);
		$criteria->compare('order_no',$this->order_no);
		$criteria->compare('status',$this->status);
		$criteria->compare('date_created',$this->date_created,true);
		$criteria->compare('date_updated',$this->date_updated,true);
		$criteria->compare('date_deadline',$this->date_deadline,true);
		$criteria->compare('date_completed',$this->date_completed,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Task the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function scopes() {

		return array(
			'active' => array(
				'condition' => 'status=:status',
				'params' => array(
					':status' => self::STATUS_ACTIVE
				)
			)
		);

	}

	public function tasksDataProvider($accountId){

		$models = $this->getTasks($accountId);

		return new CArrayDataProvider($models);

	}

	public function getTasks($accountId) {

		return Task::model()->active()->findAll(array('condition'=>'owner_id=:owner_id', 'params' => array(':owner_id' => $accountId)));

	}

	public static function priorityList() {

		return array(
			self::PRIORITY_NONE => "None",
			self::PRIORITY_HIGH => "High",
			self::PRIORITY_MEDIUM => "Medium",
			self::PRIORITY_LOW => "Low",
		);

	}

	public function getPriorityLabel(){
		return (isset(self::priorityList()[$this->priority]))?self::priorityList()[$this->priority]:null;
	}
}
