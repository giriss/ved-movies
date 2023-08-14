<?php
namespace App\Controllers;

// This is our model, we import it here to use it below
use App\Models\User;
use App\Models\Log;
use Leaf\Form;

/**
 * UsersController (Demo)
 * ---------------
 * This is a demo users controller put together to give
 * you an idea on basic features of leaf. Each block is commented
 * to help you understand exactly what's going on.
 *
 * Some blocks can be used as alternatives depending on your preference,
 * you can switch to those as you see fit.
 *
 * Although a demo, it's a real controller and works correctly as is.
 * You can always delete this controller or link it to a route if you wish
 * to use it as a reference.
 */
class UsersController extends Controller
{
    private function generateApiKey()
    {
        return md5(uniqid(rand(), true));
    }

    // refer to base controller to find package initialization
    // and auth settings
    public function login()
    {
        // You can also mass assign particular fields from the request
        $credentials = request()->get(['username', 'password']);

        // You can perform operations on your model like this
        $user = User::where('username', $credentials['username'])->first();

        // auth is initialised in the base controller
        // login allows us to sign a user in, and also generates
        // a jwt automatically
        $user = auth()->login($credentials);

        // password encoding has been configured in the base controller

        // This line catches any errors that MAY happen
        if (!$user) {
            response()->exit(auth()->errors());
        }

        // We can call json on the response global shortcut method
        response()->json($user);
    }

    public function register()
    {
        // $username = request()->get('username');
        // $fullname = request()->get('fullname');
        // $email = request()->get('email');
        // $password = request()->get('password');

        // You can also directly pick vars from the request object
        $credentials = request()->get(['fullname', 'username', 'email', 'password']);
        $credentials['api_key'] = $this->generateApiKey();

        // You can validate your data with Leaf Form Validation
        $validation = Form::validate([
            'username' => 'validUsername',
            'fullname' => 'required',
            'email' => 'email',
            'password' => 'required'
        ]);

        // Throws an error if there's an issue in validation
        if (!$validation) response()->exit(Form::errors());

        // Direct registration with Leaf Auth. Registers and initiates a
        // login, so you don't have to call login again, unless you want
        // to. The 3rd parameter makes sure that the same username
        // and email can't be registered multiple times
        $user = auth()->register($credentials, [
            'username', 'email', 'api_key',
        ]);

        // throw an auth error if there's an issue
        if (!$user) {
            response()->exit(auth()->errors());
        }

        response()->json($user);
    }

    public function monitor() {
        $userId = auth()->id() ?? response()->exit(auth()->errors());

        // Get the current id
        $user = User::find($userId);
        $user->api_key;

        $logs = Log::firstWhere(['api_key' => $user->api_key, 'date' => date("Y-m-d")]);
        response()->json($logs ?? ['count' => 0]);
    }
}
