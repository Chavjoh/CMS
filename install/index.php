<!DOCTYPE html>
<html>
	<head>
        <title>Chavjoh CMS Installation</title>

		<link href="bootstrap/css/bootstrap.min.css" rel="stylesheet" />
		<link href="bootstrap-wizard/bootstrap-wizard.css" rel="stylesheet" />
		<link href="chosen/chosen.css" rel="stylesheet" />

		<style type="text/css">
			.wizard-modal p {
				margin: 0 0 10px;
				padding: 0;
			}
		</style>
	</head>

	<body style="padding: 30px;">

		<button id="open-wizard" class="btn btn-primary">Open wizard</button>

		<div class="wizard" id="wizard">
			<h1>Chavjoh CMS Installation</h1>

            <div class="wizard-card" data-onValidated="checkRequirement" data-cardname="welcome">
                <h3>Welcome</h3>
                <p>
                    Welcome to the installation interface. <br />
					Follow the instructions to properly install the CMS.
                </p>
                <br />
                <h3>Requirements</h3>
                <p>
                    <ul>
                        <li>PHP 5.4+</li>
                        <li>MySQL (InnoDB and version 5+ required)</li>
                    </ul>
					<button id="checkRequirement" class="btn">Check requirements to continue</button>
					<div id="stateRequirement">&nbsp;</div>
                </p>
            </div>

            <div class="wizard-card" data-onValidated="" data-cardname="permission">
                <h3>Permissions</h3>
                <p>
                    The following folders and files need to be set at CHMOD 777 :
					<ul>
						<li>includes/caches</li>
						<li>includes/compiles</li>
						<li>includes/modules (recursive)</li>
						<li>includes/skins (recursive)</li>
						<li>includes/wrappers (recursive)</li>
						<li>includes/configuration.xml</li>
					</ul>
					<button id="checkPermission" class="btn">Check permissions to continue</button>
					<div id="statePermission">&nbsp;</div>
                </p>
            </div>

			<div class="wizard-card" data-onValidated="checkInformation" data-cardname="information">
				<h3>Website information</h3>

				<div class="wizard-input-section">
					<p>
						To begin, please enter the website name :
					</p>

					<div class="control-group">
						<input id="information-name" type="text" placeholder="Website name" data-validate="notEmpty" class="span6" />
					</div>
				</div>

				<div class="wizard-input-section">
					<p>
						Optionally, give a short description and the keywords of the website :
					</p>

					<div class="control-group">
						<input id="information-description" type="text" placeholder="Website description" data-validate="" class="span6" /> <br />
						<input id="information-keywords" type="text" placeholder="Website keywords" data-validate="" class="span6" />
					</div>
				</div>
			</div>

            <div class="wizard-card" data-onValidated="checkDatabase" data-cardname="database">
                <h3>Database connexion</h3>

                <div class="wizard-input-section">
                    <p>
                        Please fill information about the database connexion :

                        <div class="control-group">
                            <input id="database-name" type="text" placeholder="Database name" data-validate="" class="span3" />
							<input id="database-prefix" type="text" placeholder="Table prefix" data-validate="" class="span3" />
                        </div>
                        <div class="control-group">
                            <input id="database-user" type="text" placeholder="User" data-validate="" class="span3" />
							<input id="database-password" type="password" placeholder="Password" data-validate="" class="span3" />
                        </div>
                        <div class="control-group">
                            <input id="database-host" type="text" placeholder="Host" data-validate="" class="span5" />
							<input id="database-port" type="text" placeholder="Port" data-validate="" class="span1" />
                        </div>
						<button id="checkDatabase" class="btn">Check connexion to continue</button>
						<div id="stateDatabase">&nbsp;</div>
                    </p>
                </div>
            </div>

            <div class="wizard-card" data-onValidated="setInformation" data-cardname="administration">
                <h3>Website administration</h3>

                <div class="wizard-input-section">
                    <p>
                        Please indicate the URL key to access to the administration interface.
						The default value is "admin".
						If you use it, you can access to the administration by the following URL :
						<?php
						include('../includes/classes/Server.php');
						echo dirname(Server::getBaseUrl()).'/admin';
						?>
                    </p>

                    <div class="control-group">
                        <input id="administration-key" type="text" placeholder="Administration key" data-validate="validateAdminKey" value="admin" />
                    </div>
                </div>

				<div class="wizard-input-section">
					<p>
						Enter an username and a password to access to administration interface :
					</p>

					<div class="control-group">
						<input id="administration-user" type="text" placeholder="Administration user" data-validate="notEmpty" value="" />
						<input id="administration-password" type="password" placeholder="Administration password" data-validate="notEmpty" value="" />
					</div>
				</div>
            </div>

			<div class="wizard-error">
				<div class="alert alert-error">
					<strong>There was a problem</strong> with your submission. <br />
					Please correct the errors and re-submit.
				</div>
			</div>

			<div class="wizard-failure">
				<div class="alert alert-error">
					<strong>There was a problem</strong> submitting the form. <br />
					Please try again in a minute.
				</div>
			</div>

			<div class="wizard-success">
				<div class="alert alert-success">
					Website was created and configured <strong>successfully.</strong>
				</div>

				<a class="btn im-done" href="../">Go to FrontEnd</a>
				<span style="padding: 0 10px">or</span>
				<a class="btn im-done" href="../admin/" id="adminLink">Go to BackEnd</a>
			</div>

		</div>

		<script src="./scripts/jquery.min.js"></script>
		<script src="./chosen/chosen.jquery.js"></script>
		<script src="./bootstrap/js/bootstrap.min.js"></script>
		<script src="./bootstrap-wizard/bootstrap-wizard.min.js"></script>
		<script src="./scripts/installation.js"></script>

	</body>
</html>
