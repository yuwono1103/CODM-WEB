namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Membuat Akun Admin Utama
        User::create([
            'username' => 'admin_codm',
            'email' => 'admin@marketplace.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
            'is_suspended' => false,
        ]);

        // Membuat Akun Seller Contoh
        User::create([
            'username' => 'seller_codm',
            'email' => 'seller@marketplace.com',
            'password' => Hash::make('seller123'),
            'phone' => '628123456789',
            'role' => 'seller',
            'is_suspended' => false,
        ]);
    }
}