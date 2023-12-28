<?php

namespace App\Services\Admin\User;

use App\Actions\Utility\PaginateCollection;
use App\Models\User;

class UserService
{
    public function getData($request)
    {
        $query = User::query()->with(['village', 'roles']);

        $query->withoutRole('Super Admin');

        $query->when($request->has('search'), function ($q) use ($request) {
            $q->where('name', 'like', '%' . $request->search . '%')->orWhereHas('village', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%');
            });
        });

        $per_page = $request->per_page ?? 10;

        $paginateCollection = new PaginateCollection();

        $data = $paginateCollection->handle($query->get(), $per_page);

        return $data;
    }

    public function store($request)
    {
        $data = $request->validated();

        $data['password'] = bcrypt($data['password']);

        $user = User::create($data);

        $user->assignRole('Admin');

        return $user;
    }

    public function update($request, $id)
    {
        $data = $request->validated();

        $user = User::findOrFail($id);

        if ($request->has('password')) {
            $data['password'] = bcrypt($data['password']);
        }

        $user->update($data);

        return $user;
    }

    public function destroy($request, $id)
    {
        $user = User::findOrFail($id);

        $user->delete();

        return $user;
    }

    public function destroyMany($request)
    {
        $ids = $request->ids;

        $users = User::whereIn('id', $ids)->get();

        foreach ($users as $user) {
            $user->delete();
        }

        return $users;
    }
}
