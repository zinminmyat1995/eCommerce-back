<?php
namespace App\Http\Controllers;

use App\Models\User; // Make sure to import the User model
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    protected $authService;
    /**
     * Get all users.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $currentUser = Auth::user();
        // dd($currentUser);
        $users = User::select([
            'user_name',
            'user_email',
        ])->get();
        return response()->json($users);
    }

    public function login(Request $request)
    {
        // Retrieve the user by email using the user repository
        $user = $this->findByEmail($request->email);

        // Check if the user exists and if the provided password matches the stored hashed password
        if (!$user || !Hash::check($request->password, $user->user_password)) {
            return [
                'status' => 401,
                'message' => 'Invalid email or password',
            ];
        }

        // Generate a new authentication token for the user
        $token = $user->createToken('authToken')->plainTextToken;

        return [
            'status' => 200,
            'message' => 'Login successful',
            'token' => $token,
            'user' => $user,
        ];
    }

    public function findByEmail($email)
    {
        return User::where('user_email', $email)->first(); // laravel eloquent
    }

    public function changeAliceName(Request $request){
        $newName = $request->newName;
        User::where('user_email', 'alice@example.com')->update(['user_name' => $newName]);
        return "success";
    }
}
