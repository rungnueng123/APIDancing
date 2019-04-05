<?php
include("db.php");

$email_encrypt = $_GET['email'];
//$email = openssl_decrypt($_GET['email'],"AES-128-ECB",$secret_key);
//echo $email.'/'.$email_encrypt;

?>
<!DOCTYPE html>
<html>

<head>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>

<body class="page-header-fixed page-sidebar-closed-hide-logo page-sidebar-fixed page-sidebar-closed-hide-logo">

<div id="main-wrapper">

	<div id="highlighted" class="hl-basic hidden-xs">
		<div class="container-fluid">
			<div class="col-md-12" style="padding: 24px 0">
				<div class="row">
					<div class="col-md-3">
					</div>
					<div class="col-md-6">
						<div class="row">
							<div class="col-md-2">
								<img src="image/logo.png" width="100" height="100">
							</div>
							<div class="col-md-10">
								<h1>Forgot Password</h1>
							</div>

						</div>
					</div>
					<div class="col-md-3">
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="col-md-12">
		<div class="col-md-3">
		</div>
		<div class="col-md-6">
			<br>
			<div class="alert alert-success showSuccess" style="display: none">
				<p>Reset Success!!</p>
			</div>
			<div class="alert alert-danger showFail" style="display: none">
				<p>Something wrong happened! Please try again!!</p>
			</div>
		</div>
		<div class="col-md-3">
		</div>
	</div>
	<div id="content" class="interior-page">
		<div class="container-fluid">
			<div class="row">
				<!--Content-->
				<div class="col-md-12">
					<div class="col-md-3"></div>
					<div class="col-md-6 content-area-right">
						<p>Please enter your new password.</p>

						<label class="label-default" id="txtPass">New Password</label>
						<input id="pass" class="form-control" type="password"><br>

						<label class="label-default" id="txtCpass">Confirm New Password</label>
						<input id="cpass" class="form-control" type="password"><br>

						<input type="button" class="btn btn-primary btnSave" id="btnSubmit" name="btnSubmit"
						       value="Save">
					</div>
					<div class="col-md-3"></div>
				</div>
			</div>
		</div>
	</div>
</div>
</body>

</html>

<script>
    $(document).ready(function () {

        $('.btnSave').click(function (e) {
            document.getElementById("txtPass").style.color = "";
            document.getElementById("txtCpass").style.color = "";
            if ($('#pass').val().trim() == "" || $('#cpass').val().trim() == "") {
                alert('กรอกข้อมูลให้ครบถ้วน');
                $('#pass').focus();
                document.getElementById("txtPass").style.color = "red";
                document.getElementById("txtCpass").style.color = "red";
                return false;
            }
            if ($('#pass').val().trim() != $('#cpass').val().trim()) {
                alert('พาสเวิร์ดไม่ถูกต้อง');
                $('#pass').focus();
                $('#pass').val('');
                $('#cpass').val('');
                document.getElementById("txtPass").style.color = "red";
                document.getElementById("txtCpass").style.color = "red";
                return false;
            } else {

                var email = '<?php echo $_GET['email']?>';
                var pass = $('#pass').val().trim();

                if (confirm("Are you sure you want to confirm this?")) {
                    $.ajax({
                        url: "update_pass.php",
                        method: "POST",
                        data: {
                            email: email,
                            pass: pass,
                        },
                        success: function (data) {
                            alert(data);
                            if (data === 'Reset Success') {
                                $('.showSuccess').show();
                                $('#content').hide();
                                return false;
                            } else if (data === 'Something wrong happened! Please try again') {
                                $('.showFail').show();
                                return false;
                            } else {
                                alert(data);
                            }
                        }
                    });
                } else {
                    return false;
                }

            }
        });

    });
</script>

