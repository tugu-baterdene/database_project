<?php
session_start();
include('header.php');   // your navbar here
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

                <h4 class="text-center">John Doe</h4>
                <p class="text-center text-muted">CEO of Company</p>

                <hr>

                <div class="details-list">
                    <div class="d-flex justify-content-between py-2">
                        <span>Computing ID</span><span>rgp3qv</span>
                    </div>
                    <div class="d-flex justify-content-between py-2">
                        <span>Detail 2</span><span>40</span>
                    </div>
                    <div class="d-flex justify-content-between py-2">
                        <span>Detail 3</span><span>50</span>
                    </div>
                </div>

                <div class="text-center mt-4">
                    <button class="btn btn-primary w-100">View Public Profile</button>
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
                            Settings
                        </button>
                    </li>
                </ul>

                <!-- TAB CONTENT -->
                <div class="tab-content">

                    <!-- TAB 1: ACCOUNT INFORMATION -->
                    <div class="tab-pane fade show active" id="account" role="tabpanel">

                        <form>
							<div class="row mb-3">
                                <div class="col-md-6">
                                    <label>Name</label>
                                    <input type="text" class="form-control" value="John Doe">
                                </div>
                                <div class="col-md-6">
                                    <label>Phone Number</label>
                                    <input type="text" class="form-control" value="123-456-7890">
                                </div>
                            </div>


                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label>Major</label>
                                    <input type="text" class="form-control" value="Computer Science">
                                </div>
                                <div class="col-md-6">
                                    <label>School Year</label>
                                    <select class="form-control">
                                        <option>1st Year</option>
                                        <option>2nd Year</option>
                                        <option>3rd Year</option>
										<option>4th Year</option>
										<option>Graduate Student</option>
										<option>Other</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label>Password</label>
                                    <input type="password" class="form-control" value="password123">
                                </div>
                                <div class="col-md-6">
                                    <label>Verify Password</label>
                                    <input type="password" class="form-control" value="password123">
                                </div>
                            </div>

                            <div class="row mb-4">
                                <div class="col-md-12">
                                    <label>Bio</label>
                                    <input type="text" class="form-control" value="hello :)">
                                </div>
                            </div>

                            <div class="text-end">
                                <button class="btn btn-primary">SAVE</button>
                            </div>

                        </form>

                    </div>

                    <!-- TAB 2 CONTENT -->
                    <div class="tab-pane fade" id="tab2" role="tabpanel">
                        <form>
							<div class="row mb-3">
                                <div class="col-md-6">
                                    <label>On/Off Grounds</label>
                                    <select class="form-control">
                                        <option>On</option>
                                        <option>Off</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label>Pets</label>
                                    <select class="form-control">
                                        <option>Yes</option>
                                        <option>No</option>
                                    </select>
                                </div>
                            </div>
							<div class="row mb-3">
                                <div class="col-md-6">
                                    <label>Drinking</label>
                                    <select class="form-control">
                                        <option>Yes</option>
                                        <option>No</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label>Smoking</label>
                                    <select class="form-control">
                                        <option>Yes</option>
                                        <option>No</option>
                                    </select>
                                </div>
                            </div>
							<div class="row mb-3">
                                <div class="col-md-6">
                                    <label>Number of Roommates</label>
                                    <input type="text" class="form-control" value="4+">
                                </div>
                                <div class="col-md-6">
                                    <label>Budget</label>
                                    <input type="text" class="form-control" value="$1200">
                                </div>
                            </div>
							<div class="row mb-3">
                                <div class="col-md-12">
                                    <label>Sleeping Habits/Preferences</label>
                                    <input type="text" class="form-control" value="light sleeper :/">
                                </div>
                            </div>

							<div class="text-end">
                                <button class="btn btn-primary">SAVE</button>
                            </div>
						</form>
                    </div>

                    <!-- TAB 3 CONTENT -->
                    <div class="tab-pane fade" id="tab3" role="tabpanel">
                        <h4>Tab 3 Content</h4>
                        <p>This is where Tab 3 information goes.</p>
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
