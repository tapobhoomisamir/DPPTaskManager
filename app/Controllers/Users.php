<?php namespace App\Controllers;
use App\Models\UserModel;

class Users extends BaseController {

    public function register() {
        helper(['form']);
        if ($this->request->getMethod() === 'post') {
            $userModel = new UserModel();
            $userModel->save([
                'name' => $this->request->getPost('name'),
                'email' => $this->request->getPost('email'),
                'password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
                'role' => 'user'
            ]);
            return redirect()->to('/users/login');
        }
        return view('users/register');
    }

    public function login() {
        helper(['form']);
        if ($this->request->getMethod() === 'post') {
            $userModel = new UserModel();
            $user = $userModel->where('email', $this->request->getPost('email'))->first();
            if ($user && password_verify($this->request->getPost('password'), $user['password'])) {
                session()->set(['user_id' => $user['id'], 'role' => $user['role'], 'isLoggedIn' => true]);
                return redirect()->to('/users/dashboard');
            } else {
                return view('users/login', ['error' => 'Invalid credentials']);
            }
        }
        return view('users/login');
    }

    public function dashboard() {
        if (!session()->get('isLoggedIn')) return redirect()->to('/users/login');
        return "Welcome to dashboard, " . session()->get('role');
    }

    public function logout() {
        session()->destroy();
        return redirect()->to('/users/login');
    }

    public function list() {
        if (session()->get('role') != 'admin') return "Access Denied";
        $userModel = new UserModel();
        $data['users'] = $userModel->findAll();
        return view('users/list', $data);
    }
}
