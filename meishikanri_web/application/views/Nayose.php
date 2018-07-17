<!DOCTYPE html>
<html>
<head>
<?php include("Header.php");?>
</head>
<body>
	<?php include("Navigation.php");?>	
	<div class="container">
		<?php if(empty($nayose_list)){ ?>
			名寄せ候補の名刺はありません
		<?php }else{ ?>
		<div style="float:left;width:14%;">
			<div class="table-responsive">
    			<table class="table table-bordered table-condensed"  style="background-color:#ffffff">
    				<thead>
    					<tr class="active">
    						<td colspan="3" align="center">
    							<?= $nayose_page ?> / <?= $max_nayose_page ?> ページ目 (<?= $max_nayose_row ?>件)
    						</td>
    					</tr>
    				</thead>
    				<tbody>
                	<?php foreach($nayose_list as $name): ?>
                    <?= form_open('Nayose/get_card') ?>
                    	<tr>
                    		<td>
                    			<button type="submit" class="btn btn-link" name="nayose_name" value="<?= $name['lastname'].' '.$name['firstname'] ?>"><?= $name['lastname'].' '.$name['firstname'] ?></button>
                    			<input type="hidden" name="set_num" value="<?= $name['set_num'] ?>">
                    		</td>
                    	</tr>
                    <?= form_close() ?>
                	<?php endforeach; ?>
                	</tbody>
            		<tfoot>
            			<tr class="active">
            				<td>
                        	<?= form_open('Nayose/replace_name')?>
                            <?php if($nayose_page == 1 || $nayose_list == NULL){ ?>
                                <button type="submit" class="btn btn-default btn-sm" disabled="disabled">前の20件</button>
                            <?php }else{ ?>
                                <button type="submit" class="btn btn-default btn-sm" name="replace_name" value="<?= $nayose_page-1 ?>">前の20件</button>
                            <?php } ?>
                            <?php if($nayose_page == $max_nayose_page || $nayose_list == NULL){ ?>
                                <button type="submit" class="btn btn-default btn-sm" disabled="disabled">次の20件</button>
                            <?php }else{ ?>
                                <button type="submit" class="btn btn-default btn-sm" name="replace_name" value="<?= $nayose_page+1 ?>">次の20件</button>
                            <?php } ?>
                            <?= form_close() ?>	
                			</td>
                		</tr>
        			</tfoot>
    			</table>
    		</div>
		</div>
		<div style="float:right;width:85%;">
		<?php if($nayose_card == NULL){ ?>
			<font size=4  style="white-space:pre-wrap;"> 名寄せする氏名を選択してください </font>
		<?php } ?>
		<?php if($nayose_card != NULL){ ?>
			<div class="table-responsive">
        		<table class="table table-bordered table-condensed"  style="background-color:#ffffff">
    				<thead>
    					<tr class="active">
    						<td colspan="3" align="center">
                				<?= form_open('Nayose/replace_set')?>
                                <?php if($set_num <= 1){ ?>
                                <button type="submit" class="btn btn-default" disabled="disabled">前の組み合わせ</button>
                                <?php }else{ ?>
                                <button type="submit" class="btn btn-default" name="replace_set" value="<?= $set_num-1 ?>">前の組み合わせ</button>
                                <?php } ?>
                                <?= $set_num ?> / <?= $max_set_num ?> 件目
                                <?php if($set_num >= $max_set_num){ ?>
                                <button type="submit" class="btn btn-default" disabled="disabled">次の組み合わせ</button>
                                <?php }else{ ?>
                                <button type="submit" class="btn btn-default" name="replace_set" value="<?= $set_num+1 ?>">次の組み合わせ</button>
                                <?php } ?>
                            	<?= form_close() ?>
    						</td>
    					</tr>    			
    				</thead>
    			<?php $attributes = array('name' => 'nayose_form'); ?>
				<?= form_open('Nayose/nayose',$attributes) ?>
				<tbody>
        				<tr>
        					<th width="30%">
        						<div align="center">
        							名刺画像（表面）
        						</div>
        					</th>
        					<th>
        						<div align="center">
        							経歴
        						</div>
        					</th>
        					<th width="10%">
        						<div align="center">
        							名刺交換日
        						</div>
        					</th>
        				</tr>
        				<?php foreach($nayose_card as $card): ?>
        				<tr>
        					<td>
        						<div align="center">
        							<img src="<?= $card['img_name_f'] ?>" style="max-width:500px; max-height:300px" alt="No Image">
        							<input type="hidden" name="nayose_set[]" value="<?= $card['main_code'] ?>">
        						</div>
        					</td>
        					<td>
        						<div class="table-responsive">
        							<table class="table table-bordered table-condensed">
            							<tr>
            								<td width="4%">
            									会社名
            								</td>
            								<td>
            									<?= $card['company_name'] ?>
            								</td>
            							</tr>
            							<tr>
            								<td>
            									部署
            								</td>
            								<td>
            									<?= $card['position'] ?>
            								</td>
            							</tr>
            							<tr>
            								<td>
            									役職
            								</td>
            								<td>
            									<?= $card['post'] ?>
        									</td>
        								</tr>
        							</table>
        						</div>
        					</td>
        					<td>
        						<div align="center">
        							<?= $card['exchange_date'] ?>
        						</div>
        					</td>
        				</tr>
        				<?php endforeach; ?>
        			</tbody>
        			<tfoot>
                		<tr class="active">
                    		<td colspan="3" align="center">
                    			<button type="submit" class="btn btn-primary" name="nayose_btn" value="nayose">この組み合わせを名寄せする: 経歴をまとめ、古い名刺を非表示にする</button>
            				</td>
            			</tr>
            			<tr class="active">
            				<td colspan="3" align="center">
                    			<button type="submit" class="btn btn-warning" name="nayose_btn" value="kenmu">この組み合わせを兼務にする: 同一人物として、両方の名刺を表示する</button>
                    		</td>
                    	</tr>
                    	<tr class="active">
                    		<td colspan="3" align="center">
                    			<button type="submit" class="btn btn-danger" name="nayose_btn" value="betsujin">この組み合わせを別人にする: 別の人物として、両方の名刺を表示する</button>
                    		</td>
                		</tr>
            		</tfoot>
            	</table>
        	</div>
    		<?= form_close() ?>
		<?php } ?>
		</div>
	<?php } ?>
	</div>
</body>
</html>