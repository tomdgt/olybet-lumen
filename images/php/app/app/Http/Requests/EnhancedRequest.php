<?php

namespace App\Http\Requests;

use Illuminate\Support\Facades\App;
use Urameshibr\Requests\FormRequest;

class EnhancedRequest extends FormRequest
{
    protected function getRouteParameter(string $key): ?string
    {
        return $this->getRouteParameters()[$key] ?? null;
    }

    protected function getRouteParameters(): array
    {
        return App::getCurrentRoute()[2] ?? [];
    }
}
