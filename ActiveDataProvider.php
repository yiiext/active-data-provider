<?php
/**
 * ActiveDataProvider class file.
 *
 * @author Veaceslav Medvedev <slavcopost@gmail.com>
 * @license http://www.opensource.org/licenses/bsd-license.php
 * @link https://github.com/yiiext
 */
/**
 * Use data provider as active record behavior.
 *
 * @property CActiveRecord $owner
 *
 * @author Veaceslav Medvedev <slavcopost@gmail.com>
 * @version 0.1
 * @package yiiext
 * @link https://github.com/yiiext
 */
class ActiveDataProvider extends CActiveDataProvider implements IBehavior
{
	/**
	 * Rewrite CActiveDataProvider::__construct to not set model in construct.
	 *
	 * @param mixed $modelClass the model class (e.g. 'Post') or the model finder instance
	 * (e.g. <code>Post::model()</code>, <code>Post::model()->published()</code>).
	 * @param array $config configuration (name=>value) to be applied as the initial property values of this class.
	 */
	public function __construct($modelClass = null, $config = array())
	{
		parent::__construct($modelClass, $config);
	}

	/**
	 * When attach, set provider's model and modelClass.
	 *
	 * @param CComponent $owner
	 */
	public function attach($owner)
	{
		$this->modelClass = get_class($owner);
		$this->model = $owner;
		$this->setId($this->modelClass);
	}

	/**
	 * Detach behavior. Not implement in this behavior.
	 *
	 * @param CComponent $owner
	 */
	public function detach($owner)
	{
		$this->model = null;
	}

	/**
	 * Behavior owner is owr model.
	 *
	 * @return CActiveRecord
	 */
	public function getOwner()
	{
		return $this->model;
	}

	/**
	 * Not implement in this behavior.
	 *
	 * @return bool
	 */
	public function getEnabled()
	{
		return true;
	}

	/**
	 * Not implement in this behavior.
	 *
	 * @param bool $value
	 */
	public function setEnabled($value)
	{
	}

	/**
	 * The scope that filter models by safe attributes values.
	 *
	 * @return CActiveRecord The owner model.
	 */
	public function filterByAttributesValues()
	{
		/** @var CDbCriteria $criteria */
		$criteria = $this->owner->getDbCriteria();

		/** @var CDbColumnSchema $column */
		foreach($this->owner->safeAttributeNames as $attribute) {
			$column = $this->owner->tableSchema->getColumn($attribute);
			if($column !== null) {
				$searchName = $this->owner->tableAlias .'.' . $attribute;
				$searchValue = $this->owner->$attribute;
				$partialMatch = $column->type == 'string';
				$criteria->compare($searchName, $searchValue, $partialMatch);
			}
		}

		return $this->owner;
	}

	/**
	 * This method is for BC to old applications.
	 *
	 * @deprecated
	 * @return ActiveDataProvider
	 */
	public function search()
	{
		return $this;
	}
}

