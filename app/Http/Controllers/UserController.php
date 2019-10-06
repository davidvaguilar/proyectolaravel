<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;

class UserController extends Controller
{
    public function index(Request $request)
    {
        //if( !$request->ajax() ) return redirect('/');
        $buscar = $request->buscar;
        $criterio = $request->criterio;

        if( $buscar == '' ){
            $usuarios = User::join('roles', 'users.idrol', '=', 'roles.id')
                    ->select('users.id','users.nombre', 'users.tipo_documento',
                            'users.num_documento', 'users.direccion', 'users.telefono',
                            'users.email', 'users.usuario', 'users.password',
                            'users.condicion', 'users.idrol', 'roles.nombre as rol')
                    ->orderBy('id', 'desc')->paginate(3);
            
        } else {
            $usuarios = User::join('roles', 'users.idrol', '=', 'roles.id')
                    ->select('users.id','users.nombre', 'users.tipo_documento',
                            'users.num_documento', 'users.direccion', 'users.telefono',
                            'users.email', 'users.usuario', 'users.password',
                            'users.condicion', 'users.idrol', 'roles.nombre as rol')
                    ->where('users.'.$criterio, 'like', '%'.$buscar.'%')
                    ->orderBy('id', 'desc')->paginate(3);
        }
        
        return[
            'pagination' => [
                'total' => $usuarios->total(),
                'current_page' => $usuarios->currentPage(),
                'per_page' => $usuarios->perPage(),
                'last_page' => $usuarios->lastPage(),
                'from' => $usuarios->firstItem(),
                'to' => $usuarios->lastItem(),
            ],
            'usuarios' => $usuarios
        ];
    }

    public function store(Request $request)
    {
        if( !$request->ajax()) return redirect('/');
        $usuario = new User();
        $usuario->nombre = $request->nombre;
        $usuario->tipo_documento = $request->tipo_documento;
        $usuario->num_documento = $request->num_documento;
        $usuario->telefono = $request->telefono;
        $usuario->email = $request->email;
        $usuario->direccion = $request->direccion;
        $usuario->usuario = $request->usuario;
        $usuario->password = bcrypt($request->password);
        $usuario->idrol = $request->idrol;
        $usuario->save();
    }

    public function update(Request $request)
    {
        if( !$request->ajax()) return redirect('/');
        $usuario = User::findOrFail($request->id);
        $usuario->nombre = $request->nombre;
        $usuario->tipo_documento = $request->tipo_documento;
        $usuario->num_documento = $request->num_documento;
        $usuario->telefono = $request->telefono;
        $usuario->email = $request->email;
        $usuario->direccion = $request->direccion;
        $usuario->usuario = $request->usuario;
        $usuario->password = bcrypt($request->password);
        $usuario->idrol = $request->idrol;
        $usuario->save();
    }

    public function desactivar(Request $request)
    {
        if( !$request->ajax()) return redirect('/');
        $usuario = User::findOrFail($request->id);
        $usuario->condicion = '0';
        $usuario->save();
    }

    public function activar(Request $request)
    {
        if( !$request->ajax()) return redirect('/');
        $usuario = User::findOrFail($request->id);
        $usuario->condicion = '1';
        $usuario->save();
    }
}
