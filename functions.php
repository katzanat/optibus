<?php

// // Require the phpMailer
require_once 'inc/phpMailer/mailer.php';
// Require the Twilio bundled autoload file
require_once 'inc/twilio-php-master/Twilio/autoload.php';
// Use the REST API Client to make requests to the Twilio REST API
use Twilio\Rest\Client;

/**
 * Creates a random 32 bytes and store it to session
 * @return string
 */
function lc_get_nonce() {

	try {
		$nonce = bin2hex(random_bytes(16));
	} catch (TypeError $e) {
	    // Well, it's an integer, so this IS unexpected.
	    lc_log( $e->getMessage() );
	} catch (Error $e) {
	    // This is also unexpected because 32 is a reasonable integer.
	    lc_log( $e->getMessage() );
	} catch (Exception $e) {
	    // If you get this message, the CSPRNG failed hard.
	    lc_log( $e->getMessage() );
	}

	$_SESSION['nonces'][] = $nonce;

	return $nonce;
}

/**
 * Validates nonce string from session
 * @param  string $nonce
 * @return
 */
function lc_validate_nonce( $nonce = '' ) {

	if ( empty( $nonce ) || empty( $_SESSION['nonces'] ) ) return false;

	if ( ( $key = array_search( $nonce, $_SESSION['nonces'] ) ) !== false ) {
	    unset( $_SESSION['nonces'][$key] );
		return true;
	}

	return false;
}

/**	
 * Writes the log and returns the exception
 *
 * @param  string $message
 * @param  string $sql
 * @return string
 */
function lc_log( $message ) {
    $exception = 'Unhandled Exception. <br />';
    $exception .= $message;
    $exception .= "<br /> You can find the error back in the log.";

    # Write into log
    $log = new Log();
    $log->write( $message );
    
    return $exception;
}

function lc_set_lead( $email = '', $company = '', $ott_sc, $job_sc, $company_sc, $email_sc) {
	if ( empty( $email ) ) return;

	$company = strtolower( $company );

	$db = new Db();
	return $db->query("INSERT INTO leads(email, win_lose, company, score_email, score_company, score_job_title, score_ott ) VALUES(:email, :win_lose, :company, :score_email, :score_company, :score_job_title, :score_ott)", array("email"=>$email, 'win_lose'=>'', 'company'=>$company, 'score_email'=>$email_sc, 'score_company'=>$company_sc, 'score_job_title'=>$job_sc, 'score_ott'=>$ott_sc));
}

function lc_get_lead( $email = '' ) {
	if ( empty( $email ) ) return;

	$db = new Db();
	return $db->row( "SELECT * FROM leads WHERE email = :email", array( "email" => $email ) );
}

function lc_get_winners_losers( $status ) {

	$db    = new Db();
	$leads = $db->query( "SELECT email FROM leads WHERE win_lose = :status", array( "status" => $status ) );

	if ( ! empty( $leads ) ) {
		return $leads;
	} else {
		return false;
	}
}

function lc_get_config_val($type = '') {
  $db = new Db();
  return $config = $db->row("SELECT * FROM configuration WHERE type = :type",array("type" => $type ) );
}

//function lc_get_rows(  ) {

	//$db = new Db();
	//$num_of_rows = $db->query( "SELECT * FROM leads" );
 //  return count($num_of_rows);
//}

function lc_lead_exists( $email = '' ) {
	if ( empty( $email ) ) return;

	$db = new Db();
	$lead = $db->row( "SELECT ID FROM leads WHERE email = :email", array( "email" => $email ) );

	if ( ! empty( $lead ) ) {
		return reset( $lead );
	} else {
		return false;
	}
}

function lc_company_exists( $company = '' ) {
	if ( empty( $company ) ) return;

	$company = strtolower( $company );

	$db = new Db();
	$leads = $db->query( "SELECT email FROM leads WHERE company = :company", array( "company" => $company ) );

	if ( ! empty( $leads ) ) {
		return $leads;
	} else {
		return array();
	}
}

