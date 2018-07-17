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
    	var num = $('input[name="add_tag_attribute"]').filter(':checked').val();
    	
    	if(document.add_tag_form.add_tag.value == ""){ 
    		flag = true;
    	}
    	
    	if(num == "NoData"){ 
    		flag = true;
    	}
    	
    	if(flag){
    		str = "必須項目が未入力または未選択です。";
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
<?php include("Navigation.php");?>
	<div class="container">
		<div class="panel panel-default">
			<div class="panel-heading">
				新しいタグを作成します
			</div>
			<div class="panel-body">
				<div class="table-responsive">
				    <?php $attributes = array('class' => 'form-inline', 'name' => 'add_tag_form', 'onSubmit' => 'return required_check()'); ?>
        			<?= form_open('Tag/add_tag', $attributes)?>
        			<table class="table table-bordered table-condensed table-striped" style="background-color:#ffffff">
        				<tr>
        					<td colspan="2">
        						<input type="text" class="form-control" placeholder="新しいタグを入力してください *必須" name="add_tag" value="" style="width:100%;">
        					</td>
        				</tr>
        			</table>
        			<table class="table table-bordered table-condensed table-striped" style="background-color:#ffffff">
        				<tr>
        					<td colspan="2">
        						新しいタグの属性を選択してください<font color="red"> *必須</font>
        						<input type="radio" name="add_tag_attribute" value="NoData" style="display:none;" checked="checked">
        					</td>
        				</tr>
        				<tr>
        					<th width=50% >
        						<div class="radio">
        							<label>
                                		<input type="radio" name="add_tag_attribute" value="1" onclick="obj_share=document.getElementById('open_share').style; obj_share.display='block'; obj_my=document.getElementById('open_my').style; obj_my.display='none'">共有タグ
                                	</label>
        						</div>
        					</th>
        					<th>
	        					<div class="radio">
        							<label>
                                		<input type="radio" name="add_tag_attribute" value="<?= $user_id ?>" onclick="obj_my=document.getElementById('open_my').style; obj_my.display='block'; obj_share=document.getElementById('open_share').style; obj_share.display='none'">マイタグ
                                	</label>
        						</div>
        					</th>
        				</tr>
        			</table>
        			<div id="open_share" style="display:none;clear:both;">
        			<table class="table table-bordered table-condensed table-striped" style="background-color:#ffffff">
        				<tr>
        					<td>
        						新しいタグの親に指定したいタグがあれば選択してください
        					</td>
        				</tr>
        				<tr>
        					<td>
        						<div class="radio">
        						<?php if(!empty($share_tag)){ ?>
                                	<?php foreach ( $share_tag as $parent ): ?>
                                		<?php if($parent['parent'] == NULL){ ?>
                                			<label>
                                				<input type="radio" name="add_tag_parent" value="<?= $parent['tag_code'] ?>"><?= $parent['tag'] ?>
                                			</label>
                                			<br>
                                		<?php } ?>
                                	<?php endforeach; ?>
                                <?php }else{ ?>
                                	親に指定できるタグはありません
                                <?php } ?>
            					</div>
        					</td>
        				</tr>
        			</table>
        			</div>
        			<div id="open_my" style="display:none;clear:both;">
        			<table class="table table-bordered table-condensed table-striped" style="background-color:#ffffff">
        				<tr>
        					<td>
        						新しいタグの親に指定したいタグがあれば選択してください
        					</td>
        				</tr>
        				<tr>
        					<td>
        						<div class="radio">
        						<?php if(!empty($my_tag)){ ?>
                                	<?php foreach ( $my_tag as $parent ): ?>
                                    	<?php if($parent['parent'] == NULL){ ?>
                                    		<label>
                                    			<input type="radio" name="add_tag_parent" value="<?= $parent['tag_code'] ?>"><?= $parent['tag'] ?>
                                    		</label>
                                    		<br>
                                    	<?php } ?>
                                	<?php endforeach; ?>
                                <?php }else{ ?>
                                	親に指定できるタグはありません
                                <?php } ?>
            					</div>
        					</td>
        				</tr>
        			</table>
        			</div>
				</div>
			</div>
			<div class="panel-footer">
				<button type="submit" class="btn btn-primary" id="submitAdd">新しいタグを追加</button>
			</div>
			<?= form_close() ?>
		</div>
	</div>
</body>
</html>