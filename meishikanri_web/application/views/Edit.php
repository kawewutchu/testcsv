<!DOCTYPE html>
<html>
<head>
<?php include("Header.php");?>
<script type="text/javascript">
    function required_check(){
    	var str = "";
        var tel = 0;
        var mobile = 0;
        var fax = 0;
        var postal = 0;

        if(document.update_form.update_company.value == ""){
    		str = str + "会社名が未入力です。";
    	}
    	if(document.update_form.update_lastname.value == ""){
    		str = str + "氏名(姓)が未入力です。";
    	}
    	if(document.update_form.update_firstname.value == ""){
    		str = str + "氏名(名)が未入力です。";
    	}
    	if(document.update_form.UpdateTelA.value != ""){
        	if(!(document.update_form.UpdateTelA.value.match(/^[0-9]+$/))){
        		tel++;
        	}
    	}
    	if(document.update_form.UpdateTelB.value != ""){
    		if(!(document.update_form.UpdateTelB.value.match(/^[0-9]+$/))){
        		tel++;
    		}
    	}
    	if(document.update_form.UpdateTelC.value != ""){
    		if(!(document.update_form.UpdateTelC.value.match(/^[0-9]+$/))){
        		tel++;
    		}
    	}
    	if(tel != 0){
    		str = str + "電話番号が不正です。";
    	}
    	if(document.update_form.UpdateMobileA.value != ""){
        	if(!(document.update_form.UpdateMobileA.value.match(/^[0-9]+$/))){
        		mobile++;
        	}
    	}
    	if(document.update_form.UpdateMobileB.value != ""){
    		if(!(document.update_form.UpdateMobileB.value.match(/^[0-9]+$/))){
    			mobile++;
    		}
    	}
    	if(document.update_form.UpdateMobileC.value != ""){
    		if(!(document.update_form.UpdateMobileC.value.match(/^[0-9]+$/))){
    			mobile++;
    		}
    	}
    	if(mobile != 0){
    		str = str + "携帯電話番号が不正です。";
    	}
    	if(document.update_form.UpdateFaxA.value != ""){
        	if(!(document.update_form.UpdateFaxA.value.match(/^[0-9]+$/))){
        		fax++;
        	}
    	}
    	if(document.update_form.UpdateFaxB.value != ""){
    		if(!(document.update_form.UpdateFaxB.value.match(/^[0-9]+$/))){
    			fax++;
    		}
    	}
    	if(document.update_form.UpdateFaxC.value != ""){
    		if(!(document.update_form.UpdateFaxC.value.match(/^[0-9]+$/))){
    			fax++;
    		}
    	}
    	if(fax != 0){
    		str = str + "FAX番号が不正です。";
    	}
    	if(document.update_form.UpdatePostalA.value != ""){
        	if(!(document.update_form.UpdatePostalA.value.match(/^[0-9]+$/))){
        		postal++;
        	}
    	}
    	if(document.update_form.UpdatePostalB.value != ""){
    		if(!(document.update_form.UpdatePostalB.value.match(/^[0-9]+$/))){
    			postal++;
    		}
    	}
    	if(postal != 0){
    		str = str + "郵便番号が不正です。";
    	}
    	if(document.update_form.UpdateMail.value != ""){
        	if(!(document.update_form.UpdateMail.value.match(/^(?:(?:(?:(?:[a-zA-Z0-9_!#\$\%&¥¥'*+/=?\^`{}~|\-]+)(?:\.(?:[a-zA-Z0-9_!#\$\%&¥¥'*+/=?\^`{}~|\-]+))*)|(?:¥¥"(?:\\[^\r\n]|[^\\¥¥"])*¥¥")))\@(?:(?:(?:(?:[a-zA-Z0-9_!#\$\%&¥¥'*+/=?\^`{}~|\-]+)(?:\.(?:[a-zA-Z0-9_!#\$\%&¥¥'*+/=?\^`{}~|\-]+))*)|(?:\[(?:\\\S|[\x21-\x5a\x5e-\x7e])*\])))$/))){
        		str = str + "メールアドレスが不正です。";
        	}
    	}

    	if(str == ""){
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
	<script type="text/javascript">
		$(function(){
	  		$("#datepicker").datepicker();
		});
		function imgwin(img, size){
		    window.open(img, "imgwindow", size);
		}
	</script>
	<div class="container">
	<?php $attributes = array('name' => 'update_form', 'onSubmit' => 'return required_check()'); ?>
	<?= form_open('Edit/update', $attributes)?>
		<div class="">
			<table class="table">
				<tr>
					<th width="50%">
						<div align="center">
						<?php if(!isset($pdf)){ ?>
							<img src="<?= $meishi_detail['0']['img_name_f'] ?>" style="max-width:500px; max-height:300px" onclick="imgwin('<?= $meishi_detail['0']['img_name_f'] ?>', 'width=900, height=540')" class="img-responsive" alt="表側(未登録)">
						<?php }else{ ?>
							<img src="<?= $meishi_detail['0']['img_name_f'] ?>" style="max-width:500px; max-height:300px" onclick="imgwin('<?= $pdf ?>', 'width=900, height=1080')" class="img-responsive" alt="表側(未登録)">
						<?php } ?>
						</div>
					</th>
					<th width="50%">
						<div align="center">
						<?php if(!isset($pdf)){ ?>
							<img src="<?= $meishi_detail['0']['img_name_b'] ?>" style="max-width:500px; max-height:300px" onclick="imgwin('<?= $meishi_detail['0']['img_name_b'] ?>', 'width=900, height=540')" class="img-responsive" alt="裏側(未登録)">
						<?php }else{ ?>
							<img src="<?= $meishi_detail['0']['img_name_b'] ?>" style="max-width:500px; max-height:300px" onclick="imgwin('<?= $pdf ?>', 'width=900, height=1080')" class="img-responsive" alt="裏側(未登録)">
						<?php } ?>
						</div>
					</th>
				</tr>
			</table>
		</div>
		<div class="" style="position: relative; top:10px;  width:100%; overflow: auto; height: calc(100vh - 550px);">
			<table class="table table-bordered table-hover table-condensed" style="background-color:#ffffff">
				<tr>
					<th width="25%">画像の表面 / 裏面</th>
					<td>
						<input type="radio" name="sides" value="1"> 入れ替える <input type="radio" name="sides" value="0" checked> 入れ替えない
					</td>
					</tr>
				<tr>
					<th>
						会社名<font color="red"> *必須</font>
					</th>
					<td>
						<div class="form-group">
							<div class="col-sm-10">
								<input type="text" class="form-control" id="" value="<?= ($meishi_detail['0']['company_name']) ?>" name="update_company">
							</div>
						</div>
					</td>
				</tr>
				<tr>
					<th>
						会社名(カナ)
					</th>
					<td>
						<div class="form-group">
							<div class="col-sm-10">
								<input type="text" class="form-control" id="" value="<?= ($meishi_detail['0']['kana_company_name']) ?>" name="update_kana_company">
							</div>
						</div>
					</td>
				</tr>
				<tr>
					<th>
						部署
					</th>
					<td>
						<div class="form-group">
							<div class="col-sm-10">
								<input type="text" class="form-control" id="" value="<?= ($meishi_detail['0']['position']) ?>" name="update_position">
							</div>
						</div>
					</td>
				</tr>
				<tr>
					<th>
						役職
					</th>
					<td>
						<div class="form-group">
							<div class="col-sm-10">
								<input type="text" class="form-control" id="" value="<?= ($meishi_detail['0']['post']) ?>" name="update_post">
							</div>
						</div>
					</td>
				</tr>
						<tr>
							<th>氏名<font color="red"> *必須</font></th>
							<td>
								<div class="form-inline">
									<div class="col-sm-10">
										<input type="text" class="form-control" id="" value="<?= ($meishi_detail['0']['lastname']) ?>" name="update_lastname">
										<input type="text" class="form-control" id="" value="<?= ($meishi_detail['0']['firstname']) ?>" name="update_firstname">
									</div>
								</div>
							</td>
						</tr>
						<tr>
							<th>氏名(カナ)</th>
							<td>
								<div class="form-inline">
									<div class="col-sm-10">
										<input type="text" class="form-control" id="" value="<?= ($meishi_detail['0']['kana_lastname']) ?>" name="update_kana_lastname">
										<input type="text" class="form-control" id="" value="<?= ($meishi_detail['0']['kana_firstname']) ?>" name="update_kana_firstname">
									</div>
								</div>
							</td>
						</tr>
						<tr>
							<th>電話番号</th>
							<td>
								<div class="form-inline">
									<div class="col-sm-10">
										<input type="text" size="12" class="form-control" id="UpdateTelA" value="<?= ($meishi_detail['0']['tel_abc'][0]) ?>" name="update_tel[]"> -
										<input type="text" size="12" class="form-control" id="UpdateTelB" value="<?= ($meishi_detail['0']['tel_abc'][1]) ?>" name="update_tel[]"> -
										<input type="text" size="12" class="form-control" id="UpdateTelC" value="<?= ($meishi_detail['0']['tel_abc'][2]) ?>" name="update_tel[]">
									</div>
								</div>
							</td>
						</tr>
						<tr>
							<th>携帯電話</th>
							<td>
								<div class="form-inline">
									<div class="col-sm-10">
										<input type="text" size="12" class="form-control" id="UpdateMobileA" value="<?= ($meishi_detail['0']['mobile_abc'][0]) ?>" name="update_mobile[]"> -
										<input type="text" size="12" class="form-control" id="UpdateMobileB" value="<?= ($meishi_detail['0']['mobile_abc'][1]) ?>" name="update_mobile[]"> -
										<input type="text" size="12" class="form-control" id="UpdateMobileC" value="<?= ($meishi_detail['0']['mobile_abc'][2]) ?>" name="update_mobile[]">
									</div>
								</div>
							</td>
						</tr>
						<tr>
							<th>FAX</th>
							<td>
								<div class="form-inline">
									<div class="col-sm-10">
										<input type="text" size="12" class="form-control" id="UpdateFaxA" value="<?= ($meishi_detail['0']['fax_abc'][0]) ?>" name="update_fax[]"> -
										<input type="text" size="12" class="form-control" id="UpdateFaxB" value="<?= ($meishi_detail['0']['fax_abc'][1]) ?>" name="update_fax[]"> -
										<input type="text" size="12" class="form-control" id="UpdateFaxC" value="<?= ($meishi_detail['0']['fax_abc'][2]) ?>" name="update_fax[]">
									</div>
								</div>
							</td>
						</tr>
						<tr>
							<th>E-mail</th>
							<td>
								<div class="form-group">
									<div class="col-sm-10">
										<input type="text" class="form-control" id="UpdateMail" value="<?= ($meishi_detail['0']['mail']) ?>" name="update_mail">
									</div>
								</div>
							</td>
						</tr>
						<tr>
							<th>URL</th>
							<td>
								<div class="form-group">
									<div class="col-sm-10">
										<input type="text" class="form-control" id="" value="<?= ($meishi_detail['0']['url']) ?>" name="update_url">
									</div>
								</div>
							</td>
						</tr>
						<tr>
							<th>郵便番号</th>
							<td>
								<div class="form-inline">
									<div class="col-sm-10">
										〒 <input type="text" size="12" class="form-control" id="UpdatePostalA" value="<?= ($meishi_detail['0']['postal_ab'][0]) ?>" name="update_postal[]"> -
										<input type="text" size="12" class="form-control" id="UpdatePostalB" value="<?= ($meishi_detail['0']['postal_ab'][1]) ?>" name="update_postal[]" onKeyUp="AjaxZip3.zip2addr('update_postal[]',this,'update_address_a','update_address_b','update_address_c');">
									</div>
								</div>
							</td>
						</tr>
						<tr>
							<th>都道府県</th>
							<td>
								<div class="form-group">
									<div class="col-sm-10">
										<input type="text" class="form-control" id="" value="<?= ($meishi_detail['0']['address_a']) ?>" name="update_address_a">
									</div>
								</div>
							</td>
						</tr>
						<tr>
							<th>市区町村</th>
							<td>
								<div class="form-group">
									<div class="col-sm-10">
										<input type="text" class="form-control" id="" value="<?= ($meishi_detail['0']['address_b']) ?>" name="update_address_b">
									</div>
								</div>
							</td>
						</tr>
						<tr>
							<th>町域・番地</th>
							<td>
								<div class="form-group">
									<div class="col-sm-10">
										<input type="text" class="form-control" id="" value="<?= ($meishi_detail['0']['address_c']) ?>" name="update_address_c">
									</div>
								</div>
							</td>
						</tr>
						<tr>
							<th>建物名</th>
							<td>
								<div class="form-group">
									<div class="col-sm-10">
										<input type="text" class="form-control" id="" value="<?= ($meishi_detail['0']['address_d']) ?>" name="update_address_d">
									</div>
								</div>
							</td>
						</tr>
						<tr>
							<th>名刺交換日</th>
							<td>
								<div class="form-group">
									<div class="col-xs-4">
										<input type="text" class="form-control" id="datepicker" name="update_exchange_date" value="<?= $meishi_detail['0']['exchange_date'] ?>" readonly>
									</div>
								</div>
							</td>
						</tr>
						<tr>
							<th>所有者</th>
							<td>
								<div class="form-group">
									<div class="col-xs-4">
										<select class="form-control" id="UpdateHolder" name="update_holder">
											<option value="<?= $meishi_detail['0']['holder_code'] ?>" selected><?= $meishi_detail['0']['user_last'] ?> <?= $meishi_detail['0']['user_first'] ?></option>
                                    		<?php foreach ( $user_list as $user ): ?>
                                        	<option value="<?= $user['user_id'] ?>"><?= $user['user_last']?> <?= $user['user_first'] ?></option>
                                   			<?php endforeach; ?>
                                   		</select>
									</div>
								</div>
							</td>
						</tr>
						<tr>
							<th>メモ</th>
							<td>
								<div class="col-sm-10">
									<textarea class="form-control" rows="5" class="form-control" id="" name="update_memo"><?= $meishi_detail['0']['memo'] ?></textarea>
								</div>
							</td>
						</tr>
						<tr>
							<th>タグ</th>
							<td>
							<!--
							<div onclick="obj=document.getElementById('open_parent').style; obj.display=(obj.display=='none')?'block':'none';">
								<a style="cursor:pointer;">タグ一覧を展開 / 折り畳み</a>
							</div>
							 -->
							<div id="open_parent" style="">
								<div class="checkbox">
									<table class="table table-bordered table-condensed"  style="background-color:#ffffff">
                                    	<tr>
                                        	<td width="50%" class="bg-info">
                                        		共有タグ
                                        	</td>
                                        	<td class="bg-success">
                                        		マイタグ
                                        	</td>
                                    	</tr>
                                    	<tr>
                                        	<td>
                                            	<?php foreach($share_tag as $parent): ?>
                                            		<?php if($parent['parent'] == NULL){ ?>
                                            			<label>
                                            			<?php if(in_array($parent['tag_code'], $checked_tag)){ ?>
                                            				<input type="checkbox" name="update_tag[]" value="<?= $parent['tag_code'] ?>" checked="checked"><?= $parent['tag'] ?>
                                            			<?php }else{ ?>
                                            				<input type="checkbox" name="update_tag[]" value="<?= $parent['tag_code'] ?>"><?= $parent['tag'] ?>
            	                                		<?php } ?>
                                            			</label>
            	                                		<br>
                                                		<?php foreach ($share_tag as $offspring ): ?>
                                                			<?php if($offspring['parent'] == $parent['tag_code']){ ?>
                                                				<label>
            		                                			<?php if(in_array($offspring['tag_code'], $checked_tag)){ ?>
                                                        			<input type="checkbox" name="update_tag[]" value="<?= $offspring['tag_code'] ?>" checked="checked"> └ <?= $offspring['tag'] ?>
                                                        		<?php }else{ ?>
                                                        			<input type="checkbox" name="update_tag[]" value="<?= $offspring['tag_code'] ?>"> └ <?= $offspring['tag'] ?>
                                                        		<?php } ?>
                                                        		</label>
                                                        		<br>
                                                			<?php } ?>
                                                		<?php endforeach; ?>
                                            		<?php } ?>
                                            	<?php endforeach; ?>
                                        	</td>
                                        	<td>
                                            	<?php foreach($my_tag as $parent): ?>
                                            		<?php if($parent['parent'] == NULL){ ?>
                                            			<label>
                                            			<?php if(in_array($parent['tag_code'], $checked_tag)){ ?>
                                            				<input type="checkbox" name="update_tag[]" value="<?= $parent['tag_code'] ?>" checked="checked"><?= $parent['tag'] ?>
                                            			<?php }else{ ?>
                                            				<input type="checkbox" name="update_tag[]" value="<?= $parent['tag_code'] ?>"><?= $parent['tag'] ?>
            	                                		<?php } ?>
                                            			</label>
            	                                		<br>
                                                		<?php foreach ($my_tag as $offspring ): ?>
                                                			<?php if($offspring['parent'] == $parent['tag_code']){ ?>
                                                				<label>
            		                                			<?php if(in_array($offspring['tag_code'], $checked_tag)){ ?>
                                                        			<input type="checkbox" name="update_tag[]" value="<?= $offspring['tag_code'] ?>" checked="checked"> └ <?= $offspring['tag'] ?>
                                                        		<?php }else{ ?>
                                                        			<input type="checkbox" name="update_tag[]" value="<?= $offspring['tag_code'] ?>"> └ <?= $offspring['tag'] ?>
                                                        		<?php } ?>
                                                        		</label>
                                                        		<br>
                                                			<?php } ?>
                                                		<?php endforeach; ?>
                                            		<?php } ?>
                                            	<?php endforeach; ?>
                                        	</td>
                                    	</tr>
                                	</table>
            					</div>
            				</div>
						</td>
					</tr>
				</table>
			</div>
			<button type="submit" class="btn btn-success" id="submit-btn" style="position: relative; top:20px; ">更新</button>
			<input type="hidden" name="update_code" value="<?= $meishi_detail['0']['main_code'] ?>">
			<?php if(isset($pdf)){ ?>
				<input type="hidden" name="pdf" value="<?= $pdf ?>">
			<?php } ?>
		<?= form_close() ?>
	</div>
	</body>
</html>
