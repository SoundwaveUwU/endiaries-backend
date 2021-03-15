<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use App\Models\User;
use Hash;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Auth;
use Illuminate\View\View;
use Validator as ValidatorFacade;

class ChangePasswordController extends Controller
{

    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Where to redirect users after password is changed.
     *
     * @var string $redirectTo
     */
    protected $redirectTo = '/change_password';

    /**
     * Change password form
     *
     * @return Factory|View
     */
    public function showChangePasswordForm()
    {
        $user = Auth::getUser();

        return view('auth.change_password', compact('user'));
    }

    /**
     * Change password.
     *
     * @param Request $request
     *
     * @return $this|RedirectResponse
     */
    public function changePassword(Request $request)
    {
        $user = Auth::getUser();
        if (!$user instanceof User) {
            throw new \RuntimeException('user not found');
        }
        $this->validator($request->all())->validate();
        if (Hash::check($request->get('current_password'), $user->password)) {
            $user->password = $request->get('new_password');
            $user->save();
            return redirect($this->redirectTo)->with('message', 'Password changed successfully!');
        }

        return redirect()->back()->withErrors('Current password is incorrect');
    }

    /**
     * Get a validator for an incoming change password request.
     *
     * @param  array  $data
     * @return Validator
     */
    protected function validator(array $data): Validator
    {
        return ValidatorFacade::make($data, [
            'current_password' => 'required',
            'new_password' => 'required|min:6|confirmed',
        ]);
    }
}
