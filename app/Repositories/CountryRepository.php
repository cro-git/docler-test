<?php


namespace App\Repositories;


use App\Country;

class CountryRepository
{
    private $model;

    public function __construct(Country $country)
    {
        $this->model = $country;
    }

    public function model()
    {
        return $this->model;
    }

    public function all()
    {
        return $this->model->all();
    }

    public function findByIso2($iso2)
    {
        return $this->model->where('iso2',$iso2)->first();
    }
}
