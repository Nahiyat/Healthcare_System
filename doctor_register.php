<!DOCTYPE html>
<html>
<head>
    <title>Doctor Register</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="bg2">

<div class="overlay">
    <div class="card">

<h2>Doctor Registration</h2>

<form action="save_doctor.php" method="POST">

    <input type="text" name="name" placeholder="Doctor Name" required>

    <select name="specialization" required>
        <option value="">Select Specialization</option>
        <option>ENT</option>
        <option>Cardiology</option>
        <option>Neurology</option>
        <option>Orthopedic</option>
        <option>Cancer</option>
        <option>General Physician</option>
    </select>

    <input type="text" name="phone" placeholder="Phone" required>
    <input type="number" name="age" placeholder="Age" required>

    <input type="password" name="password" placeholder="Password" required>

    <input type="password" name="confirm_password" placeholder="Confirm Password" required>

    <button type="submit">Save</button>

</form>

    </div>
</div>

</body>
</html>