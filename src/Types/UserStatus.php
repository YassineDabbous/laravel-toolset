<?php namespace Ysn\SuperCore\Types;


abstract class UserStatus
{
    const UNVERIFIED = 0;
    const ACTIVE = 1;
    const DISACTIVATED = 2;
    const DISABLED = 4; // disabled by administration


    const list = [
        UserStatus::UNVERIFIED       =>  'UNVERIFIED',
        UserStatus::ACTIVE           =>  'ACTIVE',
        UserStatus::DISACTIVATED     =>  'DISACTIVATED',
        UserStatus::DISABLED         =>  'DISABLED',
    ];
}
