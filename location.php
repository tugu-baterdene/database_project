<?php
require('connect-db.php');
require('location-db.php');
include('header.php'); 

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
} 

$user_id = $_SESSION['user_id'];
$group = fetchGroup($user_id);
/*
$user = fetchUser($user_id); // change to be connected by group (?)
$address = fetchAddress($user_id);
$location = fetchLocation($address);
$apt = fetchApt($address);
$house = fetchHouse($address);
$dorm = fetchDorm($address);
$landlord = fetchLandlord($address);
*/
?>

<?php 
if ($_SERVER['REQUEST_METHOD'] == 'POST') 	 // check if addr AND/OR landlord is listed --> if not then add
{
	if (!empty($_POST['aptBtn'])) // ASSUMING ONLY CREATE, NOT UPDATE
	{
		if (!checkAddr($_POST['addr_a'])) { // if it does not exist
			addLocation($_POST['addr_a'], $_POST['bedroom_a'], $_POST['bathroom_a'], $_POST['on_off_a'], $_POST['price_a'], $_POST['extra_cost_a']);
			addApt($_POST['addr_a'], $_POST['elevator'], (int) $_POST['num_of_floors'], $_POST['balcony'], $_POST['pets'], $_POST['smoking']);
			if (!checkLandlord($_POST['name_a'], $_POST['contact_a'])) { // if landlord does not exist
				addLandlord($_POST['name_a'], $_POST['contact_a']);
			}
			$l_row = fetchLandlord($_POST['name_a'], $_POST['contact_a']);
			$l_id = $l_row['l_id'];
			addOwns($l_id, $_POST['addr_a']);
		}
		else { // if it does exist
            updateLocation($_POST['addr_a'], $_POST['bedroom_a'], $_POST['bathroom_a'], $_POST['on_off_a'], $_POST['price_a'], $_POST['extra_cost_a']);
            updateApt($_POST['addr_a'], $_POST['elevator'], (int) $_POST['num_of_floors'], $_POST['balcony'], $_POST['pets'], $_POST['smoking']);

            $l_row = checkOwns($_POST['addr_a']); // original landlord
            $l_id = $l_row ? $l_row['l_id'] : null; // original landlord ID

            $new_l_row = fetchLandlord($_POST['name_a'], $_POST['contact_a']); // if existing, gets requested landlord
            $new_l_id = $new_l_row ? $new_l_row['l_id'] : null; // requested landlord's ID

            if (!checkLandlord($_POST['name_a'], $_POST['contact_a'])) { // if landlord does not exist (new landlord)
                addLandlord($_POST['name_a'], $_POST['contact_a']);
                $new_l_row = fetchLandlord($_POST['name_a'], $_POST['contact_a']);
                $new_l_id = $new_l_row['l_id'];
                addOwns($new_l_id, $_POST['addr_a']);
				removeOwns($l_id, $_POST['addr_a']);
            }
            else if ((int) $l_id != $new_l_id) { // landlord exists in table but not the original landlord (changed)
                addOwns($new_l_id, $_POST['addr_a']);
				removeOwns($l_id, $_POST['addr_a']);
            }
            // else don't change since landlord exists and is the original landlord
        }
		updateGroup($group['g_id'], $_POST['addr_a']);

	}
	else if (!empty($_POST['houseBtn'])) // ASSUMING ONLY CREATE, NOT UPDATE
	{
		if (!checkAddr($_POST['addr_h'])) { // if it does not exist
			addLocation($_POST['addr_h'], $_POST['bedroom_h'], $_POST['bathroom_h'], $_POST['on_off_h'], $_POST['price_h'], $_POST['extra_cost_h']);
			addHouse($_POST['addr_h'], $_POST['yard'], (int) $_POST['stories'], $_POST['porch']);
			if (!checkLandlord($_POST['name_h'], $_POST['contact_h'])) { // if landlord does not exist
				addLandlord($_POST['name_h'], $_POST['contact_h']);
			}
			$l_row = fetchLandlord($_POST['name_h'], $_POST['contact_h']);
			$l_id = $l_row['l_id'];
			addOwns($l_id, $_POST['addr_h']);
		}
		else { // if it does exist
            updateLocation($_POST['addr_h'], $_POST['bedroom_h'], $_POST['bathroom_h'], $_POST['on_off_h'], $_POST['price_h'], $_POST['extra_cost_h']);
			updateHouse($_POST['addr_h'], $_POST['yard'], (int) $_POST['stories'], $_POST['porch']);

            $l_row = checkOwns($_POST['addr_h']); 
            $l_id = $l_row ? $l_row['l_id'] : null;

            $new_l_row = fetchLandlord($_POST['name_h'], $_POST['contact_h']);
            $new_l_id = $new_l_row ? $new_l_row['l_id'] : null; 

            if (!checkLandlord($_POST['name_h'], $_POST['contact_h'])) { // if landlord does not exist (new landlord)
                addLandlord($_POST['name_h'], $_POST['contact_h']);
                $new_l_row = fetchLandlord($_POST['name_h'], $_POST['contact_h']);
                $new_l_id = $new_l_row['l_id'];
                addOwns($new_l_id, $_POST['addr_h']);
				removeOwns($l_id, $_POST['addr_h']);
            }
            else if ((int) $l_id != $new_l_id) { // landlord exists in table but not the original landlord (changed)
                addOwns($new_l_id, $_POST['addr_h']);
				removeOwns($l_id, $_POST['addr_h']);
            }
            // else don't change since landlord exists and is the original landlord
        }
		updateGroup($group['g_id'], $_POST['addr_h']);
	}
	else if (!empty($_POST['dormBtn'])) // ASSUMING ONLY CREATE, NOT UPDATE
	{
		// TAKE OUT LANDLORD --> will have UVA set as landlord
		if (!checkAddr($_POST['addr_d'])) { // if it does not exist
			addLocation($_POST['addr_d'], $_POST['bedroom_d'], $_POST['bathroom_d'], $_POST['on_off_d'], $_POST['price_d'], $_POST['extra_cost_d']);
			addDorm($_POST['addr_d'], $_POST['style'], $$_POST['single_double'], $_POST['kitchen']);

			// set "landlord" for UVA housing
			$name_d = "UVA Housing";
			$contact_d = "434-924-3736";
			$l_row = fetchLandlord($name_d, $contact_d);
			$l_id = $l_row['l_id'];
			addOwns($l_id, $_POST['addr_d']);
		}
		else { // it does exist but needs change
			updateLocation($_POST['addr_d'], $_POST['bedroom_d'], $_POST['bathroom_d'], $_POST['on_off_d'], $_POST['price_d'], $_POST['extra_cost_d']);
			updateDorm($_POST['addr_d'], $_POST['style'], $_POST['single_double'], $_POST['kitchen']);
		}
		updateGroup($group['g_id'], $_POST['addr_d']);
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
        <h2>Create New Location</h2>
		<p class="text text-muted">Select the appropriate location type and fill in the necessary information.</p>

        <!-- Main Content -->
        <div class="col-md-12">
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
                                    <label>Address (Include apartment number or letter)</label>
                                    <input type="text" class="form-control" id='addr_a' name='addr_a' required
										value="<?php if ($location['addr'] !=null) echo $location['addr']; ?>">
                                </div>
                            </div>
							<div class="row mb-3">
                                <div class="col-md-6">
                                    <label>Landlord Name</label>
                                    <input type="text" class="form-control" id='name_a' name='name_a' required
										value="<?php if ($landlord['name'] !=null) echo $location['name']; ?>">
                                </div>
								 <div class="col-md-3">
                                    <label>Landlord's Phone Number</label>
                                    <input type="text" class="form-control" id='contact_a' name='contact_a' required
										value="<?php if ($landlord['contact'] !=null) echo $location['contact'];?>">
                                </div>
								<div class="col-md-3">
                                    <label>On/Off Grounds</label>
                                    <select class="form-control" id='on_off_a' name='on_off_a' required>
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
                                <div class="col-md-4">
                                    <label>Rent</label>
                                    <input type="text" class="form-control" id='price_a' name='price_a'
										value="<?php if ($location['price'] !=null) echo $location['price']; ?>" required/>
                                </div>
                                <div class="col-md-8">
                                    <label>Extra Costs (Ex. unaccounted utilities)</label>
                                    <input type="text" class="form-control" id='extra_cost_a' name='extra_cost_a'
										value="<?php if ($location['extra_cost'] !=null) echo $location['extra_cost']; ?>" required/>
                                </div>
                            </div>
							<div class="row mb-3">
                                <div class="col-md-3">
                                    <label>Bedrooms</label>
                                    <input type="text" class="form-control" id='bedroom_a' name='bedroom_a'
										value="<?php if ($location['bedroom'] !=null) echo $location['bedroom']; ?>" required/>
                                </div>
                                <div class="col-md-3">
                                    <label>Bathrooms</label>
                                    <input type="text" class="form-control" id='bathroom_a' name='bathroom_a'
										value="<?php if ($location['bathroom'] !=null) echo $location['bathroom']; ?>" required/>
                                </div>
								<div class="col-md-3">
                                    <label>Number of Floors</label>
                                    <input type="number" class="form-control" id='num_of_floors' name='num_of_floors'
										value="<?php if ($apt['num_of_floors'] !=null) echo $apt['num_of_floors']; ?>" required/>
                                </div>
								<div class="col-md-3">
                                    <label>Has Elevators?</label>
                                    <select class="form-control" id='elevator' name='elevator' required>
										<option selected></option>
										<option value='1'
											<?php if ($apt['elevator'] == '1') 
												echo ' selected="selected"'?> 
											>
											Yes</option>
										<option value='0'
											<?php if ($apt['elevator'] == '0') 
												echo ' selected="selected"'?> 
											>
											No</option>
                                    </select>
                                </div>
							</div>
							<div class="row mb-3">
								<div class="col-md-3">
                                    <label>Has Balcony?</label>
                                    <select class="form-control" id='balcony' name='balcony' required>
										<option selected></option>
										<option value='1'
											<?php if ($apt['balcony'] == '1') 
												echo ' selected="selected"'?> 
											>
											Yes</option>
										<option value='1'
											<?php if ($apt['balcony'] == '0') 
												echo ' selected="selected"'?> 
											>
											No</option>
                                    </select>
                                </div>
								<div class="col-md-3">
                                    <label>Allows Pets?</label>
                                    <select class="form-control" id='pets' name='pets' required>
										<option selected></option>
										<option value='1'
											<?php if ($apt['pets'] == '1') 
												echo ' selected="selected"'?> 
											>
											Yes</option>
										<option value='0'
											<?php if ($apt['pets'] == '0') 
												echo ' selected="selected"'?> 
											>
											No</option>
                                    </select>
                                </div>
								<div class="col-md-3">
                                    <label>Allows Smoking?</label>
                                    <select class="form-control" id='smoking' name='smoking' required>
										<option selected></option>
										<option value='1'
											<?php if ($apt['smoking'] == '1') 
												echo ' selected="selected"'?> 
											>
											Yes</option>
										<option value='0'
											<?php if ($apt['smoking'] == '0') 
												echo ' selected="selected"'?> 
											>
											No</option>
                                    </select>
                                </div>
                            </div>

							<div class="text-end">
								<input type="submit" class="btn btn-primary" value="CREATE APARTMENT" id="aptBtn" name="aptBtn" />  
                            </div>

                        </form>

                    </div>

                    <!-- TAB 2 CONTENT -->
                    <div class="tab-pane fade" id="tab2" role="tabpanel">
                        <form method="post" action="<?php echo $_SERVER['PHP_SELF'] ?>" onsubmit="return validateInput()">
							<div class="row mb-3">
                                <div class="col-md-12">
                                    <label>Address</label>
                                    <input type="text" class="form-control" id='addr_h' name='addr_h' required
										value="<?php if ($location['addr'] !=null) echo $location['addr']; ?>" 
										onclick="window.location.href='search.php'">
                                </div>
                            </div>
							<div class="row mb-3">
                                <div class="col-md-6">
                                    <label>Landlord Name</label>
                                    <input type="text" class="form-control" id='name_h' name='name_h' required
										value="<?php if ($landlord['name'] !=null) echo $location['name']; ?>">
                                </div>
								 <div class="col-md-3">
                                    <label>Landlord's Phone Number</label>
                                    <input type="text" class="form-control" id='contact_h' name='contact_h' required
										value="<?php if ($landlord['contact'] !=null) echo $landlord['contact']; ?>">
                                </div>
								<div class="col-md-3">
                                    <label>On/Off Grounds</label>
                                    <select class="form-control" id='on_off_h' name='on_off_h' required>
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
                                <div class="col-md-4">
                                    <label>Rent</label>
                                    <input type="text" class="form-control" id='price_h' name='price_h'
										value="<?php if ($location['price'] !=null) echo $location['price']; ?>" required/>
                                </div>
                                <div class="col-md-8">
                                    <label>Extra Costs (Ex. unaccounted utilities)</label>
                                    <input type="text" class="form-control" id='extra_cost_h' name='extra_cost_h'
										value="<?php if ($location['extra_cost'] !=null) echo $location['extra_cost']; ?>" required/>
                                </div>
                            </div>
							<div class="row mb-3">
                                <div class="col-md-3">
                                    <label>Bedrooms</label>
                                    <input type="text" class="form-control" id='bedroom_h' name='bedroom_h'
										value="<?php if ($location['bedroom'] !=null) echo $location['bedroom']; ?>" required/>
                                </div>
                                <div class="col-md-3">
                                    <label>Bathrooms</label>
                                    <input type="text" class="form-control" id='bathroom_h' name='bathroom_h'
										value="<?php if ($location['bathroom'] !=null) echo $location['bathroom']; ?>" required/>
                                </div>
								<div class="col-md-2">
                                    <label>Number of Stories</label>
                                    <input type="number" class="form-control" id='stories' name='stories'
										value="<?php if ($house['stories'] !=null) echo $house['stories']; ?>" required/>
                                </div>
								<div class="col-md-2">
                                    <label>Has a Yard?</label>
                                    <select class="form-control" id='yard' name='yard' required>
										<option selected></option>
										<option value='1'
											<?php if ($house['yard'] == '1') 
												echo ' selected="selected"'?> 
											>
											Yes</option>
										<option value='0'
											<?php if ($house['yard'] == '0') 
												echo ' selected="selected"'?> 
											>
											No</option>
                                    </select>
                                </div>
								<div class="col-md-2">
                                    <label>Has a Porch?</label>
                                    <select class="form-control" id='porch' name='porch' required>
										<option selected></option>
										<option value='1'
											<?php if ($house['porch'] == '1') 
												echo ' selected="selected"'?> 
											>
											Yes</option>
										<option value='0'
											<?php if ($house['porch'] == '0') 
												echo ' selected="selected"'?> 
											>
											No</option>
                                    </select>
                                </div>
							</div>

							<div class="text-end">
								<input type="submit" class="btn btn-primary" value="CREATE HOUSE" id="houseBtn" name="houseBtn" />  
                            </div>
						</form>
                    </div>

                    <!-- TAB 3 CONTENT -->
                    <div class="tab-pane fade" id="tab3" role="tabpanel">
                        <form method="post" action="<?php echo $_SERVER['PHP_SELF'] ?>" onsubmit="return validateInput()">
							<div class="row mb-3">
                                <div class="col-md-12">
                                    <label>Address</label>
                                    <input type="text" class="form-control" id='addr_d' name='addr_d' required
										value="<?php if ($location['addr'] !=null) echo $location['addr']; ?>">
                                </div>
                            </div>
							<div class="row mb-3">
                                <div class="col-md-4">
                                    <label>Rent</label>
                                    <input type="text" class="form-control" id='pric_d' name='price_d'
										value="<?php if ($location['price'] !=null) echo $location['price']; ?>" required/>
                                </div>
                                <div class="col-md-8">
                                    <label>Extra Costs (Ex. unaccounted utilities)</label>
                                    <input type="text" class="form-control" id='extra_cost_d' name='extra_cost_d'
										value="<?php if ($location['extra_cost'] !=null) echo $location['extra_cost']; ?>" required/>
                                </div>
                            </div>
							<div class="row mb-3">
                                <div class="col-md-4">
                                    <label>Bedrooms</label>
                                    <input type="text" class="form-control" id='bedroom_d' name='bedroom_d'
										value="<?php if ($location['bedroom'] !=null) echo $location['bedroom']; ?>" required/>
                                </div>
                                <div class="col-md-4">
                                    <label>Bathrooms</label>
                                    <input type="text" class="form-control" id='bathroom_d' name='bathroom_d'
										value="<?php if ($location['bathroom'] !=null) echo $location['bathroom']; ?>" required/>
                                </div>
								<div class="col-md-4">
                                    <label>On/Off Grounds</label>
                                    <select class="form-control" id='on_off_d' name='on_off_d' required>
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
								<div class="col-md-4">
                                    <label>Style/Type</label>
                                    <select class="form-control" id='style' name='style' required>
										<option selected></option>
										<option value='motel'
											<?php if ($dorm['style'] == 'motel') 
												echo ' selected="selected"'?> 
											>
											Motel</option>
										<option value='hall'
											<?php if ($dorm['style'] == 'hall') 
												echo ' selected="selected"'?> 
											>
											Hall</option>
										<option value='suite'
											<?php if ($dorm['style'] == 'suite') 
												echo ' selected="selected"'?> 
											>
											Suite</option>
                                    </select>
                                </div>
								<div class="col-md-4">
                                    <label>Single or Double?</label>
                                    <select class="form-control" id='single_double' name='single_double' required>
										<option selected></option>
										<option value='Single'
											<?php if ($dorm['single_double'] == 'Single') 
												echo ' selected="selected"'?> 
											>
											Single</option>
										<option value='Double'
											<?php if ($dorm['single_double'] == 'Double') 
												echo ' selected="selected"'?> 
											>
											Double</option>
                                    </select>
                                </div>
								<div class="col-md-4">
                                    <label>Has Kitchen?</label>
                                    <select class="form-control" id='kitchen' name='kitchen' required>
										<option selected></option>
										<option value='1'
											<?php if ($dorm['kitchen'] == '1') 
												echo ' selected="selected"'?> 
											>
											Yes</option>
										<option value='0'
											<?php if ($dorm['kitchen'] == '0') 
												echo ' selected="selected"'?> 
											>
											No</option>
                                    </select>
                                </div>
                            </div>
							<div class="text-end">
								<input type="submit" class="btn btn-primary" value="CREATE DORM" id="dormBtn" name="dormBtn" />  
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