function lc_email_domain_count( $lead_email = '' ) {
	if ( empty( $lead_email ) ) return;

	$lead_email = strtolower( $lead_email );

	$domain = substr(strrchr($lead_email, "@"), 1);

	$db = new Db();
	$leads = $db->query( "SELECT email FROM leads WHERE win_lose = :win_lose", array( "win_lose" => 'w' ) );

	$count = 0;

	if ( ! empty( $leads ) ) {
		foreach ( $leads as $email ) {
			$email = reset( $email );
			$edomain = substr(strrchr($email, "@"), 1);

			if ( $edomain == $domain ) {
				$count++;
			}
		}

		return $count;
	} else {
		return 0;
	}
}

function lc_email_domain_company_count( $lead_email = '' ) {
	if ( empty( $lead_email ) ) return;

	$lead_email = strtolower( $lead_email );

	$domain = substr(strrchr($lead_email, "@"), 1);
	$explode = explode( '.', $domain );
	$domain_company = $explode[0];

	if ( empty( $domain_company ) ) return 0;

	$db = new Db();
	$leads = $db->query( "SELECT company FROM leads WHERE win_lose = :win_lose", array( "win_lose" => 'w' ) );

	$count = 0;

	if ( ! empty( $leads ) ) {
		foreach ( $leads as $company ) {
			$company = reset( $company );
			if ( $domain_company == $company ) {
				$count++;
			}
		}

		return $count;
	} else {
		return 0;
	}
}

function lc_get_win_lose_count( $status = 'w' ) {

	$db = new Db();
	$status_count = $db->row( "SELECT COUNT(win_lose) AS winlose FROM leads WHERE win_lose=:win_lose", array( "win_lose" => $status ) );

	if ( ! empty( $status_count ) ) {
		return $status_count['winlose'];
	}
}

function lc_get_cond_score( $field, $value ) {
	$value = strtolower( $value );
	$db = new Db();
	$cond = $db->row( "SELECT * FROM conditions WHERE field = :field AND value = :value", array( "field" => $field, "value" => $value ) );

	if ( ! empty( $cond ) ) {
		return (int) $cond['score'];
	} else {
		return 0;
	}
}

function lc_ajax_check_store_lead( $request ) {

	if ( lc_lead_exists( $request['email'] ) )	{
		$response = array( 'status' => 'error', 'msg_slug' => 'already_played', 'msg' => 'Sorry, you have already played before!' );
	} else {

		if ( lc_set_lead( $request['email'], $request['company'], $request['ott_score'], $request['job_score'], $request['company_score'], $request['email_score'] ) ) {
			$response = array( 'status' => 'success', 'msg_slug' => 'inserted_lead', 'msg' => 'Lead has been inserted', 'additional_pref_score' => 0 );

            $response['isRandom'] = lc_random();
			// Company win limit check

			// Count email domains
			// $email_domain_counts = lc_email_domain_count( $request['email'] );
			
			// Count companies
			//$company_exists = count( lc_company_exists( $request['company'] ) );

			// Count email domains that are same as companies
			// $email_domain_company_counts = lc_email_domain_company_count( $request['email'] );

			// bigger than [2] since we've already stored one.
			//if ( $company_exists > 2 ) {
			//	$response['is_loser'] = 'yes';
			//}

			// Apply database conds score
//			if ( ! empty(  $request['conditions']) ) {
//				$score = 0;
//				$email_already_q = false;
//				foreach ( $request['conditions'] as $field => $value ) {
//					if ( ($field == 'email' || $field == 'email_domain') && $email_already_q === true ) continue;
//
//					$q_score = lc_get_cond_score( $field, $value );
//					$score += $q_score;
//
//					if ( ($field == 'email' || $field == 'email_domain') && $q_score > 0 ) {
//						$email_already_q = true;
//					}
//				}
//
//				$response['additional_pref_score'] = $score;
//			}
		} else {
			$response = array( 'status' => 'error', 'msg_slug' => 'database_error', 'msg' => 'An unknown error has occurred, please try again!' );
		}
	}

	return $response;
}

function lc_random() {
  $range = lc_get_config_val("random");
  $range = $range["value"];
  $rand = rand(1, $range);
  if($rand == 1) {
   return true;
  } else {
    return false;
  }
}