<style>
	#highlighted {
		position: relative;
		background-color: #6C7B8A;
	}

	@media (min-width: 992px)
		#highlighted .container-fluid {
			margin-bottom: 2.5rem;
		}

		#highlighted .container-fluid h1, #highlighted .container-fluid p {
			color: #FFF;
		}

		.h1, h1 {
			font-size: 54.93px;
		}

		.h1, h1, h2, h3, h4, h5, h6 {
			font-family: Verlag, museo-sans, 'Helvetica Neue', Helvetica, Arial, sans-serif;
			color: #414141;
		}

		.h1, body, h1, h2, h3, h4, h5, h6, html {
			font-weight: 300;
		}

		#content {
			background-position: right bottom;
			background-repeat: no-repeat;
		}

		.interior-page {
			background-color: #FFF;
			padding-bottom: 30px;
		}

		#highlighted + #content.interior-page .interior-page-nav {
			margin-top: -4em;
		}

		#highlighted + #content.interior-page .interior-page-nav, .interior-page .interior-page-nav {
			padding-left: 0;
		}

		.sidebar {
			margin-top: 2em;
		}

		@media (min-width: 1200px)
			.col-lg-2 {
				width: 16.66666667%;
			}

			.content-area-right {
				max-width: 1200px;
				padding-right: 15px;
				padding-left: 15px;
			}

			.container-fluid > .row h2.crumb-title {
				margin-bottom: 0;
			}

			.page-title {
				min-height: 50px;
			}

			.page-title, ul {
				margin: 0;
				list-style: none;
			}

			.content-crumb-div {
				margin: 5px 0 20px;
			}

			a {
				text-decoration: none;
			}

			.container-fluid .row .modal, .page .modal {
				position: fixed;
				top: 35%;
			}

			#highlighted + #content.interior-page .interior-page-nav, .interior-page .interior-page-nav {
				padding-left: 0;
			}

			#highlighted + #content.interior-page .interior-page-nav {
				margin-top: -4em;
			}

			.dynamicDiv.panel-group {
				border: 1px solid #E7E9E9;
				margin-left: 30px;
			}

			.panel-group {
				margin-bottom: 0;
				background-color: #fff;
			}

			.panel-group .panel {
				-webkit-border-radius: 0;
				-moz-border-radius: 0;
				border-radius: 0;
				border: none;
				box-shadow: none;
			}

			.panel-group .panel-heading {
				padding: 0;
				border: none;
			}

			.panel-default > .panel-heading {
				color: #333;
				background-color: #f5f5f5;
				border-color: #ddd;
			}

			.panel-group .panel-heading .panel-title {
				font-size: 1.1em;
				font-family: Verlag, museo-sans, 'Helvetica Neue', Helvetica, Arial, sans-serif;
			}

			.interior-page-nav .panel-group .panel-heading .panel-title a {
				background: 0 0;
			}

			span.subMenuHighlight, ul.panel-heading li.panel-title a:hover {
				color: #ED3C95;
			}

			.panel-group .panel-heading .panel-title {
				font-size: 1.1em;
				font-family: Verlag, museo-sans, 'Helvetica Neue', Helvetica, Arial, sans-serif;
			}

			ul.panel-heading {
				margin-bottom: 1px;
			}

			.panel-group {
				margin-bottom: 0;
				background-color: #fff;
			}

			.label-default {
				background-color: #FFF;
				margin-top: 10px;
			}

			label {
				display: inline-block;
				margin-bottom: 5px;
				font-weight: 700;
			}

			.form-control {
				border-radius: 0;
			}

			.btn-primary {
				color: #fff;
				background-color: #6C7B8A;
				border-color: #6C7B8A;
				width: 100%;
			}

			.btn-block {
				display: block;
			}

			.btn {
				padding: 8px 28px;
				font-weight: 400;
				-webkit-transition: background .3s ease-in;
				transition: background .3s ease-in;
				white-space: normal;
				border-width: 0 0 1px;
			}

			.content-area-right {
				margin-top: 10px;
			}
</style>
