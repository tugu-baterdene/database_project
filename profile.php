<?php
// Example: This is where you'd load existing user data from a database.
// $user = fetch_user($_SESSION['id']);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Profile</title>
    <link rel="stylesheet" href="profile.css">
</head>
<body>

<header>
    <div>Roommate Connections</div>
    <button class="save-btn">SAVE</button>
</header>

<div class="container">

    <!-- LEFT COLUMN -->
    <div class="left-column">
        <div class="profile-pic"></div>

        <div class="input-group">
            <label>First Name</label>
            <input type="text" name="first_name">
        </div>

        <div class="input-group">
            <label>Last Name</label>
            <input type="text" name="last_name">
        </div>

        <div class="input-group">
            <label>Computing ID</label>
            <input type="text" name="comp_id">
        </div>

        <div class="input-group">
            <label>Password</label>
            <input type="password" name="password">
        </div>
    </div>

    <!-- RIGHT COLUMN -->
    <div class="right-column">

        <div class="inline-inputs">
            <div>
                <label>Phone</label>
                <input type="text" name="phone">
            </div>
            <div>
                <label>Email</label>
                <input type="text" style="width: 350px;" name="email">
            </div>
        </div>

        <div class="inline-inputs">
            <div>
                <label>Age</label>
                <input type="number" name="age">
            </div>
            <div>
                <label>Year</label>
                <input type="text" name="year">
            </div>
            <div style="flex:1;">
                <label>Major</label>
                <input type="text" style="width: 100%;" name="major">
            </div>
            <div>
                <label>Num of Roommates</label>
                <input type="number" name="num_roommates">
            </div>
        </div>

        <label>Status</label>
        <div class="status-buttons">
            <button>OPEN</button>
            <button>IN PROGRESS</button>
            <button>CLOSED</button>
        </div>

        <label>Bio</label>
        <textarea name="bio"></textarea>

        <label>Preferences</label>
        <textarea class="preferences-box" name="preferences"></textarea>
    </div>

</div>

</body>
</html>
