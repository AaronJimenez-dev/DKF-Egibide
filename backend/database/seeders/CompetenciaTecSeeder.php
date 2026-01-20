<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CompetenciaTecSeeder extends Seeder {
    public function run(): void {
        DB::table('competencias_tec')->insert([
            [
                'descripcion' => 'Programa y prueba aplicaciones de manera estructurada, aplicando principios fundamentales y avanzados de programación, utilizando estructuras de control y depurando código para asegurar su correcto funcionamiento.',
                'ciclo_id' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'descripcion' => 'Desarrolla el frontend de una aplicación web utilizando lenguajes y tecnologías web adecuadas para crear interfaces de usuario funcionales, accesibles y que garanticen la correcta interacción con el usuario.',
                'ciclo_id' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'descripcion' => 'Desarrolla la lógica de negocio de una aplicación web utilizando lenguajes de programación y arquitecturas orientados a la web para gestionar procesos y funcionalidades del lado del servidor.',
                'ciclo_id' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'descripcion' => 'Gestiona bases de datos relacionales y no relacionales aplicando consultas y procedimientos de manejo de datos para almacenar y recuperar información de manera eficiente.',
                'ciclo_id' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'descripcion' => 'Implementa mecanismos de autenticación y autorización utilizando estándares de seguridad para controlar el acceso a los recursos de la aplicación.',
                'ciclo_id' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'descripcion' => 'Implementa servicios web y puntos de acceso utilizando APIs para la comunicación entre el cliente y el servidor en aplicaciones web.',
                'ciclo_id' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'descripcion' => 'Configura entornos de desarrollo y producción automatizando el despliegue de aplicaciones web y gestionando la configuración de servidores y servicios en entornos remotos',
                'ciclo_id' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'descripcion' => 'Utiliza sistemas de control de versiones para gestionar el código fuente de forma colaborativa y organizada a lo largo del ciclo de vida del desarrollo.',
                'ciclo_id' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'descripcion' => 'Realiza pruebas y depuración de aplicaciones web implementando técnicas de testeo y corrección de errores para garantizar la calidad y estabilidad del producto final.',
                'ciclo_id' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
        ]);
    }
}
