<?php

namespace lib\services\reset;


use common\entities\user\User;
use http\Exception\RuntimeException;
use Yii;

class ResetByEmailService
{

    /**
     * @param $form
     */
    public function sendResetEmail($email)
    {
        /* @var $user User */
        $user = User::findOne([
            'status' => User::STATUS_ACTIVE,
            'email'  => $email,
        ]);

        if ( ! $user)
        {
            throw new \DomainException('User is not found.');
        }

        if ( ! User::isPasswordResetTokenValid($user->password_reset_token))
        {
            $user->generatePasswordResetToken();
            if ( ! $user->save())
            {
                throw new RuntimeException('Save error.');
            }
        }

        $send = Yii::$app
            ->mailer
            ->compose(
                ['html' => 'apiPasswordResetToken-html', 'text' => 'apiPasswordResetToken-text'],
                ['user' => $user]
            )
            ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name . ' robot'])
            ->setTo($email)
            ->setSubject('Password reset for ' . Yii::$app->name)
            ->send();

        if ( ! $send)
            throw new RuntimeException('Sending error.');
    }

    /**
     * @param $token
     *
     * @return User
     */
    public function validateToken($token): User
    {
        if (empty($token) || ! is_string($token))
        {
            throw new \DomainException('Password reset token cannot be blank.');
        }
        $user = User::findByPasswordResetToken($token);
        if ( ! $user)
        {
            throw new \DomainException('Wrong password reset token.');
        }

        return $user;
    }


    /**
     * @param $user User
     */
    public function clearToken($user)
    {
        $user->password_reset_token = null;
        $user->save();
    }

}