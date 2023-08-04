<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Carbon\Carbon;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $pageSize = $request->input('pageSize', 10);
        $page = $request->input('page', 1);
        $paging = filter_var($request->input('paging', true), FILTER_VALIDATE_BOOLEAN);

        $query = User::query();

        if ($paging) {
            $users = $query->paginate($pageSize, ['*'], 'page', $page);
        } else {
            $users = $query->simplePaginate($pageSize, ['*'], 'page', $page);
        }

        return response()->json(['users' => $users], 200);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'surname' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'nullable|min:8',
        ]);

        $user = new User();
        $user->name = $request->name;
        $user->surname = $request->surname;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->save();

        //token
        // $currentUser = User::where([
        //     ['email', '=', $user->email],
        // ])->first();
        
        // $tokenResult = $currentUser->createToken('Personal Access Token');
        // $token = $tokenResult->token;
        // $token->expires_at = Carbon::now()->addMinutes(2);
        // $token->save();

        // return response()->json(['message' => 'Utilizador criado com sucesso!', 'user' => $user, "accessToken" => $tokenResult->accessToken], 201);
        return response()->json(['message' => 'Utilizador criado com sucesso!', 'user' => $user], 201);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string',
            'surname' => 'required|string',
            'email' => 'required|email|unique:users,email,' . $id
        ]);

        $user = User::findOrFail($id);
        $user->name = $request->name;
        $user->surname = $request->surname;
        $user->email = $request->email;
        $user->save();

        return response()->json(['message' => 'Utilizador actualizado com sucesso!', 'user' => $user], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return response()->json(['message' => 'Utilizador eliminado com sucesso!'], 200);
    }
}
