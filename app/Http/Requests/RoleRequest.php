<<?php



use Illuminate\Foundation\Http\FormRequest;

class RoleRequest extends FormRequest
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
        $unique = 'unique:roles,slug';
        //проверка соответствует ли входящий запрос именованному маршруту. 
        if($this->routeIs(['admin.role.update'])) { 
            // получаем модель Role через маршрут admin/role/{role} 
            $model = $this->route('role');
            $unique = 'unique:roles,slug,'.$model->id.',id';
        }
        return  [
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

    public function message()
    {
        return  [
            'required' => 'Поле «:attribute» обязательно для заполнения',
            'max' => 'Поле «:attribute» должно быть не больше :max символов',
        ];
    }

    public function attributes() 
    {
        return [
            'name' => 'Наименование',
            'slug' => 'Идентификатор'
        ];
    }
}
