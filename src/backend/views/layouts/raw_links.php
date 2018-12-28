<?php
use yii\helpers\Html;
?>

<div class="col-md-6 col-sm-4 col-xs-12 clearfix">

    <ul id="dropdown-info" class="list-inline links-list pull-right">

        <!-- Language Selector -->
        <?php
        /*
         <li class="dropdown language-selector">

        Language: &nbsp;
            <a href="#" class="dropdown-toggle" data-toggle="dropdown" data-close-others="true">
                <img src="/admin/assets/images/flag-uk.png" />
            </a>

            <ul class="dropdown-menu pull-right">
                <li>
                    <a href="#">
                        <img src="/admin/assets/images/flag-de.png" />
                        <span>Deutsch</span>
                    </a>
                </li>
                <li class="active">
                    <a href="#">
                        <img src="/admin/assets/images/flag-uk.png" />
                        <span>English</span>
                    </a>
                </li>
                <li>
                    <a href="#">
                        <img src="/admin/assets/images/flag-fr.png" />
                        <span>François</span>
                    </a>
                </li>
                <li>
                    <a href="#">
                        <img src="/admin/assets/images/flag-al.png" />
                        <span>Shqip</span>
                    </a>
                </li>
                <li>
                    <a href="#">
                        <img src="/admin/assets/images/flag-es.png" />
                        <span>Español</span>
                    </a>
                </li>
            </ul>

        </li>
        <li>
            <a href="#">
                <i class="entypo-chat"></i>Чат
                <span class="badge badge-success chat-notifications-badge">0</span>
            </a>
        </li>
        */
        ?>

        <!--<li class="sep"></li>

        <li>
            <a href="#">
                <i class="entypo-mail"></i>Заявки
                <span class="badge badge-secondary chat-notifications-badge">3</span>
            </a>
        </li>-->

        <li class="sep hidden-xs"></li>

        <li class="hidden-xs">
            <?= Html::a('Выход <i class="entypo-logout right"></i>',
                ['/admin/logout'],
                ['data-method'=>'post'])
            ?>
        </li>
    </ul>

</div>