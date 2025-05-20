<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
?>

<?php

@include_once(__DIR__ . '/template/head.inc.php');
@include_once(__DIR__ . '/src/Helpers/Auth.php');

if (!isLoggedIn()) {
    header("Location: login.php");
    exit;
}

$user = user();

?>

<div class="instellingen">
    <div class="kopjes"><a href="#">Mijn Account</a></div>
    <div class="kopjes"><a href="#">Instellingen</a></div>
    <div class="kopjes"><a href="#">Bestellingen</a></div>
    <div class="kopjes"><a href="#">Wishlist</a></div>
</div>

<div class="account-details uk-container uk-margin">
    <h2>Uw Profielgegevens</h2>
    <table class="uk-table uk-table-divider uk-table-hover">
        <tr><td><strong>Voornaam:</strong></td><td><?= htmlspecialchars($user->firstname) ?></td></tr>
        <tr><td><strong>Tussenvoegsel:</strong></td><td><?= htmlspecialchars($user->prefixes) ?></td></tr>
        <tr><td><strong>Achternaam:</strong></td><td><?= htmlspecialchars($user->lastname) ?></td></tr>
        <tr><td><strong>Email:</strong></td><td><?= htmlspecialchars($user->email) ?></td></tr>
        <tr><td><strong>Straatnaam:</strong></td><td><?= htmlspecialchars($user->street) ?></td></tr>
        <tr><td><strong>Huisnummer:</strong></td><td><?= htmlspecialchars($user->house_number) ?> <?= htmlspecialchars($user->addition) ?></td></tr>
        <tr><td><strong>Postcode:</strong></td><td><?= htmlspecialchars($user->zipcode) ?></td></tr>
        <tr><td><strong>Woonplaats:</strong></td><td><?= htmlspecialchars($user->city) ?></td></tr>
    </table>
</div>

<style>
    .instellingen {
        display: flex;
        justify-content: center;
        margin: 30px 0;
        gap: 20px;
        flex-wrap: wrap;
    }

    .kopjes {
        background-color: #fff;
        padding: 10px 20px;
        border-radius: 25px;
        border: 1px solid #ccc;
        cursor: pointer;
        transition: all 0.3s;
    }

    .kopjes a {
        text-decoration: none;
        color: #000;
    }

    .kopjes:hover {
        background-color: #000;
        border-color: #000;
    }

    .kopjes:hover a {
        color: #fff;
    }

    .account-details {
        max-width: 800px;
        margin: 0 auto 50px auto;
        background: #fff;
        padding: 30px;
        border-radius: 12px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.05);
    }
</style>
<?php
@include_once(__DIR__ . '/template/foot.inc.php');
?>
