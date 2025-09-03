<?php namespace Ysn\SuperCore\Types;


final class AccountStatus
{
    const ACTIVE = 0;
    const INREVIEW = 1;
    const DISACTIVATED = 2;
    const HIDDEN = 3; // when reported by users for illegal content
    const DISABLED = 4; // disabled by administration


    const list = [
        AccountStatus::INREVIEW         =>  'INREVIEW',
        AccountStatus::ACTIVE           =>  'ACTIVE',
        AccountStatus::DISACTIVATED     =>  'DISACTIVATED',
        AccountStatus::HIDDEN           =>  'HIDDEN',
        AccountStatus::DISABLED         =>  'DISABLED',
    ];
}
