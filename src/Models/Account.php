<?php
namespace Ysn\SuperCore\Models;

use Ysn\SuperAuth\Concerns\InteractsWithAuth;
use Ysn\SuperChat\Concerns\InteractsWithChat;
use Ysn\SuperCommon\Concerns\InteractsWithCommon;
use Ysn\SuperFeed\Concerns\InteractsWithFeed; 
use Ysn\SuperMarket\Concerns\InteractsWithMarket;

class Account extends BaseAccount
{
    use InteractsWithAuth;
    use InteractsWithChat;
    use InteractsWithFeed;
    use InteractsWithMarket;
    use InteractsWithCommon;

    // $with = []; // with cause subrelation load when querying posts, commments ...
    //protected $withCount = ['active_tokens as online']; cause problem when called before select() method


}
