<?php
@include_once(__DIR__ . '/template/head.inc.php');

// Initialize variables
$name = $email = $message = "";
$nameErr = $emailErr = $messageErr = "";
$successMsg = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty($_POST["name"])) {
        $nameErr = "Naam is verplicht.";
    } else {
        $name = htmlspecialchars(trim($_POST["name"]));
    }

    if (empty($_POST["email"])) {
        $emailErr = "E-mail is verplicht.";
    } elseif (!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
        $emailErr = "Ongeldig e-mail adres.";
    } else {
        $email = htmlspecialchars(trim($_POST["email"]));
    }

    if (empty($_POST["message"])) {
        $messageErr = "Bericht is verplicht.";
    } else {
        $message = htmlspecialchars(trim($_POST["message"]));
    }

    if (!$nameErr && !$emailErr && !$messageErr) {
        $successMsg = "Bedankt voor je bericht, we nemen zo snel mogelijk contact met je op!";
        $name = $email = $message = "";
    }
}
?>

<style>
    .contact-form {
        max-width: 500px;
        margin: 30px auto;
        padding: 30px;
        border: 1px solid #ccc;
        border-radius: 12px;
        background-color: #f9f9f9;
        padding-left: 15px;
        box-shadow: 0 0 10px rgba(0,0,0,0.05);
    }

    .contact-form h1 {
        text-align: center;
        color: #333;
    }

    .contact-form label {
        display: block;
        margin-top: 10px;
        font-weight: bold;
    }

    .contact-form input[type="text"],
    .contact-form textarea {
        width: 100%;
        padding: 10px;
        margin-top: 5px;
        border: 1px solid #bbb;
        border-radius: 6px;
        font-size: 14px;
    }

    .contact-form textarea {
        height: 120px;
        resize: vertical;
    }

    .contact-form .error {
        color: red;
        font-size: 13px;
    }

    .contact-form .success {
        color: green;
        text-align: center;
        font-weight: bold;
        margin-bottom: 10px;
    }

    .contact-form input[type="submit"] {
        background-color: #007bff;
        color: white;
        border: none;
        padding: 10px 20px;
        margin-top: 15px;
        border-radius: 6px;
        cursor: pointer;
    }

    .contact-form input[type="submit"]:hover {
        background-color: #0056b3;
    }
</style>

<div class="contact-form">
    <h1>Klantenservice</h1>

    <?php if ($successMsg): ?>
        <p class="success"><?= $successMsg ?></p>
    <?php endif; ?>

    <form method="post" action="">
        <label for="name">Naam:</label>
        <input type="text" id="name" name="name" value="<?= htmlspecialchars($name) ?>">
        <span class="error"><?= $nameErr ?></span>

        <label for="email">E-mail:</label>
        <input type="text" id="email" name="email" value="<?= htmlspecialchars($email) ?>">
        <span class="error"><?= $emailErr ?></span>

        <label for="message">Bericht:</label>
        <textarea id="message" name="message"><?= htmlspecialchars($message) ?></textarea>
        <span class="error"><?= $messageErr ?></span>

        <input type="submit" value="Verzenden">
    </form>
</div>

<?php
@include_once(__DIR__ . '/template/foot.inc.php');
?>
