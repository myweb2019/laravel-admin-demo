<?php

namespace App\Lib;

class Helper {
    /**
     * 错误信息json格式
     * 
     * @param {string} $message
     * @return {*} { msg: $message, status: 422,}
     */
    public static function sendErrorJson(string $message)
    {
        return response()->json([
            'msg' => $message,
            'status' => '422',
        ], 422);
    }

    /**
     * 成功信息json格式
     * 
     * @param {*} $data
     * @return {*} { msg: 成功, status: 200, data: any}
     */
    public static function sendSuccessJson($data)
    {
        return response()->json([
            'msg' => '成功',
            'status' => '200',
            'data' => $data
        ]);
    }
}