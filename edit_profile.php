<?php

require_once "config.php";

$id = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);

if (!$id) {
    header("Location: index.php");
    exit;
}

$stmt = $pdo->prepare(
    "SELECT * FROM registrations WHERE id = :id LIMIT 1"
);

$stmt->execute([":id" => $id]);

$student = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$student) {
    header("Location: index.php");
    exit;
}

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

            $update = $pdo->prepare(
                "UPDATE registrations
                 SET name = :name,
                     email = :email,
                     student_id = :student_id,
                     major = :major
                 WHERE id = :id"
            );

            $update->execute([
                ":name" => $name,
                ":email" => $email,
                ":student_id" => $student_id,
                ":major" => $major,
                ":id" => $id
            ]);

            header("Location: dashboard.php?id=" . $id . "&updated=1");
            exit;

        } catch (PDOException $e) {

            if ($e->getCode() == "23000") {
                $error = "Email or Student ID is already used by another registration.";
            } else {
                $error = "Unable to update profile. Please try again.";
            }

        }

    }
}

function e($value) {
    return htmlspecialchars(
        (string)$value,
        ENT_QUOTES,
        "UTF-8"
    );
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Edit Student Profile | Trisakti</title>

    <link rel="stylesheet" href="style.css">

</head>

<body class="portal-body">

<header class="site-header">

    <a class="brand" href="dashboard.php?id=<?= (int)$id ?>">
        TRISAKTI<span>.</span>
    </a>

    <div class="header-caption">
        STUDENT PORTAL / EDIT PROFILE
    </div>

</header>

<main class="edit-main">

    <a
        class="back-link"
        href="dashboard.php?id=<?= (int)$id ?>"
    >
        ← Back to Dashboard
    </a>

    <section class="form-container edit-card">

        <p class="eyebrow">STUDENT INFORMATION</p>

        <h2>Edit Profile</h2>

        <p class="subtitle">
            Update your registration details below.
        </p>

        <?php if ($error): ?>

            <div class="alert alert-error">
                <?= e($error) ?>
            </div>

        <?php endif; ?>

        <form method="POST" id="registrationForm">

            <label for="name">Full Name</label>

            <input
                id="name"
                type="text"
                name="name"
                value="<?= e($student['name']) ?>"
                required
            >

            <label for="email">Email Address</label>

            <input
                id="email"
                type="email"
                name="email"
                value="<?= e($student['email']) ?>"
                required
            >

            <label for="student_id">Student ID</label>

            <input
                id="student_id"
                type="text"
                name="student_id"
                value="<?= e($student['student_id']) ?>"
                required
            >

            <label for="major">Study Program</label>

            <select id="major" name="major" required>

                <option
                    value="Information Systems"
                    <?= $student['major'] === 'Information Systems' ? 'selected' : '' ?>
                >
                    Information Systems
                </option>

                <option
                    value="Informatics Engineering"
                    <?= $student['major'] === 'Informatics Engineering' ? 'selected' : '' ?>
                >
                    Informatics Engineering
                </option>

                <option
                    value="Other"
                    <?= $student['major'] === 'Other' ? 'selected' : '' ?>
                >
                    Other
                </option>

            </select>

            <button class="primary-button" type="submit">
                Save Changes →
            </button>

        </form>

    </section>

</main>

</body>
</html>