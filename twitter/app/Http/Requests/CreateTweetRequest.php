<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateTweetRequest extends FormRequest
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
            'tweet' => 'required|string|max:140',
            'image' => 'max:100000|mimes:jpg,jpeg,png,gif',
        ];
    }

    public function messages()
    {
        return [
            'tweet.required' => 'ツイートは必須項目です',
            'tweet.string' => '文字でお願いします',
            'tweet.max' => 'ツイートは140字以内でお願いします。',
            'image.max' => 'ファイルが大きすぎます。100MB以下でお願いします。' ,
            'image.mimes' => 'jpg,jpeg,png,gifのどれかでお願いします。',
        ];
    }

    public function attributes()
    {
        return [
            'tweet' => 'ツイート',
        ];
    }
}
