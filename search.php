<?php
require('connect-db.php');
require('search-db.php');
include('header.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
} 

$user_id = $_SESSION['user_id'];
$user = fetchUser($user_id);

$results = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $results = searchUsers(
        $_POST['name_filter'],
        $_POST['year_filter'],
        $_POST['major_filter'],
        $_POST['status_filter']
    );
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Roommate Search</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>

<body>
<div class="container mt-4">
    <h2>Search for Roommates</h2>
    <p class="text-muted">Filter by the criteria below.</p>

    <form method="POST" class="row g-3">

        <div class="col-md-4">
            <label class="form-label">Name</label>
            <input type="text" name="name_filter" class="form-control">
        </div>

        <div class="col-md-2">
            <label class="form-label">Year</label>
            <select name="year_filter" class="form-select">
                <option value="">Any</option>
                <option value="1">1st year</option>
                <option value="2">2nd year</option>
                <option value="3">3rd year</option>
                <option value="4">4th year</option>
            </select>
        </div>

        <div class="col-md-3">
            <label class="form-label">Major</label>
            <input type="text" name="major_filter" class="form-control">
        </div>

        <div class="col-md-3">
            <label class="form-label">Status</label>
            <select name="status_filter" class="form-select">
                <option value="">Any</option>
                <option value="searching">Searching for roommates</option>
                <option value="found">Roommate found</option>
            </select>
        </div>

        <div class="col-12 d-grid mt-2">
            <button class="btn btn-dark" type="submit">Search</button>
        </div>

    </form>

    <hr/>

    
    <?php if (!empty($results)): ?>
        <h4>Results</h4>
        <table class="table table-bordered mt-3">
            <thead class="table-secondary">
                <tr>
                    <th>Name</th>
                    <th>Major</th>
                    <th>Year</th>
                    <th>Status</th>
                    <th>Bio</th>
                </tr>
            </thead>

            <tbody>
            <?php foreach ($results as $user): ?>
                <tr>
                    <td><?php echo htmlspecialchars($user['stu_name']); ?></td>
                    <td><?php echo htmlspecialchars($user['major']); ?></td>
                    <td><?php echo htmlspecialchars($user['school_year']); ?></td>
                    <td><?php echo htmlspecialchars($user['status']); ?></td>
                    <td><?php echo htmlspecialchars($user['bio']); ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>

    <?php elseif ($_SERVER["REQUEST_METHOD"] == "POST"): ?>
        <p>No results found.</p>
    <?php endif; ?>

</div>
</body>
</html>
