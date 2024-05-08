<?php

function env(string $name, mixed $default = null): mixed
{
    return getenv($name) ?? $default;
}
