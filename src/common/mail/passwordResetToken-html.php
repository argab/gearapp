<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user \common\entities\User */

$resetLink = Yii::$app->urlManager->createAbsoluteUrl(['reset/reset-password', 'token' => $user->password_reset_token]);

?>
<div class="password-reset">
    <p>Hello <?= Html::encode($user->email) ?>,</p>

    <p>Follow the link below to reset your password:</p>

    <p><?= Html::a(Html::encode($resetLink), $resetLink) ?></p>
</div>