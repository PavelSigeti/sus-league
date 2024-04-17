<?php

namespace Database\Seeders;

use App\Models\University;
use Illuminate\Database\Seeder;

class UniversitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $universities = ['ИТМО', 'СПбГУ', 'ГУАП', 'РГУ нефти и газа (НИУ) им. И.М. Губкина', 'Горный СПБ', 'НГУ им. Лесгафта', 'ГУМРФ им. Макарова', 'ЛЭТИ', 'СПбГХФУ', 'РАНХиГС', 'СПбПУ', 'ВШЭ', 'ДГТУ', 'МФТИ', 'МГУ', 'Синергия', 'СПбМТУ', 'БГТУ “ВОЕНМЕХ”', 'ПСПбГМУ', 'КГТУ', 'БФУ им. Канта', 'СПбГЭУ', 'ВШПМ СПбГУПТД', 'РГПУ им. Герцена', 'СПбГУТ им.Бонч-Бруевича', 'СПбГПМУ', 'Московский Политех', 'МГТУ им. Н.Э. Баумана' ];

        foreach ($universities as $university) {
            University::query()->create([
                'name' => $university,
            ]);
        }
    }
}
