package com.example.TrabandoEnClase

import com.fasterxml.jackson.annotation.JsonProperty
import com.fasterxml.jackson.annotation.JsonFormat
import java.sql.Date

data class Torneo(
    val id: Int,
    val nombre: String,

    @JsonProperty("fecha_inicio")
    @JsonFormat(shape = JsonFormat.Shape.STRING, pattern = "yyyy-MM-dd")
    val fecha_Inicio: Date,

    @JsonProperty("fecha_fin")
    @JsonFormat(shape = JsonFormat.Shape.STRING, pattern = "yyyy-MM-dd")
    val fecha_Fin: Date,

    val categoria: String,
    val modalidad: String,
    val organizador: String,
    val precio: Double,
    val sedes: String
)