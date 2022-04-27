<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        return view('content.user.table');
    }

    public function api()
    {
        $model = User::query();
        return \DataTables::eloquent($model)
            ->toJson();
    }

    public function list_select(Request $data)
    {
        $item = User::where('name', 'like', '%' . $data->q . '%')->get();
        $itemCount =  $item->count();
        //? create if not find
        // if ($itemCount == 0) {
        //     $item[] = ['id' =>  $data->q, 'name' => $data->q];
        //     $itemCount = 1;
        // }

        return ['total_count' => $itemCount, 'item' => $item];
    }

    public function create()
    {
        return view('content.user.add');
    }

    public function store(UserRequest $data)
    {
        User::create($data->validated());

        session()->flash('toastr', ['type' => 'success', 'title' => __('toastr.title.success'), 'contant' =>  __('toastr.contant.success')]);
        return redirect(route('user'));
    }

    public function edit($id)
    {
        $last = User::findOrFail($id);

        return view('content.user.edit', ['last' => $last]);
    }

    public function update(UserRequest $data)
    {
        $user = User::findOrFail($data->id);

        $user->update($data->validated());
        $user->password = Hash::make($data->password);
        $user->save();

        session()->flash('toastr', ['type' => 'success', 'title' => __('toastr.title.success'), 'contant' =>  __('toastr.contant.success')]);
        return redirect(route('user'));
    }
}
