<div class="row task-item">
	<div class="col-md-1">
		<?php echo ($pagination->currentPage * $pagination->pageSize) + (++$index); ?>
	</div>
	<div class="col-md-11">
		<div class="task-item-title">
			<div class="pull-right">
				<?php echo CHtml::link('Complete', 'javascript:void(0)', array('class' => 'btn btn-sm btn-primary')); ?> | 
				<?php echo CHtml::link('Delete', 'javascript:void(0)', array('class' => 'btn btn-sm btn-danger')); ?>
			</div>
			<strong><?php echo $data->title ?></strong>	
		</div>
		<div class="task-item-info">
			<div class="row">
				<div class="col-md-2">
					<small><?php echo CHtml::activeLabel($data,'priority'); ?></small><br />
					<span><?php echo $data->getPriorityLabel(); ?></span>
				</div>
				<div class="col-md-2">
					<small><?php echo CHtml::activeLabel($data,'date_created'); ?></small><br />
					<span><?php echo (new DateTime($data->date_created))->format('d/m/Y'); ?></span>
				</div>
			</div>
			<?php if(!empty($data->taskInfo->task_desc)) { ?>
				<br />
				<div class="row">
					<div class="col-md-12">
						<?php echo CHtml::activeLabel($data->taskInfo,'task_desc'); ?><br />
						<small><?php echo CHtml::encode($data->taskInfo->task_desc); ?></small>
					</div>
				</div>
			<?php } ?>
		</div>
		
	</div>
</div>