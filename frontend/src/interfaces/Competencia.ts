export interface Competencia {
  id: number;
  descripcion: string;
  tipo: "TECNICA" | "TRANSVERSAL";
}

export interface CompetenciaAsignada {
  competencia_tec_id: number;
  nota: number | null;
  descripcion: string;
}
