<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var string[]
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var string[]
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    protected function invalid($request, $exception) 
    {   /* что бы flash переменная session не изчезала так как может быть два запроса
         *в случае неправельной валидации данных при редактирование из превью (первый запрос)
         *отправка формы (второй запрос)
        */
        $redirect = parent::invalid($request, $exception);
        if (session('preview')) {
            return $redirect->with('preview', 'yes');
        }
        return $redirect;
    }
}
