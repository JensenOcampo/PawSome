<?php
include 'config.php';

// Sanitize Input Function
function sanitize($inputs)
{
    return htmlspecialchars(stripslashes(trim($inputs)));
}

// Initialize Variables
$schedule = isset($_GET['schedule']) ? sanitize($_GET['schedule']) : '';
$errors = [];
$successMessage = '';

// Handle Form Submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $ownername = sanitize($_POST['owner_name'] ?? '');
    $contact = sanitize($_POST['contact'] ?? '');
    $email = sanitize($_POST['email'] ?? '');
    $address = sanitize($_POST['address'] ?? '');
    $category = sanitize($_POST['category_id'] ?? '');
    $breed = sanitize($_POST['breed'] ?? '');
    $age = sanitize($_POST['age'] ?? '');
    $service = isset($_POST['service_ids']) ? json_encode($_POST['service_ids']) : '';
    $schedule = sanitize($_POST['schedule'] ?? '');

    // Validation
    if (empty($ownername)) $errors[] = "Owner Name is required.";
    if (empty($contact)) {
        $errors[] = "Contact Number is required.";
    } elseif (!is_numeric($contact) || strlen($contact) > 11) {
        $errors[] = "Invalid Contact Number (must be numeric and up to 11 digits).";
    }
    if (empty($email)) {
        $errors[] = "Email is required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid Email Address.";
    }
    if (empty($address)) $errors[] = "Address is required.";
    if (empty($category)) $errors[] = "Pet Type is required.";
    if (empty($breed)) $errors[] = "Breed is required.";
    if (empty($age)) $errors[] = "Age is required.";
    if (empty($service)) $errors[] = "At least one service must be selected.";

    // Insert Data if No Errors
    if (empty($errors)) {
        $stmt = $db->prepare("INSERT INTO appointment_list(owner_name, contact, email, address, category_id, breed, age, service_ids, schedule) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        if ($stmt) {
            $stmt->bind_param('sssssssss', $ownername, $contact, $email, $address, $category, $breed, $age, $service, $schedule);
            if ($stmt->execute()) {
                echo "<script>
                        alert('Appointment added successfully!');
                        location.href = 'appointment.php';
                        </script>";
            } else {
                $errors[] = "Failed to add appointment: " . $stmt->error;
            }
            $stmt->close();
        } else {
            $errors[] = "Database error: " . $db->error;
        }
    }
}

// Display Errors
if (!empty($errors)) {
    echo "<div class='alert alert-danger'>" . implode('<br>', $errors) . "</div>";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Appointment</title>
</head>

<body>
    <form action="appointment_add.php?schedule=<?= htmlspecialchars($schedule) ?: '' ?>" method="POST" id="appointment-form">
        <input type="hidden" name="schedule" value="<?= htmlspecialchars($schedule) ?>">

        <div class="form-group">
            <label class="text-muted">Appointment Schedule</label>
            <p><b><?= !empty($schedule) && strtotime($schedule) ? date("F d, Y", strtotime($schedule)) : 'No date selected' ?></b></p>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="owner_name" class="control-label">Owner Name</label>
                    <input type="text" name="owner_name" id="owner_name" class="form-control" placeholder="Owner Name" required>
                </div>
                <div class="form-group">
                    <label for="contact" class="control-label">Contact #</label>
                    <input type="text" name="contact" id="contact" class="form-control" placeholder="Contact Number" required>
                </div>
                <div class="form-group">
                    <label for="email" class="control-label">Email</label>
                    <input type="email" name="email" id="email" class="form-control" placeholder="Email Address" required>
                </div>
                <div class="form-group">
                    <label for="address" class="control-label">Address</label>
                    <textarea name="address" id="address" class="form-control" rows="3" placeholder="Address" required></textarea>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <label for="category_id" class="control-label">Pet Type</label>
                    <select name="category_id" id="category_id" class="form-control" required>
                        <option value="" selected disabled>Select Pet Type</option>
                        <?php
                        $categories = $db->query("SELECT * FROM category_list WHERE delete_flag = 0 ORDER BY name ASC");
                        while ($row = $categories->fetch_assoc()): ?>
                            <option value="<?= $row['id'] ?>"><?= ucwords($row['name']) ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="breed" class="control-label">Breed</label>
                    <input type="text" name="breed" id="breed" class="form-control" placeholder="Breed" required>
                </div>
                <div class="form-group">
                    <label for="age" class="control-label">Age</label>
                    <input type="text" name="age" id="age" class="form-control" placeholder="(1 yr/mos old)" required>
                </div>

                <div class="form-group">
                    <label for="service_ids" class="control-label">Service(s)</label>
                    <select name="service_ids[]" id="service_ids" class="form-control" multiple required>
                        <option value="" selected hidden disabled>Select Service(s)</option>
                        <?php
                        $services = $db->query("SELECT * FROM service_list WHERE delete_flag = 0 ORDER BY name ASC");
                        while ($service = $services->fetch_assoc()): ?>
                            <option value="<?= $service['id'] ?>"><?= ucwords($service['name']) ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
            </div>
        </div>

        <button type="submit" class="btn btn-primary" id="submit" name="submit">Submit Appointment</button>
    </form>
</body>

</html>