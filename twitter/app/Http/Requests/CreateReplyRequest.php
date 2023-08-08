<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateReplyRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'reply' => 'required|string|max:140',
        ];
    }

    public function messages()
    {
        return [
            'reply.required' => '返信内容を記入してください',
            'reply.string' => '文字列でお願いします',
            'reply.max' => '返信は140字以内でお願いします。'
        ];
    }

}
