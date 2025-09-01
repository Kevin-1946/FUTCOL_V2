package com.example.TrabandoEnClase

import java.time.LocalDate
import java.time.LocalTime

data class Encuentro(
    val id: Long? = null,
    val torneoId: Long? = null,
    val equipoLocalId: Long? = null,
    val equipoVisitanteId: Long? = null,
    val juezId: Long? = null,
    val sedeId: Long? = null,
    val fecha: LocalDate? = null,
    val hora: LocalTime? = null,
    val golesLocal: Int? = null,
    val golesVisitante: Int? = null,
    val estado: String? = null
)
