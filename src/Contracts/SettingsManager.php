<?php

declare(strict_types=1);


namespace Ysn\SuperCore\Contracts;
 

interface SettingsManager
{
    public function update(array $updates, string $key = 'settings.config') : array;
}
