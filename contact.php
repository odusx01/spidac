<?php
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(0);

// Load .env
$env = [];
foreach (file(__DIR__ . '/.env') as $line) {
    $line = trim($line);
    if ($line && strpos($line, '=') !== false && $line[0] !== '#') {
        [$k, $v] = explode('=', $line, 2);
        $env[trim($k)] = trim($v);
    }
}
$host   = $env['DB_HOST'] ?? 'localhost';
$user   = $env['DB_USER'] ?? '';
$pass   = $env['DB_PASS'] ?? '';
$dbname = $env['DB_NAME'] ?? '';

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

// Initialize variables
$name = $email = $message = "";
$success_message = $error_message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name    = trim($_POST["name"]);
    $email   = trim($_POST["email"]);
    $message = trim($_POST["message"]);

    if (empty($name) || empty($email) || empty($message)) {
        $error_message = "All fields are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_message = "Invalid email address.";
    } else {
        $stmt = $conn->prepare("INSERT INTO contact_messages (name, email, message) VALUES (?, ?, ?)");
        if (!$stmt) {
            $error_message = "Prepare failed: " . $conn->error;
        } else {
            $stmt->bind_param("sss", $name, $email, $message);
            
            if ($stmt->execute()) {
                $success_message = "<h2>Thank you! Your message has been sent, we will be in touch with you shortly.</h2>";
                // Clear form fields
                $name = $email = $message = "";
            } else {
                $error_message = "Error: " . $stmt->error;
            }
            $stmt->close();
        }
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="logo/spidac.png" type="image/png">
    <link href="https://fonts.cdnfonts.com/css/hangyaboly" rel="stylesheet">


  <script id="Cookiebot" src="https://consent.cookiebot.com/uc.js" data-cbid="8e4b60ca-6840-4d39-996d-d869f0333afc" data-blockingmode="auto" type="text/javascript"></script>
  
    <meta name="description" content="Get in touch with SPIDAC, privacy technology specialists. Contact us for privacy tech implementation, automation advisory, and data protection training services.">
    <meta property="og:title" content="Contact Us - SPIDAC Privacy Technology Specialists">
    <meta property="og:description" content="Get in touch with SPIDAC, privacy technology specialists. Contact us for privacy tech implementation, automation advisory, and data protection training services.">
    <meta property="og:type" content="website">
    <title>Contact Us - SPIDAC Privacy Technology Specialists</title>
    <style>
        /* contact.php — submit button style override */
        #submitBtn { border: none; cursor: pointer; width: 100%; font-size: 1rem; background: #1e2a5e; color: white; padding: 12px 28px; border-radius: 8px; font-weight: 600; transition: background 0.2s; }
        #submitBtn:hover { background: #2c4aa0; }
    </style>
    <!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=AW-17516084686"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'AW-17516084686');
</script>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<link rel="stylesheet" href="spidac-header.css">
<link rel="stylesheet" href="spidac-global.css">

</head>

<body>
    <div id="header"></div>

    <!-- Page Hero -->
    <section class="page-hero">
        <div class="container">
            <h1>Get in Touch</h1>
            <p>Have a question or ready to start a project? We would love to hear from you.</p>
        </div>
    </section>

    <!-- Contact Form Section -->
    <section class="contact-form-section">
        <div class="container" style="max-width: 720px; margin: auto;">

            <div class="contact-info">
                <div class="contact-info-item">
                    <i class="fas fa-envelope"></i>
                    <a href="mailto:contact@spidac.com">contact@spidac.com</a>
                </div>
            </div>

            <?php if (!empty($success_message)): ?>
                <div class="form-message success-message">
                    <?php echo $success_message; ?>
                </div>
            <?php endif; ?>

            <?php if (!empty($error_message)): ?>
                <div class="form-message error-message">
                    <?php echo htmlspecialchars($error_message); ?>
                </div>
            <?php endif; ?>

            <div class="contact-card">
                <form action="contact.php" method="post" id="contactForm">
                    <div class="form-field">
                        <label for="name">Name</label>
                        <input type="text" id="name" name="name" required placeholder="Your name" value="<?php echo htmlspecialchars($name); ?>">
                    </div>

                    <div class="form-field">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" required placeholder="your@email.com" value="<?php echo htmlspecialchars($email); ?>">
                    </div>

                    <div class="form-field">
                        <label for="message">Message</label>
                        <textarea id="message" name="message" rows="6" required placeholder="How can we help?"><?php echo htmlspecialchars($message); ?></textarea>
                    </div>

                    <button type="submit" id="submitBtn">
                        Send Message <i class="fas fa-paper-plane" style="margin-left: 6px;"></i>
                    </button>
                </form>
            </div>

            <script>
            document.getElementById('contactForm').addEventListener('submit', function() {
                var btn = document.getElementById('submitBtn');
                btn.textContent = 'Sending...';
                btn.disabled = true;
                btn.style.opacity = '0.7';
                btn.style.cursor = 'not-allowed';
            });
            </script>
        </div>
    </section>

    <div id="footer"></div>
<script src="include.js"></script>
</body>
</html>