<?php
// 1. ENABLE ERROR REPORTING (For Debugging)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// 2. Include the database connection
if (!file_exists('db_connect.php')) {
    die("Error: db_connect.php file not found!");
}
include 'db_connect.php';

// 3. Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Collect and sanitize input data
    $full_name = $conn->real_escape_string($_POST['full_name'] ?? '');
    $email = $conn->real_escape_string($_POST['email'] ?? '');
    $phone = $conn->real_escape_string($_POST['phone'] ?? '');
    $nationality = $conn->real_escape_string($_POST['nationality'] ?? '');
    $adults = (int)($_POST['adults'] ?? 0);
    $children = (int)($_POST['children'] ?? 0);
    $id_proof = $conn->real_escape_string($_POST['id_proof'] ?? '');
    $safari_type = $conn->real_escape_string($_POST['safari_type'] ?? '');
    $booking_date = $conn->real_escape_string($_POST['booking_date'] ?? '');
    $time_slot = $conn->real_escape_string($_POST['slot'] ?? ''); 
    $message = $conn->real_escape_string($_POST['message'] ?? '');

    // SQL Query
    $sql = "INSERT INTO bookings (full_name, email, phone, nationality, adults, children, id_proof, safari_type, booking_date, time_slot, message)
            VALUES ('$full_name', '$email', '$phone', '$nationality', '$adults', '$children', '$id_proof', '$safari_type', '$booking_date', '$time_slot', '$message')";

    // Execute Query
    if ($conn->query($sql) === TRUE) {
        
        // --- START EMAIL SENDING CODE ---
        $to = "akashkhangar104@gmail.com"; 
        $subject = "New Booking Request: " . $full_name;
        
        // Construct the email body
        $txt = "You have received a new safari booking request.\n\n";
        $txt .= "---------------------------------\n";
        $txt .= "Customer: " . $full_name . "\n";
        $txt .= "Phone: " . $phone . "\n";
        $txt .= "Email: " . $email . "\n";
        $txt .= "Nationality: " . $nationality . "\n";
        $txt .= "---------------------------------\n";
        $txt .= "Safari Date: " . $booking_date . "\n";
        $txt .= "Time Slot: " . $time_slot . "\n";
        $txt .= "Safari Type: " . $safari_type . "\n";
        $txt .= "Pax: " . $adults . " Adults, " . $children . " Children\n";
        $txt .= "ID Proof: " . $id_proof . "\n";
        $txt .= "---------------------------------\n";
        $txt .= "Special Request: " . $message . "\n";

        // Headers (Important for delivery)
        // Use a 'noreply' address from your own domain as the 'From' to avoid spam filters
        $headers = "From: no-reply@safariwithakash.com" . "\r\n" .
                   "Reply-To: " . $email . "\r\n" .
                   "X-Mailer: PHP/" . phpversion();

        // Send the email
        mail($to, $subject, $txt, $headers);
        // --- END EMAIL SENDING CODE ---

        echo "<script>
                alert('Booking request submitted successfully! We will contact you shortly.');
                window.location.href = 'index.html';
              </script>";
    } else {
        echo "SQL Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
} else {
    echo "Error: Form was not submitted via POST method.";
}
?>