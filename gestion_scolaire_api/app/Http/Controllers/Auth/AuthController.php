<?php
// App\Http\Controllers\Auth\AuthController.php
namespace App\Http\Controllers\Auth; // Correction de l'espace de noms

use App\Http\Controllers\Controller; // Cette ligne est cruciale
use Illuminate\Support\Facades\Hash; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api')->except(['login', 'register']);
    }

    public function login(Request $request)
    {
        // Juste pour la première fois : assigner la permission "all" à l'utilisateur 1
    //     $admin = \App\Models\User::find(1);
    //     if ($admin && !$admin->hasPermission('all')) {
    //         // Si la permission "all" n'existe pas encore, on la crée
    //         $permission = \App\Models\Permission::firstOrCreate(
    //             ['name' => 'all'],
    //             ['display_name' => 'All Permissions', 'description' => 'Accès total à toutes les fonctionnalités']
    //         );
    //     }
    //     // Attacher directement la permission à l'utilisateur
    // $admin->permissions()->syncWithoutDetaching([$permission->id]);
    
        // Validation des entrées
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:8',
        ]);
    
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }
    
        // Tentative d'authentification
        $credentials = $request->only('email', 'password');
    
        if (!$token = auth()->attempt($credentials)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Identifiants incorrects'
            ], 401);
        }
    
        // Récupération de l'utilisateur connecté
        $user = auth()->user();
        $roles = $user->roles->pluck('name')->toArray();
    
        return response()->json([
            'status' => 'success',
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'roles' => $roles,
                'permissions' => $user->allPermissions()->pluck('name')->toArray(),
            ]
        ]);
    }
    

    public function me()
    {
        return response()->json(auth()->user());
    }

    public function logout()
    {
        auth()->logout();
        return response()->json(['message' => 'Successfully logged out']);
    }

    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60,
            'user' => auth()->user()
        ]);
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nom_complet' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'role' => 'sometimes|string|in:Administrateur,Directeur,Secrétaire,Comptable,Enseignant,Visiteur'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $user = User::create([
            'nom_complet' => $request->nom_complet,
            'name' => $request->nom_complet, // Pour compatibilité
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role ?? 'Visiteur', // Valeur par défaut
        ]);

        $token = auth()->login($user);

        return $this->respondWithToken($token);
    }
}