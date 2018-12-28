<?php

namespace lib\services\auth;


use common\entities\user\User;
use common\forms\reset\RequestByEmailForm;
use common\forms\reset\SetPasswordByEmailForm;
use SebastianBergmann\Timer\RuntimeException;
use Yii;

class ResetPasswordByEmailService
{

    /**
     * Resets password.
     *
     * @param SetPasswordByEmailForm $form
     *
     * @return bool
     * @throws \yii\base\Exception
     */
    public function resetPassword(SetPasswordByEmailForm $form)
    {
        $user = $form->user;
        $user->setPassword($this->password);
        $user->removePasswordResetToken();

        return $user->save(false);
    }


    /**
     * Sends an email with a link, for resetting the password.
     */
    public function sendEmail($form): void
    {
        /* @var $user User */
        $user = User::findOne([
            'status' => User::STATUS_ACTIVE,
            'email'  => $form->email,
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
                ['html' => 'passwordResetToken-html', 'text' => 'passwordResetToken-text'],
                ['user' => $user]
            )
            ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name . ' robot'])
            ->setTo($form->email)
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
}