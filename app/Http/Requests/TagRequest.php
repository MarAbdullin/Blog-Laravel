<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TagRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $unique = 'unique:tags,slug';
        //проверка соответствует ли входящий запрос именованному маршруту.
        if($this->routeIs('admin.tag.update')){
            // получаем модель Category через маршрут admin/category/{category}
            $model = $this->route('tag');

            $unique = 'unique:tags,slug,'.$model->id.',id'; //unique:tags,slug,2,id
        }
        return [
            'name' => [
                'required',
                'string',
                'max:50',
            ],
            'slug' => [
                'required',
                'max:50',
                $unique,
                'regex:~^[-_a-z0-9]+$~i',
            ]
        ];
    }

    //Возвращает массив сообщений об ошибках для заданных правил
    public function messages() {
        return [
            'required' => 'Поле «:attribute» обязательно для заполнения',
            'max' => 'Поле «:attribute» должно быть не больше :max символов',
        ];
    }

    //Возвращает массив дружественных пользователю названий полей
    public function attributes() {
        return [
            'name' => 'Наименование',
            'slug' => 'ЧПУ (англ.)'
        ];
    }
}
