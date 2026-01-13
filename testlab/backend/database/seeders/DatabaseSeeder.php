<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Project;
use App\Models\Version;
use App\Models\TestCase;
use App\Models\TestExecution;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1️⃣ Crear o actualizar usuarios
        $admin = User::updateOrCreate(
            ['email' => 'admin@test.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('password123'),
                'rol' => 'admin'
            ]
        );

        $tester = User::updateOrCreate(
            ['email' => 'tester@test.com'],
            [
                'name' => 'Tester User',
                'password' => Hash::make('password123'),
                'rol' => 'tester'
            ]
        );

        $manager = User::updateOrCreate(
            ['email' => 'manager@test.com'],
            [
                'name' => 'Manager User',
                'password' => Hash::make('password123'),
                'rol' => 'manager'
            ]
        );

        // 2️⃣ Crear o actualizar proyectos
       $project1 = Project::updateOrCreate(
    ['name' => 'E-commerce Platform'],
    [
        'description' => 'Desarrollo de plataforma de ventas online',
        'status' => 'active',
        'created_by' => $admin->id
    ]
);

$project2 = Project::updateOrCreate(
    ['name' => 'Mobile App'],
    [
        'description' => 'Aplicación iOS y Android',
        'status' => 'active',
        'created_by' => $admin->id
    ]
);

        // Asociar usuarios a proyectos (evita duplicados)
        $project1->users()->syncWithoutDetaching([$admin->id, $tester->id]);
        $project2->users()->syncWithoutDetaching([$manager->id]);

        // 3️⃣ Crear o actualizar versiones
        $version1 = Version::updateOrCreate(
            ['version_number' => 'v1.0', 'project_id' => $project1->id],
            ['release_date' => now()->addDays(30), 'description' => 'Primera versión estable']
        );

        $version2 = Version::updateOrCreate(
            ['version_number' => 'v1.1', 'project_id' => $project1->id],
            ['release_date' => now()->addDays(60), 'description' => 'Segunda versión con mejoras']
        );

        // 4️⃣ Crear o actualizar test cases
        $testCase1 = TestCase::updateOrCreate(
            ['title' => 'Login de usuario'],
            [
                'objective' => 'Verificar que el usuario puede iniciar sesión',
                'preconditions' => 'Usuario registrado, cuenta activa',
                'steps' => json_encode([
                    'Navegar a página de login',
                    'Ingresar email válido',
                    'Ingresar contraseña correcta',
                    'Hacer clic en Login'
                ]),
                'expected_result' => 'Usuario redirigido al dashboard',
                'user_profile' => 'Usuario estándar'
            ]
        );

        $testCase2 = TestCase::updateOrCreate(
            ['title' => 'Registro de nuevo usuario'],
            [
                'objective' => 'Verificar registro de usuario nuevo',
                'preconditions' => 'Email no registrado previamente',
                'steps' => json_encode([
                    'Hacer clic en Registrarse',
                    'Completar formulario',
                    'Aceptar términos',
                    'Hacer clic en Crear cuenta'
                ]),
                'expected_result' => 'Usuario registrado y email de confirmación enviado',
                'user_profile' => 'Nuevo usuario'
            ]
        );

        // 5️⃣ Asociar test cases a versiones (tabla pivot)
        $version1->testCases()->syncWithoutDetaching([$testCase1->id, $testCase2->id]);

        // 6️⃣ Crear o actualizar test executions
        TestExecution::updateOrCreate(
            [
                'test_case_id' => $testCase1->id,
                'version_id' => $version1->id,
                'user_id' => $tester->id,
            ],
            [
                'result' => 'passed',
                'comment' => 'Test ejecutado correctamente',
                'test_data' => json_encode(['browser' => 'Chrome', 'os' => 'Windows']),
                'error_status' => 'none',
                'observations' => 'Todo funcionó como se esperaba',
                'executed_at' => now()->subDays(2)
            ]
        );

        TestExecution::updateOrCreate(
            [
                'test_case_id' => $testCase1->id,
                'version_id' => $version1->id,
                'user_id' => $tester->id,
                'result' => 'failed'
            ],
            [
                'comment' => 'El botón de login no funciona en móvil',
                'test_data' => json_encode(['browser' => 'Mobile Safari', 'os' => 'iOS']),
                'error_status' => 'high',
                'correction_notes' => 'Se debe ajustar el responsive design',
                'observations' => 'Falló en resolución móvil',
                'executed_at' => now()->subDays(1)
            ]
        );

        TestExecution::updateOrCreate(
            [
                'test_case_id' => $testCase2->id,
                'version_id' => $version1->id,
                'user_id' => $admin->id
            ],
            [
                'result' => 'passed',
                'comment' => 'Registro exitoso',
                'test_data' => json_encode(['browser' => 'Firefox', 'os' => 'Linux']),
                'error_status' => 'none',
                'observations' => null,
                'executed_at' => now()
            ]
        );

        // 7️⃣ Mensaje en consola
        $this->command->info('✅ Datos de prueba creados o actualizados correctamente');
    }
}
