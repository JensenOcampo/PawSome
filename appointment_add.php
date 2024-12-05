<?php
include 'config.php';

$schedule = isset($_GET['schedule']) ? $_GET['schedule'] : '';

function sanitize($inputs)
{
    $inputs = trim($inputs);
    $inputs = stripslashes($inputs);
    $inputs = htmlspecialchars($inputs);
    return $inputs;
}

$errors = [];
$successMessage = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Process the form submission here
    $ownername = sanitize($_POST['owner_name']);
    $contact = sanitize($_POST['contact']);
    $email = sanitize($_POST['email']);
    $address = sanitize($_POST['address']);
    $category = sanitize($_POST['category_id'] ?? '');
    $breed = sanitize($_POST['breed']);
    $age = sanitize($_POST['age']);
    $service = isset($_POST['service_ids']) ? json_encode($_POST['service_ids']) : '';
    $schedule = sanitize($_POST['schedule']);

    // Validation checks
    if (empty($ownername)) {
        $errors[] = "Owner Name is required";
    }
    if (empty($contact)) {
        $errors[] = "Contact Number is required";
    } elseif (!is_numeric($contact)) {
        $errors[] = "Invalid Contact Number";
    } elseif (strlen($contact) > 11) {
        $errors[] = "Contact Number must be 11 digits";
    }
    if (empty($email)) {
        $errors[] = "Email is required";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid Email";
    }
    if (empty($address)) {
        $errors[] = "Address is required";
    }
    if (empty($category)) {
        $errors[] = "Category is required";
    }
    if (empty($breed)) {
        $errors[] = "Breed is required";
    }
    if (empty($age)) {
        $errors[] = "Age is required";
    }
    if (empty($service)) {
        $errors[] = "Service is required";
    }

    if (empty($errors)) {
        // Insert into the database
        $addQuery = mysqli_query($db, "INSERT INTO appointment_list(owner_name, contact, email, address, category_id, breed, age, service_ids, schedule) VALUES ('$ownername','$contact','$email','$address','$category','$breed','$age','$service', '$schedule')");
        if ($addQuery) {
            echo "<script>
                    alert('Appointment added successfully!');
                    location.href = 'appointment.php'; 
                </script>";
        } else {
            $errors[] = "Failed to add appointment.";
        }
    }
}

if (!empty($errors)) {
    echo "<div class='alert alert-danger'>" . implode('<br>', $errors) . "</div>";
}
?>

<form action="appointment_add.php?schedule=<?= htmlspecialchars($schedule) ?>" method="POST" id="appointment-form">
    <input type="hidden" name="schedule" value="<?= htmlspecialchars($schedule) ?>">

    <div class="form-group">
        <label class="text-muted">Appointment Schedule</label>
        <p><b><?= !empty($schedule) ? date("F d, Y", strtotime($schedule)) : 'No date selected' ?></b></p>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="owner_name" class="control-label">Owner Name</label>
                <input type="text" name="owner_name" id="owner_name" class="form-control" placeholder="Owner Name">
            </div>
            <div class="form-group">
                <label for="contact" class="control-label">Contact #</label>
                <input type="text" name="contact" id="contact" class="form-control" placeholder="Contact Number">
            </div>
            <div class="form-group">
                <label for="email" class="control-label">Email</label>
                <input type="email" name="email" id="email" class="form-control" placeholder="Email Address">
            </div>
            <div class="form-group">
                <label for="address" class="control-label">Address</label>
                <textarea name="address" id="address" class="form-control" rows="3" placeholder="Address"></textarea>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <label for="category_id" class="control-label">Pet Type</label>
                <select name="category_id" id="category_id" class="form-control">
                    <option value="" selected disabled>Select Pet Type</option>
                    <?php
                    $categories = $db->query("SELECT * FROM category_list WHERE delete_flag = 0 ORDER BY name ASC");
                    while ($row = $categories->fetch_assoc()):
                    ?>
                        <option value="<?= $row['id'] ?>"><?= ucwords($row['name']) ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="breed" class="control-label">Breed</label>
                <input type="text" name="breed" id="breed" class="form-control" placeholder="Breed">
            </div>
            <div class="form-group">
                <label for="age" class="control-label">Age</label>
                <input type="text" name="age" id="age" class="form-control" placeholder="(1 yr/mos old)">
            </div>

            <div class="form-group">
                <label for="service_ids" class="control-label">Service(s)</label>
                <select name="service_ids[]" id="service_ids" class="form-control" multiple>
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