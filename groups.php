<?php
require('connect-db.php');
require('groups-db.php');
include('header.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    if (isset($_POST['createGroupBtn'])) {
        $status = $_POST['group_status'] ?? 'Searching';
        $size   = $_POST['num_of_people'] ?? 1; // This is the Target Capacity
        $addr   = $_POST['addr'] ?? null;
        $type   = $_POST['property_type'] ?? 'none';
        
        $lname  = $_POST['landlord_name'] ?? '';
        $lemail = $_POST['landlord_email'] ?? '';
        
        $details = [
            'bedroom' => !empty($_POST['bedroom']) ? $_POST['bedroom'] : 1,
            'bathroom'=> !empty($_POST['bathroom']) ? $_POST['bathroom'] : 1,
            'price'   => !empty($_POST['price']) ? $_POST['price'] : 0,
            'elevator' => isset($_POST['elevator']) ? 1 : 0,
            'floors'   => !empty($_POST['num_of_floors']) ? $_POST['num_of_floors'] : 1,
            'balcony'  => isset($_POST['balcony']) ? 1 : 0,
            'pets'     => isset($_POST['pets']) ? 1 : 0,
            'smoking'  => isset($_POST['smoking']) ? 1 : 0,
            'yard'     => isset($_POST['yard']) ? 1 : 0,
            'stories'  => !empty($_POST['stories']) ? $_POST['stories'] : 1,
            'porch'    => isset($_POST['porch']) ? 1 : 0,
            'style'         => $_POST['dorm_style'] ?? 'Hall',
            'single_double' => $_POST['single_double'] ?? 'Single',
            'kitchen'       => isset($_POST['kitchen']) ? 1 : 0
        ];

        createGroupWithProperty($user_id, $status, $addr, $size, $type, $details, $lname, $lemail);
        
        header("Location: groups.php");
        exit();
    }

    if (isset($_POST['leaveGroupBtn'])) {
        leaveGroup($user_id, $_POST['g_id']);
        header("Location: groups.php");
        exit();
    }
}

$userGroup = fetchUserGroups($user_id);
$hasGroup = !empty($userGroup);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Group Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
    <h2>Group Dashboard</h2>
    <div class="p-4 border rounded shadow-sm bg-white">

        <?php if (!$hasGroup): ?>
            <div class="text-center py-5">
                <h4 class="mb-3">You are not currently in a group.</h4>
                <p class="text-muted">Create a group and add your property details to get started.</p>
                <button type="button" class="btn btn-primary btn-lg" data-bs-toggle="modal" data-bs-target="#createGroupModal">
                    Create New Group
                </button>
            </div>

        <?php else: ?>
            <?php 
                $groupRow = $userGroup[0]; 
                $group = fetchGroupDetails($groupRow['g_id']); 
                $members = fetchGroupMembers($groupRow['g_id']); 

                // --- LOGIC UPDATED FOR TARGET SIZE ---
                $currentSize = count($members);        // Actual people in the group
                $targetSize = (int)$group['num_of_people']; // The intended capacity

                // If the group is full (Current >= Target) and not yet marked Closed
                if ($group['status'] !== 'Closed' && $currentSize >= $targetSize) {
                    updateGroupStatus($group['g_id'], 'Closed');
                    $group['status'] = 'Closed'; // Reflect change immediately
                }
                
                // Optional: If you want to re-open if someone leaves
                // if ($group['status'] == 'Closed' && $currentSize < $targetSize) {
                //     updateGroupStatus($group['g_id'], 'Searching');
                //     $group['status'] = 'Searching';
                // }
            ?>
            <div class="row">
                <div class="col-md-7">
                    <h4>Group #<?php echo $group['g_id']; ?></h4>
                    
                    <p>
                        <strong>Status:</strong> 
                        <span class="badge <?php echo ($group['status'] == 'Closed') ? 'bg-danger' : 'bg-info text-dark'; ?>">
                            <?php echo htmlspecialchars($group['status']); ?>
                        </span>
                    </p>
                    
                    <p class="mb-1"><strong>Capacity:</strong> <?php echo $currentSize . " / " . $targetSize; ?> Members</p>
                    <div class="progress mb-3" style="height: 15px;">
                        <div class="progress-bar <?php echo ($currentSize >= $targetSize) ? 'bg-success' : 'bg-primary'; ?>" 
                             role="progressbar" 
                             style="width: <?php echo ($targetSize > 0) ? ($currentSize / $targetSize) * 100 : 0; ?>%;">
                        </div>
                    </div>

                    <p><strong>Address:</strong> <?php echo htmlspecialchars($group['addr'] ?? 'No Address Assigned'); ?></p>
                    
                    <hr>
                    <h5>Property Management</h5>
                    <p class="text-muted">Need to edit the house/apartment details or add a new one?</p>
                    
                    <a href="location.php" class="btn btn-dark">
                        Manage Location Details
                    </a>

                </div>
                <div class="col-md-5 border-start">
                    <h6>Current Members</h6>
                    <ul class="list-group mb-3">
                        <?php foreach ($members as $member): ?>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <?php echo htmlspecialchars($member['stu_name']); ?>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                    <form method="POST" class="mt-4">
                        <input type="hidden" name="g_id" value="<?php echo $group['g_id']; ?>">
                        <button type="submit" name="leaveGroupBtn" class="btn btn-danger w-100">Leave Group</button>
                    </form>
                </div>
            </div>
        <?php endif; ?>

    </div>
