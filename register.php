<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="bg2">

<div class="overlay">
    <div class="card">

        <h2>New Patient Registration</h2>

        <form action="save_patient.php" method="POST">

            <input type="text" name="name" placeholder="Full Name" required>
            <input type="date" name="date_of_birth" required>

            <select name="gender">
                <option>Male</option>
                <option>Female</option>
                <option>Other</option>
            </select>

            <input type="text" name="phone" placeholder="Phone Number" required>
            <input type="email" name="email" placeholder="Email" required>

            <textarea name="address" placeholder="Address"></textarea>

            <input type="text" name="emergency" placeholder="Emergency Contact">

            
             <!--Blood Type -->
            <select name="blood_type" required>
               <option value="">Select Blood Type</option>
               <option>A+</option>
               <option>A-</option>
               <option>B+</option>
               <option>B-</option>
               <option>AB+</option>
               <option>AB-</option>
               <option>O+</option>
               <option>O-</option>
            </select>
            
            <!-- Password -->
            <input 
                type="password" 
                name="password" 
                placeholder="Create Password" 
                required
                pattern="(?=.*[A-Z]).{8,}"
                title="Must be at least 8 characters, include a number">

            <!-- Confirm Password -->
            <input 
                type="password" 
                name="confirm_password" 
                placeholder="Confirm Password" 
                required>

            <br>
            <button type="submit"> Submit </button>

            

        </form>

        <br>
        <a href="patient-type.html"><button class="back">Cancel</button></a>

    </div>
</div>

</body>
</html>