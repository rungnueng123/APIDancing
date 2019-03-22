<?php

include("welcome.php");
echo '<html>
	<head>
	</head>
	<body>
	<div id="main-wrapper">
		<div class="page-container">
			<div class="portlet-body">
					<div class="portlet light">
						<div class="portlet-body ">
							<div class="form-body">
								<div class="row" style="padding-top: 5px;padding-bottom: 5px">
									<div class="col-md-12 borderBottom">
										<div class="col-md-6">
											<label>ACCOUNT</label>
										</div>
										<div class="col-md-6">
											<label>' . $username . '</label>
										</div>
									</div>
								</div>
								<div class="row" style="padding-top: 5px;padding-bottom: 5px">
									<div class="col-md-12 borderBottom">
										<div class="col-md-6">
											<label>PRODUCT</label>
										</div>
										<div class="col-md-6">
											<label>' . $namepack . '</label>
										</div>
									</div>
								</div>
								<div class="row" style="padding-top: 5px;padding-bottom: 5px">
									<div class="col-md-12 borderBottom">
										<div class="col-md-6">
											<label>COIN</label>
										</div>
										<div class="col-md-6">
											<label>' . $coin . '</label>
										</div>
									</div>
								</div>
								<div class="row" style="padding-top: 5px;padding-bottom: 5px">
									<div class="col-md-12 borderBottom">
										<div class="col-md-6">
											<label>AMOUNT</label>
										</div>
										<div class="col-md-6">
											<label>' . $realBaht . '</label>
										</div>
									</div>
								</div>
							
							
								
								<div class="form-group">
									<form name="checkoutForm" method="POST" action="checkout_omise.php">
										  <script type="text/javascript" src="https://cdn.omise.co/omise.js"
										  data-key="pkey_test_5ex0mvopz0piutpxdzw"
										  data-image="https://danceschool.matchbox-station.com/imgBanner/bg.png"
										  data-frame-label="BeatsBox"
										  data-button-label="Pay with Omise"
										  data-submit-label="Checkout"
										  data-location="no"
										  data-amount="' . $baht . '"
										  data-currency="thb"
										  >
										  </script>
										  <input type="hidden" name="bath" value="' . $baht . '"/>
										  <input type="hidden" name="public_key_omise" value="' . $public_key_omise . '"/>
										  <input type="hidden" name="secret_key_omise" value="' . $secret_key_omise . '"/>
										  <input type="hidden" name="api_version_omise" value="' . $api_version_omise . '"/>
										  <input type="hidden" name="userID" value="' . $userID . '"/>
										  <input type="hidden" name="coinPackID" value="' . $coinPackID . '"/>
										  <input type="hidden" name="namepack" value="' . $namepack . '"/>
										  <input type="hidden" name="coin" value="' . $coin . '"/>
										  <input type="hidden" name="realBaht" value="' . $realBaht . '"/>
									</form>
								</div>
							</div>
						</div>
					</div>
			</div>
		</div>
	</div>
	<style>
	@font-face{
		font-family: kanitRegular;
		src: url("font/kanit_regular.ttf");
	}
	.borderBottom{
		border-bottom: 1px solid #6C7B8A;
		font-family: kanitRegular;
	}
	</style>
	</body>
	</html>';