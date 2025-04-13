<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Cache\RateLimiter;
use App\Models\akun_kas;
use App\Models\CategorieSupplierModel;

class AuthController extends Controller
{   
    protected $limiter;

    public function showLoginForm()
    {
        return view('/login');  
    }
    
    public function index()
    {   
        
        $kas = akun_kas::all();
        $users = User::all();
        $linkCategori = CategorieSupplierModel::all();

        return view('/Auth.index',['users' => $users,'kas' => $kas,'linkCategori' => $linkCategori]);
    }
    public function statusUpdate($id)
    {   
         // Atur tanggal_login ke nilai yang Anda inginkan, misalnya null atau string kosong
        $user = User::find($id);
        $users = Auth::user();
        if($user)
        {
            if($user->status_aktif){
                $user->status_aktif = 0;
            }else {
                $user->status_aktif = 1;                
            }
            $user->save();
        }
        return back();
    }
    // public function show($id)
    // {   
        
    //     $users = User::findOrFail($id);
    //     $password = $users->password;
    //     $plainPassword = $this->decryptMD5($password);
    //     return view('/auth.userDetail', [
    //         'id' => $id,
    //         'jabatan' => $users->jabatan,
    //         'plainPassword' => $plainPassword,
    //         'username' => $users->username,
    //         'status' => $users->status_aktif,
    //         'tanggal_log' => $users->tanggal_log,
    //         'tanggal_reg' => $users->created_at,
    //         'tanggal_edit' => $users->updated_at,
    //     ]);
    // }
    public function show($idUser)
    {
        // Mengambil data transaksi berdasarkan nomor bukti
        $users = User::where('id', $idUser)->first();

        // Jika transaksi ditemukan, kirim data dalam format yang sesuai
        if ($users) {
            // Contoh: Kirim data dalam format JSON
            return response()->json($users);
        } else {
            // Jika transaksi tidak ditemukan, kirim respons kosong atau pesan kesalahan
            return response()->json(['error' => 'Transaksi tidak ditemukan.'], 404);
        }
    }
    private function decryptMD5($hashedPassword)
    {
        // Implementasi logika untuk mendekripsi hash MD5 kembali ke teks biasa
        // Misalnya, menggunakan rainbow tables atau pendekatan lainnya
        // Di sini, saya menggunakan fungsi hash() PHP sebagai contoh sederhana.
        return hash('md5', $hashedPassword);
    }

