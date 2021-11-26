<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CommentRequest extends FormRequest
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
        $rules = [
            'content' => [
                'required',
                'string',
                'max:500',
            ],
        ];
        // при добавлении нового комментария есть скрытое поле post_id
        if ($this->routeIs('blog.comment')) {
            $rules['post_id'] = [
                'required',
                'numeric',
                'min:1',
                'exists:posts,id'
            ];
        }
        return $rules;
    }

    /**
     * Возвращает массив сообщений об ошибках для заданных правилy
     */
    public function messages() {
        return [
            'required' => 'Поле «:attribute» обязательно для заполнения',
            'max' => 'Поле «:attribute» должно быть не больше :max символов',
            'numeric' => 'Идентификатор публикации должен быть числом',
            'min' => 'Идентификатор публикации должен быть :min или больше',
            'exists' => 'Публикации с таким идентификатором не существует',
        ];
    }

    /**
     * Возвращает массив дружественных пользователю названий полей
     */
    public function attributes() {
        return [
            'content' => 'Текст комментария'
        ];
    }
}
