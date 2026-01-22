export interface NotaCompetenciaTecnica {
  asignatura_id: number;
  nombre_asignatura: string;
  nota_media: number;
}

export interface NotaCompetenciaTransversal {
  estancia_id: number;
  nota_media: number;
}

export interface NotaEgibide {
  nota: string;
  asignatura_id: number;
}

export interface NotaCuaderno {
  nota: number;
}

export interface CalculoNotasTecnicasResponse {
  alumno_id: number;
  notas_competenciasTec: NotaCompetenciaTecnica[];
}
