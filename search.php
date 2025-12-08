<?php
require_once('connect-db.php');
require_once('search-db.php');
include('header.php');

$results = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $results = searchUsers($_POST);
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
    <p class="text-muted">Filter by any criteria below. (Click Search to See All)</p>

    <form method="POST" class="row g-3">

        <div class="col-md-4">
            <label class="form-label">Name</label>
            <input type="text" name="name" class="form-control">
        </div>

        <div class="col-md-2">
            <label class="form-label">Year</label>
            <select name="year" class="form-select">
                <option value="">Any</option>
                <option value="1">1st year</option>
                <option value="2">2nd year</option>
                <option value="3">3rd year</option>
                <option value="4">4th year</option>
            </select>
        </div>

        <div class="col-md-3">
            <label class="form-label">Major</label>
            <input type="text" name="major" class="form-control">
        </div>

        <div class="col-md-3">
            <label class="form-label">Status</label>
            <select name="status" class="form-select">
                <option value="">Any</option>
                <option value="searching">Searching for roommates</option>
                <option value="found">Roommate found</option>
            </select>
        </div>

        <hr class="mt-4">

        <h4>Living Preferences</h4>

        <div class="col-md-3">
            <label class="form-label">On/Off Grounds</label>
            <select name="on_off" class="form-select">
                <option value="">Any</option>
                <option value="On">On</option>
                <option value="Off">Off</option>
            </select>
        </div>

        <div class="col-md-3">
            <label class="form-label">Max Budget ($)</label>
            <input type="number" name="budget" class="form-control">
        </div>


        <div class="col-md-2">
            <label class="form-label">Drinking</label>
            <select name="drinking" class="form-select">
                <option value="">Any</option>
                <option value="Yes">Yes</option>
                <option value="No">No</option>
            </select>
        </div>

        <div class="col-md-2">
            <label class="form-label">Smoking</label>
            <select name="smoking" class="form-select">
                <option value="">Any</option>
                <option value="Yes">Yes</option>
                <option value="No">No</option>
            </select>
        </div>

        <div class="col-md-2">
            <label class="form-label">Pets</label>
            <select name="pets" class="form-select">
                <option value="">Any</option>
                <option value="Yes">Yes</option>
                <option value="No">No</option>
            </select>
        </div>

        <hr class="mt-4">


        <div class="col-12 d-grid mt-3">
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
                <th>Year</th>
                <th>Major</th>
                <th>Status</th>
                <th>Budget</th>
                <th>On/Off Grounds</th>
                <th>Bedrooms</th>
                <th>Bathrooms</th>
            </tr>
            </thead>

            <tbody>
            <?php foreach ($results as $row): ?>
                <tr>
                    <td>
                        <a href="public_profile.php?comp_id=<?php echo $row['comp_id']; ?>">
                            <?= htmlspecialchars($row['stu_name']) ?>
                        </a>
                    </td>
                    <td><?= htmlspecialchars($row['school_year']) ?></td>
                    <td><?= htmlspecialchars($row['major']) ?></td>
                    <td><?= htmlspecialchars($row['status']) ?></td>
                    <td><?= htmlspecialchars($row['budget']) ?></td>
                    <td><?= htmlspecialchars($row['on_off_grounds']) ?></td>
                    <td><?= htmlspecialchars($row['bedroom']) ?></td>
                    <td><?= htmlspecialchars($row['bathroom']) ?></td>
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
