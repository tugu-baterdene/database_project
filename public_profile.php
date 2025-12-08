<?php
session_start();
include('connect-db.php'); // your DB connection
require('profile-db.php');
include('header.php'); 

if (!isset($_SESSION['user_id'])) {
    header("Location: sign_in.php");
    exit();
} 

if (isset($_GET['comp_id'])) {
    $user_id = $_GET['comp_id']; 
} else {
    $user_id = $_SESSION['user_id']; 
}
$user = fetchUser($user_id);
$pref = fetchPref($user_id);
$location = fetchLocation($user_id);
$status = $user['status'];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Profile</title>

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
                    <img src="https://via.placeholder.com/140" class="rounded-circle profile-img">
                </div>

                <h4 class="text-center"><?php echo htmlspecialchars($user['stu_name']); ?></h4>
                <p class="text-center text-muted">Student at the University of Virginia</p>

                <hr>

                <div class="details-list">
                    <div class="d-flex justify-content-between py-2">
                        <span>Computing ID</span><span><?php echo htmlspecialchars($user['comp_id']); ?></span>
                    </div>
                    <div class="d-flex justify-content-between py-2">
                        <span>Status</span><span><?php echo htmlspecialchars($status); ?></span>
                    </div>
					<div class="d-flex justify-content-between py-2">
                        <span>School Year</span><span><?php echo htmlspecialchars($user['school_year']); ?></span>
                    </div>
                    <div class="d-flex justify-content-between py-2">
                        <span>Major</span><span><?php echo htmlspecialchars($user['major']); ?></span>
                    </div>
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
                            Account
                        </button>
                    </li>

                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="tab2-tab" data-bs-toggle="tab"
                                data-bs-target="#tab2" type="button" role="tab">
                            Preferences
                        </button>
                    </li>

                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="tab3-tab" data-bs-toggle="tab"
                                data-bs-target="#tab3" type="button" role="tab">
                            Location
                        </button>
                    </li>
                </ul>

                <!-- TAB CONTENT -->
                <div class="tab-content">

                    <!-- TAB 1: ACCOUNT INFORMATION -->
                    <div class="tab-pane fade show active" id="account" role="tabpanel">

                        <form method="post" action="<?php echo $_SERVER['PHP_SELF'] ?>" onsubmit="return validateInput()">
							<div class="row mb-3">
                                <div class="col-md-6">
                                    <label>Name</label>
                                    <input type="text" class="form-control" id='stu_name' name='stu_name'
										value="<?php if ($user['stu_name'] !=null) echo $user['stu_name']; ?>" readonly/>
                                </div>
                                <div class="col-md-6">
                                    <label>Phone Number</label>
                                    <input type="text" class="form-control" id='phone_number' name='phone_number'
										value="<?php if ($user['phone_number'] !=null) echo $user['phone_number']; ?>" readonly/>
                                </div>
                            </div>


                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label>Major</label>
                                    <input type="text" class="form-control" id='major' name='major'
										value="<?php if ($user['major'] !=null) echo $user['major']; ?>" readonly/>
                                </div>
                                <div class="col-md-6">
                                    <label>School Year</label>
                                    <select class="form-control" id='school_year' name='school_year' disabled>
										<option selected></option>
										<option value='1'
											<?php if ($user['school_year'] == '1') 
												echo ' selected="selected"'?> 
											>
											1st Year</option>
										<option value='2'
											<?php if ($user['school_year'] == '2') 
												echo ' selected="selected"'?> 
											>
											2nd Year</option>
										<option value='3' 
											<?php if ($user['school_year'] == '3') 
												echo ' selected="selected"'?> 
											>
											3rd Year</option>
										<option value='4' 
											<?php if ($user['school_year'] == '4') 
												echo ' selected="selected"'?> 
											>
											4th Year</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row mb-4">
                                <div class="col-md-12">
                                    <label>Bio</label>
                                    <input type="text" class="form-control" id='bio' name='bio'
										value="<?php if ($user['bio'] !=null) echo $user['bio']; ?>" readonly/>
                                </div>
                            </div>

                        </form>

                    </div>

                    <!-- TAB 2 CONTENT -->
                    <div class="tab-pane fade" id="tab2" role="tabpanel">
                        <form method="post" action="<?php echo $_SERVER['PHP_SELF'] ?>" onsubmit="return validateInput()">
							<div class="row mb-3">
                                <div class="col-md-6">
                                    <label>On/Off Grounds</label>
                                    <select class="form-control" id='on_off' name='on_off' disabled>
										<option selected></option>
										<option value='On'
											<?php if ($pref['on_off_grounds'] == 'On') 
												echo ' selected="selected"'?> 
											>
											On Grounds</option>
										<option value='Off'
											<?php if ($pref['on_off_grounds'] == 'Off') 
												echo ' selected="selected"'?> 
											>
											Off Grounds</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label>Pets</label>
                                    <select class="form-control" id='pets' name='pets' disabled>
										<option selected></option>
										<option value='Yes'
											<?php if ($pref['pets'] == 'Yes') 
												echo ' selected="selected"'?> 
											>
											Yes</option>
										<option value='No'
											<?php if ($pref['pets'] == 'No') 
												echo ' selected="selected"'?> 
											>
											No</option>
                                    </select>
                                </div>
                            </div>
							<div class="row mb-3">
                                <div class="col-md-6">
                                    <label>Drinking</label>
                                    <select class="form-control" id='drinking' name='drinking' disabled>
										<option selected></option>
										<option value='Yes'
											<?php if ($pref['drinking'] == 'Yes') 
												echo ' selected="selected"'?> 
											>
											Yes</option>
										<option value='No'
											<?php if ($pref['drinking'] == 'No') 
												echo ' selected="selected"'?> 
											>
											No</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label>Smoking</label>
                                    <select class="form-control" id='smoking' name='smoking' disabled>
										<option selected></option>
										<option value='Yes'
											<?php if ($pref['smoking'] == 'Yes') 
												echo ' selected="selected"'?> 
											>
											Yes</option>
										<option value='No'
											<?php if ($pref['smoking'] == 'No') 
												echo ' selected="selected"'?> 
											>
											No</option>
                                    </select>
                                </div>
                            </div>
							<div class="row mb-3">
                                <div class="col-md-6">
                                    <label>Desired Number of Roommates</label>
                                    <input type="text" class="form-control" id='num_roommates' name='num_roommates'
										value="<?php if ($pref['num_of_roommates'] !=null) echo $pref['num_of_roommates']; ?>" readonly/>
                                </div>
                                <div class="col-md-6">
                                    <label>Budget</label>
                                    <input type="text" class="form-control" id='budget' name='budget'
										value="<?php if ($pref['budget'] !=null) echo $pref['budget']; ?>" readonly/>
                                </div>
                            </div>
							<div class="row mb-3">
                                <div class="col-md-12">
                                    <label>Sleeping Habits/Preferences</label>
                                    <input type="text" class="form-control" id='sleep' name='sleep'
										value="<?php if ($pref['sleeping'] !=null) echo $pref['sleeping']; ?>" readonly/>
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
										value="<?php if ($location['addr'] !=null) echo $location['addr']; ?>" readonly>
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
                                <div class="col-md-12">
                                    <label>Extra Costs (Ex. unaccounted utilities)</label>
                                    <input type="text" class="form-control" id='extra_cost' name='extra_cost'
										value="<?php if ($location['extra_cost'] !=null) echo $location['extra_cost']; ?>" readonly/>
                                </div>
                            </div>
							
						</form>
                    </div>

                </div>

            </div>
        </div>

		<div class="text-end">
			<input type="submit" class="btn btn-dark" value="Add to Group" id="addUser" name="addUser" />  
        </div>

    </div>

</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>