    public function __construct(RateLimiter $limiter)
    {
        $this->limiter = $limiter;
    }
    protected function sendThrottleNotification($seconds)
    {
        $notification = 'Upaya masuk gagal, coba lagi dalam waktu ' . $seconds . ' seconds.';
        session()->flash('throttleNotification', $notification);
        session()->flash('seconds', $seconds);
    }
    protected function updateThrottleNotification()
    {
        $seconds = session('seconds') * 2; // Kalikan waktu jeda dengan 2
        session()->put('seconds', $seconds); // Perbarui waktu jeda di sesi
        $notification = 'Upaya masuk gagal, coba lagi dalam waktu ' . $seconds . ' detik.';
        session()->flash('throttleNotification', $notification);
        session()->flash('seconds', $seconds);

    }
    public function login(Request $request)
    {
        $request->validate([
            'login' => 'required',
            'password' => 'required',
        ], [
            'login.required' => 'Username atau ID Email harus diisi.',
            'password.required' => 'Password harus diisi.',
        ]);
        // Get user by email or username
        $user = User::where('email', $request->login)
        ->orWhere('username', $request->login)
        ->first();
            // Jika pengguna tidak ditemukan, arahkan kembali ke halaman login dengan pesan kesalahan
        if (!$user) {
            return redirect()->route('login')->withInput()->withErrors(['login' => 'username atau email tidak cocok.']);
        }

        $loginField = filter_var($request->login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
        $credentials = [$loginField => $request->login, 'password' => $request->password];
 
        $maxAttempts = 3;
        if ($this->hasTooManyLoginAttempts($request)) {
            $seconds = $this->limiter->availableIn($this->throttleKey($request->input('login'), $request));
            $this->sendThrottleNotification($seconds);
            return redirect()->back()->withInput($request->only('login'))->withErrors(['login' => 'Terlalu banyak percobaan login. Silakan coba lagi dalam beberapa saat.']);
        }
    if (strcmp($request->login, $user->username) !== 0 && strcmp($request->login, $user->email) !== 0) {
        return redirect()->route('login')->withInput()->withErrors(['login' => 'username atau email tidak cocok.']);
    }
    
    // Check if user exists and password is correct
    if ($user && $this->validatePassword($request->password, $user->password)) {
        // Update the user's last login time
        $user->update(['tanggal_login' => now()]);
        // Store the remember_token in the user record
        $user->remember_token = session()->getId();
        $user->save();
        // Mengirim event SessionIdChanged
        $request->session()->put('remember_token', $request->session()->getId());
        // Authenticate the user
        Auth::login($user);
        // Redirect the user to the intended page after login
        return redirect()->intended('beranda.php');
    }

        $this->incrementLoginAttempts($request);
        $this->updateThrottleNotification();
        return redirect()->route('login')->withInput()->withErrors(['login' => 'Username atau password salah.']);
    }
    
    protected function hasTooManyLoginAttempts(Request $request)
    {
        $key = $this->throttleKey($request->input('login'), $request);
        return $this->limiter->tooManyAttempts($key, 3);
    }
    
    protected function incrementLoginAttempts(Request $request)
    {
        $this->limiter->hit($this->throttleKey($request->input('login'), $request));
    }
    
    protected function clearLoginAttempts(Request $request)
    {
        $this->limiter->clear($this->throttleKey($request->input('login'), $request));
    }

    protected function throttleKey($login, Request $request)
    {
        return 'login:' . $login . ':' . $request->ip();
    }
    private function validateUsername($inputUsername, $storedUsername)
    {
        // Bandingkan input username dengan username yang disimpan, memperhatikan penulisan huruf besar dan kecil
        return strtolower($inputUsername) === strtolower($storedUsername);
    }
    private function validatePassword($inputPassword, $hashedPassword)
    {
        // Gunakan md5 untuk mengenkripsi kata sandi yang dimasukkan pengguna
        $hashedInputPassword = md5($inputPassword);
        
        // Bandingkan hasil hash kata sandi yang dimasukkan dengan kata sandi yang disimpan di database
        return $hashedInputPassword === $hashedPassword;
    }
    // Method untuk menghasilkan custom session ID (misalnya menggunakan UUID)
    private function generateCustomSessionId() {
        // Anda dapat menggunakan metode yang sesuai untuk menghasilkan session ID kustom di sini
        return \Illuminate\Support\Str::uuid(); // Contoh menggunakan UUID
    }  

    public function logout(Request $request)
    {
        $request->user()->remember_token = null;
        $request->user()->save();

        Auth::logout();

        return redirect('/');
    }

    public function create(){
        $users = User::all();
        return view('/auth.userAdd',
        ['users' => $users]);
    }
    // Controller method untuk memeriksa session_id

    public function addUserstore(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required',
                'username' => 'required|unique:users',
                'email' => 'required|unique:users',
                'password' => 'required',
                'role' => 'required',
            ], [
                'name.required' => 'Nama harus di isi.',
                'username.required' => 'Username harus diisi.',
                'password.required' => 'Password harus diisi.',
                'role.required' => 'Role harus diisi.',
            ]);
            
    
            // Membuat pengguna baru menggunakan data dari permintaan
            $user = User::create([
                'name' => $request->name,
                'username' => $request->username,
                'password' => $request->password,
                'email' => $request->email,
                'status_aktif' => 1,
                'role' => $request->jabatan,
                'created_at' => Carbon::now(),
            ]);
    
