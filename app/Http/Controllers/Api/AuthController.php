<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\User;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class AuthController extends Controller
{
    /**
     *
     * Construtor
     *
     */
    public function __construct(
    ){
        $this->middleware('auth:api', ['except' => ['login', 'register','guest']]);
    }

    /**
     * Funções das Requests
     */
    public function register(Request $request)
    {
        try {
            $user = User::create([
                'name'    => $request->name,
                'email' => $request->email,
                'user' => $request->user,
                'lat' => $request->lat,
                'long' => $request->long,
                'alcool_em_gel' => $request->alcool_em_gel,
                'alcool_liquido' => $request->alcool_liquido,
                'mascara' => $request->mascara,
                'luva' => $request->luva,
                'aberto' => $request->aberto,
                'obeservacao' => $request->obeservacao,
                'url' => $request->url,
                'password' => $request->password,
            ]);

            $token = $this->guard()->login($user);
        } catch (\Exception $e) {
            //return $e;
            return response()->json(['error' => 'Seus dados já possuem em nossa base de dados'], 401);
        }
        return $this->respondWithToken($token);
    }

    public function login(Request $request)
    {
        $credentials = request(['email', 'password']);
        if (! $token = $this->guard()->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        return $this->respondWithToken($token);
    }

    public function logout()
    {
        $this->guard()->logout();
        return response()->json(['message' => 'Saiu com sucesso']);
    }

    public function businessGet()
    {
        if (!$this->guard()->user()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        return response()->json($this->guard()->user());
    }

    public function businessUpdate(Request $request)
    {
        if (!$this->guard()->user()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        try{
         $this->guard()->user()->update($request->except(['name','email','user','email_verified_at','type','remember_token']));
        }catch(\Exception $e){
            return response()->json(['error' => 'Não foi possivel atualizar'], 401);
        }
        return response()->json(['message' => 'Salvo com sucesso']);
    }

    // Usuários gest
    public function guest()
    {
        $users = User::whereNotNull("lat")->get();
        return response()->json($users);
    }


    /**
     * Funções para as requests
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type'   => 'bearer',
            'expires_in'   => auth('api')->factory()->getTTL() * 60
        ]);
    }

    /**
     * Get the guard to be used during authentication.
     */
    public function guard()
    {
        return Auth::guard('api');
    }
}
