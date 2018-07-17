<!DOCTYPE html>
<html>
<head>
<?php include("Header.php");?>

<script src="/MeishiKanri/assets/js/jquery.js"></script>
<script src="/MeishiKanri/assets/js/jquery-ui.js"></script>
<link rel="stylesheet" href="/MeishiKanri/assets/css/jquery-ui.css" type="text/css" />
 
<script type="text/javascript">
    function required_check(){
    	var flag = false, str = 0;
    	
    	if(document.upload_form.upload_user.value == "NoData"){ 
    		flag = true;
    	}
    	if(flag){
    		str = "必須項目が未選択です。";
    	}   
    	if(str == 0){
    		return true;
    	}else{
    		window.alert(str);
    		return false;
    	}
    }
</script>

</head>
<body>
	
	<div class="container">
		<div class="panel panel-default">
			<div class="panel-heading">
				スキャン済みの名刺をアップロードします<br>
				名刺の所有者を選択してください
			</div>
		<div class="panel-body">
		<div class="table-responsive">
		<?php $attributes = array('class' => 'form-inline', 'name' => 'upload_form', 'onSubmit' => 'return required_check()'); ?>
		<?= form_open('Upload/get_tag', $attributes)?>
			<table class="table table-bordered table-hover table-condensed" style="background-color:#ffffff">
				<tr>
    				<th style="width:10%;">所有者<font color="red"> *必須</font></th>
    				<td>
    					<div class="form-group">
    						<div class="col-xs-4">
    							<select class="form-control" id="UploadUser" name="upload_user">
    								<option value="NoData" selected>選択してください</option>
                                   	<?php foreach ( $user_list as $user ): ?>
                                    <option value="<?= $user['user_id'] ?>"><?= $user['user_last']?> <?= $user['user_first'] ?></option>
                                    <?php endforeach; ?>
                                </select>
    						</div>
    					</div>
    				</td>
				</tr>
			</table>
		</div>
		</div>
		<div class="panel-footer">
		<button type="submit" class="btn btn-primary" id="submit-btn">交換日、タグ入力へ</button>
		<?= form_close() ?>
		</div>
		</div>
	</div>
</body>
</html>