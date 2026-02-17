<?php

namespace Yaseen\Toolset\Http\Requests;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Yaseen\Toolset\Http\Responses\FormattedApiResponses;

/**
 * Trait HasStandardizedErrors
 *
 * Overrides the default FormRequest failure behavior to use our
 * standardized JSON response format.
 */
trait HasStandardizedErrors
{
    use FormattedApiResponses;

    public ?string $authorizationMessage = null;

    /**
     * Handle a failed validation attempt.
     *
     * @param  \Illuminate\Contracts\Validation\Validator  $validator
     * @return void
     *
     * @throws \Illuminate\Http\Exceptions\HttpResponseException
     */
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException($this->validation( $validator->errors() ));
    }

    /**
     * Handle a failed authorization attempt.
     *
     * @return void
     *
     * @throws \Illuminate\Http\Exceptions\HttpResponseException
     */
    protected function failedAuthorization()
    {
        throw new HttpResponseException($this->unauthorized($this->authorizationMessage));
    }
}