</div>

<div class="modal fade" id="createGroupModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title">Create Group & Property</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form method="POST">
          <div class="modal-body">
                
                <h6 class="text-primary border-bottom pb-2">Group Details</h6>
                <div class="row mb-3">
                    <div class="col">
                        <label class="form-label">Group Status</label>
                        <select class="form-select" name="group_status">
                            <option value="Searching">Searching</option>
                            <option value="Closed">Closed</option>
                        </select>
                    </div>
                    <div class="col">
                        <label class="form-label">Target Group Size</label>
                        <input type="number" class="form-control" name="num_of_people" value="1" min="1" placeholder="Total Capacity">
                        <div class="form-text">How many total people do you want in this group?</div>
                    </div>
                </div>

                <h6 class="text-primary border-bottom pb-2 mt-4">Landlord Details</h6>
                <div class="row mb-3">
                    <div class="col">
                        <label class="form-label">Landlord Name</label>
                        <input type="text" class="form-control" name="landlord_name" placeholder="Name">
                    </div>
                    <div class="col">
                        <label class="form-label">Landlord Email</label>
                        <input type="email" class="form-control" name="landlord_email" placeholder="Email">
                    </div>
                </div>

                <h6 class="text-primary border-bottom pb-2 mt-4">Property Address</h6>
                <div class="mb-3">
                    <label class="form-label">Address (Required)</label>
                    <input type="text" class="form-control" name="addr" placeholder="e.g. 123 Main St..." required>
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Property Type</label>
                    <select class="form-select" id="propertyTypeSelector" name="property_type" onchange="togglePropertyFields()">
                        <option value="none">Select Type...</option>
                        <option value="apartment">Apartment</option>
                        <option value="house">House</option>
                        <option value="dorm">Dorm</option>
                    </select>
                </div>

                <div id="genericFields" style="display:none;" class="p-3 border rounded mb-3 bg-light">
                    <h6>General Info</h6>
                    <div class="row g-3">
                        <div class="col-md-4"><label class="form-label">Bedrooms</label><input type="number" class="form-control" name="bedroom"></div>
                        <div class="col-md-4"><label class="form-label">Bathrooms</label><input type="number" class="form-control" name="bathroom"></div>
                        <div class="col-md-4"><label class="form-label">Monthly Price ($)</label><input type="number" class="form-control" name="price"></div>
                    </div>
                </div>

                <div id="aptFields" style="display:none;" class="p-3 border rounded mb-3">
                    <h6>Apartment Specifics</h6>
                    <div class="mb-3"><label class="form-label">Total Floors</label><input type="number" class="form-control" name="num_of_floors"></div>
                    <div class="d-flex gap-3 flex-wrap">
                        <div class="form-check"><input class="form-check-input" type="checkbox" name="elevator" value="1"><label class="form-check-label">Elevator</label></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" name="balcony" value="1"><label class="form-check-label">Balcony</label></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" name="pets" value="1"><label class="form-check-label">Pets Allowed</label></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" name="smoking" value="1"><label class="form-check-label">Smoking Allowed</label></div>
                    </div>
                </div>

                <div id="houseFields" style="display:none;" class="p-3 border rounded mb-3">
                    <h6>House Specifics</h6>
                    <div class="mb-3"><label class="form-label">Stories</label><input type="number" class="form-control" name="stories"></div>
                    <div class="d-flex gap-3">
                        <div class="form-check"><input class="form-check-input" type="checkbox" name="yard" value="1"><label class="form-check-label">Yard</label></div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" name="porch" value="1"><label class="form-check-label">Porch</label></div>
                    </div>
                </div>

                <div id="dormFields" style="display:none;" class="p-3 border rounded mb-3">
                    <h6>Dorm Specifics</h6>
                    <div class="row mb-3">
                        <div class="col">
                            <label class="form-label">Style</label>
                            <select class="form-select" name="dorm_style">
                                <option value="Hall">Hall</option>
                                <option value="Suite">Suite</option>
                            </select>
                        </div>
                        <div class="col">
                            <label class="form-label">Room Type</label>
                            <select class="form-select" name="single_double">
                                <option value="Single">Single</option>
                                <option value="Double">Double</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="kitchen" value="1"> <label class="form-check-label">Has Kitchen</label>
                    </div>
                </div>

          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="submit" name="createGroupBtn" class="btn btn-primary">Create Group</button>
          </div>
      </form>
    </div>
  </div>
</div>

<script>
function togglePropertyFields() {
    var type = document.getElementById('propertyTypeSelector').value;
    
    document.getElementById('genericFields').style.display = 'none';
    document.getElementById('aptFields').style.display = 'none';
    document.getElementById('houseFields').style.display = 'none';
    document.getElementById('dormFields').style.display = 'none';

    if (type !== 'none') {
        document.getElementById('genericFields').style.display = 'block';
    }

    if (type === 'apartment') {
        document.getElementById('aptFields').style.display = 'block';
    } else if (type === 'house') {
        document.getElementById('houseFields').style.display = 'block';
    } else if (type === 'dorm') {
        document.getElementById('dormFields').style.display = 'block';
    }
}
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>