function lc_ajax_win_status( $request ) {
	if ( ! empty( $request['email'] ) && ! empty( $request['win_status'] ) ) {

		$db = new Db();
		$db->query("UPDATE leads SET win_lose=:status WHERE email=:email", array("status"=>$request['win_status'], "email"=>$request['email']));

		if ( $request['win_status'] == 'w' ) {
			lc_add_data_value( 'win_count', '0' );
		} else {
			$win_count = (int) lc_get_data_value( 'win_count' );
			$win_count = $win_count+1;

			lc_add_data_value( 'win_count', $win_count );
		}

	} else {
		$response = array( 'status' => 'failed', 'msg' => 'Bad request' );
	}

	return $response;
}

function lc_reset_win_limit() {
	$db = new Db();
	return $db->query("UPDATE leads SET win_lose='o' WHERE win_lose = 'w'");
}

function lc_reset_leads() {
	$db = new Db();
	return $db->query("TRUNCATE TABLE leads;");
}

function lc_reset_conditions() {
	$db = new Db();
	return $db->query("TRUNCATE TABLE conditions;");
}

// Fills the conditions table from csv file in root
function lc_fill_conditions() {
	//open the file as read-only
	$file = fopen("cx_conditions.csv", "r");

	$db = new Db();

	// lineLength is unlimited when set to 0
	// comma delimited
	while($data = fgetcsv($file, $lineLength = 0, $delimiter = ",")) {
	   if ($data[0] == 'field' && $data[1] == 'value' && $data[2] == 'score') continue;

		$db->query("INSERT INTO conditions(field, value, score) VALUES(:field, :value, :score)", array("field"=> $data[0], 'value'=> strtolower($data[1]), 'score'=> $data[2]));
	}
}

function lc_fill_configuration(){
  $data_type = "random";
  $data_value = 50;
  $db = new Db();
  $db->query("INSERT INTO configuration(type, value) VALUES(:type, :value)", array("type"=> $data_type, 'value'=> $data_value));
}

