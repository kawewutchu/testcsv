
<!-- Navigation -->
 <script type="text/javascript">
    	$(function(){
    　		$("#datepicker1").datepicker();
    		$("#datepicker2").datepicker();
    	});
</script>
<script type="text/javascript">
    	function required_check_nav(){
        	var flag = 0;
            if($("#datepicker1").val() != ""){
            	if(!($("#datepicker1").val().match(/^[0-9]{4}\/[0-9]{1,2}\/[0-9]{1,2}$/))){
            		flag++;
            	}else{
            		var parts = $("#datepicker1").val().split("/");
            		var nYear = Number(parts[0]);
            		var nMonth = Number(parts[1]) - 1;
            		var nDay = Number(parts[2]);
            		if(!((0 <= nMonth)&&(nMonth <= 11)&&(0 <= nDay)&&(nDay <= 31))){
            			flag++;
                	}else{
                		var dt = new Date(nYear, nMonth, nDay);            		
                		if(!((dt.getFullYear() == nYear)&&(dt.getMonth() == nMonth)&&(dt.getDate() == nDay))){
                			flag++;
                    	}
                	}
            	}
        	}
            if($("#datepicker2").val() != ""){
            	if(!($("#datepicker2").val().match(/^[0-9]{4}\/[0-9]{1,2}\/[0-9]{1,2}$/))){
            		flag++;
            	}else{
            		var parts = $("#datepicker2").val().split("/");
            		var nYear = Number(parts[0]);
            		var nMonth = Number(parts[1]) - 1;
            		var nDay = Number(parts[2]);
            		if(!((0 <= nMonth)&&(nMonth <= 11)&&(0 <= nDay)&&(nDay <= 31))){
            			flag++;
                	}else{
                		var dt = new Date(nYear, nMonth, nDay);            		
                		if(!((dt.getFullYear() == nYear)&&(dt.getMonth() == nMonth)&&(dt.getDate() == nDay))){
                			flag++;
                    	}
                	}
            	}
            }
            if(($("#datepicker1").val() != "")&&($("#datepicker2").val() != "")){
            	if(!($("#datepicker1").val() <= $("#datepicker2").val())){
                	flag++;
            	}
            }

        	if(flag == 0){	
	        	return true;
            }else{
            	$("#datepicker1").datepicker('setDate',null)
            	$("#datepicker2").datepicker('setDate',null)
            	window.alert("名刺交換日の入力が不正です");
            	return false;
        	}
        }
</script>
<?php $this->load->library('session'); ?>
<nav class="navbar navbar-default topnav navbar-fixed-top" role="navigation">
	<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
		<span class="sr-only">Toggle navigation</span>
		<span class="icon-bar"></span> 
		<span class="icon-bar"></span> 
		<span class="icon-bar"></span>
	</button>
	<div class="container topnav">	
		<!-- Brand and toggle get grouped for better mobile display -->
		 
		<div class="navbar-header">			
			<a class="navbar-brand topnav" href="<?= base_url('Menu/reload_menu')?>">				
				<font color="white">ようこそ <?= $this->session->userdata['user_last'] ?> <?= $this->session->userdata['user_first'] ?> さん</font>
			</a>
		</div> 
		<!-- Collect the nav links, forms, and other content for toggling -->
		<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
    		<ul class="nav navbar-nav navbar-right">
				<li>
        			<a href="<?= base_url('MobileUpload/mobileuploadpage')?>">
        				Mobile Photo
        			</a>
				</li>
				<li>
        			<a href="<?= base_url('Tag/get_tag')?>">
        				タグ編集
        			</a>
				</li>
				<li>
					<a href="<?= base_url('Input/no_entered')?>">
						未入力名刺
					</a>
				</li>
				<li>
					<a href="<?= base_url('Nayose/get_name')?>">
						名寄せ機能
					</a>
				</li>
				<li>
    				<a href="<?= base_url('Login/logout')?>">
    					ログアウト
    				</a>
    			</li>
			</ul>
        	<?php $attributes = array('class' => 'navbar-form form-inline', 'role' => 'search', 'name' => 'search_form', 'onSubmit' => 'return required_check_nav()'); ?>
        	<?= form_open('Menu/search', $attributes)?>
        	<div class="form-group">
        		<ul class="nav navbar-nav navbar-right">
					<li>
            			<select class="selectpicker" name="search_user" id="UserSelect">
            				<option style="background-color:#EBEBEB" value="<?= @$this->session->userdata['search_user']; ?>"><?= @$this->session->userdata['search_user_name'] ?></option>
                            <option style="background-color:#ffffff" value="">所有者指定なし</option>
                            <?php foreach ( @$this->session->userdata['user_list'] as $row ): ?>
                                <option style="background-color:#ffffff" value="<?= $row['user_id'] ?>"><?= $row['user_last']?> <?= $row['user_first'] ?></option>
                            <?php endforeach; ?>
            			</select>
            		</li>
            		<li>
            			<select class="selectpicker" style="width:200px" name="search_tag" id="TagSelect">
            			<option style="background-color:#EBEBEB" value="<?= @$this->session->userdata['search_tag']; ?>"><?= @$this->session->userdata['search_tag_name'] ?></option>
            			<option style="background-color:#ffffff" value="">タグ指定なし</option>
            			<?php foreach ( @$this->session->userdata['tag_list'] as $parent ): ?>
                            <?php if($parent['parent'] == NULL){ ?>
        	                    <?php if($parent['attribute'] == '1'){ ?>
            	                	<option class="bg-info" value="<?= $parent['tag_code'] ?>"><?= $parent['tag'].' （共有）' ?></option>
    	                        <?php }else{ ?>
            	                	<option class="bg-success" value="<?= $parent['tag_code'] ?>"><?= $parent['tag'].' （'.$parent['user_last'].' '.$parent['user_first'].'）' ?></option>
    	                        <?php } ?>
                            <?php } ?>
                            <?php foreach (@$this->session->userdata['tag_list'] as $offspring ): ?>
                                <?php if($offspring['parent'] == $parent['tag_code']){ ?>
                                    <option style="background-color:#ffffff" value="<?= $offspring['tag_code'] ?>"> └ <?= $offspring['tag'] ?></option>
                                <?php } ?>
                            <?php endforeach; ?>
                        <?php endforeach; ?>
            			</select>
            		</li>
            		<li>
                		<input type="text" style="width:220px" class="form-control" placeholder="会社名・氏名" name="search_word" value="<?= @$this->session->userdata['search_word'] ?>"> 
	        		</li>
	        		<li>
    	        		<div class="inline-form">
    	        			<font color="white">名刺交換日</font>
                            <input type="text" style="width:110px" class="form-control" id="datepicker1" name="search_date_from" placeholder="yyyy/mm/dd" value="<?= @$this->session->userdata['search_date_from'] ?>">
                            <font color="white"> 〜 </font>
                            <input type="text" style="width:110px" class="form-control" id="datepicker2" name="search_date_to" placeholder="yyyy/mm/dd" value="<?= @$this->session->userdata['search_date_to'] ?>">
                        	<button type="submit" class="btn btn-warning" name="search_btn" value="search"><i class='glyphicon glyphicon-search'></i>検索</button>
                        	<!-- 後で管理者のみ実行可能に変更
                    		<button type="submit" class="btn btn-success" name="search_btn" value="output"><span class="glyphicon glyphicon-save" aria-hidden="true">CSV出力</span></button>
                    		 -->
                    	</div>
                	</li>
            	</ul>
            </div>
            <?= form_close() ?>
        </div>
		<!-- /.navbar-collapse -->
	</div>
	<!-- /.container -->
</nav>
