<?php

namespace Ysn\SuperCore\Http\Requests;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Ysn\SuperCore\Http\Responses\HasRestfulResponse;

trait MyFailureTrait
{
    use HasRestfulResponse;
    public $validationErrors = null;
    public $authorizationMessage = null;

    protected function failedValidation(Validator $validator)
    {
        $this->validationErrors = $validator->errors();
        throw new HttpResponseException($this->validation($validator->errors()));
    }
    
    protected function failedAuthorization()
    {
        throw new HttpResponseException($this->unauthorized($this->authorizationMessage));
    }
}