            if ($user) {
                return redirect()->back()->with('message', 'Add New User success!');
            } else {
                // Jika terjadi kesalahan saat membuat pengguna
                return redirect()->back()->with('message', 'Failed to add new User!');
            }
        } catch (\Exception $e) {
            // Tangani pengecualian yang mungkin terjadi
            return redirect()->back()->with('message', 'Failed to add new User: ' . $e->getMessage());
        }
    }

    public function updateUser(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required',
            'email' => 'required',
            'role' => 'required|in:Admin,Manager,Owner',
            'password' => 'required',
        ]);
        $user = User::findOrFail($id);
        
        if ($request->password == '******') {
            $user->name = $request->name;
            $user->username = $request->username;
            $user->email = $request->email;
            $user->role = $request->role;
            $user->status_aktif = $request->status_aktif;
            $user->updated_at = Carbon::now();
            $user->save();
        } else {
            $user->name = $request->name;
            $user->username = $request->username;
            $user->email = $request->email;
            $user->password = $request->password;
            $user->role = $request->role;
            $user->status_aktif = $request->status_aktif;
            $user->updated_at = Carbon::now();
            $user->save();

            return redirect()->route('user.index')->with('success', 'User berhasil ditambahkan.');
        }

        return redirect()->back()->with('success', 'User berhasil diperbarui.');
    }

    public function destroy(User $id)
    {
        $id->delete();
        return redirect()->back()->with('success', 'User deleted successfully');
    }
    
        public function searchPengguna(Request $request)
        {
            $searchTerm = str_replace(' ', '', $request->input('searchVoucher'));
            $orderBy = $request->input('orderBy');
            $orderDirection = $request->input('orderDirection');
    
            $query = DB::table('user_pengguna')
                ->join('t_master_kendaraan', 'user_pengguna.id_kendaraan', '=', 't_master_kendaraan.id_kendaraan')
                ->select('user_pengguna.*', 't_master_kendaraan.id_kendaraan', 't_master_kendaraan.nama_pemilik', 't_master_kendaraan.nomor_wa','t_master_kendaraan.nomor_plat','user_pengguna.id_pengguna')
                ->where('user_pengguna.username', 'like', "%$searchTerm%")
                ->orWhere('user_pengguna.id_pengguna', 'like', "%$searchTerm%")
                ->orWhere('t_master_kendaraan.nama_pemilik', 'like', "%$searchTerm%")
                ->orWhere('t_master_kendaraan.nomor_wa', 'like', "%$searchTerm%")
                ->orWhere('t_master_kendaraan.id_kendaraan', 'like', "%$searchTerm%");
    
            // Tambahkan pengurutan jika ada permintaan
            if ($orderBy && $orderDirection) {
                $query->orderBy($orderBy, $orderDirection);
            }
    
            $results = $query->get();
    
            return response()->json($results);
        }
        public function edit(Request $request, $idUser)
        {
            $kas = akun_kas::all();
            $user = User::findOrFail($idUser);
            $linkCategori = CategorieSupplierModel::all();

            return view('Auth.edit',['user'=>$user, 'kas'=>$kas, 'linkCategori'=>$linkCategori]);
            // return redirect()->route('auth.index')->with(['message' => 'User berhasil di perbarui']);
        }

        public function editDetail(Request $request)
        {
            $kas = akun_kas::all();
            $user = User::findOrFail(Auth::user()->id);
            $linkCategori = CategorieSupplierModel::all();
            return view('Auth.editDetail',['user'=>$user, 'kas'=>$kas, 'linkCategori'=>$linkCategori]);
            // return redirect()->route('auth.index')->with(['message' => 'User berhasil di perbarui']);
        }

        public function deletePengguna($id_pengguna)
            {
                try {
                    // Cari kendaraan berdasarkan nomor transaksi // Jika id diganti dengan string lalu dapatkan primaryKey nya di model
                    $card_member = User::where('id_pengguna', $id_pengguna)->first();
            
                    if (!$card_member) {
                        return response()->json(['success' => false, 'message' => 'Card Member not found.'], 404);
                    }
                    
                    $card_member->delete();
            
                    return response()->json(['success' => true, 'message' => 'Card Member deleted successfully.']);
                } catch (\Exception $e) {
                    return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
                }
            }
    
}
