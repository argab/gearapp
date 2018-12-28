<?php

namespace api\traits;

use common\entities\user\UserFavorite;

trait FavoriteTrait
{
    public function toFavorite()
    {
        UserFavorite::toFavorite($this);
    }

    public function unFavorite()
    {
        UserFavorite::unFavorite($this);
    }


}
