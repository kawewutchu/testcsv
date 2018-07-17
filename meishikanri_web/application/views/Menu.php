<!DOCTYPE html>
<html>
<head>
<?php include("Header.php");?>

<script type="text/javascript">
$(function() {
	//ヘッダー固定js
	$('.table').tablefix({height: window.innerHeight - 200, fixRows: 2});
});
</script>

</head>
<body>
	<?php include("Navigation.php");?>
	<div class="container">
			<?php if(!($meishi_list == NULL)){ ?>
			<div class="col-xs-12 col-lg-12">
				<table class="table table-bordered table-hover table-condensed" style="width:100%; background-color:#ffffff; table-layout:auto; word-break:break-all;">
					<thead>
						<tr>
							<td colspan="6" align="center">
							<div>
							<?= form_open('Menu/replace')?>
							<?= $max_row ?>件中 <?= ($page-1)*30+1 ?> 〜 <?= $page*30 ?>件目
								<div class="btn-group" role="group">
    							<?php if($meishi_list == NULL){ ?>
    								<button type="submit" class="btn btn-default btn-sm" disabled="disabled">最初</button>
    							<?php }else{?>
    								<button type="submit" class="btn btn-default btn-sm" name="prev_next" value="<?= 1 ?>">最初</button>
    							<?php } ?>
    							<!--
    							<?php if($page-10 < 1 || $meishi_list == NULL){ ?>
    								<button type="submit" class="btn btn-default btn-sm" disabled="disabled">10ぺージ前</button>
    							<?php }else{ ?>
    								<button type="submit" class="btn btn-default btn-sm" name="prev_next" value="<?= $page-10 ?>">10ページ前</button>
    							<?php } ?>
    							 -->
    							<?php if($page == 1 || $meishi_list == NULL){ ?>
    								<button type="submit" class="btn btn-default btn-sm" disabled="disabled">前の30件</button>
    							<?php }else{ ?>
    								<button type="submit" class="btn btn-default btn-sm" name="prev_next" value="<?= $page-1 ?>">前の30件</button>
    							<?php } ?>
    							<?php if($page == $max_page || $meishi_list == NULL){ ?>
    								<button type="submit" class="btn btn-default btn-sm" disabled="disabled">次の30件</button>
    							<?php }else{ ?>
    								<button type="submit" class="btn btn-default btn-sm" name="prev_next" value="<?= $page+1 ?>">次の30件</button>
    							<?php } ?>
    							<!--
    							<?php if($page+10 > $max_page || $meishi_list == NULL){ ?>
    								<button type="submit" class="btn btn-default btn-sm" disabled="disabled">10ぺージ後</button>
    							<?php }else{ ?>
    								<button type="submit" class="btn btn-default btn-sm" name="prev_next" value="<?= $page+10 ?>">10ページ後</button>
    							<?php } ?>
    							 -->
    							<?php if($meishi_list == NULL){ ?>
    								<button type="submit" class="btn btn-default btn-sm" disabled="disabled">最後</button>
    							<?php }else{ ?>
    								<button type="submit" class="btn btn-default btn-sm" name="prev_next" value="<?= $max_page ?>">最後</button>
    							<?php } ?>
								</div>
							<?= form_close() ?>
							</div>
							</td>
						</tr>
						<tr>
							<th width="15%"><div align="center"><font size="2">名刺</font></div></th>
							<th width="17%"><div align="center"><font size="2">会社名・氏名</font></div></th>
							<th width="17%"><div align="center"><font size="2">部署・役職</font></div></th>
							<th width="17%"><div align="center"><font size="2">連絡先</font></div></th>
							<th width="17%"><div align="center"><font size="2">タグ</font></div></th>
							<th width="17%"><div align="center"><font size="2">所有者・名刺交換日</font></div></th>
						</tr>
					</thead>
                    <?php foreach ( $meishi_list as $row ): ?>
                    <tbody>
                    	<tr>
                    	<!--  -->
							<td style="max-width:170px;">
								<div align="center" style="overflow: auto;">
									<a data-toggle="modal" data-target="#<?= $row['main_code'] ?>">
    								<img src="<?= $row['img_name_f'] ?>" style="max-width:150px; max-height:90px;" alt="No Image">
    								</a>
    								<div class="modal fade" id="<?= $row['main_code'] ?>" tabindex="-1">
                                    	<div class="modal-dialog">
                                    		<div class="modal-content">
                                    			<div class="modal-body">
                                    				<img src="<?= $row['img_name_f'] ?>" style="max-width:575px; max-height:345px;" alt="No Image">
                                    				<img src="<?= $row['img_name_b'] ?>" style="max-width:575px; max-height:345px;" alt="No Image">
                                    			</div>
                                    			<div class="modal-footer">
                                    				<button type="button" class="btn btn-default" data-dismiss="modal">閉じる</button>
                                    			</div>
                                    		</div>
                                    	</div>
                                    </div>
    								<br>
    								<?= form_open('Menu/to_detail')?>
    	                    		<button type="submit" class="btn btn-link" name="detail_code" value="<?= $row['main_code'] ?>"><span class="glyphicon glyphicon-file" aria-hidden="true"></span>詳細を見る</button>
    	                    		<?= form_close() ?>
								</div>
							</td>
							<!--  -->
							<td>
								<font size=2><?= ($row['company_name']) ?></font>
								<br>
								<font size=3><?php echo ($row['lastname'].' '.$row['firstname']) ?></font>
							</td>
							<td>
								<font size=2>部署：<?= ($row['position']) ?></font>
								<br>
								<font size=2>役職：<?= ($row['post']) ?></font>
							</td>
							<td>
								<img src="/MeishiKanri/assets/img/tel.png" height="20px" alt="Tel">
								<font size=2><?= $row['tel'] ?></font>
								<br>
								<img src="/MeishiKanri/assets/img/fax.png" height="20px" alt="Fax">
								<font size=2><?= $row['fax'] ?></font>
								<br>
								<img src="/MeishiKanri/assets/img/mobile.png" height="20px" alt="mobile">
								<font size=2><?= $row['mobile'] ?></font>
								<br>
								<img src="/MeishiKanri/assets/img/mail.png" height="15px" alt="Mail">
								<a href="mailto:<?php echo $row['mail'] ?>?body=
                				<?php if(!empty($row['company_name']))
                					{
                						echo ($row['company_name']),"%0d%0a";
                					}?>
                				<?php if(!empty($row['position']))
                					{
                						echo ($row['position']),"%0d%0a";
                					}?>
                				<?php if(!empty($row['post']))
                					{
                						echo ($row['post']),"%0d%0a";
                					}?>
                				<?if(!empty($row['lastname']) && !empty($row['firstname']))
                					{
                						echo ($row['lastname'].' '.$row['firstname']),' ','様';
                                    }?>">
                					<font size=2><?php echo $row['mail'] ?></font>
                				</a>
                			<td style="word-wrap:break-word;">
								<font size=2><?= ($row['tag']) ?></font>
                			</td>
							<td>
								<font size=2>所有者：<?= $row['user_last']?> <?= $row['user_first'] ?></font>
								<br>
								<font size=2>名刺交換日：<?= ($row['exchange_date']) ?></font>
							</td>
                    	</tr>
                    </tbody>
                    <?php endforeach; ?>
					<!--
                    <tfoot>
                    	<tr>
							<th><div align="center"><font size=2>名刺</font></div></th>
							<th><div align="center"><font size=2>会社名・氏名</font></div></th>
							<th><div align="center"><font size=2>部署・役職</font></div></th>
							<th><div align="center"><font size=2>連絡先</font></div></th>
							<th><div align="center"><font size="2">タグ</font></div></th>
							<th><div align="center"><font size=2>所有者・名刺交換日</font></div></th>
						</tr>
						<tr>
							<td colspan="6" align="center">
							<div>
							<?= form_open('Menu/replace')?>
							<?= $max_row ?>件中 <?= ($page-1)*30+1 ?> 〜 <?= $page*30 ?>件目
								<div class="btn-group" role="group">
    							<?php if($meishi_list == NULL){ ?>
    								<button type="submit" class="btn btn-default btn-sm" disabled="disabled">最初</button>
    							<?php }else{?>
    								<button type="submit" class="btn btn-default btn-sm" name="prev_next" value="<?= 1 ?>">最初</button>
    							<?php } ?>
    							<?php if($page == 1 || $meishi_list == NULL){ ?>
    								<button type="submit" class="btn btn-default btn-sm" disabled="disabled">前の30件</button>
    							<?php }else{ ?>
    								<button type="submit" class="btn btn-default btn-sm" name="prev_next" value="<?= $page-1 ?>">前の30件</button>
    							<?php } ?>
    							<?php if($page == $max_page || $meishi_list == NULL){ ?>
    								<button type="submit" class="btn btn-default btn-sm" disabled="disabled">次の30件</button>
    							<?php }else{ ?>
    								<button type="submit" class="btn btn-default btn-sm" name="prev_next" value="<?= $page+1 ?>">次の30件</button>
    							<?php } ?>
    							<?php if($meishi_list == NULL){ ?>
    								<button type="submit" class="btn btn-default btn-sm" disabled="disabled">最後</button>
    							<?php }else{ ?>
    								<button type="submit" class="btn btn-default btn-sm" name="prev_next" value="<?= $max_page ?>">最後</button>
    							<?php } ?>
								</div>
							<?= form_close() ?>
							</div>
							</td>
						</tr>
					</tfoot>
					-->
				</table>
			</div>
			<?php } ?>
	</div>
</body>
</html>