function lc_db_install() {
	$db = new Db();
	$table = $db->query("SHOW TABLES LIKE 'leads'");	

	if ( empty( $table ) ) {
		$db->query("CREATE TABLE IF NOT EXISTS `leads` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `win_lose` varchar(1) COLLATE utf8mb4_unicode_ci NOT NULL,
  `company` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `score_email` int(2)COLLATE utf8mb4_unicode_ci NOT NULL,
  `score_company` int(2)COLLATE utf8mb4_unicode_ci NOT NULL,
  `score_job_title` int(2)COLLATE utf8mb4_unicode_ci NOT NULL,
  `score_ott` int(2)COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=1;");

		$db->query("CREATE TABLE IF NOT EXISTS `conditions` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `field` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `score` int(11) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=1;");

		$db->query("CREATE TABLE IF NOT EXISTS `data` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `data_key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `data_value` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `score` int(11) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=1;");

$db->query("CREATE TABLE IF NOT EXISTS `configuration` (
 `ID` int(11) NOT NULL AUTO_INCREMENT,
 `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
 `value` int(11)COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=1;");

        lc_fill_configuration();
//		lc_fill_conditions();
		echo 'Installed.';
	} else {
		echo 'Already installed!';die;
	}
}

function lc_twilio_send_sms( $phones, $msg ) {

	// Your Account SID and Auth Token from twilio.com/console
	// TODO: Please replace with live credentials
	$sid = 'AC77ed85a1e2fb7af18caf832e1d2b2102';
	$token = '249393e4dcbbb855408b29ecfe59e453';
	$client = new Client($sid, $token);

	if ( ! array( $phones ) ) $phones = array( $phones );

	$statuses = array();

	foreach ( $phones as $phone ) {
		// Use the client to do fun stuff like send text messages!
		try{
			$statuses[] = $client->messages->create(
			    // the number you'd like to send the message to
			    $phone,
			    array(
			        // A Twilio phone number you purchased at twilio.com/console
			        // TODO: Please replace with the number you bought from Twilio
			        'from' => '+972526289049',
			        // the body of the text message you'd like to send
			        'body' => $msg
			    )
			);
		} catch(Twilio\Exceptions\RestException $e) {
			lc_log_something( 'logs/sms_logs.txt', "Twilio could not deliver SMS due to this error: " . $e->getMessage() );
		}
	}

	return $statuses;
}

function lc_log_something( $log_file_name, $log_line ) {
	
	$log = '[' . date("F j, Y, g:i a"). '] '. $log_line . PHP_EOL;

    $Handle = fopen( $log_file_name, 'a' );
    //write data to file
    fwrite($Handle, $log);
    //close file
    fclose($Handle);

	//Save string to log, use FILE_APPEND to append.
	// file_put_contents($log_file_name, $log, FILE_APPEND);
}

function lc_ajax_send_sms( $request ) {
	// Twilio Send SMS
	 if ( $request['random'] == 'true' ) {
	  $msg = sprintf( '%s %s, is a randomized winner.', $request['first_name'], $request['last_name'] );
	 } else {
	  $msg = sprintf( '%s %s, %s from %s is qualified. %s. Good luck!', $request['first_name'], $request['last_name'], $request['position'], $request['company'], $request['inhouse'] );
	 }


	// TODO: Please replace the phone number with the real number that will receive the SMS
	// And add multiple phone numbers here
	// Anat: +972558807602
	// CX: +972558807602
	// 
	$phones = array(
		'+972558807602',
		 //'+972543020460',
	);

	lc_twilio_send_sms( $phones, $msg );

	$response = array( 'status' => 'success', 'msg_slug' => 'sms_sent', 'msg' => 'SMS sent!' );

	return $response;
}


function send_email_to_winner ( $request ) {
    $is_random = $request['random'];
    $first_name = $request['first_name'];
    $last_name = $request['last_name'];
    $job_title = $request['position'];
    $company = $request['company'];
    $operating = $request['inhouse'];
    if ( $is_random == 'true' ) {
        $content = 'inc/phpMailer/random_template.html';
        $subject = 'Buzzooka Random Win';
    } else {
        $content = 'inc/phpMailer/winner_template.html';
        $subject = 'Buzzooka Qualified Lead';
    };
    sendMail( $first_name, $last_name, $job_title, $company, $operating, $subject, $content );
}

function lc_get_allowed_ajax_actions() {
	return array( 
		'lc_ajax_check_store_lead',
		'lc_ajax_send_sms',
		'lc_ajax_win_status',
		'lc_random',
		'send_email_to_winner'
	);
}

function lc_print( $var ) {
	echo '<pre>' . print_r( $var, true ) . '</pre>';die;
}

function lc_countries() {
	$countries = array(
		'Singapore',
		'Afghanistan',
		'Albania',
		'Algeria',
		'American Samoa',
		'Andorra',
		'Angola',
		'Anguilla',
		'Antarctica',
		'Antigua and Barbuda',
		'Argentina',
		'Armenia',
		'Aruba',
		'Australia',
		'Austria',
		'Azerbaijan',
		'Bahamas',
		'Bahrain',
		'Bangladesh',
		'Barbados',
		'Belarus',
		'Belgium',
		'Belize',
		'Benin',
		'Bermuda',
		'Bhutan',
		'Bolivia',
		'Bosnia and Herzegowina',
		'Botswana',
		'Bouvet Island ',
		'Brazil',
		'British Indian Ocean Territory',
		'Brunei Darussalam',
		'Bulgaria',
		'Burkina Faso',
		'Burundi',
		'Cambodia',
		'Cameroon',
		'Canada',
		'Cape Verde',
		'Cayman Islands',
		'Central African Republic',
		'Chad',
		'Chile',
		'China',
		'Christmas Island',
		'Cocos (Keeling) Islands',
		'Colombia',
		'Comoros',
		'Congo',
		'Cook Islands',
		'Costa Rica',
		'Cote d\'lvoire',
		'Croatia (Hrvatska)',
		'Cuba',
		'Cyprus',
		'Czech Republic',
		'Denmark',
		'Djibouti',
		'Dominica',
		'Dominican Republic',
		'East Timor',
		'Ecuador',
		'Egypt',
		'El Salvador',
		'Equatorial Guinea',
		'Eritrea',
		'Estonia',
		'Ethiopia',
		'Falkland Islands (Malvinas',
		'Faroe Islands',
		'Fiji',
		'Finland',
		'France',
		'French Guiana',
		'French Polynesia',
		'French Southern Territories',
		'Gabon',
		'Gambia',
		'Georgia',
		'Germany',
		'Ghana',
		'Gibraltar',
		'Greece',
		'Greenland',
		'Grenada',
		'Guadeloupe',
		'Guam',
		'Guatemala',
		'Guinea',
		'Guinea-Bissau',
		'Guyana',
		'Haiti',
		'Heard and Mc Donald Island',
		'Holy See (Vatican City State)',
		'Honduras',
		'Hong Kong',
		'Hugary',
		'Iceland',
		'India',
		'Indonesia',
		'Iran (Islamic Republic of)',
		'Iraq',
		'Ireland',
		'Israel',
		'Italy',
		'Jamaica',
		'Japan',
		'Jordan',
		'Kazakhstan',
		'Kenya',
		'Kiribati',
		'Korea, Democratic People\'s Republic of',
		'Korea, Republic of',
		'Kuwait',
		'Kyrgyzstan',
		'Lao People\'s Democratic Republic',
		'Latvia',
		'Lebanon',
		'Lesotho',
		'Liberia',
		'Libyan Arab Jamahiriya',
		'Liechtenstein',
		'Lithuania',
		'Luxembourg',
		'Macau',
		'Macedonia, The Former Yugoslav Republic of',
		'Madagascar',
		'Malawi',
		'Malaysia',
		'Maldives',
		'Mali',
		'Malta',
		'Marshall Islands',
		'Martinique',
		'Mauritania',
		'Mauritius',
		'Mayotte',
		'Mexico',
		'Micronesia, Federated States of',
		'Moldova, Republic of',
		'Monaco',
		'Mongolia',
		'Montserrat',
		'Morocco',
		'Mozambique',
		'Myanmar',
		'Namibia',
		'Nauru',
		'Nepal',
		'Netherlands',
		'Netherlands Antilles',
		'New Caledonia',
		'New Zealand',
		'Nicaragua',
		'Niger',
		'Nigeria',
		'Niue',
		'Norfolk Island',
		'Northern Mariana Islands',
		'Norway',
		'Oman',
		'Pakistan',
		'Palau',
		'Panama',
		'Papua New Guinea',
		'Paraguay',
		'Peru',
		'Philippines',
		'Pitcairn',
		'Poland',
		'Portugal',
		'Puerto Rico',
		'Qatar',
		'Reunion',
		'Romania',
		'Russian Federation',
		'Rwanda',
		'Saint Kitts and Nevis',
		'Saint LUCIA',
		'Saint Vincent and the Grenadines',
		'Samoa',
		'San Marino',
		'Sao Tome and Principe',
		'Saudi Arabia',
		'Senegal',
		'Seychelles',
		'Sierra Leone',
		'Slovakia (Slovak Republic)',
		'Slovenia',
		'Solomon Islands',
		'Somalia',
		'South Africa',
		'South Georgia and the South Sandwich Islands',
		'Spain',
		'Sri Lanka',
		'St. Helena',
		'St. Pierre and Miquelon',
		'Sudan',
		'Suriname',
		'Svalbard and Jan Mayen Islands',
		'Sweden',
		'Switzerland',
		'Syrian Arab Republic',
		'Taiwan, Province of China',
		'Tajikistan',
		'Tanzania, United Republic of',
		'Thailand ',
		'Togo',
		'Tokelau',
		'Tonga',
		'Trinidad and Tobago',
		'Tunisia',
		'Turkey',
		'Turkmenistan',
		'Turks and Caicos Islands',
		'Tuvalu',
		'Uganda',
		'Ukraine',
		'United Arab Emirates',
		'United Kingdom',
		'United States',
		'United States Minor Outlying Islands',
		'Uruguay',
		'Uzbekistan',
		'Vanuatu',
		'Venezuela',
		'Viet Nam',
		'Virgin Islands (British)',
		'Virgin Islands (U.S)',
		'Wallis and Futuna Islands',
		'Western Sahara',
		'Yemen',
		'Zambia',
		'Zimbabwe',
);

	return $countries;
}

function lc_states() {
	$states = array(
		'Alabama',
		'Alaska',
		'Arizona',
		'Arkansas',
		'California',
		'Colorado',
		'Connecticut',
		'Delaware',
		'District of Columbia',
		'Federated Micronesia',
		'Florida',
		'Georgia',
		'Guam',
		'Hawaii',
		'Idaho',
		'Illinois',
		'Indiana',
		'Iowa',
		'Kansas',
		'Kentucky',
		'Louisiana',
		'Maine',
		'Maryland',
		'Massachusetts',
		'Michigan',
		'Minnesota',
		'Mississippi',
		'Missouri',
		'Montana',
		'Nebraska',
		'Nevada',
		'New Hampshire',
		'New Jersey',
		'New Mexico',
		'New York',
		'North Carolina',
		'North Dakota',
		'Ohio',
		'Oklahoma',
		'Oregon',
		'Pennsylvania',
		'Puerto Rico',
		'Rhode Island',
		'South Carolina',
		'South Dakota',
		'Tennessee',
		'Texas',
		'United States Minor Outlying Islands',
		'Utah',
		'Vermont',
		'Virginia',
		'Washington',
		'West Virginia',
		'Wisconsin',
		'Wyoming',
	);

	return $states;
}

function lc_employees_count() {
	return array(
		'0-10',
		'11-20',
		'21-50',
		'51-100',
		'101-200',
		'201-500',
		'500+',
	);
}

function lc_develop_in_house() {
	return array(
		'Yes',
		'No',
	);
}

function lc_industries() {
	return array(
		'Retail',
		'Fashion',
		'Advertising',
		'Technology',
	);
}

function lc_data_exists( $key = '' ) {
	if ( empty( $key ) ) return;

	$db = new Db();
	$data = $db->row( "SELECT ID FROM data WHERE data_key = :key", array( "key" => $key ) );

	if ( ! empty( $data ) ) {
		return reset( $data );
	} else {
		return false;
	}
}

function lc_get_data_value( $key ) {
	$db = new Db();
	$data = $db->row( "SELECT data_value FROM data WHERE data_key = :key", array( "key" => $key ) );	

	if ( ! empty( $data ) ) {
		return reset( $data );
	}
}

function lc_add_data_value( $key, $value ) {
	$data_id = lc_data_exists( $key );
	$db = new Db();

	if ( ! empty( $data_id ) ) {
		return $db->query( "UPDATE data SET data_value=:value WHERE id=:id", array( "value" => $value, "id" => $data_id ) );
	} else {
		return $db->query("INSERT INTO data(data_key, data_value) VALUES(:data_key, :data_value)", array("data_key"=>$key, 'data_value'=>$value));	
	}
}

// /?reset_win_limit=7ded20a8075bca09f9a6bc809e06d6a7
if ( isset( $_GET['reset_win_limit'] ) && $_GET['reset_win_limit'] == md5( 'lc_reset_limit' ) ) {
	echo lc_reset_win_limit();die;
}

// /?install=database
if ( isset( $_GET['install'] ) && $_GET['install'] == 'database' ) {
	lc_db_install();die;
}

// /?killall=a30999b76f0cb71959f06cdc4b0f4105
if ( isset( $_GET['killall'] ) && $_GET['killall'] == md5( 'resetleads' ) ) {
	echo lc_reset_leads();die;
}

// /?killallconds=6352d5caa2211ce831b8da2b47fa1cec
if ( isset( $_GET['killallconds'] ) && $_GET['killallconds'] == md5( 'resetconds' ) ) {
	echo lc_reset_conditions();die;
}

// /?getWinners=4d200caf1cbd8f194588d28fc58cda2a
if ( isset( $_GET['getWinners'] ) && $_GET['getWinners'] == md5( 'getWinners' ) ) {
	lc_print( lc_get_winners_losers( 'w' ) );
}

// /?getLosers=8f135f2d11dff2783bb3a3b9e1541142
if ( isset( $_GET['getLosers'] ) && $_GET['getLosers'] == md5( 'getLosers' ) ) {
	lc_print( lc_get_winners_losers( 'l' ) );
}

// if (isset( $_GET['baselectComp'] )) {
// 	$db = new Db();
// 	$data = $db->row( "SELECT value FROM conditions WHERE value = :value", array( "value" => 'ibm' ) );
// 	lc_print( $data );
// }

// if (isset( $_GET['badeleteComp'] )) {
// 	$db = new Db();
// 	$data = $db->query( "DELETE FROM conditions WHERE value = :value", array( "value" => 'ibm' ) );
// 	lc_print( $data );
// }