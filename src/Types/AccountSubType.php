<?php namespace Ysn\SuperCore\Types;
final class AccountSubType
{
    const UNDEFINED = 0;
    // FOR EMPLOYEE
    const ADMIN = 1;
    const MANAGER = 2;
    const MODERATOR = 3;
    // FOR PERSONAL
    const DRIVER = 4;
    // FOR BUSINESS
    const STORE_MANAGER = 5;
    const STORE = 6;
    // 
    const SUPPORTER = 7;
    const BRANCH_MANAGER = 8;
    const IMPORTER = 9;
    const GUEST = 99;

    const ALL = [
        AccountSubType::UNDEFINED,
        AccountSubType::ADMIN,
        AccountSubType::MANAGER,
        AccountSubType::MODERATOR,
        AccountSubType::DRIVER,
        AccountSubType::STORE_MANAGER,
        AccountSubType::STORE,
        AccountSubType::GUEST,
    ];
}
