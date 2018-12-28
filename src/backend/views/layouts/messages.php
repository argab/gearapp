<?php

    $mess_success = !empty($mess_success) ? $mess_success : (Yii::$app->session->getFlash('mess_success') ?: null);
    $mess_error = !empty($mess_error) ? $mess_error : (Yii::$app->session->getFlash('mess_error') ?: (isset($model->errors) ? $model->errors : null));
    $mess_info = !empty($mess_info) ? $mess_info : (Yii::$app->session->getFlash('mess_info') ?: null);

    if( ! empty($mess_success) ||  ! empty($mess_error) ||  ! empty($mess_info)):
?>

<ul class="list-unstyled col-md-12 col-sm-12 col-xs-12" style="margin-top: 20px">

    <?php
        if( ! empty($mess_success)):

            foreach((array)$mess_success as $mess){

                ?>
                    <li class="alert alert-success"><?= is_array($mess) ? (is_array($mess[0]) ? join('<br>', $mess[0]) : $mess[0]) : $mess ?></li>
                <?php

            }

        endif;
    ?>

    <?php
    if( ! empty($mess_error)):

        foreach((array)$mess_error as $mess) {

            if (!isset($m) || $mess != $m ) {

            ?>
            <li class="alert alert-danger"><?= is_array($mess) ? (is_array($mess[0]) ? join('<br>', $mess[0]) : $mess[0]) : $mess ?></li>
            <?php
            }

            $m = &$mess;
        }

    endif;
    ?>

    <?php
    if( ! empty($mess_info)):

        foreach((array)$mess_info as $mess){

            ?>
            <li class="alert alert-info"><?= is_array($mess) ? (is_array($mess[0]) ? join('<br>', $mess[0]) : $mess[0]) : $mess ?></li>
            <?php

        }

    endif;
    ?>
</ul>

<div class="clearfix"></div>

        <?php
            endif;
        ?>