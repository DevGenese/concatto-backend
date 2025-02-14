<?php

namespace Database\Seeders;

use App\Models\ExpenseType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ExpenseTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ExpenseType::insert([
            [
            'name' => 'H/Nº',
            'unity' => 'R$',
            ],
            [
            'name' => 'Horas',
            'unity' => 'R$',
            ],
            [
            'name' => 'Assessor',
            'unity' => 'R$',
            ],
            [
            'name' => 'Empresa',
            'unity' => 'R$',
            ],
            [
            'name' => 'KM',
            'unity' => 'KM',
            ],
            [
            'name' => 'KM/R$',
            'unity' => 'R$',
            ],
            [
            'name' => 'Deslocamento',
            'unity' => 'R$',
            ],
            [
            'name' => 'Alimentação',
            'unity' => 'R$',
            ],
            [
            'name' => 'Materiais',
            'unity' => 'R$',
            ],
            [
            'name' => 'Hospedagem',
            'unity' => 'R$',
            ],
            [
            'name' => 'Taxi/Uber',
            'unity' => 'R$',
            ],
            [
            'name' => 'Passagem',
            'unity' => 'R$',
            ],
        ]);
    }
}
