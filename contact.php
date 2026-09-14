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
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Museo Sans', 'Segoe UI', Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #fff;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        /* Header — see spidac-header.css */

        .card-button {
            display: inline-block;
            background: #1e2a5e;
            color: white;
            padding: 10px 20px;
            font-size: 0.9rem;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            transition: background 0.2s ease;
            margin-top: 1.2rem;
        }

        .card-button:hover {
            background: #2c4aa0;
        }

        /* nav — see spidac-header.css */

        /* WHO WE ARE Quote Styling */
        .who-quote {
            font-family: 'Playfair Display', serif; /* Elegant serif font */
            font-size: 1.3rem;
            color: #000; /* Accent color matching your palette */
            line-height: 1.8;
            font-weight: 500;
            border-left: 6px solid #1e2a5e; /* subtle accent */
            padding-left: 1rem;
            margin-bottom: 1.5rem;
            font-style: italic;
        }

        /* .logo — see spidac-header.css */
        /* .nav-links — see spidac-header.css */

        /* Hero Section */
        .hero {
            position: relative;
            color: #fff;
            padding: 130px 0 80px;
            margin-top: 70px;
            background: linear-gradient(135deg, #0d1832 0%, #1e2a5e 60%, #2c3e7a 100%);
            overflow: hidden;
        }

        .hero > .container {
            position: relative;
            z-index: 1;
        }

        .hero h1 {
            font-size: 3.5rem;
            margin-bottom: 1rem;
            font-weight: 700;
            line-height: 1.2;
        }

        .hero .subtitle {
            font-size: 1.3rem;
            margin-bottom: 2rem;
            opacity: 0.9;
            max-width: 800px;
            margin-left: auto;
            margin-right: auto;
        }

        .hero-description {
            font-size: 1.1rem;
            max-width: 900px;
            margin: 0 auto 3rem;
            opacity: 0.9;
            line-height: 1.7;
        }

        .cta-button {
            display: inline-block;
            background: #1e2a5e;
            color: white;
            padding: 14px 32px;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            font-size: 1rem;
            transition: background 0.2s ease, transform 0.2s ease;
            box-shadow: none;
        }

        .cta-button:hover {
            background: #2c4aa0;
            transform: translateY(-2px);
        }

        /* Services Section */
        .services {
            padding: 80px 0;
            background: #f8f9fa;
        }

        .section-title {
            text-align: center;
            font-size: 2.5rem;
            margin-bottom: 1rem;
            color: #1e2a5e;
            font-weight: 700;
            letter-spacing: -0.5px;
        }

        .section-subtitle {
            text-align: center;
            color: #666;
            font-size: 1.1rem;
            max-width: 700px;
            margin: 0 auto 3rem;
            line-height: 1.7;
        }

        .services-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 2rem;
            margin-bottom: 4rem;
        }

        .service-card {
            background: white;
            padding: 2.5rem;
            border-radius: 15px;
            box-shadow: 0 8px 30px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
            border-top: 4px solid #1e2a5e;
        }

        .service-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(0,0,0,0.15);
        }

        .service-card h3 {
            font-size: 1.5rem;
            margin-bottom: 1rem;
            color: #1e2a5e;
            font-weight: 600;
        }

        .service-card p {
            color: #666;
            line-height: 1.6;
        }

        /* Training Section */
        .training {
            padding: 80px 0;
            background: white;
        }

        /* About Section */
        .about {
            padding: 80px 0;
            background: #f8f9fa;
        }

        .about-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 3rem;
            margin-bottom: 5rem;
        }

        .about-card {
            background: white;
            padding: 2.5rem;
            border-radius: 15px;
            box-shadow: 0 8px 30px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
            text-align: center;
        }

        .about-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(0,0,0,0.15);
        }

        .about-icon {
            width: 70px;
            height: 70px;
            background: linear-gradient(135deg, #1e2a5e 0%, #2c4aa0 100%);
            border-radius: 50%;
            margin: 0 auto 1.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.8rem;
            color: white;
        }

        .about-card h3 {
            font-size: 1.8rem;
            margin-bottom: 1.5rem;
            color: #1e2a5e;
            font-weight: 700;
        }

        .about-card p {
            color: #666;
            line-height: 1.8;
            font-size: 1.1rem;
        }

        /* Team Section */
        .team-section {
            background: white;
            padding: 4rem 0;
            border-radius: 20px;
            margin: 3rem 0;
        }

        .team-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
            margin-top: 2rem;
        }

        .team-card {
            background: #f8f9fa;
            padding: 2rem;
            border-radius: 15px;
            text-align: center;
            transition: all 0.3s ease;
            border: 2px solid transparent;
        }

        .team-card:hover {
            border-color: #1e2a5e;
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(30, 42, 94, 0.1);
        }

        .team-avatar {
            width: 120px;
            height: 120px;
            background: linear-gradient(135deg, #1e2a5e 0%, #2c4aa0 100%);
            border-radius: 50%;
            margin: 0 auto 1.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2.5rem;
            color: white;
            font-weight: bold;
        }

        .team-card h4 {
            font-size: 1.3rem;
            margin-bottom: 0.5rem;
            color: #1e2a5e;
            font-weight: 600;
        }

        .team-role {
            color: #ff6b6b;
            font-weight: 500;
            margin-bottom: 1rem;
            font-size: 1rem;
        }

        .team-bio {
            color: #666;
            line-height: 1.6;
            font-size: 0.95rem;
        }

        /* Tech Stack Section */
        .tech-stack {
            background: #1e2a5e;
            padding: 4rem 0;
            border-radius: 20px;
            margin: 3rem 0;
            text-align: center;
        }

        .tech-stack h3 {
            color: white;
            font-size: 2.2rem;
            margin-bottom: 3rem;
            font-weight: 700;
        }

        .tech-logos {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 2rem;
            margin-top: 2rem;
        }

        .tech-logo {
            background: white;
            padding: 2rem;
            border-radius: 15px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
            min-height: 150px;
        }

        .tech-logo:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(0,0,0,0.2);
        }

        .tech-logo-icon {
            font-size: 3rem;
            margin-bottom: 1rem;
            color: #1e2a5e;
        }

        .tech-logo h4 {
            color: #1e2a5e;
            font-weight: 600;
            font-size: 1.1rem;
            text-align: center;
        }

        .training-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 2rem;
            margin-top: 3rem;
        }

        .training-card {
            background: #f8f9fa;
            padding: 2.5rem;
            border-radius: 15px;
            border-left: 5px solid #1e2a5e;
            transition: all 0.3s ease;
        }

        .training-card:hover {
            transform: translateX(5px);
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }

        .training-card h3 {
            font-size: 1.4rem;
            margin-bottom: 1rem;
            color: #1e2a5e;
            font-weight: 600;
        }

        .training-card p {
            color: #666;
            line-height: 1.6;
            margin-bottom: 1rem;
        }

        .training-features {
            list-style: none;
            padding-left: 0;
        }

        .training-features li {
            color: #555;
            margin-bottom: 0.5rem;
            padding-left: 1.5rem;
            position: relative;
        }

        .training-features li::before {
            content: '✓';
            position: absolute;
            left: 0;
            color: #1e2a5e;
            font-weight: bold;
        }

        /* Value Props Section */
        .value-props {
            padding: 80px 0;
            background: white;
        }

        .value-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 3rem;
            margin-top: 3rem;
        }

        .value-card {
            text-align: center;
            padding: 2rem;
        }

        .value-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #1e2a5e 0%, #2c4aa0 100%);
            border-radius: 50%;
            margin: 0 auto 1.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            color: white;
        }

        .value-card h3 {
            font-size: 1.5rem;
            margin-bottom: 1rem;
            color: #1e2a5e;
        }

        .value-card p {
            color: #666;
            line-height: 1.6;
        }

        /* Partnership Section */
        .partnership {
            padding: 80px 0;
            background: #0d1832;
            color: white;
            text-align: center;
        }

        .partnership h2 {
            font-size: 2.5rem;
            margin-bottom: 2rem;
            font-weight: 700;
        }

        .partnership-badge {
            background: rgba(255,255,255,0.1);
            padding: 1rem 2rem;
            border-radius: 25px;
            display: inline-block;
            margin: 1rem;
            font-weight: 600;
            border: 2px solid rgba(255,255,255,0.3);
        }

        /* CTA Section */
        .final-cta {
            padding: 80px 0;
            background: linear-gradient(135deg, #ff6b6b 0%, #ffa726 100%);
            color: white;
            text-align: center;
        }

        .final-cta h2 {
            font-size: 2.5rem;
            margin-bottom: 1rem;
            font-weight: 700;
        }

        .final-cta p {
            font-size: 1.2rem;
            margin-bottom: 2rem;
            opacity: 0.9;
        }

        .cta-button-secondary {
            background: white;
            color: #0d1832;
            padding: 13px 28px;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            font-size: 1rem;
            transition: background 0.2s ease, transform 0.2s ease;
            display: inline-block;
        }

        .cta-button-secondary:hover {
            background: #e8edf5;
            transform: translateY(-2px);
        }

        /* Footer */
        footer {
            background: #333;
            color: white;
            padding: 3rem 0;
            text-align: center;
        }

        .footer-content {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 2rem;
            margin-bottom: 2rem;
        }

        .footer-section h3 {
            margin-bottom: 1rem;
            color: #ccc;
        }

        .footer-section p, .footer-section a {
            color: #ccc;
            text-decoration: none;
            line-height: 1.6;
        }

        .footer-section a:hover {
            color: white;
        }

        .footer-bottom {
            border-top: 1px solid #555;
            padding-top: 2rem;
            color: #999;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .hero h1 {
                font-size: 2.5rem;
            }

            .hero .subtitle {
                font-size: 1.1rem;
            }

            .services-grid {
                grid-template-columns: 1fr;
            }

            .section-title {
                font-size: 2.2rem;
            }
        }

        /* Animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .service-card {
            animation: fadeInUp 0.6s ease forwards;
        }

        .service-card:nth-child(2) {
            animation-delay: 0.2s;
        }

        .service-card:nth-child(3) {
            animation-delay: 0.4s;
        }

        .service-card:nth-child(4) {
            animation-delay: 0.6s;
        }
        
        .ceo-quote {
            text-align: center;
            margin: 3rem 0 1rem;
            font-style: italic;
            color: #000; /* matches your brand accent */
            font-size: 1.1rem;
            line-height: 1.7;
        }

        .ceo-quote .ceo-name {
            font-style: normal;
            font-weight: 600;
            color: #1e2a5e;
            margin-top: 0.8rem;
            font-size: 1rem;
        }

        /* Contact form */
        .contact-form-section {
            padding: 80px 0;
            background: #f8f9fa;
        }

        .contact-card {
            background: white;
            padding: 2.5rem;
            border-radius: 16px;
            border: 1px solid #e8eaf0;
            box-shadow: 0 2px 12px rgba(13,24,50,0.07);
        }

        .form-field {
            margin-bottom: 1.5rem;
        }

        .form-field label {
            display: block;
            font-weight: 600;
            margin-bottom: 0.5rem;
            color: #1e2a5e;
            font-size: 0.95rem;
        }

        .form-field input,
        .form-field textarea {
            width: 100%;
            padding: 0.9rem 1rem;
            border: 1px solid #d0d4e0;
            border-radius: 8px;
            font-size: 1rem;
            font-family: inherit;
            color: #333;
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
            background: #fff;
        }

        .form-field input:focus,
        .form-field textarea:focus {
            outline: none;
            border-color: #1e2a5e;
            box-shadow: 0 0 0 3px rgba(30,42,94,0.08);
        }

        .form-message {
            padding: 1rem 1.5rem;
            border-radius: 8px;
            margin-bottom: 1.5rem;
            font-weight: 500;
        }

        .success-message {
            background: #e8f5e9;
            color: #2e7d32;
            border: 1px solid #a5d6a7;
        }

        .error-message {
            background: #fdecea;
            color: #c62828;
            border: 1px solid #ef9a9a;
        }

        .contact-info {
            display: flex;
            gap: 1.5rem;
            justify-content: center;
            flex-wrap: wrap;
            margin-bottom: 2.5rem;
        }

        .contact-info-item {
            display: flex;
            align-items: center;
            gap: 0.6rem;
            color: #555;
            font-size: 1rem;
        }

        .contact-info-item i {
            color: #1e2a5e;
            font-size: 1.1rem;
        }

        .contact-info-item a {
            color: #1e2a5e;
            text-decoration: none;
            font-weight: 500;
        }

        .contact-info-item a:hover {
            text-decoration: underline;
        }

/* Hamburger + mobile nav — see spidac-header.css */

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

</head>

<body>
    <div id="header"></div>

    <!-- Hero Section -->
    <section class="hero" id="home">
        <div class="container">
            <h1>Get in Touch</h1>
            <p class="hero-description">Have a question or ready to start a project? We would love to hear from you.</p>
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

                    <button type="submit" id="submitBtn" class="cta-button" style="border: none; cursor: pointer; width: 100%; font-size: 1rem;">
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