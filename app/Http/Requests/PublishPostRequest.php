<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PublishPostRequest extends FormRequest
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
        //todo :: Максимальная длина заголовка
        return [
            'body'=>['required'],
            'title'=>['required'],
            'communityForSendId'=>['required']
        ];
    }
}