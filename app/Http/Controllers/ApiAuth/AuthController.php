<?php

namespace App\Http\Controllers\ApiAuth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\Modelos\Comentario;
use App\Modelos\Producto;
use app\Http\Middleware\InicioSesion;
use Laravel\Sanctum\PersonalAccessToken;

class AuthController extends Controller
{
    public function indexcomments(Request $request, $id = null){
        
        if ($request->user()->tokenCan('user:info') || $request->user()->tokenCan('admin:admin')){
            if($id)
                return response()->json(["comentario"=>Comentario::find($id)],200);    
            return response()->json(["comentarios"=>Comentario::all()],200);
        }
        abort(401, "Scope invalido");        
    }

    public function indexProductos(Request $request, $id = null){
        if($request->user()->tokenCan('user:info'))
            if($id)
                return response()->json(["producto"=>Producto::find($id)],200);
        return response()->json(["Productos"=>Producto::all()],200);
        
    }

    public function comentariopersona(Request $request, $id){
        if ($request->user()->tokenCan('admin:admin'))
            $comment = Comentario::where('users_id', $id)->get();
            return response()->json($comment);
        abort(401, "Scope invalido");
    }

    public function index(Request $request)
    {
        if ($request->user()->tokenCan('user:info') && $request->user()->tokenCan('admin:admin'))
            return response()->json(["users" => User::all()], 200);
        
        if ($request->user()->tokenCan('user:info'))
            return response()->json(["perfil" => $request->user()], 200);
        
        abort(401, "Scope invalido");
    }

    public function logOut(Request $request){
        return response()->json(["afectados" => $request->user()->tokens()->delete()], 200);
    }

    public function logIn(Request $request){
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email|password' => ['Credenciales incorrectas...'],
            ]);
        }
        
        $token = $user->createToken($request->email, [$request->role])->plainTextToken;
        return response()->json(["token" => $token], 201);
    }

    public function eliminarTokens(Request $request){
        if ($request->user()->tokenCan('admin:admin'))
            $userReq = new User();
            $userReq = $userReq->find($request->id);
            return response()->json(["afectados"=>$userReq->tokens()->delete()],200);
    }

    public function otorgarPermisosEscritura(Request $request){
        if ($request->user()->tokenCan('admin:admin'))
            $request->validate([
                'email'=> 'required|email'
            ]);
            $user = User::where('email', $request->email)->first();
            if (!$user) {
                throw ValidationException::withMessages([
                    'email' => ['Usuario incorrecto...'],
                ]);
            }
            $token = $user->createToken($request->email, ['user:post'])->plainTextToken;
            return response()->json(["token" => $token], 201);
    }
    
    public function guardarcomentario(Request $request){
        if ($request->user()->tokenCan('user:info')||$request->user()->tokenCan('admin:admin'))
            $comm = new Comentario();
            $comm->titulo = $request->titulo;
            $comm->comentario = $request->comentario;
            $comm->users_id = $request->user;
            $comm->producto_id = $request->producto;

        if($comm->save())
            return response()->json(["comentario"=>$comm],201);
        return response()->json(null,400);

    }
    public function editarComentario(Request $request, $id){
        if($request->user()->tokenCan('user:info'))
            $actualizar = new Comentario();
            $actualizar = Comentario::find($id);
            $actualizar->titulo = $request->get('titulo');
            $actualizar->comentario = $request->get('comentario');
            $actualizar->save();
            return response()->json(["comentario"=>$actualizar],201);
        abort(401, "Scope invalido");
    }
    public function destruirComentario(Request $request, $id){
        if($request->user()->tokenCan('user:info'))
            $borrar = new Comentario();
            $borrar = Comentario::find($id);
            $borrar->delete();

        return response()->json(["comentarios"=>Comentario::all()],200);
    }
    public function guardarProducto(Request $request){
        if($request->user()->tokenCan('user:post'))
            
        $prod = new Producto();
            $prod->nombre = $request->nombre;
            $prod->precio = $request->precio;

        if($prod->save())
            return response()->json(["producto"=>$prod],201);
        abort(401, "Scope invalido");
    }

    public function editarProducto(Request $request, $id){
        if($request->user()->tokenCan('user:post'))
            $actualizar = new Producto();
            $actualizar = Producto::find($id);
            $actualizar->nombre = $request->get('nombre');
            $actualizar->precio = $request->get('precio');
            $actualizar->save();
            
        return response()->json(["producto"=>$actualizar],201);
        abort(401, "Scope invalido");

    }
    public function destruirProducto(Request $request, $id){
        if ($request->user()->tokenCan('admin:admin'))

            $borrar = new Producto();
            $borrar = Producto::find($id);
            $borrar->delete();
        return response()->json(["Productos"=>Producto::all()],200);
    }

    public function registro(Request $request){
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            'name' => 'required',
        ]);
        // $validatedData = $this->validate(
        //     $request,
        //     [
        //         'email' => 'required|email',
        //         'password' => 'required',
        //         'name' => 'required',
        //     ],
        //     [
        //         'required' => 'Please fill in the :attribute field'
        //     ]
        // );

        $user           = new User();
        $user->name     = $request->name;
        $user->email    = $request->email;
        $user->password = Hash::make($request->password);

        if ($user->save())
            return response()->json($user, 200);


        return abort(422, "fallo al insertar");
    }
}
