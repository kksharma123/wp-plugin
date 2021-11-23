
<?php 
		if($_POST){
			//print_r($_POST);
			//die();exit;
			// echo get_option()
// $zz= 'https://api.staging.myhippo.io/v1/herd/quote?auth_token=zcXbR1NoE0zoozyuqAa75s5gBATbeiUsbkGhvb5toGiNWUdDjIUkAU5XgDwCRTet&street=435%20Homer%20Ave&city=Palo%20Alto&state=CA&zip=94301&first_name=John&last_name=Gill&email=Test%40test.com&phone=7869885582&date_of_birth=05061979';
//print_r($zz);

	$dob = $_POST['date_of_birth'];
	$changeDate = date("m-d-Y", strtotime($dob));
	//echo "Changed date format is: ". $changeDate. " (MM-DD-YYYY)";


	$date_of_birth = str_replace("-", "", $changeDate);
	$street = $_POST['street'];
	$city = $_POST['city'];
	$state = $_POST['state'];
	$zip = $_POST['zip'];
	$first_name = $_POST['first_name'];
	$last_name = $_POST['last_name'];
	$email = $_POST['email'];
	$phone = '7869885582';
	$birthdate = $date_of_birth;
	$query = array();
	$query['date_of_birth'] =$birthdate;
	$query['phone'] =$phone;
	$query['email'] =$email;
	$query['first_name'] =$first_name;
	$query['last_name'] =$last_name;
	$query['zip'] =$zip;
	$query['state'] =$state;
	$query['city'] =$city;
	$query['street'] =$street;
	$options = get_option('booking_form_settings');
	$url = $options['cs_1_url'];
	$auth_token = $options['cs_1_token'];
	//echo $url;
	$full_url = $url.'?auth_token='.$auth_token.'&'.http_build_query($query);
	//echo $full_url;

	   $curl = curl_init();
	   curl_setopt($curl, CURLOPT_URL, $full_url);
	   curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
	   curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
	   $result = curl_exec($curl);
	   $final_data = json_decode($result);

	   echo '<div class="full-width">';
	    echo 'Quote Premium : '. $final_data->quote_premium ; 
	    echo '</div>';
	   if(!$result){die("Connection Failure");}
	   curl_close($curl);
      //echo "<pre>";print_r($result);echo '</pre>';
		}else{




?>
<div class="full-width">
	<form id="form_data" method="POST">
	<div class="container">
		<div class="form-fields">
			<label>First Name <span>*</span></label>
			<input type="text" value="" name="first_name" class="required">
		</div>
		<div class="form-fields">
			<label>Middle Name</label>
			<input type="text" value="" name="middle_name" class="required">
		</div>

		<div class="form-fields">
			<label>Last Name <span>*</span></label>
			<input type="text" value="" name="last_name" class="required">
		</div>

		<div class="form-fields">
			<label>Street Address <span>*</span></label>
			<input type="text" value="" name="street" class="required">
		</div>

		<div class="form-fields">
			<label>Unit </label>
			<input type="text" value="" name="unit">
		</div>

		<div class="form-fields">
			<label>City <span>*</span></label>
			<input type="text" value="" name="city" class="required">
		</div>
		<div class="form-fields">
			<label>State <span>*</span></label>
		<?php $states = array(
		    'AL'=>'Alabama',
		    'AK'=>'Alaska',
		    'AZ'=>'Arizona',
		    'AR'=>'Arkansas',
		    'CA'=>'California',
		    'CO'=>'Colorado',
		    'CT'=>'Connecticut',
		    'DE'=>'Delaware',
		    'DC'=>'District of Columbia',
		    'FL'=>'Florida',
		    'GA'=>'Georgia',
		    'HI'=>'Hawaii',
		    'ID'=>'Idaho',
		    'IL'=>'Illinois',
		    'IN'=>'Indiana',
		    'IA'=>'Iowa',
		    'KS'=>'Kansas',
		    'KY'=>'Kentucky',
		    'LA'=>'Louisiana',
		    'ME'=>'Maine',
		    'MD'=>'Maryland',
		    'MA'=>'Massachusetts',
		    'MI'=>'Michigan',
		    'MN'=>'Minnesota',
		    'MS'=>'Mississippi',
		    'MO'=>'Missouri',
		    'MT'=>'Montana',
		    'NE'=>'Nebraska',
		    'NV'=>'Nevada',
		    'NH'=>'New Hampshire',
		    'NJ'=>'New Jersey',
		    'NM'=>'New Mexico',
		    'NY'=>'New York',
		    'NC'=>'North Carolina',
		    'ND'=>'North Dakota',
		    'OH'=>'Ohio',
		    'OK'=>'Oklahoma',
		    'OR'=>'Oregon',
		    'PA'=>'Pennsylvania',
		    'RI'=>'Rhode Island',
		    'SC'=>'South Carolina',
		    'SD'=>'South Dakota',
		    'TN'=>'Tennessee',
		    'TX'=>'Texas',
		    'UT'=>'Utah',
		    'VT'=>'Vermont',
		    'VA'=>'Virginia',
		    'WA'=>'Washington',
		    'WV'=>'West Virginia',
		    'WI'=>'Wisconsin',
		    'WY'=>'Wyoming',
		);
		///echo "<pre>";print_r($states);
		echo '<select name="state">';
		foreach($states as $state=>$value){
			echo '<option value='.$state.'>'.$value.'</option>';
		}
		echo '</select>'; ?>
		</div>

		<div class="form-fields">
			<label>Zip <span>*</span></label>
			<input type="text" value="" name="zip" pattern = "/^[0-9]{5}(?:-[0-9]{4})?$/" class="required">
		</div>
		<div class="form-fields">
			<label>Date of Birth <span>*</span></label>
			<input type="date" value="" name="date_of_birth" pattern="\d{4}-\d{2}-\d{2}" class="required">
		</div>
		<div class="form-fields">
			<label>Phone Number <span>*</span></label>
			<input type="tel" id="phone" name="phone" pattern="[0-9]{3}-[0-9]{2}-[0-9]{3}" class="required">
		</div>

		<div class="form-fields">
			<label>Email Address <span>*</span></label>
			<input type="email" name="email" pattern=".+@globex\.com" class="required" >
		</div>

	
    </div>
</div>
<div class="full-width">
    <div class="container">
		<div class="cc-selector-2">
	<div class="contain_radio">
        <input id="house" type="radio" name="house" value="house" />
        <label class="drinkcard-cc house" for="house"></label>
    </div>
    <div class="contain_radio">
        <input id="condo" type="radio" name="house" value="condo" />
        <label class="drinkcard-cc condo" for="condo"></label>
	</div>
	<div class="contain_radio">
        <input id="ho5" type="radio" name="house" value="ho5" />
        <label class="drinkcard-cc ho5" for="ho5"></label>
    </div>
	</div>
</div>
<div class="full-width form-button">
<button type="button" class="submit_form">Submit</button>
</div>
 </form>

	</div>

</div>
<?php } ?>