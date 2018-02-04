<?php
class TdTodoController extends Controller {
	
	public $views = array();
	public $urls = array();

	public function init() {

		parent::init();

		$this->views = array(
			'view_index' => 'application.yii1-module-todo-mngr.views.todo.index',
			'view__taskView' => 'application.yii1-module-todo-mngr.views.todo._taskView',
		);

		$this->urls = array(
			'url_createNewTask' => Yii::app()->createAbsoluteUrl('TdTodoController/createNewTask'),
			'url_taskList' => Yii::app()->createAbsoluteUrl('TdTodoController/index'),
		);

	}

	public function actionIndex() {

		$webUser = Yii::app()->user;
		$account = $webUser->account;
		$task = new Task;
		$taskInfo = new TaskInfo;

		$dataProvider = Task::model()->tasksDataProvider($account->id);

		$listViewOptions = array(
			'id' => 'task-list',
			'ajaxUrl' => $this->urls['url_taskList'],
			'dataProvider'=>$dataProvider,
			'itemView'=>$this->views['view__taskView'],
			'viewData' => array(
				'pagination' => $dataProvider->getPagination()
			),
			'emptyText' => "<div><span>No available task.</span></div>",
		);

		$this->render($this->views['view_index'], array(
			'listViewOptions' => $listViewOptions,
			'urlCreateNewTask' => $this->urls['url_createNewTask'],
			'task' => $task,
			'taskInfo' => $taskInfo
		));

	}

	public function actionCreateNewTask() {

		if(isset($_POST['Task']) && isset($_POST['TaskInfo'])) {

			$webUser = Yii::app()->user;
			$account = $webUser->account;

			$response = array();

			$task = new Task;
			$taskInfo = new TaskInfo;

			$task->setAttributes($_POST['Task']);
			$taskInfo->setAttributes($_POST['TaskInfo']);

			$valid = $task->validate();
			$valid = $taskInfo->validate() && $valid;

			if($valid){

				$task->owner_id = $account->id;

				if($task->save(false)){

					$taskInfo->task_id = $task->id;

					if($taskInfo->save(false)){

						if(Yii::app()->request->isAjaxRequest){
							$response = array(
								'status' => 1,
								'message' => 'Task successfully saved.'
							);
						}
						else
							Yii::app()->user->setFlash('success', "Task successfully saved.");

					}
					else
						$valid = false;

				}
				else
					$valid = false;

			}
			else
				$valid = false;

			if(!$valid) {

				$errorMessage = "<strong>Saving task failed.</strong>";

				$errorMessage .= "<br /><br />".CHtml::errorSummary($task);

				if(Yii::app()->request->isAjaxRequest){

					$response = array(
						'status' => 0,
						'message' => $errorMessage
					);
				}
				else{
					Yii::app()->user->setFlash('success', $errorMessage);
				}

			}

			echo CJSON::encode($response);

		}

	}

}