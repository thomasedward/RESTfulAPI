<?php
namespace App\Traits;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
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
        $collection  = $this->sortDate($collection,$transformer);
        $collection  = $this->filterDate($collection,$transformer);
        $collection  = $this->paginate($collection);
        $collection  = $this->transformerDate($collection,$transformer);
        $collection  = $this->catchResponse($collection);

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
    protected function paginate(Collection $collection)
    {
        $rules= [
          'pre_page' => 'integer|min:2|max:50',
        ];
        Validator::validate(request()->all() , $rules);
        $page = LengthAwarePaginator::resolveCurrentPage();
        $prePage = 15;
        if (request()->has('pre_page'))
        {
            $prePage = (int) request()->pre_page;
        }
        $results = $collection->slice(($page - 1 ) * $prePage , $prePage)->values();
        $paginated = new LengthAwarePaginator($results , $collection->count(),$prePage , $page ,[
           'path' => LengthAwarePaginator::resolveCurrentPage(),
        ]);
        $paginated->appends(request()->all());
        return $paginated;

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
    protected function catchResponse($data)
    {
        $url = request()->url();
        $queryParams = request()->query();
        ksort($queryParams);
        $queryString = http_build_query($queryParams);
        $fullUrl = "{$url}?{$queryString}";
        //return $data;

        //$i = 1;
        $text = '';
        foreach ($data as $d)
        {
            foreach ($d as $da)
            {
                $text = $da;

                //return $text;
            }



        }
        DB::table('search_urls')
            ->insert(['url' => $url,
                      'FullUrl' => $fullUrl,
                      'data' => (int)$data,
                      'created_at' =>\Carbon\Carbon::now()->toDateTimeString(),
                      'updated_at' => \Carbon\Carbon::now()->toDateTimeString() ]);


        return Cache::remember($fullUrl , 30/60 , function () use ($data) {

           return $data;
        });
    }
}
