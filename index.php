<?php
require_once "config.php";

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $name = trim($_POST["name"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $student_id = trim($_POST["student_id"] ?? "");
    $major = trim($_POST["major"] ?? "");

    if (
        $name === "" ||
        !filter_var($email, FILTER_VALIDATE_EMAIL) ||
        $student_id === "" ||
        $major === ""
    ) {

        $error = "Please complete all fields correctly.";

    } else {

        try {

            $sql = "INSERT INTO registrations
                    (name, email, student_id, major)
                    VALUES (:name, :email, :student_id, :major)";

            $stmt = $pdo->prepare($sql);

            $stmt->execute([
                ":name" => $name,
                ":email" => $email,
                ":student_id" => $student_id,
                ":major" => $major
            ]);

            $registrationId = $pdo->lastInsertId();

            header("Location: dashboard.php?id=" . urlencode($registrationId));
            exit;

        } catch (PDOException $e) {

            if ($e->getCode() == "23000") {
                $error = "Email or Student ID already registered.";
            } else {
                $error = "Something went wrong. Please try again.";
            }

        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Student Registration | Trisakti</title>

    <link rel="stylesheet" href="style.css">
</head>

<body>

<header class="site-header">

    <a class="brand" href="index.php">
        TRISAKTI<span>.</span>
    </a>

    <div class="header-caption">
        STUDENT REGISTRATION SYSTEM
    </div>

</header>

<main class="landing">

    <section class="intro">

        <p class="eyebrow">UNIVERSITY DIGITAL SERVICES</p>

        <h1>
            Start your<br>
            <span>next chapter.</span>
        </h1>

        <p class="description">
            Complete your student registration through
            our simple and user-friendly digital platform.
        </p>

        <div class="features">

            <p><span>01</span> Easy registration process</p>
            <p><span>02</span> Secure database storage</p>
            <p><span>03</span> Personal student dashboard</p>

        </div>

    </section>

    <section class="form-container">

        <p class="eyebrow">NEW STUDENT</p>

        <h2>Registration Form</h2>

        <p class="subtitle">
            Please enter your information below.
        </p>

        <?php if ($error): ?>

            <div class="alert alert-error">
                <?= htmlspecialchars($error) ?>
            </div>

        <?php endif; ?>

        <form method="POST" id="registrationForm">

            <label for="name">Full Name</label>

            <input
                type="text"
                id="name"
                name="name"
                placeholder="Enter your full name"
                required
            >

            <label for="email">Email Address</label>

            <input
                type="email"
                id="email"
                name="email"
                placeholder="name@example.com"
                required
            >

            <label for="student_id">Student ID</label>

            <input
                type="text"
                id="student_id"
                name="student_id"
                placeholder="Enter your student ID"
                required
            >

            <label for="major">Study Program</label>

            <select name="major" id="major" required>

                <option value="">Select your program</option>

                <option value="Information Systems">
                    Information Systems
                </option>

                <option value="Informatics Engineering">
                    Informatics Engineering
                </option>

                <option value="Other">
                    Other
                </option>

            </select>

            <button class="primary-button" type="submit">
                Create Student Account →
            </button>

        </form>

        <p class="note">
            Academic web development project.
        </p>

    </section>

</main>

<footer class="site-footer">

    <p>© 2026 TRISAKTI STUDENT PORTAL</p>

    <p>HTML · CSS · JAVASCRIPT · PHP · MYSQL</p>

</footer>

<script src="script.js"></script>

</body>
</html>