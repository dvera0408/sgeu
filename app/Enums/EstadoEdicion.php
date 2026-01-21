<?php

namespace App\Enums;

enum EstadoEdicion: string
{
    case PLANIFICADA = 'planificada';
    case ACTIVA = 'activa';
    case POSPUESTA = 'pospuesta';
    case FINALIZADA = 'finalizada';
    case INHABILITADA = 'inhabilitada';
}
