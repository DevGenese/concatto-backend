<?php

namespace Database\Seeders;

use App\Models\Cooperative;
use App\Models\Location;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CooperativeAndLocationsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         // Caminho do arquivo CSV dentro da pasta public
         $filePath = public_path('relatorio-escolas Andrey - Contrato.csv'); // Substitua pelo nome do seu arquivo CSV

         // Verifica se o arquivo existe
         if (!file_exists($filePath)) {
             $this->command->error("Arquivo CSV não encontrado em: {$filePath}");
             return;
         }
 
         // Abre o arquivo CSV
         $file = fopen($filePath, 'r');
 
         // Ignora o cabeçalho (primeira linha)
         fgetcsv($file);
 
         // Lê o arquivo linha por linha
         while (($data = fgetcsv($file, 1000, ',')) !== false) {
            // Verifica se a Cooperative já existe ou cria uma nova
            $cooperative = Cooperative::firstOrCreate(
                ['name' => $data[0]], // Critério de busca
                ['created_at' => now(), 'updated_at' => now()] // Dados adicionais para criação
            );

            // Verifica se a Location já existe ou cria uma nova
            $location = Location::firstOrCreate(
                ['name' => $data[1]], // Critério de busca
                [
                    'status' => $data[4] == "Ativo" ? true : false,
                    'created_at' => now(),
                    'updated_at' => now()
                ] // Dados adicionais para criação
            );

         }
 
         fclose($file);
 
         $this->command->info('Dados do CSV importados com sucesso!');
    }
}
