<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PostRequest extends FormRequest
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
        $unique = 'unique:posts,slug';
        if ('admin.post.update' == $this->route()->getName()) {
            // получаем модель Post через маршрут admin/post/{post}
            $model = $this->route('post');
            /*
             * Проверка на уникальность slug, исключая этот пост по идентифкатору:
             * 1. posts — таблица базы данных, где проверяется уникальность
             * 2. slug — имя колонки, уникальность значения которой проверяется
             * 3. значение, по которому из проверки исключается запись таблицы БД
             * 4. поле, по которому из проверки исключается запись таблицы БД
             */
            $unique = 'unique:posts,slug,'.$model->id.',id';  //unique:posts,slug,2,id (не проверять уникальность slug поста с id 2)
        }
        return [
            'name' => [
                'required',
                'string',
                'min:3',
                'max:100',
            ],
            'slug' => [
                'required',
                'string',
                'max:100',
                $unique,
                'regex:~^[-_a-z0-9]+$~i',
            ],
            'category_id' => [
                'required',
                'numeric',
                'min:1'
            ],
            'excerpt' => [
                'required',
                'min:100',
                'max:500',
            ],
            'content' => [
                'required',
                'min:50',
            ],
            'image' => [
                'mimes:jpeg,jpg,png',
                'max:5000'
            ],
        ];
    }

    public function message()
    {
        return  [
            'required' => 'Поле «:attribute» обязательно для заполнения',
            'unique' => 'Такое значение поля «:attribute» уже используется',
            'min' => [
                'string' => 'Поле «:attribute» должно быть не меньше :min символов',
                'numeric' => 'Нужно выбрать категорию нового поста блога',
                'file' => 'Файл «:attribute» должен быть не меньше :min Кбайт'
            ],
            'max' => [
                'string' => 'Поле «:attribute» должно быть не больше :max символов',
                'file' => 'Файл «:attribute» должен быть не больше :max Кбайт'
            ],
            'mimes' => 'Файл «:attribute» должен иметь формат :values',
        ];
    }

    public function attributes() 
    {
        return [
            'name' => 'Наименование',
            'slug' => 'ЧПУ (англ.)',
            'category_id' => 'Категория',
            'excerpt' => 'Анонс поста',
            'content' => 'Текст поста',
            'image' => 'Изображение',
        ];
    }
}
