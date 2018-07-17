<!DOCTYPE html>
<html>
<head>
<?php include("Header.php");?>
<script src="/MeishiKanri/assets/js/jquery.js"></script>
<script src="/MeishiKanri/assets/js/jquery-ui.js"></script>

<link href="<?= base_url('assets/css/landing-page.css')?>" rel="stylesheet">

<script type="text/javascript">
function required_check(){
	var str="";

	if((document.login_form.LoginID.value.match(/^[¥¥']+$/))){
		str = str + "不正なIDです。";
	}else if((document.login_form.LoginID.value.match(/^[¥¥"]+$/))){
		str = str + "不正なIDです。";
	}
	if((document.login_form.LoginPassword.value.match(/^[¥¥']+$/))){
		str = str + "不正なPasswordです。";
	}else if((document.login_form.LoginPassword.value.match(/^[¥¥"]+$/))){
		str = str + "不正なPasswordです。";
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
<body style="padding-top:0px">
	<?php if(isset($logged_in)){ ?>
		<script type="text/javascript">
    		(function () {
    			window.alert("ログインに失敗しました");
    		}());
    	</script>
	<?php } ?>
	<div class="intro-header">
		<div class="container">
			<div class="row">
				<div class="col-lg-12">
					<div class="intro-message">
						<h1>名刺管理システム</h1>
						<h3>ログイン</h3>
						<hr class="intro-divider">
						<?php $attributes = array('name' => 'login_form', 'onSubmit' => 'return required_check()'); ?>
						<?= form_open('Login/auth', $attributes)?>
                        <ul class="list-inline intro-social-buttons">
							<li>
								<input type="text" class="form-control" id="LoginID" name="user_id" placeholder="ID"><br>
								<input type="password" class="form-control" id="LoginPassword" name="password" placeholder="Password"></li>
							<li>
								<button type="submit" class="btn btn-primary" id="submit-btn-test">送信</button>
							</li>
						</ul>
						<?= form_close() ?>                        
                    </div>
				</div>
			</div>
		</div>
	</div>
</body>
</html>