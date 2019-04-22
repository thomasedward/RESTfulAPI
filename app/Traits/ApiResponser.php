<?php
namespace App\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

trait  ApiResponser
{

    private function successResponse($data, $code)
    {
        return response()->json($data,$code);
    }
    protected function errorResponse($message , $code)
    {
        return response()->json(['error' => $message , 'code'=> $code],$code);
    }

    protected function showAll(Collection $collection , $code = 200)
    {
        if ($collection->isEmpty())
        {
            return $this->successResponse(['data'=> $collection],$code);
        }
        $transformer = $collection->first()->transformer;
        $collection = $this->sortDate($collection,$transformer);
        $collection = $this->filterDate($collection,$transformer);
        $collection = $this->transformerDate($collection,$transformer);

        return $this->successResponse(['data'=> $collection],$code);
    }

    protected function showOne(Model $model , $code = 200)
    {
        $transformer = $model->transformer;
        $model = $this->transformerDate($model,$transformer);
        return $this->successResponse(['data'=> $model],$code);
    }
    protected function sortDate(Collection $collection ,$transformer)
    {
        if (request()->has('sort_by'))
        {
            $attribute = $transformer::originalAttribute(request()->sort_by);
            $collection = $collection->sortBy->{$attribute};
        }

        return $collection;
    }
    protected function filterDate(Collection $collection ,$transformer)
    {
        foreach (request()->query() as $query => $value) {
            $attribute = $transformer::originalAttribute($query);

            if (isset($attribute, $value)) {
                $collection = $collection->where($attribute, $value);
            }
        }

        return $collection;
    }
    protected function showMessage($message  , $code = 200)
    {
        return $this->successResponse(['data'=> $message],$code);
    }
    protected function transformerDate($data,$transformer)
    {
        $transformation = fractal($data , new $transformer);
        return $transformation->toArray();
    }
}
