<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CompetenciaTec;

class CompetenciaTecSeeder extends Seeder
{
    public function run(): void
    {
        $competencias = [
            // ciclo_id 1 — ya tiene datos, se omite

            // ciclo_id 2 — GM Elect. Vehículos (131DA / 132DA)
            ['ciclo_id' => 2, 'descripcion' => 'Diagnostica averías en sistemas eléctricos del vehículo'],
            ['ciclo_id' => 2, 'descripcion' => 'Mantiene sistemas de transmisión y frenado'],
            ['ciclo_id' => 2, 'descripcion' => 'Realiza operaciones de mecanizado básico'],

            // ciclo_id 3 — GM Mant. Electromecánica (131GB / 132GB)
            ['ciclo_id' => 3, 'descripcion' => 'Aplica técnicas de fabricación mecánica'],
            ['ciclo_id' => 3, 'descripcion' => 'Configura automatismos eléctricos y neumáticos'],
            ['ciclo_id' => 3, 'descripcion' => 'Realiza el montaje y mantenimiento electromecánico'],

            // ciclo_id 4 — GM Sist. Microinformáticos y Redes (131KA / 131KB / 132KA / 132KB)
            ['ciclo_id' => 4, 'descripcion' => 'Instala y configura sistemas operativos monopuesto'],
            ['ciclo_id' => 4, 'descripcion' => 'Configura redes locales de área local'],
            ['ciclo_id' => 4, 'descripcion' => 'Aplica medidas básicas de seguridad informática'],

            // ciclo_id 5 — GM Soldadura y Calderería (131LA / 132LA)
            ['ciclo_id' => 5, 'descripcion' => 'Ejecuta soldaduras en atmósfera natural y protegida'],
            ['ciclo_id' => 5, 'descripcion' => 'Realiza trazado, corte y conformado de piezas'],
            ['ciclo_id' => 5, 'descripcion' => 'Interpreta planos y documentación técnica'],

            // ciclo_id 6 — GS Admón. Sistemas Informáticos en Red (141AA / 142AA)
            ['ciclo_id' => 6, 'descripcion' => 'Administra sistemas operativos en red'],
            ['ciclo_id' => 6, 'descripcion' => 'Gestiona bases de datos y servicios de red'],
            ['ciclo_id' => 6, 'descripcion' => 'Implanta y administra aplicaciones web'],

            // ciclo_id 7 — GS Automatización y Robótica Industrial (141DD / 142DD)
            ['ciclo_id' => 7, 'descripcion' => 'Programa sistemas secuenciales y autómatas'],
            ['ciclo_id' => 7, 'descripcion' => 'Configura y mantiene robots industriales'],
            ['ciclo_id' => 7, 'descripcion' => 'Integra sistemas de comunicaciones industriales'],

            // ciclo_id 8 — GS Automoción (141EA / 142EA)
            ['ciclo_id' => 8, 'descripcion' => 'Diagnostica y repara sistemas eléctricos del vehículo'],
            ['ciclo_id' => 8, 'descripcion' => 'Mantiene motores térmicos y sus sistemas auxiliares'],
            ['ciclo_id' => 8, 'descripcion' => 'Gestiona la logística del taller de vehículos'],

            // ciclo_id 9 — GS Desarrollo Aplicaciones Web (141GA / 142GA)
            ['ciclo_id' => 9, 'descripcion' => 'Desarrolla aplicaciones web en entorno cliente'],
            ['ciclo_id' => 9, 'descripcion' => 'Desarrolla aplicaciones web en entorno servidor'],
            ['ciclo_id' => 9, 'descripcion' => 'Despliega y gestiona aplicaciones web en producción'],

            // ciclo_id 10 — GS Mecatrónica Industrial (141QA / 142QA)
            ['ciclo_id' => 10, 'descripcion' => 'Configura sistemas mecatrónicos hidráulicos y neumáticos'],
            ['ciclo_id' => 10, 'descripcion' => 'Integra sistemas eléctricos y electrónicos en maquinaria'],
            ['ciclo_id' => 10, 'descripcion' => 'Simula y verifica sistemas mecatrónicos'],

            // ciclo_id 11 — GS Telecomunicaciones e Informática (141TA / 142TA)
            ['ciclo_id' => 11, 'descripcion' => 'Configura infraestructuras de telecomunicaciones'],
            ['ciclo_id' => 11, 'descripcion' => 'Mantiene sistemas de redes locales e informáticos'],
            ['ciclo_id' => 11, 'descripcion' => 'Gestiona proyectos de instalaciones de telecomunicaciones'],

            // ciclo_id 12 — GS Mant. Elect. Aviones (141XA)
            ['ciclo_id' => 12, 'descripcion' => 'Aplica fundamentos de electricidad y electrónica en aeromecánica'],
            ['ciclo_id' => 12, 'descripcion' => 'Realiza prácticas de mantenimiento en elementos mecánicos de aeronave'],
            ['ciclo_id' => 12, 'descripcion' => 'Identifica y aplica normativa de factores humanos en aviación'],

            // ciclo_id 13 — GS Des. Aplicaciones Multiplataforma (147FA / 148FA / 149FA)
            ['ciclo_id' => 13, 'descripcion' => 'Desarrolla aplicaciones multiplataforma con acceso a datos'],
            ['ciclo_id' => 13, 'descripcion' => 'Programa interfaces de usuario para dispositivos móviles'],
            ['ciclo_id' => 13, 'descripcion' => 'Gestiona servicios y procesos en aplicaciones multiplataforma'],

            // ciclo_id 14 — GB Informática y Comunicaciones (151FA / 152FA)
            ['ciclo_id' => 14, 'descripcion' => 'Monta y mantiene sistemas y componentes informáticos'],
            ['ciclo_id' => 14, 'descripcion' => 'Instala redes para transmisión de datos'],
            ['ciclo_id' => 14, 'descripcion' => 'Configura sistemas operativos en equipos microinformáticos'],

            // ciclo_id 15 — CE Ciberseguridad (176AB)
            ['ciclo_id' => 15, 'descripcion' => 'Gestiona y responde ante incidentes de ciberseguridad'],
            ['ciclo_id' => 15, 'descripcion' => 'Aplica técnicas de hacking ético y análisis forense'],
            ['ciclo_id' => 15, 'descripcion' => 'Bastiona redes y sistemas conforme a normativa vigente'],
        ];

        foreach ($competencias as $competencia) {
            CompetenciaTec::create($competencia);
        }
    }
}