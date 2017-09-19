<?php require 'app.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Optibus - Play and Win!</title>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="assets/css/select2.min.css" >
       <link rel="stylesheet" type="text/css" href="assets/css/casino.css" >
       <link rel="stylesheet" href="assets/css/nice-select.css" >
       <link rel="stylesheet" href="assets/css/jquery.modal.min.css" >
        <link rel="stylesheet" type="text/css" href="assets/css/kaltura-fonts.css" >
       <link rel="stylesheet" type="text/css" href="assets/css/style.css" >
       <link rel="stylesheet" type="text/css" href="assets/css/customer.css" >
    <link rel="shortcut icon" href="http://www.optibus.co/wp-content/uploads/2016/03/favicon.ico" type="image/x-icon"/>
    <!-- Analytics -->
    <script>
	(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
	(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
	m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
	})(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

	ga('create', 'UA-93850628-1', 'auto');
	ga('send', 'pageview');

	function changeHandle()
	{
	  	document.getElementsByClassName("handle")[0].style.background = "url(assets/images/handle_down.png)";
	  	document.getElementsByClassName("handle")[0].style.top = "190px";
	}

	</script>

</head>
<?php
	// TODO: Change win limit (100) here.
	// TMP: Making everyone lose (except for Qualified JS)
	$body_class = array( 'loser-playing' );
	// if ( lc_get_win_lose_count() >= 100 ) {
	// } else {
	// 	$body_class = array();

	// 	$win_count = (int) lc_get_data_value( 'win_count' );

	// 	if ( $win_count == 69 ) {
	// 		$body_class[] = 'preferred-player-playing';
	// 	}
	// }
?>
	<body class="<?php echo implode( ' ', $body_class ); ?>">
	<div class="fontrender" style="height: 0; overflow: hidden">
        <h1 class="render1">pgfdfg</h1>
        <p class="render2">dfgdfg</p>
    </div>
		<div class="app-parent" style="display: none">
			<div class="app-inner-parent">
				<div class="brands">
					<div class="left-logo">
						<img src="assets/images/customer_logo.png" alt="" />
					</div><!-- .left-logo -->
					</div><!-- .brands -->
				<div id="welcome-screen" class="screen">
					<div class="container">
						<div class="head-text">
							<h1>Play to Win!</h1>
							<button data-target="form-screen" class="cta-btn welcome-start relocate-btn load_sound">Start</button>
						</div><!-- .head-text -->
					</div><!-- .container -->
				</div><!-- #welcome-screen -->
				<div id="form-screen" class="screen" style="display: none">
					<div class="container">
						<div class="page-content">
							<form action="" class="who-are-you-form cta-form">
								<div class="form-inner-container group">
									<div class="form-left-side">
										<div class="name-field field-container group">
											<div class="first-name-field-container">
												<label for="first_name">
													* First Name
												</label>
												<input type="text" name="first_name" id="first_name" class="form-field first-name-field required-field no-numbers-field" placeholder="First Name" />
											</div><!-- .first-name-field -->
											<div class="last-name-field-container">
												<label for="last_name">
													* Last Name
												</label><!-- .last-name-field-container -->
												<input type="text" name="last_name" id="last_name" class="form-field last-name-field required-field no-numbers-field" placeholder="Last Name" />
											</div>
										</div><!-- .name-field field-container -->
										<div class="field-container">
											<label for="email">
												* Business Email
											</label>
											<input type="text" class="form-field email-field lc-email-field required-field" id="email" name="email" placeholder="your.email@here.com" />
										</div><!-- .email-field field-container -->
										<div class="field-container">
                                        	<label for="mobile">
                                        	Mobile Number (to contact you for your prize)
                                        	</label>
                                        	<input type="tel" class="form-field phone-field numbers-only-field" id="mobile" name="mobile" placeholder="" />
                                        </div><!-- .phone-field field-container -->
                                        <div class="field-container">
                                             <label for="company">
                                        	* Company Name
                                        	</label>
                                        	<input type="text" class="form-field company-field lc-company-field required-field" id="company" name="company" />
                                        </div><!-- .company-field field-container -->
									</div><!-- .form-left-side -->
									<div class="form-right-side">
                                        <div class="field-container">
											<label for="position">
												* Job Title
											</label>
											<input type="text" class="form-field position-field no-numbers-field lc-job-title required-field" id="position" name="position" />
										</div><!-- .company-field field-container -->
                                        <div class="field-container group">
											<label for="country">
												Country
											</label>
											<select name="country" id="country" class="country-field form-field field-select">
												<option value="">Select Country</option>
												<?php foreach(lc_countries() as $value) { ?>
													<option value="<?php echo htmlspecialchars( $value ); ?>" title="<?php echo htmlspecialchars($value) ?>"><?php echo htmlspecialchars($value) ?></option>
												<?php } ?>
											</select>
										</div><!-- .country-field field-container -->
											<div class="field-container state-field-container group" style="display: none;">
                                        		<label for="state">
                                        		State
                                        		</label>
                                        		<select name="state" id="state" class="state-field form-field field-select">
                                        		  <option value="">Select State</option>
                                        			<?php foreach( lc_states() as $value ) { ?>
                                        		   <option value="<?php echo htmlspecialchars( $value ); ?>" title="<?php echo htmlspecialchars($value) ?>"><?php echo htmlspecialchars($value) ?></option>
                                        			<?php } ?>
                                        		</select>
                                        	</div><!-- .country-field field-container -->
										<div class="field-container group">
											<label for="inhouse_apps">
												*  Does your company operate fixed route public transportation?
											</label>
											<select name="inhouse_apps" id="inhouse_apps" class="form-field inhouse-apps-field required-field field-select nice-select">
												<option value="">Select</option>
												<?php foreach ( lc_develop_in_house() as $answer ) {
													printf( '<option value="%1$s">%1$s</option>', htmlspecialchars( $answer ) );
												}
												?>
											</select>
										</div><!-- .field-container -->
									</div><!-- .form-right-side -->
								</div><!-- .form-inner-container group -->
								<input type="hidden" value="<?php echo lc_get_nonce(); ?>" name="nonce" />
								<input type="hidden" value="<?php echo lc_get_nonce(); ?>" name="sms_nonce" />
								<div class="tos-link-container">
									By clicking “Go”, you agree to the <a href="#tos-modal" class="tos-link modal-link">Terms and Conditions</a>.
								</div><!-- .tos-link-container -->
								<button data-target="game-screen" class="cta-btn form-btn" type="submit">GO!</button>
							</form><!-- .who-are-you-form -->
						</div><!-- .page-content -->
					</div><!-- .container -->
				</div><!-- #form-screen -->
				<div id="game-screen" class="screen" style="display: none;">
					<div class="container">
						<div class="page-content">
							<div id="game"></div><!-- #game -->
							<div class="spin-to-win-container">
								<button id="slot_play" class="cta-btn slot_play form-btn" onclick="changeHandle();">Touch to spin</button>
							</div><!-- .spin-to-win-container -->
							<div class="handle"></div>
						</div><!-- .page-content -->
					</div><!-- .container -->
				</div><!-- #game-screen -->
				<div id="lose-screen" class="screen" style="display: none;">
					<div class="container">
						<div class="page-content">
							<div class="lost-container group" style="width:400px;">
								<div class="lost-text">
									<h1 class="">AWWWW…</h1>
									<p class="user-full-name"></p>
									<p class="loser-msg"> - we’re sorry.</p>
									<p class="all-yours">Thank you for playing, better<br/> luck next time!</p>
								</div><!-- .lost-text -->
							</div><!-- .lost-container -->
						</div><!-- .page-content -->
					</div><!-- .container -->
				</div><!-- #lose-screen -->
				<div id="already_played" class="screen" style="display: none;">
					<div class="container">
						<div class="page-content">
							<div class="already-container">
							    <p class="user-full-name"></p>
								<h1>Sorry, you already tried you luck.</h1>
								<p class="see-you">Thanks for playing!</p>
							</div><!-- .already-container -->
						</div><!-- .page-content -->
					</div><!-- .container -->
				</div><!-- #lose-screen -->
				<div id="win-screen" class="screen" style="display: none;">
					<div class="container">
						<div class="page-content">
							<div class="won-container">
							    <p class="user-full-name"></p>
								<h1>Congratulations, you won!</h1>
								<p class="all-yours">Someone will reach-out shortly to hand over your prize.</p>
							</div><!-- .won-container -->
						</div><!-- .page-content -->
					</div><!-- .container -->
				</div><!-- #win-screen -->	
				<input type="hidden" id="winStatusNonce" value="<?php echo lc_get_nonce(); ?>" />
				<input type="hidden" id="hasToWLNonce" value="<?php echo lc_get_nonce(); ?>" />
				<div class="back-arrow-container">
					<a href="#" class="refreshApp"><img src="assets/images/arrow.png" alt="" /></a>
				</div><!-- .back-arrow-container -->
				<div class="buzzooka-powered-container">
					<img src="assets/images/buzzooka-powered.png" alt="" />
				</div><!-- .buzzooka-powered-container -->
			</div><!-- .app-inner-parent -->
		</div><!-- .app-parent -->
		<audio id='appSound'></audio>
		<div id="tos-modal" class="modal" style="display: none;">
			<h3>Terms &amp; Conditions</h3>
			<p>Last updated: February 28, 2017</p>
			<p>These Terms and Conditions (&quot;Terms&quot;, &quot;Terms and Conditions&quot;) govern your relationship with Buzzooka mobile application (the &quot;Service&quot;) operated by Buzzooka (&quot;us&quot;, &quot;we&quot;, or &quot;our&quot;).</p>
			<p>Please read these Terms and Conditions carefully before using our Buzzooka mobile application (the &quot;Service&quot;).</p>
			<p>Your access to and use of the Service is conditioned on your acceptance of and compliance with these Terms. These Terms apply to all visitors, users and others who access or use the Service.</p>
			<p>By accessing or using the Service you agree to be bound by these Terms. If you disagree with any part of the terms then you may not access the Service.</p>
			<p><strong>Intellectual Property</strong></p>
			<p>The Service and its original content, features and functionality are and will remain the exclusive property of Buzzooka and its licensors. The Service is protected by copyright, trademark, and other laws of both the Israel and foreign countries. Our trademarks and trade dress may not be used in connection with any product or service without the prior written consent of Buzzooka.</p>
			<p><strong>Links To Other Web Sites</strong></p>
			<p>Our Service may contain links to third-party web sites or services that are not owned or controlled by Buzzooka.</p>
			<p>Buzzooka has no control over, and assumes no responsibility for, the content, privacy policies, or practices of any third party web sites or services. You further acknowledge and agree that Buzzooka shall not be responsible or liable, directly or indirectly, for any damage or loss caused or alleged to be caused by or in connection with use of or reliance on any such content, goods or services available on or through any such web sites or services.</p>
			<p>We strongly advise you to read the terms and conditions and privacy policies of any third-party web sites or services that you visit.</p>
			<p><strong>Termination</strong></p>
			<p>We may terminate or suspend your access immediately, without prior notice or liability, for any reason whatsoever, including without limitation if you breach the Terms.</p>
			<p>Upon termination, your right to use the Service will immediately cease.</p>
			<p><strong>Limitation Of Liability</strong></p>
			<p>In no event shall Buzzooka, nor its directors, employees, partners, agents, suppliers, or affiliates, be liable for any indirect, incidental, special, consequential or punitive damages, including without limitation, loss of profits, data, use, goodwill, or other intangible losses, resulting from (i) your access to or use of or inability to access or use the Service; (ii) any conduct or content of any third party on the Service; (iii) any content obtained from the Service; and (iv) unauthorized access, use or alteration of your transmissions or content, whether based on warranty, contract, tort (including negligence) or any other legal theory, whether or not we have been informed of the possibility of such damage, and even if a remedy set forth herein is found to have failed of its essential purpose.</p>
			<p><strong>Disclaimer</strong></p>
			<p>Your use of the Service is at your sole risk. The Service is provided on an &quot;AS IS&quot; and &quot;AS AVAILABLE&quot; basis. The Service is provided without warranties of any kind, whether express or implied, including, but not limited to, implied warranties of merchantability, fitness for a particular purpose, non-infringement or course of performance.</p>
			<p>Buzzooka its subsidiaries, affiliates, and its licensors do not warrant that a) the Service will function uninterrupted, secure or available at any particular time or location; b) any errors or defects will be corrected; c) the Service is free of viruses or other harmful components; or d) the results of using the Service will meet your requirements.</p>
			<p><strong>Governing Law</strong></p>
			<p>These Terms shall be governed and construed in accordance with the laws of Israel, without regard to its conflict of law provisions.</p>
			<p>Our failure to enforce any right or provision of these Terms will not be considered a waiver of those rights. If any provision of these Terms is held to be invalid or unenforceable by a court, the remaining provisions of these Terms will remain in effect. These Terms constitute the entire agreement between us regarding our Service, and supersede and replace any prior agreements we might have between us regarding the Service.</p>
			<p><strong>Changes</strong></p>
			<p>We reserve the right, at our sole discretion, to modify or replace these Terms at any time. If a revision is material we will try to provide at least 30 days notice prior to any new terms taking effect. What constitutes a material change will be determined at our sole discretion.</p>
			<p>By continuing to access or use our Service after those revisions become effective, you agree to be bound by the revised terms. If you do not agree to the new terms, please stop using the Service.</p>
			<p><strong>Contact Us</strong></p>
			<p>If you have any questions about these Terms, please contact us.</p>
		</div><!-- #tos-modal.modal -->
		  <script type='text/javascript' src='assets/js/jquery.min.js'></script>
                 <script type='text/javascript' src='assets/js/pace.js'></script>
                 <script type="text/javascript" src="assets/js/bowser.js"></script>
                  <script type='text/javascript' src='assets/js/whiteLists.js'></script>
                   <script type='text/javascript' src='assets/js/blackLists.js'></script>
                 <script type="text/javascript" src="assets/js/util.js"></script>
                 <script type="text/javascript" src="assets/js/casino.js"></script>
                 <script type='text/javascript' src='assets/js/jquery.nice-select.min.js'></script>
                 <script type='text/javascript' src='assets/js/jquery.modal.min.js'></script>
                 <script type='text/javascript' src='assets/js/select2.min.js'></script>
                 <script type='text/javascript' src='assets/js/scripts.js'></script>
	</body>
</html>
