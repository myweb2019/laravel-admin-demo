<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\Manager;
use App\Lib\Helper;

class IndexController extends Controller
{
    // 获取用户列表
    public function getUserlist(Request $request)
    {
        $params = $request->all();
        $keywords = $params['keywords'] ?? '';
        $pageSize = $params['pageSize'] ?? 20;

        $user = Manager::where('mobile', 'like', '%' . $keywords . '%')
            ->orWhere('username', 'like', '%' . $keywords . '%')
            ->orderBy('id', 'asc')
            ->paginate($pageSize);
        $data = $user->toArray();
        $collection = collect($data['data']);
        $newData = $collection->map(function ($item) {
            unset($item['password'], $item['updated_at'], $item['api_token']);
            return $item;
        });

        $result = [
            'data' => $newData,
            'allPage' => $data['last_page'],
            'total' => $data['total'],
            'current_page' => $data['current_page'],
            'pageSize' => $data['per_page']
        ];
        return Helper::sendSuccessJson($result);
    }
}
