<?php
require('connect-db.php');
require('groups-db.php');
include('header.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$userGroup = fetchUserGroups($user_id);
$hasGroup = !empty($userGroup);
$userGroupId = $hasGroup ? $userGroup[0]['g_id'] : null;

// Fetch all groups
$allGroups = fetchAllGroups();

?>

<?php 
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!empty($_POST['createGroupBtn'])) {
        $status = !empty($_POST['group_status']) ? $_POST['group_status'] : 'Searching';
        createGroup($user_id, $status);
        header("Location: groups.php");
        exit();
    }
    if (!empty($_POST['leaveGroupBtn'])) {
        if (!empty($_POST['g_id'])) {
            leaveGroup($user_id, $_POST['g_id']);
            header("Location: groups.php");
            exit();
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
