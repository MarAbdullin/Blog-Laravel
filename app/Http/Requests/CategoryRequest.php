<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CategoryRequest extends FormRequest
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
        $unique = 'unique:categories,slug';
        //проверка соответствует ли входящий запрос именованному маршруту.
        if ($this->routeIs('admin.category.update')) {
            // получаем модель Category через маршрут admin/category/{category}
            $model = $this->route('category');
            $unique = 'unique:categories,slug,'.$model->id.',id'; //unique:categories,slug,2,id (не проверять уникальность в таблице categories поле slug с id 2 )
        }
        return [
            'name' => [
                'required',
                'min:3',
                'max:100',
            ],
            'slug' => [
                'required',
                'max:100',
                $unique,
                'regex:~^[-_a-z0-9]+$~i',
            ],
            'content' => [
                'max:500',
            ],
            'image' => [
                'mimes:jpeg,jpg,png',
                'max:5000'
            ],
        ];
    }

  
    //Возвращает массив сообщений об ошибках для заданных правил
     public function messages() {
        return [
            'required' => 'Поле «:attribute» обязательно для заполнения',
            'unique' => 'Такое значение поля «:attribute» уже используется',
            'min' => [
                'string' => 'Поле «:attribute» должно быть не меньше :min символов',
                'file' => 'Файл «:attribute» должен быть не меньше :min Кбайт'
            ],
            'max' => [
                'string' => 'Поле «:attribute» должно быть не больше :max символов',
                'file' => 'Файл «:attribute» должен быть не больше :max Кбайт'
            ],
            'mimes' => 'Файл «:attribute» должен иметь формат :values',
        ];
    }

   
    //Возвращает массив дружественных пользователю названий полей
    public function attributes() {
        return [
            'name' => 'Наименование',
            'slug' => 'ЧПУ (англ.)',
            'content' => 'Краткое описание',
            'image' => 'Изображение',
        ];
    }
}
