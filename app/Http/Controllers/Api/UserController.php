<?php

namespace App\Http\Controllers\Api;

use App\Helpers\User\UserHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;

class UserController extends Controller
{
    private $userHelper;

    public function __construct()
    {
        $this->userHelper = new UserHelper;
    }

    public function destroy($id)
    {
        $user = $this->userHelper->delete($id);

        if (! $user) {
            return response()->failed(['Sorry user data not found'], 404);
        }

        return response()->success($user, 'User deleted successfully');
    }

    public function index(Request $request)
    {
        $filter = [
            'username' => $request->username ?? '',
            'email' => $request->email ?? '',
        ];
        $users = $this->userHelper->getAll($filter, $request->page ?? 1, $request->per_page ?? 25, $request->sort ?? '');

        return response()->success([
            'list' => UserResource::collection($users['data']),
            'meta' => [
                'total' => $users['total'],
            ],
        ]);
    }

    public function show($id)
    {
        $user = $this->userHelper->getById($id);

        if (! ($user['status'])) {
            return response()->failed(['Sorry user data not found'], 404);
        }

        return response()->success(new UserResource($user['data']));
    }

    public function store(UserRequest $request)
    {

        if (isset($request->validator) && $request->validator->fails()) {
            return response()->failed($request->validator->errors());
        }

        $payload = $request->only(['email', 'username', 'password']);
        $user = $this->userHelper->create($payload);

        if (! $user['status']) {
            return response()->failed($user['error']);
        }

        return response()->success(new UserResource($user['data']), 'User created successfully');
    }

    public function update(UserRequest $request, $id)
    {
        if (isset($request->validator) && $request->validator->fails()) {
            return response()->failed($request->validator->errors());
        }

        $payload = $request->only(['email', 'username', 'password']);
        $user = $this->userHelper->update($payload, $id ?? 0);

        if (! $user['status']) {
            return response()->failed($user['error']);
        }

        return response()->success(new UserResource($user['data']), 'User updated successfully');
    }
}
