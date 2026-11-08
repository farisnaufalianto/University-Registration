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

function e($value) {
    return htmlspecialchars((string)$value, ENT_QUOTES, "UTF-8");
}

$initials = strtoupper(substr($student["name"], 0, 1));

$registrationNumber = "TRS-" .
    str_pad((string)$student["id"], 5, "0", STR_PAD_LEFT);

$registeredDate = date(
    "d M Y",
    strtotime($student["created_at"])
);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Student Dashboard | Trisakti</title>

    <link rel="stylesheet" href="style.css">
</head>

<body class="portal-body">

<div class="portal-layout">

    <!-- SIDEBAR -->

    <aside class="sidebar">

        <a class="brand sidebar-brand" href="#overview">
            TRISAKTI<span>.</span>
        </a>

        <p class="sidebar-label">STUDENT MENU</p>

        <a class="side-link active" href="#overview">
            <span>▦</span> Overview
        </a>

        <a class="side-link" href="#profile">
            <span>◉</span> My Profile
        </a>

        <a class="side-link" href="#registration">
            <span>▤</span> Registration Status
        </a>

        <div class="sidebar-bottom">

            <div class="sidebar-user">

                <div class="avatar">
                    <?= e($initials) ?>
                </div>

                <div>
                    <strong><?= e($student["name"]) ?></strong>
                    <small>Student Account</small>
                </div>

            </div>

            <a class="logout-link" href="index.php">
                ← Back to Registration
            </a>

        </div>

    </aside>

    <!-- MAIN DASHBOARD -->

    <main class="portal-main" id="overview">

        <div class="portal-topbar">

            <div>
                <p class="eyebrow">
                    STUDENT PORTAL / OVERVIEW
                </p>

                <h1>Dashboard</h1>
            </div>

            <div class="topbar-date">
                <?= date("l, d F Y") ?>
            </div>

        </div>

        <!-- WELCOME -->

        <section class="welcome-banner">

            <div>

                <p class="eyebrow">
                    WELCOME TO TRISAKTI STUDENT PORTAL
                </p>

                <h2>
                    Hello,
                    <?= e(explode(" ", trim($student["name"]))[0]) ?>
                    👋
                </h2>

                <p>
                    Your student registration has been saved.
                    Here's your registration overview.
                </p>

            </div>

            <div class="welcome-mark">
                T<span>.</span>
            </div>

        </section>

        <!-- STATISTICS -->

        <section class="stats-grid">

            <article class="stat-card">

                <div class="stat-top">
                    <span>REGISTRATION STATUS</span>
                    <span class="stat-icon">✓</span>
                </div>

                <strong class="stat-value status-text">
                    Submitted
                </strong>

                <small>
                    Your data has been received
                </small>

            </article>

            <article class="stat-card">

                <div class="stat-top">
                    <span>STUDENT ID</span>
                    <span class="stat-icon">#</span>
                </div>

                <strong class="stat-value">
                    <?= e($student["student_id"]) ?>
                </strong>

                <small>
                    Registered student number
                </small>

            </article>

            <article class="stat-card">

                <div class="stat-top">
                    <span>STUDY PROGRAM</span>
                    <span class="stat-icon">⌂</span>
                </div>

                <strong class="stat-value stat-program">
                    <?= e($student["major"]) ?>
                </strong>

                <small>
                    Selected academic program
                </small>

            </article>

        </section>

        <!-- CONTENT -->

        <div class="content-grid">

            <!-- REGISTRATION TRACKING -->

            <section class="panel" id="registration">

                <div class="panel-heading">

                    <div>
                        <p class="eyebrow">APPLICATION</p>
                        <h3>Registration Tracking</h3>
                    </div>

                    <span class="status-pill">
                        <i></i> In Process
                    </span>

                </div>

                <p class="panel-description">
                    Follow the progress of your student registration.
                </p>

                <div class="timeline">

                    <div class="timeline-item completed">

                        <div class="timeline-marker">✓</div>

                        <div>
                            <strong>Registration Submitted</strong>

                            <p>
                                Your registration data has been
                                successfully saved.
                            </p>

                            <small><?= e($registeredDate) ?></small>
                        </div>

                    </div>

                    <div class="timeline-item current">

                        <div class="timeline-marker">2</div>

                        <div>
                            <strong>Data Verification</strong>

                            <p>
                                Your information is waiting for
                                administrative verification.
                            </p>

                            <small>Pending</small>
                        </div>

                    </div>

                    <div class="timeline-item">

                        <div class="timeline-marker">3</div>

                        <div>
                            <strong>Registration Approved</strong>

                            <p>
                                Final registration confirmation
                                will appear here.
                            </p>

                            <small>Not started</small>
                        </div>

                    </div>

                </div>

                <div class="info-notice">
                    ⓘ This is a project demo. Verification and
                    approval are illustrative statuses.
                </div>

            </section>

            <!-- STUDENT PROFILE -->

            <section class="panel profile-panel" id="profile">

                <div class="panel-heading">

                    <div>
                        <p class="eyebrow">PERSONAL DATA</p>
                        <h3>Student Profile</h3>
                    </div>

                    <span class="profile-avatar">
                        <?= e($initials) ?>
                    </span>

                </div>

                <div class="profile-name">
                    <?= e($student["name"]) ?>
                </div>

                <div class="profile-major">
                    <?= e($student["major"]) ?>
                </div>

                <div class="profile-details">

                    <div>
                        <span>Registration No.</span>
                        <strong><?= e($registrationNumber) ?></strong>
                    </div>

                    <div>
                        <span>Student ID</span>
                        <strong><?= e($student["student_id"]) ?></strong>
                    </div>

                    <div>
                        <span>Email Address</span>
                        <strong><?= e($student["email"]) ?></strong>
                    </div>

                    <div>
                        <span>Registered Date</span>
                        <strong><?= e($registeredDate) ?></strong>
                    </div>

                </div>

                <a
                    class="secondary-button"
                    href="edit_profile.php?id=<?= (int)$student['id'] ?>"
                >
                    Edit Profile ↗
                </a>

            </section>

        </div>

        <!-- BOTTOM CARD -->

        <section class="bottom-card">

            <div>

                <p class="eyebrow">
                    NEED TO UPDATE SOMETHING?
                </p>

                <h3>
                    Keep your student information up to date.
                </h3>

                <p>
                    You can review and edit your profile information
                    at any time.
                </p>

            </div>

            <a
                class="primary-button compact-button"
                href="edit_profile.php?id=<?= (int)$student['id'] ?>"
            >
                Manage Profile →
            </a>

        </section>

        <footer class="portal-footer">

            <span>© 2026 TRISAKTI STUDENT PORTAL</span>

            <span>
                STUDENT INFORMATION SYSTEM · DEMO
            </span>

        </footer>

    </main>

</div>

</body>
</html>