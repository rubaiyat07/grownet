<?php

require_once 'config/class.user.php';

$user = new USER();

if (empty($_GET['id']) || empty($_GET['code'])) {
    header('Location: index.php?page=relog&msg=invalid_request');
    exit();
}

if (isset($_GET['id']) && isset($_GET['code'])) {
    $id = base64_decode($_GET['id']);
    $code = $_GET['code'];

    $status_active = 'active';
    $status_inactive = 'inactive';

    $stmt = $user->runQuery("SELECT id, status FROM users WHERE id = :uID AND tokenCode = :code LIMIT 1");
    $stmt->execute(array(':uID' => $id, ':code' => $code));

    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row) {
        if ($row['status'] == $status_inactive) {
            $stmt = $user->runQuery("UPDATE users SET status = :status WHERE id = :uID");
            $stmt->bindParam(":status", $status_active);
            $stmt->bindParam(":uID", $id);
            $stmt->execute();
            $msg = "<div class='alert alert-info text-center'><b>Your account has been activated successfully.</b></div>
            <meta http-equiv='refresh' content='5;url=index.php?page=relog'>";
        } else {
            $msg = "<div class='alert alert-info text-center'><b>Sorry! Your account is already activated.</b></div>
            <meta http-equiv='refresh' content='5;url=index.php?page=relog'>";
        }
    } else {
        $msg = "<div class='alert alert-warning text-center'><b>Account Not Found!</b></div>
        <meta http-equiv='refresh' content='5;url=index.php'>";
    }
}

if (isset($msg)) {
    echo `<div class="alert alert-warning text-center">`.$msg.`</div>`;
}
?>