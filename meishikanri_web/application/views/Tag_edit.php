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
    	
    	if(document.update_tag_form.update_tag.value == ""){ 
    		flag = true;
    	}
    	if(flag){
    		str = "タグが未入力です。";
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
				タグを編集または削除できます<br>
			</div>
			<div class="panel-body">
				<div class="table-responsive">
				    <?php $attributes = array('class' => 'form-inline', 'name' => 'update_tag_form', 'onSubmit' => 'return required_check()'); ?>
        			<?= form_open('Tag/update_tag', $attributes)?>
        			<table class="table-condensed" style="background-color:#ffffff">
        				<tr>
        					<td colspan="2">
        						<input type="text" class="form-control" placeholder="<?= $edit_tag['0']['tag'] ?>" id="UpdateTag" name="update_tag" value="<?= $edit_tag['0']['tag'] ?>" style="width:100%;">
        					</td>
        				</tr>
            		<?php if(!empty($parent_tag) || !empty($edit_tag['0']['parent'])){ ?>
                		<tr>
                			<td>
                				親タグを変更する場合は選択してください
               					<input type="radio" name="update_parent" value="<?= $edit_tag['0']['parent'] ?>" style="display:none;" checked="checked">
               				</td>
               			</tr>
            		<?php } ?>
            		<?php if(!empty($parent_tag)){ ?>
                    <?php foreach ( $parent_tag as $parent ): ?>
                        <tr>
                            <td>
                                <div class="radio">
                                    <label>
                                   		<input type="radio" name="update_parent" value="<?= $parent['tag_code'] ?>"> <?= $parent['tag'] ?>
                                   	</label>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    <?php } ?>
                    <?php if(!empty($edit_tag['0']['parent'])){ ?>            			
                        <tr>
                            <td>
	                            <div class="radio">
                                    <label>
                                        <input type="radio" name="update_parent" value=""> 現在の親タグをはずす
                                    </label>
                                </div>
                            </td>
                        </tr>
                    <?php } ?>
		                <tr>
        			        <td>
        						<button type="submit" class="btn btn-primary" id="submitEdit">編集内容を更新</button>
    							<input type="hidden" name="update_code" value="<?= $edit_tag['0']['tag_code'] ?>">
        					</td>
        				</tr>
        			</table>
        			<?= form_close() ?>
        		</div>
			</div>			
			<div class="panel-footer">
			<button type="button" class="btn btn-danger" data-toggle="modal" data-target="#hideModal">このタグを削除</button>
				<div class="modal fade" id="hideModal" tabindex="-1">
					<div class="modal-dialog">
						<div class="modal-content">
							<div class="modal-body">
								このタグを削除しますか？
							</div>
							<div class="modal-footer">
								<?= form_open('Tag/hide_tag') ?>
									<button type="submit" class="btn btn-danger" name="hide_code" value="<?= $edit_tag['0']['tag_code'] ?>">削除</button>
								<?php form_close() ?>
									<button type="button" class="btn btn-warning" data-dismiss="modal">キャンセル</button>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</body>
</html>