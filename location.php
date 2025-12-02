<?php
session_start();
include('connect-db.php'); // your DB connection
require('location-db.php');
include('header.php'); 

if (!isset($_SESSION['user_id'])) {
    header("Location: search.php"); // UPDATE
    exit();
} 

$user_id = $_SESSION['user_id'];
$user = fetchUser($user_id);
$location = fetchLocation($user_id);
$landlord;
?>

<?php 
if ($_SERVER['REQUEST_METHOD'] == 'POST') 	
{
	if (!empty($_POST['saveUser'])) 
	{
		if (!empty($_POST['verify_passwd']) && $_POST['verify_passwd'] === $_POST['passwd']) {
			updateLogin($user_id, $_POST['stu_name'], $_POST['phone_number'], $_POST['passwd'], $_POST['school_year'], $_POST['major'], $_POST['bio']);
			$request_to_update = getRequestById($user_id);
			$user = fetchUser($user_id);
		}
		else {
			updateUser($user_id, $_POST['stu_name'], $_POST['phone_number'], $_POST['school_year'], $_POST['major'], $_POST['bio']);
			$request_to_update = getRequestById($user_id);
			$user = fetchUser($user_id);
		}
	}
	if (!empty($_POST['savePref'])) 
	{
		updatePref($user_id, $_POST['on_off'], $_POST['sleep'], $_POST['num_roommates'], $_POST['drinking'], $_POST['smoking'], $_POST['pets'], $_POST['budget']);
		$pref_to_update = getPrefById($user_id);
		$pref = fetchPref($user_id);
	}
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create New Location</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="profile.css">
</head>

<body>

<div class="profile-container container mt-5">

    <div class="row">

        <!-- Sidebar -->
        <div class="col-md-4">
            <div class="profile-card p-4">

                <div class="text-center mb-3">
                    <h2>Create New Location</h2>
                </div>
				
				<p class="text-center text-muted">Select the appropriate location type and fill in the necessary information.</p>
                <h4 class="text-center">Add Landlord Below</h4>

                <hr>

				<div class="details-list">
                    <form method="post" action="<?php echo $_SERVER['PHP_SELF'] ?>" onsubmit="return validateInput()">
						<div class="row mb-3">
                            <div class="col-md-12">
                                <label>Landlord Name</label>
                                <input type="text" class="form-control" id='stu_name' name='stu_name'
									value="<?php if ($user['stu_name'] !=null) echo $user['stu_name']; ?>" />
                            </div>
                        </div>
						<div class="row mb-3">
                            <div class="col-md-12">
                                <label>Phone Number</label>
                                <input type="text" class="form-control" id='stu_name' name='stu_name'
									value="<?php if ($user['stu_name'] !=null) echo $user['stu_name']; ?>" />
                            </div>
                        </div>
					</form>
                </div>

                <div class="text-center mt-4">
					<a href="public_profile.php" class="btn btn-primary w-100">View Public Profile</a>
                </div>

            </div>
        </div>

        <!-- Main Content -->
        <div class="col-md-8">
            <div class="profile-content p-4">

                <!-- REAL Bootstrap Tabs -->
                <ul class="nav nav-tabs mb-4" id="profileTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="account-tab" data-bs-toggle="tab"
                                data-bs-target="#account" type="button" role="tab">
                            Apartment
                        </button>
                    </li>

                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="tab2-tab" data-bs-toggle="tab"
                                data-bs-target="#tab2" type="button" role="tab">
                            House
                        </button>
                    </li>

                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="tab3-tab" data-bs-toggle="tab"
                                data-bs-target="#tab3" type="button" role="tab">
                            Dorm
                        </button>
                    </li>
                </ul>

                <!-- TAB CONTENT -->
                <div class="tab-content">

                    <!-- TAB 1: ACCOUNT INFORMATION -->
                    <div class="tab-pane fade show active" id="account" role="tabpanel">
						<form method="post" action="<?php echo $_SERVER['PHP_SELF'] ?>" onsubmit="return validateInput()">
							<div class="row mb-3">
                                <div class="col-md-12">
                                    <label>Address</label>
                                    <input type="text" class="form-control" id='addr' name='addr'
										value="<?php if ($location['addr'] !=null) echo $location['addr']; ?>"
										onclick="window.location.href='search.php'">
                                </div>
                            </div>
							<div class="row mb-3">
                                <div class="col-md-6">
                                    <label>Bedrooms</label>
                                    <input type="text" class="form-control" id='bedroom' name='bedroom'
										value="<?php if ($location['bedroom'] !=null) echo $location['bedroom']; ?>"/>
                                </div>
                                <div class="col-md-6">
                                    <label>Bathrooms</label>
                                    <input type="text" class="form-control" id='bathroom' name='bathroom'
										value="<?php if ($location['bathroom'] !=null) echo $location['bathroom']; ?>"/>
                                </div>
                            </div>
							<div class="row mb-3">
                                <div class="col-md-6">
                                    <label>Rent</label>
                                    <input type="text" class="form-control" id='price' name='price'
										value="<?php if ($location['price'] !=null) echo $location['price']; ?>"/>
                                </div>
                                <div class="col-md-6">
                                    <label>On/Off Grounds</label>
                                    <select class="form-control" id='on_off' name='on_off'>
										<option selected></option>
										<option value='On'
											<?php if ($location['on_off_grounds'] == 'On') 
												echo ' selected="selected"'?> 
											>
											On Grounds</option>
										<option value='Off'
											<?php if ($location['on_off_grounds'] == 'Off') 
												echo ' selected="selected"'?> 
											>
											Off Grounds</option>
                                    </select>
                                </div>
                            </div>
							<div class="row mb-3">
                                <div class="col-md-6">
                                    <label>Extra Costs (Ex. unaccounted utilities)</label>
                                    <input type="text" class="form-control" id='extra_cost' name='extra_cost'
										value="<?php if ($location['extra_cost'] !=null) echo $location['extra_cost']; ?>"/>
                                </div>
								<div class="col-md-6">
                                    <label>Number of Floors</label>
                                    <input type="text" class="form-control" id='num_of_floors' name='num_of_floors'
										value="<?php if ($location['extra_cost'] !=null) echo $location['extra_cost']; ?>"/>
                                </div>
                            </div>
							<div class="row mb-3">
								<div class="col-md-6">
                                    <label>Has Elevators?</label>
                                    <select class="form-control" id='elevator' name='elevator'>
										<option selected></option>
										<option value='True'
											<?php if ($location['on_off_grounds'] == 'True') 
												echo ' selected="selected"'?> 
											>
											Yes</option>
										<option value='False'
											<?php if ($location['on_off_grounds'] == 'False') 
												echo ' selected="selected"'?> 
											>
											No</option>
                                    </select>
                                </div>
								<div class="col-md-6">
                                    <label>Has Balcony?</label>
                                    <select class="form-control" id='on_off' name='on_off'>
										<option selected></option>
										<option value='True'
											<?php if ($location['on_off_grounds'] == 'True') 
												echo ' selected="selected"'?> 
											>
											Yes</option>
										<option value='False'
											<?php if ($location['on_off_grounds'] == 'False') 
												echo ' selected="selected"'?> 
											>
											No</option>
                                    </select>
                                </div>
							</div>
							<div class="row mb-3">
								<div class="col-md-6">
                                    <label>Allows Pets?</label>
                                    <select class="form-control" id='pets' name='pets'>
										<option selected></option>
										<option value='True'
											<?php if ($location['on_off_grounds'] == 'True') 
												echo ' selected="selected"'?> 
											>
											Yes</option>
										<option value='False'
											<?php if ($location['on_off_grounds'] == 'False') 
												echo ' selected="selected"'?> 
											>
											No</option>
                                    </select>
                                </div>
								<div class="col-md-6">
                                    <label>Allows Smoking?</label>
                                    <select class="form-control" id='smoking' name='smoking'>
										<option selected></option>
										<option value='True'
											<?php if ($location['on_off_grounds'] == 'True') 
												echo ' selected="selected"'?> 
											>
											Yes</option>
										<option value='False'
											<?php if ($location['on_off_grounds'] == 'False') 
												echo ' selected="selected"'?> 
											>
											No</option>
                                    </select>
                                </div>
							</div>
							
						</form>
                    </div>

                    <!-- TAB 2 CONTENT -->
                    <div class="tab-pane fade" id="tab2" role="tabpanel">
						<form method="post" action="<?php echo $_SERVER['PHP_SELF'] ?>" onsubmit="return validateInput()">
							<div class="row mb-3">
                                <div class="col-md-12">
                                    <label>Address</label>
                                    <input type="text" class="form-control" id='addr' name='addr'
										value="<?php if ($location['addr'] !=null) echo $location['addr']; ?>" 
										onclick="window.location.href='search.php'">
                                </div>
                            </div>
							<div class="row mb-3">
                                <div class="col-md-6">
                                    <label>Bedrooms</label>
                                    <input type="text" class="form-control" id='bedroom' name='bedroom'
										value="<?php if ($location['bedroom'] !=null) echo $location['bedroom']; ?>" />
                                </div>
                                <div class="col-md-6">
                                    <label>Bathrooms</label>
                                    <input type="text" class="form-control" id='bathroom' name='bathroom'
										value="<?php if ($location['bathroom'] !=null) echo $location['bathroom']; ?>" />
                                </div>
                            </div>
							<div class="row mb-3">
                                <div class="col-md-6">
                                    <label>Rent</label>
                                    <input type="text" class="form-control" id='price' name='price'
										value="<?php if ($location['price'] !=null) echo $location['price']; ?>" />
                                </div>
                                <div class="col-md-6">
                                    <label>On/Off Grounds</label>
                                    <select class="form-control" id='on_off' name='on_off' >
										<option selected></option>
										<option value='On'
											<?php if ($location['on_off_grounds'] == 'On') 
												echo ' selected="selected"'?> 
											>
											On Grounds</option>
										<option value='Off'
											<?php if ($location['on_off_grounds'] == 'Off') 
												echo ' selected="selected"'?> 
											>
											Off Grounds</option>
                                    </select>
                                </div>
                            </div>
							<div class="row mb-3">
                                <div class="col-md-6">
                                    <label>Extra Costs (Ex. unaccounted utilities)</label>
                                    <input type="text" class="form-control" id='extra_cost' name='extra_cost'
										value="<?php if ($location['extra_cost'] !=null) echo $location['extra_cost']; ?>" />
                                </div>
								<div class="col-md-6">
                                    <label>Number of Stories</label>
                                    <input type="text" class="form-control" id='stories' name='stories'
										value="<?php if ($location['extra_cost'] !=null) echo $location['extra_cost']; ?>" />
                                </div>
                            </div>
							<div class="row mb-3">
								<div class="col-md-6">
                                    <label>Has a Yard?</label>
                                    <select class="form-control" id='yard' name='yard'>
										<option selected></option>
										<option value='True'
											<?php if ($location['on_off_grounds'] == 'True') 
												echo ' selected="selected"'?> 
											>
											Yes</option>
										<option value='False'
											<?php if ($location['on_off_grounds'] == 'False') 
												echo ' selected="selected"'?> 
											>
											No</option>
                                    </select>
                                </div>
								<div class="col-md-6">
                                    <label>Has a Porch?</label>
                                    <select class="form-control" id='porch' name='porch'>
										<option selected></option>
										<option value='True'
											<?php if ($location['on_off_grounds'] == 'True') 
												echo ' selected="selected"'?> 
											>
											Yes</option>
										<option value='False'
											<?php if ($location['on_off_grounds'] == 'False') 
												echo ' selected="selected"'?> 
											>
											No</option>
                                    </select>
                                </div>
							</div>
							
						</form>
                    </div>

                    <!-- TAB 3 CONTENT -->
                    
                    <div class="tab-pane fade" id="tab3" role="tabpanel">
						<form method="post" action="<?php echo $_SERVER['PHP_SELF'] ?>" onsubmit="return validateInput()">
							<div class="row mb-3">
                                <div class="col-md-12">
                                    <label>Address</label>
                                    <input type="text" class="form-control" id='addr' name='addr'
										value="<?php if ($location['addr'] !=null) echo $location['addr']; ?>" readonly
										onclick="window.location.href='search.php'">
                                </div>
                            </div>
							<div class="row mb-3">
                                <div class="col-md-6">
                                    <label>Bedrooms</label>
                                    <input type="text" class="form-control" id='bedroom' name='bedroom'
										value="<?php if ($location['bedroom'] !=null) echo $location['bedroom']; ?>" readonly/>
                                </div>
                                <div class="col-md-6">
                                    <label>Bathrooms</label>
                                    <input type="text" class="form-control" id='bathroom' name='bathroom'
										value="<?php if ($location['bathroom'] !=null) echo $location['bathroom']; ?>" readonly/>
                                </div>
                            </div>
							<div class="row mb-3">
                                <div class="col-md-6">
                                    <label>Rent</label>
                                    <input type="text" class="form-control" id='price' name='price'
										value="<?php if ($location['price'] !=null) echo $location['price']; ?>" readonly/>
                                </div>
                                <div class="col-md-6">
                                    <label>On/Off Grounds</label>
                                    <select class="form-control" id='on_off' name='on_off' disabled>
										<option selected></option>
										<option value='On'
											<?php if ($location['on_off_grounds'] == 'On') 
												echo ' selected="selected"'?> 
											>
											On Grounds</option>
										<option value='Off'
											<?php if ($location['on_off_grounds'] == 'Off') 
												echo ' selected="selected"'?> 
											>
											Off Grounds</option>
                                    </select>
                                </div>
                            </div>
							<div class="row mb-3">
                                <div class="col-md-6">
                                    <label>Extra Costs (Ex. unaccounted utilities)</label>
                                    <input type="text" class="form-control" id='extra_cost' name='extra_cost'
										value="<?php if ($location['extra_cost'] !=null) echo $location['extra_cost']; ?>" />
                                </div>
								<div class="col-md-6">
                                    <label>Style/Type</label>
                                    <select class="form-control" id='style' name='style'>
										<option selected></option>
										<option value='motel'
											<?php if ($location['on_off_grounds'] == 'motel') 
												echo ' selected="selected"'?> 
											>
											Motel</option>
										<option value='hall'
											<?php if ($location['on_off_grounds'] == 'hall') 
												echo ' selected="selected"'?> 
											>
											Hall</option>
										<option value='suite'
											<?php if ($location['on_off_grounds'] == 'suite') 
												echo ' selected="selected"'?> 
											>
											Suite</option>
                                    </select>
                                </div>
                            </div>
							<div class="row mb-3">
								<div class="col-md-6">
                                    <label>Single or Double?</label>
                                    <select class="form-control" id='single_double' name='single_double' >
										<option selected></option>
										<option value='Single'
											<?php if ($location['on_off_grounds'] == 'Single') 
												echo ' selected="selected"'?> 
											>
											Single</option>
										<option value='Double'
											<?php if ($location['on_off_grounds'] == 'Double') 
												echo ' selected="selected"'?> 
											>
											Double</option>
                                    </select>
                                </div>
								<div class="col-md-6">
                                    <label>Has Kitchen?</label>
                                    <select class="form-control" id='kitchen' name='kitchen'>
										<option selected></option>
										<option value='True'
											<?php if ($location['on_off_grounds'] == 'True') 
												echo ' selected="selected"'?> 
											>
											Yes</option>
										<option value='False'
											<?php if ($location['on_off_grounds'] == 'False') 
												echo ' selected="selected"'?> 
											>
											No</option>
                                    </select>
                                </div>
							</div>

						</form>
                    </div>

                </div>

            </div>
        </div>

    </div>

</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>