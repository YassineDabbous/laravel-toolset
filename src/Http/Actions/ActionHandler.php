<?php

namespace Yaseen\Toolset\Http\Actions;

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Yaseen\Toolset\Http\Actions\BaseAction;
use Yaseen\Toolset\Http\Actions\Basics\DeleteAction;
use Yaseen\Toolset\Http\Responses\FormattedApiResponses;
class ActionHandler {
    use FormattedApiResponses;

    public static array $actions = [
        'delete' => DeleteAction::class,
    ];

    public static function add(string $key,string $class) {
        ActionHandler::$actions = array_merge(ActionHandler::$actions, [$key => $class]);
    }

    public function __invoke(Request $request) {
        try {
            $this->validateRequest($request);
        } catch (\Throwable $th) {
            return self::error($th->getMessage());
        }
        $this->prepare(app());
        $action = $this->resolveAction($request);
        return $action->perform($request);
        // try {
        //     return $action->prepare($request);
        // } catch (\Throwable $th) {
        //     return self::error($th->getMessage());
        // }
    	
    }
    
    protected function prepare(Application $app)
    {
        foreach (self::$actions as $key => $value) {
            $app->bind('actions.'.$key, $value);
        }
    }

    protected function resolveAction(Request $request) : BaseAction
    {
        return app('actions.'.$request->_action_);
    }

    protected function validateRequest(Request $request)
    {
        // \Log::debug($request->all());
        if (!$request->has('_type_') || !is_numeric($request->_type_)) {
            throw new \Exception('Invalid action type.');
        }
        if (!$request->has('_action_') || !is_string($request->_action_)) {
            throw new \Exception('Invalid action request.');
        }
        // if($request->filled('_keys_') && (!is_array($request->_keys_) || count($request->_keys_)==0)){
        //     throw new \Exception('Invalid action request, no items selected !');
        // }
        // if($request->filled('_filter_') && !is_array($request->_filter_)){
        //     throw new \Exception('Invalid action filter.');
        // }
        // if($request->filled('_payload_') && !is_array($request->_payload_)){
        //     throw new \Exception('Invalid action data.');
        // }
    }
    

    
}
