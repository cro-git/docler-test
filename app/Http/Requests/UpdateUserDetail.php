<?php

namespace App\Http\Requests;


use App\Country;
use App\Repositories\CountryRepository;
use Cacheable;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Symfony\Component\HttpFoundation\JsonResponse;

use Symfony\Component\HttpFoundation\Response;

class UpdateUserDetail extends FormRequest
{

    private $countryRepository = null;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }


    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response($validator->errors(), Response::HTTP_UNPROCESSABLE_ENTITY));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'citizenship' => 'country',
            'first_name' => 'alpha',
            'last_name' => 'alpha',
            'phone_number' => 'phone',
            'email' => 'email:rfc,dns',
            'active' => 'boolean',
        ];
    }

    public function expectsJson()
    {
        return ($this->route()->getAction()['middleware'] == 'api');
    }


    public function withValidator($validator)
    {
        $validator->addExtension('country', function ($attribute, $value, $parameters, $validator) {
            /** @var CountryRepository $countryRepository */
            $countryRepository = Cacheable::wrap(app(CountryRepository::class));
            return $countryRepository->findByIso2($value);
        });

        $validator->addReplacer('country', function ($message, $attribute, $rule, $parameters, $validator) {
            return __("Invalid country for :attribute", compact('attribute'));
        });


        $validator->addExtension('phone', function ($attribute, $value, $parameters, $validator) {
            return preg_match('%^(?:(?:\(?(?:00|\+)([1-4]\d\d|[1-9]\d?)\)?)?[\-\.\ \\\/]?)?((?:\(?\d{1,}\)?[\-\.\ \\\/]?){0,})(?:[\-\.\ \\\/]?(?:#|ext\.?|extension|x)[\-\.\ \\\/]?(\d+))?$%i', $value) && strlen($value) >= 10;
        });

        $validator->addReplacer('phone', function ($message, $attribute, $rule, $parameters, $validator) {
            return __(":attribute is an invalid phone number", compact('attribute'));
        });
    }
}
