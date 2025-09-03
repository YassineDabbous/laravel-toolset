<?php namespace Ysn\SuperCore\Types;

final class AccountType
{
    public static function requireCategory(int $type) : bool {
        return in_array($type, config('settings.config.types_require_category', []));
    }
    
    const UNDEFINED = 0;
    const EMPLOYEE = 1;
    const PERSONAL = 2;
    const BUSINESS = 3;

    const ALL = [
        AccountType::UNDEFINED,
        AccountType::EMPLOYEE,
        AccountType::PERSONAL,
        AccountType::BUSINESS,
    ];
    

    // const list = [
    //     AccountType::EMPLOYEE          =>  'EMPLOYEE',
    //     AccountType::PERSONAL          =>  'PERSONAL',
    //     AccountType::BUSINESS          =>  'BUSINESS',
    // ];
}
