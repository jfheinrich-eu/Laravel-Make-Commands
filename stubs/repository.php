<?php declare(strict_types=1);

namespace {

    use Illuminate\Database\Eloquent\Collection;

{ nanespace }}

use {{ model_namespace }};

class {{ class }}
{
    public function __construct(protected {{ model }} ${{ modelvar }}) {}

    public function all(): Collection
    {
        return $this->{{ modelvar }}::all();
    }
}
