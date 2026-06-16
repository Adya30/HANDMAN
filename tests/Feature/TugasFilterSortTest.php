<?php

uses(Illuminate\Foundation\Testing\RefreshDatabase::class);

use App\Models\User;
use App\Models\Departemen;
use App\Models\Tugas;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

function setupData() {
    $departemen = Departemen::create([
        'nama_departemen' => 'Engineering',
        'deskripsi_departemen' => 'Engineering Department',
    ]);

    $manager = User::create([
        'id'            => (string) Str::ulid(),
        'nama_lengkap'  => 'Manager Engineering',
        'email'         => 'manager.eng@example.com',
        'password'      => Hash::make('Password123!'),
        'no_telp'       => '081233333333',
        'jenis_kelamin' => 'L',
        'tanggal_lahir' => '1990-01-01',
        'nama_role'     => 'manager',
        'departemen_id' => $departemen->id,
        'is_active'     => 1,
    ]);

    $staff = User::create([
        'id'            => (string) Str::ulid(),
        'nama_lengkap'  => 'Staff Engineering',
        'email'         => 'staff.eng@example.com',
        'password'      => Hash::make('Password123!'),
        'no_telp'       => '081244444444',
        'jenis_kelamin' => 'P',
        'tanggal_lahir' => '1995-01-01',
        'nama_role'     => 'staff',
        'departemen_id' => $departemen->id,
        'is_active'     => 1,
    ]);

    $grup = \App\Models\GrupKerja::create([
        'id'            => (string) Str::ulid(),
        'nama_grup'     => 'Group Engineering',
        'deskripsi'     => 'Group Engineering Description',
        'departemen_id' => $departemen->id,
        'created_by'    => $manager->id,
    ]);

    // Associate staff with group work
    $grup->anggota()->attach($staff->id, ['id' => (string) Str::ulid()]);

    // Create 3 tasks with different attributes and dates
    $t1 = Tugas::create([
        'id'             => (string) Str::ulid(),
        'nama_tugas'     => 'Task Alpha',
        'deskripsi'      => 'First task description',
        'tanggal_tugas'  => '2026-06-14 09:00:00',
        'deadline_tugas' => '2026-06-15 17:00:00',
        'prioritas'      => 'Tinggi',
        'status_tugas'   => 'Selesai',
        'kategoritugas'  => 'Individu',
        'departemen_id'  => $departemen->id,
    ]);
    $t1->detailTugas()->create(['user_id' => $staff->id]);

    $t2 = Tugas::create([
        'id'             => (string) Str::ulid(),
        'nama_tugas'     => 'Task Beta',
        'deskripsi'      => 'Second task description',
        'tanggal_tugas'  => '2026-06-16 09:00:00',
        'deadline_tugas' => '2026-06-17 17:00:00',
        'prioritas'      => 'Rendah',
        'status_tugas'   => 'Belum Dikerjakan',
        'kategoritugas'  => 'Kelompok',
        'departemen_id'  => $departemen->id,
    ]);
    $t2->detailTugas()->create(['grup_kerja_id' => $grup->id]);

    $t3 = Tugas::create([
        'id'             => (string) Str::ulid(),
        'nama_tugas'     => 'Task Gamma',
        'deskripsi'      => 'Third task description',
        'tanggal_tugas'  => '2026-06-15 09:00:00',
        'deadline_tugas' => '2026-06-16 17:00:00',
        'prioritas'      => 'Sedang',
        'status_tugas'   => 'Revisi',
        'kategoritugas'  => 'Individu',
        'departemen_id'  => $departemen->id,
    ]);
    $t3->detailTugas()->create(['user_id' => $staff->id]);

    return compact('manager', 'staff', 't1', 't2', 't3');
}

test('manager can index and sort tasks by tanggal_tugas descending', function () {
    $data = setupData();

    $response = $this->actingAs($data['manager'])
        ->get(route('tugas.index'));

    $response->assertStatus(200);
    $tasks = $response->viewData('tugas');

    // Expected order: Task Beta (2026-06-16), Task Gamma (2026-06-15), Task Alpha (2026-06-14)
    expect($tasks->pluck('nama_tugas')->toArray())->toEqual(['Task Beta', 'Task Gamma', 'Task Alpha']);
});

test('manager can filter tasks by status', function () {
    $data = setupData();

    $response = $this->actingAs($data['manager'])
        ->get(route('tugas.index', ['status' => 'Selesai']));

    $response->assertStatus(200);
    $tasks = $response->viewData('tugas');

    expect($tasks->count())->toBe(1);
    expect($tasks->first()->nama_tugas)->toBe('Task Alpha');
});

test('manager can filter tasks by prioritas', function () {
    $data = setupData();

    $response = $this->actingAs($data['manager'])
        ->get(route('tugas.index', ['prioritas' => 'Sedang']));

    $response->assertStatus(200);
    $tasks = $response->viewData('tugas');

    expect($tasks->count())->toBe(1);
    expect($tasks->first()->nama_tugas)->toBe('Task Gamma');
});

test('manager can filter tasks by kategori', function () {
    $data = setupData();

    $response = $this->actingAs($data['manager'])
        ->get(route('tugas.index', ['kategori' => 'Kelompok']));

    $response->assertStatus(200);
    $tasks = $response->viewData('tugas');

    expect($tasks->count())->toBe(1);
    expect($tasks->first()->nama_tugas)->toBe('Task Beta');
});


test('staff can index and filter their own tasks', function () {
    $data = setupData();

    $response = $this->actingAs($data['staff'])
        ->get(route('staff.tugas.index'));

    $response->assertStatus(200);
    $tasks = $response->viewData('tugas');

    // Expected order: Task Beta (2026-06-16), Task Gamma (2026-06-15), Task Alpha (2026-06-14)
    expect($tasks->pluck('nama_tugas')->toArray())->toEqual(['Task Beta', 'Task Gamma', 'Task Alpha']);

    // Filter staff tasks by status
    $responseFilter = $this->actingAs($data['staff'])
        ->get(route('staff.tugas.index', ['status' => 'Revisi']));

    $responseFilter->assertStatus(200);
    $filteredTasks = $responseFilter->viewData('tugas');
    expect($filteredTasks->count())->toBe(1);
    expect($filteredTasks->first()->nama_tugas)->toBe('Task Gamma');
});
