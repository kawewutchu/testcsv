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
	<?php if($meishi_list == NULL){ ?>
		<div class="page-header">
			未入力の名刺はありません。
		</div>
	<?php }else{ ?>
			<table class="table table-bordered table-hover table-condensed" style="width:100%; background-color:#ffffff; table-layout:auto; ">
				<thead>
					<tr>
						<td colspan="5" align="center">
							<div>
							<?= form_open('Input/replace')?>
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
						<th width="10%"></th>
						<th width="25%"><div align="center">名刺（表）</div></th>
						<th width="25%"><div align="center">名刺（裏）</div></th>
						<th width="20%"><div align="center">タグ</div></th>
						<th width="20%"><div align="center">所有者・名刺交換日</div></th>
					</tr>
				</thead>
				<tbody>
                <?php foreach ( $meishi_list as $row ): ?>
                    <tr>
                    	<th>
                    		<div align="center">
                    			<?= form_open('Input/input')?>
	                    			<button type="submit" class="btn btn-primary" name="input_code" value="<?= $row['main_code'] ?>">情報入力</button>
	                    		<?= form_close() ?>
	                    		<br>
	                    		<?= form_open('Input/delete')?>
	                    			<button type="submit" class="btn btn-secondary" name="input_code" value="<?= $row['main_code'] ?>">削除</button>
	                    		<?= form_close() ?>
	                    	</div>
                    	</th>
                    	<td>
                    		<div align="center">
								<img src="<?= $row['img_name_f'] ?>" style="max-width:250px; max-height:150px" alt="No Image">
							</div>
						</td>
							<td>
								<div align="center">
									<img src="<?= $row['img_name_b'] ?>" style="max-width:250px; max-height:150px" alt="No Image">
								</div>
							</td>
    						<td style="word-wrap:break-word;">
								<font size=2><?= ($row['tag']) ?></font>
                			</td>
							<td>
    							所有者：<?= $row['user_last']?> <?= $row['user_first'] ?>
    							<br>
    							名刺交換日：<?= ($row['exchange_date']) ?>
    						</td>
                    	</tr>
                    <?php endforeach; ?>
                    </tbody>
                    <!--
                    <tfoot>
                    	<tr>
                    		<th></th>
							<th><div align="center">名刺（表）</div></th>
							<th><div align="center">名刺（裏）</div></th>
							<th><div align="center">タグ</div></th>
							<th><div align="center">所有者・名刺交換日</div></th>
						</tr>
						<tr>
							<td colspan="5" align="center">
							<div>
							<?= form_open('Input/replace')?>
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
			<?php } ?>
	</div>
</body>
</html>
