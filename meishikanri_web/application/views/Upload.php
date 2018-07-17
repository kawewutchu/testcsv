<!DOCTYPE html>
<html>
<head>
<?php include("Header.php");?>

<script src="/MeishiKanri/assets/js/jquery.js"></script>
<script src="/MeishiKanri/assets/js/jquery-ui.js"></script>
<link rel="stylesheet" href="/MeishiKanri/assets/css/jquery-ui.css" type="text/css" />

<script type="text/javascript">
    $(function() {
        $("[name='default_edit_flag']").click(function(){
            var num = $("[name='default_edit_flag']").filter(':checked').val();
            if ( num == 1 ){
            	$("#datepicker").datepicker().datepicker('setDate','today');
            	$("#datepicker").css("background-color","#f0f0f0");
            	var field = document.getElementById('date_edit_0');
                field.removeAttribute("disabled");
                var field = document.getElementById('date_edit_1');
                field.removeAttribute("disabled");
                var field = document.getElementById('date_edit_2');
                field.removeAttribute("disabled");
            }else{
           　	$("#datepicker").datepicker('setDate',null).datepicker("destroy");
                $("#datepicker").css("background-color","#ffffff");
                var field = document.getElementById('date_edit_0');
                field.setAttribute("disabled", "disabled");
                var field = document.getElementById('date_edit_1');
                field.setAttribute("disabled", "disabled");
                var field = document.getElementById('date_edit_2');
                field.setAttribute("disabled", "disabled");
            }
        });
    });
</script>

<script type="text/javascript">
    $(function() {
        $("#date_edit_0").click(function(){
        	var curent = $("#datepicker").val()
        	$("#datepicker").datepicker('setDate','curent'+(-1));
        });        

        $("#date_edit_1").click(function(){
        	$("#datepicker").datepicker('setDate','today');
        });
        
        $("#date_edit_2").click(function(){
        	var curent = $("#datepicker").val()
        	$("#datepicker").datepicker('setDate','curent'+1);
        });
    });
</script>

</head>
<body>
	<?php if(isset($close)){ ?>
		<script type="text/javascript">
    		(function () {
    			window.opener = window;
    			var win = window.open(location.href,"_self");
    			win.close();
    		}());
    	</script>
	<?php } ?>
	<script type="text/javascript">
		$(function(){
	　		$("#datepicker").datepicker().datepicker('setDate','today');
		});
	</script>
	
	<div class="container">
		<div class="panel panel-default">
			<div class="panel-heading">
				スキャン済みの名刺をアップロードします<br>
				名刺交換日とタグを指定して、アップロードボタンを押してください
			</div>
			<div class="panel-body">
				<div class="table-responsive">
					<?= form_open('Upload/new_tag') ?>
        						<button type="submit" class="btn btn-link" id="submitAddTag">新しいタグを作成する</button>
        						<input type="hidden" name="add_tag_user" value="<?= $user_id ?>">
					<?= form_close() ?>
				    <?php $attributes = array('class' => 'form-inline', 'name' => 'upload_form'); ?>
        			<?= form_open('Upload/upload', $attributes)?>
        			<table class="table table-bordered table-condensed table-striped" style="background-color:#ffffff">
        				<tr>
        					<th width=50%  class="bg-info">
        						共有タグ
        					</th>
        					<th class="bg-success">
        						マイタグ
        					</th>
        				</tr>
        				<tr>
        					<td>
        						<div class="checkbox">
                                	<?php foreach ( $share_tag as $parent ): ?>
                                		<?php if($parent['parent'] == NULL){ ?>
                                			<label>
                                				<input type="checkbox" name="upload_tag[]" value="<?= $parent['tag_code'] ?>"> <?= $parent['tag'] ?>
                                			</label>
                                			<br>
                                			<?php foreach ($share_tag as $offspring ): ?>
                                				<?php if($offspring['parent'] == $parent['tag_code']){ ?>
                                					<label>
                                						<input type="checkbox" name="upload_tag[]" value="<?= $offspring['tag_code'] ?>">  └ <?= $offspring['tag'] ?>
                                					</label>
                                					<br>
                                				<?php } ?>
                                		<?php endforeach; ?>
                                		<?php } ?>
                                	<?php endforeach; ?>
            					</div>
        					</td>
        					<td>
        						<div class="checkbox">
                                	<?php foreach ( $my_tag as $parent ): ?>
                                		<?php if($parent['parent'] == NULL){ ?>
                                			<label>
                                				<input type="checkbox" name="upload_tag[]" value="<?= $parent['tag_code'] ?>"> <?= $parent['tag'] ?>
                                			</label>
                                			<br>
                                			<?php foreach ($my_tag as $offspring ): ?>
                                				<?php if($offspring['parent'] == $parent['tag_code']){ ?>
                                					<label>
                                						<input type="checkbox" name="upload_tag[]" value="<?= $offspring['tag_code'] ?>">  └ <?= $offspring['tag'] ?>
                                					</label>
                                					<br>
                                				<?php } ?>
                                		<?php endforeach; ?>
                                		<?php } ?>
                                	<?php endforeach; ?>
            					</div>
        					</td>
        				</tr>
        				<tr>
        					<th colspan="2">
        						<div class="form-group">
        							名刺交換日
        							<label>
                						<input type="radio" name="default_edit_flag" id="defaultEditFlag0" class="radio" value="0">
                						指定しない
                					</label>
        							<label>
        								<input type="radio" name="default_edit_flag" id="defaultEditFlag1" class="radio" value="1" checked="checked">
        								指定する
        							</label>
        							<input type="text" class="form-control" id="datepicker" name="upload_exchange_date" value="" readonly>
        							<div class="btn-group" role="group">
        								<button type="button" class="btn btn-default" name="date_edit_flag" id="date_edit_0">1日前</button>
        								<button type="button" class="btn btn-default" name="date_edit_flag" id="date_edit_1">今日</button>
        								<button type="button" class="btn btn-default" name="date_edit_flag" id="date_edit_2">1日後</button>
        							</div>
        						</div>
        					</th>
        				</tr>
        			</table>
				</div>
			</div>
			<div class="panel-footer">
				<button type="submit" class="btn btn-primary" id="submitUpload">アップロード</button>
			</div>
			<input type="hidden" name="upload_user" value="<?= $user_id ?>">
			<?= form_close() ?>
		</div>
	</div>
</body>
</html>