<?php
// Mock session and database for testing
session_start();
$_SESSION['user_id'] = 'testuser123';

// Mock database functions for testing
$mockGroups = [
    ['g_id' => 1, 'status' => 'owner'],
];

$mockGroupDetails = [
    1 => ['g_id' => 1, 'status' => 'Searching', 'num_of_people' => 3, 'addr' => '123 Main St, Apt 4B'],
    2 => ['g_id' => 2, 'status' => 'Closed', 'num_of_people' => 4, 'addr' => null],
    3 => ['g_id' => 3, 'status' => 'Searching', 'num_of_people' => 2, 'addr' => '456 University Ave'],
];

$mockMembers = [
    1 => [
        ['comp_id' => 'testuser123', 'stu_name' => 'You', 'status' => 'owner'],
        ['comp_id' => 'user2', 'stu_name' => 'John Smith', 'status' => 'member'],
        ['comp_id' => 'user3', 'stu_name' => 'Jane Doe', 'status' => 'member'],
    ],
    2 => [
        ['comp_id' => 'user4', 'stu_name' => 'Bob Johnson', 'status' => 'owner'],
        ['comp_id' => 'user5', 'stu_name' => 'Alice Johnson', 'status' => 'member'],
        ['comp_id' => 'user6', 'stu_name' => 'Charlie Brown', 'status' => 'member'],
        ['comp_id' => 'user7', 'stu_name' => 'Diana Prince', 'status' => 'member'],
    ],
    3 => [
        ['comp_id' => 'user8', 'stu_name' => 'Eve Wilson', 'status' => 'owner'],
        ['comp_id' => 'user9', 'stu_name' => 'Frank Castle', 'status' => 'member'],
    ],
];

function fetchUserGroups($user_id)
{
    global $mockGroups;
    return $mockGroups;
}

function fetchGroupMembers($g_id)
{
    global $mockMembers;
    return $mockMembers[$g_id] ?? [];
}

function fetchGroupDetails($g_id)
{
    global $mockGroupDetails;
    return $mockGroupDetails[$g_id] ?? null;
}

function createGroup($user_id, $group_name)
{
    echo "<p class='alert alert-info'>Mock: Group '$group_name' created!</p>";
    return true;
}

function leaveGroup($user_id, $g_id)
{
    echo "<p class='alert alert-info'>Mock: Left group $g_id</p>";
    return true;
}

function deleteGroup($g_id)
{
    echo "<p class='alert alert-info'>Mock: Deleted group $g_id</p>";
    return true;
}

$user_id = $_SESSION['user_id'];
$userGroup = fetchUserGroups($user_id);
$hasGroup = !empty($userGroup);
$userGroupId = $hasGroup ? $userGroup[0]['g_id'] : null;

// Mock all groups for testing
$mockAllGroups = [
    ['g_id' => 1, 'status' => 'Searching', 'num_of_people' => 3, 'addr' => '123 Main St, Apt 4B'],
    ['g_id' => 2, 'status' => 'Closed', 'num_of_people' => 4, 'addr' => null],
    ['g_id' => 3, 'status' => 'Searching', 'num_of_people' => 2, 'addr' => '456 University Ave'],
];

function fetchAllGroups() {
    global $mockAllGroups;
    return $mockAllGroups;
}

?>

