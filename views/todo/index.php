<?php
Yii::app()->clientScript->registerCssFile(Yii::app()->request->baseUrl.'/css/bootstrap-datepicker.css');
Yii::app()->clientScript->registerCss("custom-styles","

	.hide {display:none;}
	.task-item {
		margin-bottom:30px;
	}
	.task-item-title {
		font-size: 18px;
	}
	.pull-right {
		float:right;
	}

");
?>
<div class="container">
	<h2>Todo</h2>
	<div class="separator"></div>

	<div class="row">
		<div class="col-md-12">

			<?php 

				$this->widget('zii.widgets.CListView', $listViewOptions);

			?>
		</div>
	</div>
	<br />
	<div class="row">	
		<div class="col-md-12">
			<?php 
				$form=$this->beginWidget('CActiveForm', array(
				    'id'=>'new-task-form',
				    'htmlOptions' => array(
				        'class' => 'form-signup'
				    )
				)); 
			?>
				<div id="form-ajax-res">
					<p class="alert"></p>
				</div>
			
				<div class="form-group">
					<?php echo $form->textField($task,'title', array('class' => 'form-control', 'placeholder' => 'New Task Title')); ?>
				</div>
				<div class="task-info hide">
					<div class="form-group">
						<?php echo $form->label($taskInfo,'task_desc'); ?>
						<?php echo $form->textArea($taskInfo,'task_desc', array('class' => 'form-control')); ?>
					</div>
					<div class="row">
						<div class="col-md-2">
							<div class="form-group">
								<?php echo $form->label($taskInfo, 'priority'); ?>
								<?php echo $form->dropDownList($task, 'priority', Task::priorityList(), array('class' => 'form-control')); ?>
							</div>	
						</div>
						<div class="col-md-2">
							<?php echo $form->label($task,'date_deadline'); ?>
							<?php echo $form->textField($task,'date_deadline', array('class' => 'form-control date-picker')); ?>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12 text-right">
							<hr />
							<?php echo CHtml::link("Cancel", "javascript:void(0)", array('class' => 'btn btn-md btn-default task-btn-cancel')); ?>
							<?php echo CHtml::submitButton('Save', array('class' => 'btn btn-md btn-primary')); ?>
						</div>
					</div>
				</div>

			<?php $this->endWidget(); ?>
		</div>
	</div>
</div>
<?php
Yii::app()->clientScript->registerScriptFile('http://code.jquery.com/jquery-2.1.1.min.js');
Yii::app()->clientScript->registerScriptFile('https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.20.1/moment.js');
Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl.'/js/bootstrap-datepicker.js');
Yii::app()->clientScript->registerScript("datepicker-init", "

	$('.date-picker').datepicker();

", CClientScript::POS_END);
Yii::app()->clientScript->registerScript('ajaxForm', "

	function refreshList() {

		$.fn.yiiListView.update('".html_entity_decode($listViewOptions['id'])."');

	}

	function alertSuccess(message){

		var alertElement = $('#form-ajax-res');

		alertElement.hide();
		alertElement.removeClass('alert-danger');
		alertElement.addClass('alert-success');
		alertElement.find('.alert').html(message);

		alertElement.show();

	}

	function alertError(message){

		var alertElement = $('#form-ajax-res');

		alertElement.hide();
		alertElement.removeClass('alert-success');
		alertElement.addClass('alert-danger');
		alertElement.find('.alert').html(message);

		alertElement.show();

	}

	function clearForm(){
		$('form input[type=\"text\"]').val('');
		$('form select').val('');
		$('form textarea').val('');
	}

	function createNewTask(data) {

		$.ajax({
			url: '".$urlCreateNewTask."',
			type: 'post',
			dataType: 'json',
			data: data,
			success: function(res){
				
				if(parseInt(res.status) == 1){
					alertSuccess(res.message);
					clearForm();
					refreshList();
				}
				else
					alertError(res.message);

			}
		});

	}

	$('#new-task-form').submit(function(e){
		
		e.preventDefault();

		var self = $(this);

		createNewTask(self.serialize());

		return false;
	});

");
Yii::app()->clientScript->registerScript('eventScripts', "

	$('#Task_title').click(function(){

		$('.task-info').show();

	});

	$('.task-btn-cancel').click(function(){

		$('.task-info').hide();
		clearForm();

	})

");
?>