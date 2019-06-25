<?php

namespace App;

use Laravel\Lumen\Application;

class OlybetApplication extends Application
{
    public function getCurrentRoute()
    {
        return $this->currentRoute;
    }
}
