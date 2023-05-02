<?php

declare(strict_types=1);

namespace JfheinrichEu\LaravelMakeCommands\Traits;

trait UseDto
{
    use DtoPropertyAccess;
    use DtoGetterAndSetter;
    use DtoTransformation;
}
