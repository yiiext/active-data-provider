ActiveDataProvider
=======================

Use data provider as active record behavior.

Usage:
------------

Attach behavior to your model
~~~
class Post extends CActiveRecord
{
	public function behaviors()
	{
		return array(
			'dataProvider' => array(
				'class' => 'ActiveDataProvider',
				'criteria' => array(
					'scopes' => array(
						'filterByAttributesValues',
						'defaultOrder',
					),
					'with' => 'category',
				),
				'pagination' => array(
					'pageSize' => 30,
					'pageVar' => 'page',
				),
			),
		);
	}
}
~~~

Now in view file can us this provider
~~~
$model = new Post('search');
$this->widget('CGridView', array(
	'dataProvider' => $model->dataProvider,
));
~~~

The component is back compatible to old code
~~~
$model = new Post('search');
$this->widget('CGridView', array(
	'dataProvider' => $model->search(),
));
~~~