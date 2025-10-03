<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class CreateAdminCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:create 
                            {--email= : Admin email address}
                            {--name= : Admin full name}
                            {--password= : Admin password}
                            {--force : Force creation even if admin exists}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create an admin user safely';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Verificar si ya existe un administrador
        $existingAdmin = User::where('role', User::ROLE_ADMIN)->first();

        if ($existingAdmin && ! $this->option('force')) {
            $this->error('Admin user already exists: '.$existingAdmin->email);
            $this->info('Use --force flag to create another admin or ignore this check.');

            return 1;
        }

        // Obtener datos del admin
        $email = $this->option('email') ?: $this->ask('Admin email address');
        $name = $this->option('name') ?: $this->ask('Admin full name');
        $password = $this->option('password') ?: $this->secret('Admin password');

        // Validar datos
        $validator = Validator::make([
            'email' => $email,
            'name' => $name,
            'password' => $password,
        ], [
            'email' => 'required|email|unique:users',
            'name' => 'required|string|max:255',
            'password' => 'required|string|min:8',
        ]);

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                $this->error($error);
            }

            return 1;
        }

        // Solicitar datos de ubicación
        $phone = $this->ask('Phone number', '+593987654321');
        $address = $this->ask('Address', 'Av. Principal 123');

        // Mostrar provincias disponibles
        $provinces = array_keys(config('ecuador.provinces'));
        $province = $this->choice('Select province', $provinces, 0);

        // Mostrar ciudades de la provincia seleccionada
        $cities = config('ecuador.provinces')[$province];
        $city = $this->choice('Select city', $cities, 0);

        // Crear el usuario administrador
        try {
            $admin = User::create([
                'name' => $name,
                'email' => $email,
                'password' => Hash::make($password),
                'phone' => $phone,
                'address' => $address,
                'province' => $province,
                'city' => $city,
                'role' => User::ROLE_ADMIN,
                'email_verified_at' => now(), // Verificar email automáticamente
            ]);

            $this->info('✅ Admin user created successfully!');
            $this->table(
                ['Field', 'Value'],
                [
                    ['Name', $admin->name],
                    ['Email', $admin->email],
                    ['Role', $admin->role],
                    ['Province', $admin->province],
                    ['City', $admin->city],
                ]
            );

            return 0;
        } catch (\Exception $e) {
            $this->error('❌ Failed to create admin user: '.$e->getMessage());

            return 1;
        }
    }
}
