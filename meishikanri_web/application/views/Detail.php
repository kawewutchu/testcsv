<!DOCTYPE html>
<html>
<head>
<?php include("Header.php");?>
</head>
<body>
	<?php include("Navigation.php");?>
	
	<script type="text/javascript">
		function imgwin(img){
		    window.open(img, "imgwindow", "width=900, height=540");
		}
	</script>
	
	<div class="container">
		<?php foreach ( $meishi_detail as $row ): ?>
			<div class="table-responsive">
				<table class="table">
					<tr>
						<td width="50%">
							<div align="center">
								<img src="<?= $row['img_name_f'] ?>" style="max-widtd:500px; max-height:300px" onclick="imgwin('<?= $row['img_name_f'] ?>')" class="img-responsive" alt="表側(No Image)">
							</div>
						</td>
						<td width="50%">
							<div align="center">
								<img src="<?= $row['img_name_b'] ?>" style="max-widtd:500px; max-height:300px" height="auto" onclick="imgwin('<?= $row['img_name_b'] ?>')" class="img-responsive" alt="裏側(No Image)">
							</div>
						</td>
					</tr>
				</table>
			</div>
			<table class="table">
				<tr bgcolor="gray">
					<th><font color="white">基本情報</font></th>
				</tr>
			</table>
			<table class="table table-bordered table-hover table-condensed"  style="background-color:#ffffff">
				<tr>
					<th width="20%">会社名</th>
					<td width="80%"><?= ($row['company_name']) ?></td>
				</tr>
				<tr>
					<th>部署</th>
					<td><?= ($row['position']) ?></td>
				<tr>
					<th>役職</th>
					<td><?= ($row['post']) ?></td>
				</tr>
				<tr>
					<th>氏名</th>
					<td><?= ($row['lastname']." ".$row['firstname']) ?></td>
				</tr>
				<tr>
					<th>氏名(カナ)</th>
					<td><?= ($row['kana_lastname']." ".$row['kana_firstname']) ?></td>
				</tr>
				<tr>
					<th>電話番号</th>
					<td><?= ($row['tel']) ?></td>
				</tr>
				<tr>
					<th>FAX</th>
					<td><?= ($row['fax']) ?></td>
				</tr>
				<tr>
					<th>携帯電話</th>
					<td><?= ($row['mobile']) ?></td>
				</tr>
				<tr>
					<th>E-mail</th>
					<td><a href="mailto:<?php echo $row['mail'] ?>?
									body=
									<?php if(!empty($row['company_name']))
				    				    {
					   	       		     echo ($row['company_name']),"%0d%0a"; 
					   	       		    }
					       			?>
									<?php if(!empty($row['position']))
					       			    {
					       			     echo ($row['position']),"%0d%0a";
					       			    }
					       			?>
									<?php if(!empty($row['post']))
					       			    {
						      		     echo ($row['post']),"%0d%0a";
						      		    }
						      		?>
									<?if(!empty($row['lastname']) && !empty($row['firstname']))
						      		    {
						      		    echo ($row['lastname'].' '.$row['firstname']),' ','様';
                                        }
						      		?>"><?= $row['mail'] ?></a>
					</td>
				</tr>
				<tr>
					<th>URL</th>
					<td><a href="<?= ($row['url']) ?>" target="_blank"><?= ($row['url']) ?></a></td>
				</tr>
				<tr>
					<th>郵便番号</th>
					<td>〒<?= ($row['postal']) ?></td>
				</tr>
				<tr>
					<th>都道府県</th>
					<td><?= ($row['address_a']) ?></td>
				</tr>
				<tr>
					<th>市区町村</th>
					<td><?= ($row['address_b']) ?></td>
				</tr>
				<tr>
					<th>町域・番地</th>
					<td><?= ($row['address_c']) ?></td>
				</tr>
				<tr>
					<th>建物名</th>
					<td><?= ($row['address_d']) ?></td>
				</tr>
				<tr>
					<th>名刺交換日</th>
					<td><?= $row['exchange_date'] ?></td>
				</tr>
				<tr>
					<th>所有者</th>
					<td><?= $row['user_last'] ?> <?= $row['user_first'] ?></td>
				</tr>
				<tr>
					<th>メモ</th>
					<td style="white-space:pre-wrap;"><?= $row['memo'] ?></td>
				</tr>
				<tr>
					<th>タグ</th>
					<td>
						<?php if(!empty($row['tag'])){ ?>
							<?php foreach($row['tag'] as $tag): ?>
								◆ <?= $tag ?><br>
							<?php endforeach; ?>
						<?php } ?>
					</td>
				</tr>
				<?php if(!empty($row['carrer'])){ ?>
				<tr>
					<th>経歴</th>
					<td>
						<div class="table-responsive">
							<table class="table table-bordered table-condensed">
								<tr>
									<td>会社名</td><td>部署</td><td>役職</td><td>名刺交換日</td>
								</tr>
								<?php foreach ( $row['carrer'] as $carrer ): ?>
									<tr>
									 <td><?= $carrer['company_name'] ?></td>
									 <td><?= $carrer['post'] ?></td>
									 <td><?= $carrer['position'] ?></td>
									 <td><?= $carrer['exchange_date'] ?></td>
									</tr>
								<?php endforeach; ?>
							</table>
						</div>
					</td>	
				</tr>
				<?php } ?>
			</table>
			<table>
			<tr>
			<td style="padding: 5px">
			<?= form_open('Edit/edit')?>
				<button type="submit" class="btn btn-primary" name="edit_code" value="<?= ($row['main_code'])?>">名刺情報の編集</button>
			<?= form_close() ?>
			</td>
			<td style="padding: 5px">
			<button type="button" class="btn btn-danger" data-toggle="modal" data-target="#hideModal">一覧から削除</button>
				<div class="modal fade" id="hideModal" tabindex="-1">
					<div class="modal-dialog">
						<div class="modal-content">
							<div class="modal-body">
								この名刺を一覧から削除しますか？
							</div>
							<div class="modal-footer">
								<?= form_open('Edit/hide')?>
									<button type="submit" class="btn btn-danger" name="hide_code" value="<?= ($row['main_code'])?>">削除</button>
								<?php form_close() ?>
									<button type="button" class="btn btn-warning" data-dismiss="modal">キャンセル</button>
							</div>
						</div>
					</div>
				</div>
			</td>
			</tr>
			</table>
		<?php endforeach; ?>
	</div>
</body>
</html>