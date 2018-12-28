<?php

/* @var $this yii\web\View */
/* @var $user \common\entities\User */

$resetLink = Yii::$app->urlManager->createAbsoluteUrl(['reset/reset-password', 'token' => $user->password_reset_token]);
?>
Hello!

Follow the link below to reset your password:

<?= $resetLink ?>
