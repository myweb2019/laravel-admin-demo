<?php

namespace App\Http\Request\Login;

use Illuminate\Contracts\Validation\Validator;

use Illuminate\Foundation\Http\FormRequest;

use Illuminate\Http\Exceptions\HttpResponseException;

use Request;

class Login extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'username' => 'required',
            'password' => 'required|min:6',
            // 'key' => 'required',
            // 'code' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'username.required' => '账号不能为空',
            'password.required' => '密码不能为空',
            'password.min' => '密码不能少于6位',
            // 'key.required'=> '缺少参数key',
            // 'code.required' => '验证码不能为空',
        ];
    }

    /**
     * 验证错误信息的返回
     */
    protected function failedValidation(Validator $validator)
    {
        $error = $validator->errors()->all();
        throw new HttpResponseException(response()->json(['msg' => $error[0],'status' => '422'], 422));
    }
}
