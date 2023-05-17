<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Providers\LoginHistory;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Repositories\AuthRepository;
use App\Validates\AuthValidate;
use Illuminate\Http\Response;

class PassportAuthController extends Controller
{

    public function __construct()
    {
        $this->validation = new AuthValidate();
        $this->repository = new AuthRepository();
    }

    private $validation;
    private $repository;

    public function login(Request $request)
    {
        $validation = $this->validation->login($request);
        if ($validation != null) return parent::getRespnse(Response::HTTP_BAD_REQUEST, $validation);

        $credentials = request(['email', 'password']);
        if(!Auth::attempt($credentials))

        return parent::getRespnse(Response::HTTP_UNAUTHORIZED, "Unauthorized", null);
        $user = $request->user();
        $tokenResult = $user->createToken('Personal Access Token');
        $token = $tokenResult->token;

        $token->save();
        $user = Auth::user();
        event(new LoginHistory($user));

        $data = [
            'access_token' => $tokenResult->accessToken,
            'token_type' => 'Bearer',
            'expires_at' => Carbon::parse(
                $tokenResult->token->expires_at
            )->toDateTimeString()
        ];

        return parent::getRespnse(Response::HTTP_ACCEPTED, "Login Success", $data);
   }

   public function register(Request $request)
   {
        $validation = $this->validation->register($request);
        if ($validation != null) return parent::getRespnse(Response::HTTP_BAD_REQUEST, $validation);

        $save = $this->repository->register($request);
        if(!$save['res']) return parent::getRespnse(Response::HTTP_BAD_REQUEST, $save['message'], null);

        return parent::getRespnse(Response::HTTP_CREATED, $save['message'], null);
   }

   public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        return parent::getRespnse(Response::HTTP_OK, "Successfully logged out", null);
    }


    public function user(Request $request)
    {
        return parent::getRespnse(Response::HTTP_OK, "user's profile", $request->user());
    }
}
