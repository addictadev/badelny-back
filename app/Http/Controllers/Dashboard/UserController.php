<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\AppBaseController;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class UserController extends AppBaseController
{
    /** @var UserRepository $userRepository*/
    private $userRepository;

    public function __construct(UserRepository $userRepo)
    {
        $this->userRepository = $userRepo;
    }

    /**
     * Display a listing of the User.
     */
    public function index()
    {
        return view('dashboard.users.index');
    }

    /**
     * Show the form for creating a new User.
     */
    public function create()
    {
        return view('dashboard.users.add-user');
    }

    /**
     * Store a newly created User in storage.
     */
    public function store(Request $request)
    {
        $request->merge([
            'password' => Hash::make($request->password)
        ]);
        $input = $request->all();
        $user = $this->userRepository->create($input);

        return redirect()->route('users.index')->with('success',trans('dashboard.Added_Successfully'));
    }

    /**
     * Display the specified User.
     */

    /**
     * Show the form for editing the specified User.
     */
    public function edit($id)
    {
        $user = $this->userRepository->find($id);
        return view('dashboard.users.add-user')->with('user', $user);
    }

   public function loadAjaxDatatable ()
   {
      return $this->userRepository->loadAjax();
   }

    /**
     * Update the specified User in storage.
     */
    public function update($id, UpdateUserRequest $request)
    {
        $request->merge([
            'password' => Hash::make($request->password)
        ]);
        $user = $this->userRepository->find($id);

        $user = $this->userRepository->update($request->all(), $id);

        return redirect()->route('users.index')->with('success',trans('dashboard.Updated_Successfully'));
    }

    /**
     * Remove the specified User from storage.
     *
     * @throws \Exception
     */
    public function destroy($id)
    {
        $user = $this->userRepository->find($id);


        $this->userRepository->delete($id);

        return redirect()->route('users.index')->with('success',trans('dashboard.Deleted_Successfully'));
    }
    public function logout()
    {
        Session::flush();

        Auth::logout();

        return redirect()->route('dashboard.home');
    }
    public function change_password_form($id)
    {
        $user = User::find($id);
        return view('dashboard.users.change_password',compact('user'));
    }

    public function change_password(Request $request , $id)
    {
        $user = User::find($id);

        $request->validate([
            'new_password' => 'min:6|required_with:password_confirmation|same:password_confirmation'
        ]);

        $user->update([
            'password' => Hash::make($request->new_password)
        ]);
        $user->save();

        return redirect()->route('users.index')->with('success',trans('dashboard.Password_changed_Successfully'));

    }
}
