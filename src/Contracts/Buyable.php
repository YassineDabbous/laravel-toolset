<?php

declare(strict_types=1);

namespace Ysn\SuperCore\Contracts;

interface Buyable
{
    public function getId(): string|int;

    public function getName(): string;
    public function getImage(): ?string;

    public function getPrice(): int;

    
    public function isOnStock(int $qty = 1): bool;
    public function decreaseQuantity(int $qty = 1);
}