<?php 
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!empty($_POST['createGroupBtn'])) {
        $status = !empty($_POST['group_status']) ? $_POST['group_status'] : 'Searching';
        createGroup($user_id, $status);
        // In real app, would redirect
    }
    if (!empty($_POST['leaveGroupBtn'])) {
        if (!empty($_POST['g_id'])) {
            leaveGroup($user_id, $_POST['g_id']);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Groups</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="groups.css">
</head>
<body>
    <div style="background: #232d4b; color: white; padding: 15px; margin-bottom: 30px;">
        <div class="container">
            <h5 style="margin: 0;">Roommate Connection (Test Mode)</h5>
            <small>Using mock data - database connection not available locally</small>
        </div>
    </div>

    <div class="container groups-container my-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>My Groups</h2>
            <a href="location.php" class="btn btn-success">Create a New Location</a>
        </div>

        <!-- Create Group Form -->
        <div class="create-group-form">
            <h5>Create a New Group</h5>
            <form method="POST">
                <div class="mb-3">
                    <label for="group_status" class="form-label">Group Status</label>
                    <select class="form-control" id="group_status" name="group_status" required>
                        <option value="Searching">Searching for Members</option>
                        <option value="Closed">Closed to New Members</option>
                    </select>
                </div>
                <button type="submit" name="createGroupBtn" class="btn btn-primary">Create Group</button>
            </form>
        </div>

        <!-- Groups List -->
        <?php if ($hasGroup): ?>
            <h3 class="mb-4 mt-5">Your Group</h3>
            <?php $groupRow = $userGroup[0]; ?>
            <?php $group = fetchGroupDetails($groupRow['g_id']); ?>
            <?php $members = fetchGroupMembers($groupRow['g_id']); ?>
            <?php $memberCount = count($members); ?>
            
            <div class="group-card">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <h5>Group ID: <?php echo $group['g_id']; ?></h5>
                        <p class="text-muted mb-2">
                            <strong>Status:</strong> <?php echo htmlspecialchars($group['status']); ?> | 
                            <strong># of People:</strong> <?php echo $memberCount; ?>
                        </p>
                        <?php if ($group['addr']): ?>
                            <p class="text-muted mb-0"><strong>Location:</strong> <?php echo htmlspecialchars($group['addr']); ?></p>
                        <?php else: ?>
                            <p class="text-warning mb-0"><strong>Location:</strong> Not yet assigned</p>
                        <?php endif; ?>
                    </div>
                    <span class="badge bg-info"><?php echo htmlspecialchars($groupRow['status']); ?></span>
                </div>

                <!-- Members Section -->
                <div class="mb-4">
                    <h6 class="mb-3">Members (<?php echo $memberCount; ?>)</h6>
                    <div class="members-list">
                        <?php if (!empty($members)): ?>
                            <?php foreach ($members as $member): ?>
                                <div class="member-item">
                                    <div>
                                        <span class="member-name"><?php echo htmlspecialchars($member['stu_name']); ?></span>
                                    </div>
                                    <span class="member-status"><?php echo htmlspecialchars($member['status']); ?></span>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p class="text-muted">No members in this group yet.</p>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Action Buttons -->
                <form method="POST" class="d-inline">
                    <input type="hidden" name="g_id" value="<?php echo $group['g_id']; ?>">
                    <a href="location.php" class="btn btn-sm btn-primary btn-action">Add/Update Location</a>
                    <button type="submit" name="leaveGroupBtn" class="btn btn-sm btn-warning btn-action">Leave Group</button>
                </form>
            </div>
        <?php else: ?>
            <div class="alert alert-info mb-4">
                <strong>You are not part of any group yet.</strong> Create a group above to get started!
            </div>
        <?php endif; ?>

        <!-- All Other Groups -->
        <?php $allGroups = fetchAllGroups(); ?>
        <?php if (!empty($allGroups)): ?>
            <h3 class="mb-4 mt-5">All Groups</h3>
            <?php foreach ($allGroups as $groupData): ?>
                <?php if ($hasGroup && $groupData['g_id'] == $userGroupId): ?>
                    <?php continue; ?>
                <?php endif; ?>
                
                <?php $group = fetchGroupDetails($groupData['g_id']); ?>
                <?php $members = fetchGroupMembers($groupData['g_id']); ?>
                <?php $memberCount = count($members); ?>
                
                <div class="group-card">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <h5>Group ID: <?php echo $group['g_id']; ?></h5>
                            <p class="text-muted mb-2">
                                <strong>Status:</strong> <?php echo htmlspecialchars($group['status']); ?> | 
                                <strong># of People:</strong> <?php echo $memberCount; ?>
                            </p>
                            <?php if ($group['addr']): ?>
                                <p class="text-muted mb-0"><strong>Location:</strong> <?php echo htmlspecialchars($group['addr']); ?></p>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Members Section -->
                    <div class="mb-4">
                        <h6 class="mb-3">Members (<?php echo $memberCount; ?>)</h6>
                        <div class="members-list">
                            <?php if (!empty($members)): ?>
                                <?php foreach ($members as $member): ?>
                                    <div class="member-item">
                                        <div>
                                            <span class="member-name"><?php echo htmlspecialchars($member['stu_name']); ?></span>
                                        </div>
                                        <span class="member-status"><?php echo htmlspecialchars($member['status']); ?></span>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <p class="text-muted">No members in this group yet.</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
