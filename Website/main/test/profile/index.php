<?php
session_start();
require_once '../init.php';

$message = '';
$authenticated = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['re_password'])) {
    $dbh = new Dbh();
    $pdo = $dbh->connect();
    $stmt = $pdo->prepare("SELECT * FROM Users WHERE username=?");
    $stmt->execute([$_SESSION['username']]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($row && password_verify($_POST['re_password'], $row['password'])) {
        $authenticated = true;
    } else {
        $message = "Incorrect password.";
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'], $_POST['value'])) {
    $authenticated = true;
    $dbh = new Dbh();
    $pdo = $dbh->connect();
    $col = $_POST['action'];
    $val = $_POST['value'];
    $pdo->prepare("UPDATE users SET $col = ? WHERE username = ?")->execute([$val, $_SESSION['username']]);
    $_SESSION['username'] = ($col === 'username') ? $val : $_SESSION['username'];
    $message = ucfirst($col) . " updated.";
}
?>

<!DOCTYPE html>
<html>
<head>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
<link href="../styles.css" media="all" rel="Stylesheet" type="text/css" />
</head>
<body>

<div class="header-bar">
<ul class="flex-list">
     <ul class="flex-left">
          <!-- logo svg here -->
     </ul>
     <ul class="flex-center"></ul>
     <ul class="flex-right">
          <li id="profileLogoutButton" class="flex-button" onclick="headerButtonClicked(this, '..')">
               <a> Back </a>
          </li>
          <?php if ($_SESSION["username"] != NULL): ?>
          <li id="profileLogoutButton" class="flex-button" onclick="headerButtonClicked(this, '../logout')">
               <a> Logout </a>
          </li>
          <?php else: ?>
          <li id="profileLoginButton" class="flex-button" onclick="headerButtonClicked(this, 'login')">
               <a> Login </a>
          </li>
          <?php endif; ?>
     </ul>
</ul>
<hr style="height: 2px; background-color: #b4b4b4; border: none;">
</div>

<?php if (!$authenticated): ?>

<div style="max-width: 480px; margin: 48px auto; padding: 0 24px; font-family: Roboto, sans-serif;">
    <?php if ($message): ?>
    <div style="color:red; margin-bottom:16px;"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>
    <form method="POST" style="display:flex; flex-direction:column; gap:8px;">
        <p>Please confirm your password to access account settings.</p>
        <input type="password" name="re_password" placeholder="Current password" style="padding:8px;">
        <button type="submit">Confirm</button>
    </form>
</div>

<?php else: ?>

<div style="max-width: 480px; margin: 48px auto; padding: 0 24px; font-family: Roboto, sans-serif;">

    <?php if ($message): ?>
    <div style="color:green; margin-bottom:16px;"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>

    <?php foreach (['username', 'password', 'email'] as $col): ?>
    <form method="POST" style="display:flex; gap:8px; margin-bottom:12px;">
        <input type="hidden" name="action" value="<?= $col ?>">
        <input type="text" name="value" placeholder="New <?= $col ?>" style="flex:1; padding:8px;">
        <button type="submit">Update <?= ucfirst($col) ?></button>
    </form>
    <?php endforeach; ?>

</div>

<?php endif; ?>

</body>
</html>

<script>
     let activeClick = null;
</script>

<script src="../header.js"></script>
<script src="../file.js"></script>