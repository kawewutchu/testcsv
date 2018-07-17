<!DOCTYPE html>
<html>
<head>
    <?php include("Header.php");?>
</head>
<body>
	<?php include("Navigation.php");?>
    <div class="container">
		<div class="panel panel-default">
			<div class="panel-heading">
				<h3>upload business card image</h3>
				upload front and back business card card
			</div>
            <div class="panel-body">
                <form method="post" id="upload_form" enctype="multipart/form-data">  
                    <input type="file" name="files" id="files" multiple/> 
                    <br> 
                    <input type="submit" name="upload" id="upload" value="Upload" class="btn btn-info" />  
                </form>  
            </div>
             <!-- Modal -->
            <div class="modal fade" id="myModal" role="dialog">
                <div class="modal-dialog">

                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Upload Image</h4>
                    </div>
                    <div class="modal-body">
                    <p>Do you want upload a image again?</p>
                    </div>
                    <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-dismiss="modal" onclick="model_yes()">Yes</button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal" onclick="model_no()">No</button>
                    </div>
                </div>
                
                </div>
            </div>
        </div>
    </div>
</body>
</html>

<script>
   1
</script>