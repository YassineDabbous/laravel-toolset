<?php namespace Ysn\SuperCore\Types;


abstract class AppealStatus
{
    const DEFAULT = AppealStatus::PENDING;

    const PENDING = 0;
    const ACCEPTED = 1;
    const REFUSED = 2;
}
