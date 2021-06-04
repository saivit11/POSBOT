<?php 

$prac_name="";
$prac_specialization="";
$prac_country="";
$prac_state="";
$prac_city="";
$prac_street="";

if (isset($_POST['search_button1'])) {
	$prac_name = strip_tags($_POST['prac_name']);
	// $prac_specialization = strip_tags($_POST['prac_type']);
	// $prac_country = strip_tags($_POST['prac_ctry']);
	$prac_city = strip_tags($_POST['prac_city']);
	// $prac_state = strip_tags($_POST['prac_state']);
	// $prac_street = strip_tags($_POST['prac_street']);

	$data_query = mysqli_query($con,"SELECT * FROM PRACTITIONER WHERE PRAC_NAME LIKE '%$prac_name%' AND PRAC_CITY='$prac_city'");
	$error_message="searching";
	// echo mysqli_num_rows($data_query);
	// echo $prac_name;
	if (mysqli_num_rows($data_query)>0) {
		// echo "WORKING PART 1";
		while ($row=mysqli_fetch_array($data_query)) {
			$name = $row['PRAC_NAME'];
			$specialization = $row['PRAC_SPECIALIZATION'];
			$country = $row['PRAC_COUNTRY'];
			$state = $row['PRAC_STATE'];
			$city = $row['PRAC_CITY'];
			$street = $row['PRAC_STREET'];
			$address = $street . ", " . $city . ", " . $state . ", " . $country . ".";
			$number = $row['PRAC_NUMBER'];
			$email = $row['PRAC_EMAIL'];

			$str .= "<div class='show_each_practitioner'>
						<div class='practitioner_details'>
							<h2>$name</h2>
							<h5>$specialization</h5>
							<h5>$address</h5>
							<h5>$number</h5>
							<h5>$email</h5>
						</div>
					</div>
					<hr>";
		}
	}

}


if (isset($_POST['search_button2'])) {
	// $prac_name = strip_tags($_POST['prac_name']);
	$prac_specialization = strip_tags($_POST['prac_type']);
	// $prac_country = strip_tags($_POST['prac_ctry']);
	$prac_city = strip_tags($_POST['prac_city']);
	// $prac_state = strip_tags($_POST['prac_state']);
	// $prac_street = strip_tags($_POST['prac_street']);

	$data_query = mysqli_query($con,"SELECT * FROM PRACTITIONER WHERE PRAC_SPECIALIZATION LIKE '%$prac_specialization%' AND PRAC_CITY='$prac_city'");
	$error_message="searching";
	// echo mysqli_num_rows($data_query);
	// echo $prac_name;
	if (mysqli_num_rows($data_query)>0) {
		// echo "WORKING PART 1";
		while ($row=mysqli_fetch_array($data_query)) {
			$name = $row['PRAC_NAME'];
			$specialization = $row['PRAC_SPECIALIZATION'];
			$country = $row['PRAC_COUNTRY'];
			$state = $row['PRAC_STATE'];
			$city = $row['PRAC_CITY'];
			$street = $row['PRAC_STREET'];
			$address = $street . ", " . $city . ", " . $state . ", " . $country . ".";
			$number = $row['PRAC_NUMBER'];
			$email = $row['PRAC_EMAIL'];

			$str .= "<div class='show_each_practitioner'>
						<div class='practitioner_details'>
							<h2>$name</h2>
							<h5>$specialization</h5>
							<h5>$address</h5>
							<h5>$number</h5>
							<h5>$email</h5>
						</div>
					</div>
					<hr>";
		}
	}
}

if (isset($_POST['search_button3'])) {
	// $prac_name = strip_tags($_POST['prac_name']);
	// $prac_specialization = strip_tags($_POST['prac_type']);
	$prac_country = strip_tags($_POST['prac_ctry']);
	$prac_city = strip_tags($_POST['prac_city']);
	$prac_state = strip_tags($_POST['prac_state']);
	$prac_street = strip_tags($_POST['prac_street']);

	$data_query = mysqli_query($con,"SELECT * FROM PRACTITIONER WHERE ((PRAC_COUNTRY='$prac_country' AND PRAC_STATE='$prac_state') AND PRAC_CITY='$prac_city') AND PRAC_STREET='$prac_street'");
	$error_message="searching";
	// echo mysqli_num_rows($data_query);
	// echo $prac_name;
	if (mysqli_num_rows($data_query)>0) {
		// echo "WORKING PART 1";
		while ($row=mysqli_fetch_array($data_query)) {
			$name = $row['PRAC_NAME'];
			$specialization = $row['PRAC_SPECIALIZATION'];
			$country = $row['PRAC_COUNTRY'];
			$state = $row['PRAC_STATE'];
			$city = $row['PRAC_CITY'];
			$street = $row['PRAC_STREET'];
			$address = $street . ", " . $city . ", " . $state . ", " . $country . ".";
			$number = $row['PRAC_NUMBER'];
			$email = $row['PRAC_EMAIL'];

			$str .= "<div class='show_each_practitioner'>
						<div class='practitioner_details'>
							<h2>$name</h2>
							<h5>$specialization</h5>
							<h5>$address</h5>
							<h5>$number</h5>
							<h5>$email</h5>
						</div>
					</div>
					<hr>";
		}
	}
}


?>