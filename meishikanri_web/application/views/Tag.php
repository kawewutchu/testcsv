<!DOCTYPE html>
<html>
<head>
 
<?php include("Header.php");?>

<link rel="stylesheet" type="text/css" href="<?= base_url('assets/css/bootstrap-select.css') ?>">
<script src="<?= base_url('assets/js/bootstrap-select.js') ?>"></script>

<script type="text/javascript">

</script>

</head>
<body>
	<?php if(isset($reset)){ ?>
		<script type="text/javascript">
    		(function () {
    			window.location = "<?= base_url('Tag/get_tag') ?>";
    		}());
    	</script>
	<?php } ?>
	<?php include("Navigation.php");?>
	<div class="container">
			<?php if(is_null($share_tag)&&is_null($my_tag)){ ?>
			編集可能なタグはありません。
			<?php }else{ ?>
			<div class="panel panel-default">
				<div class="panel-heading">
					編集可能タグ一覧
				</div>
				<div class="panel-body">
				<?= form_open('Tag/new_tag') ?>
        		<button type="submit" class="btn btn-link" id="submitAddTag">新しいタグを作成する</button>
        		<?= form_close() ?>
        		<br>
        		<?= form_open('Tag/edit_tag') ?>
                    <table class="table table-bordered table-condensed table-striped" style="background-color:#ffffff">
        				<tr>
        					<th width=50% class="bg-info" >
        						共有タグ
        					</th>
        					<th class="bg-success">
        						マイタグ
        					</th>
        				</tr>
        				<tr>
        					<td>
        						<?php foreach ($share_tag as $parent): ?>
                                	<?php if($parent['parent'] == NULL){ ?>
                              			<label class="taglabel">
                                			<?= $parent['tag'] ?><button type="submit" class="btn btn-link btn-xs" name="edit_code" value="<?= $parent['tag_code'] ?>">編集</button>
                                		</label>
                                		<br>
                                	<?php } ?>
                                	<?php foreach ($share_tag as $offspring ): ?>
                                		<?php if($offspring['parent'] == $parent['tag_code']){ ?>
                                			<label class="taglabel">
                                				└ <?= $offspring['tag'] ?><button type="submit" class="btn btn-link btn-xs" name="edit_code" value="<?= $offspring['tag_code'] ?>">編集</button>
                                			</label>
                                			<br>
                                		<?php } ?>
                                	<?php endforeach; ?>
                    			<?php endforeach; ?>
        					</td>
        					<td>
        						<?php foreach ($my_tag as $parent): ?>
                                	<?php if($parent['parent'] == NULL){ ?>
                                		<label class="taglabel">
                                			<?= $parent['tag'] ?><button type="submit" class="btn btn-link btn-xs" name="edit_code" value="<?= $parent['tag_code'] ?>">編集</button>
                                		</label>
                                		<br>
                                	<?php } ?>
                                	<?php foreach ($my_tag as $offspring ): ?>
                                		<?php if($offspring['parent'] == $parent['tag_code']){ ?>
                                			<label class="taglabel">
                                				└ <?= $offspring['tag'] ?><button type="submit" class="btn btn-link btn-xs" name="edit_code" value="<?= $offspring['tag_code'] ?>">編集</button>
                                			</label>
                                			<br>
                                		<?php } ?>
                                	<?php endforeach; ?>
                                <?php endforeach; ?>
        					</td>
        				</tr>
        			</table>
                <?= form_close() ?>
            	</div>
				<div class="panel-footer">
					編集可能タグ一覧
				</div>
			</div>
			<?php } ?>	
	</div>
</body>
</html>