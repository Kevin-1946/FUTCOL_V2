package com.example.TrabandoEnClase

import org.springframework.beans.factory.annotation.Autowired
import org.springframework.jdbc.core.JdbcTemplate
import org.springframework.jdbc.core.RowMapper
import org.springframework.stereotype.Service
import java.sql.ResultSet

@Service
class TorneosService {

    @Autowired
    lateinit var jdbcTemplate: JdbcTemplate

    // Mapeo de la respuesta de la base de datos a un objeto Torneo
    private val rowMapper = RowMapper<Torneo> { rs: ResultSet, _: Int ->
        Torneo(
            id = rs.getInt("id"),
            nombre = rs.getString("nombre"),
            fecha_Inicio = rs.getDate("fecha_inicio"),
            fecha_Fin = rs.getDate("fecha_fin"),
            categoria = rs.getString("categoria"),
            modalidad = rs.getString("modalidad"),
            organizador = rs.getString("organizador"),
            precio = rs.getDouble("precio"),
            sedes = rs.getString("sedes")
        )
    }

    // GET - Obtener todos los torneos
    fun obtenerTorneos(): List<Torneo> {
        val sql = "SELECT * FROM torneos"
        return jdbcTemplate.query(sql, rowMapper)
    }

    // POST - Agregar un torneo
    fun agregarTorneo(torneo: Torneo) {
        try {
            val sql = """
                INSERT INTO torneos 
                (nombre, fecha_inicio, fecha_fin, categoria, modalidad, organizador, precio, sedes, created_at, updated_at)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())
            """.trimIndent()

            jdbcTemplate.update(
                sql,
                torneo.nombre,
                torneo.fecha_Inicio,
                torneo.fecha_Fin,
                torneo.categoria,
                torneo.modalidad,
                torneo.organizador,
                torneo.precio,
                torneo.sedes
            )
        } catch (e: Exception) {
            throw Exception("Error al agregar torneo: ${e.message}")
        }
    }

    // PUT - Actualizar un torneo
    fun actualizarTorneo(torneo: Torneo) {
        try {
            val sql = """
                UPDATE torneos 
                SET nombre = ?, fecha_inicio = ?, fecha_fin = ?, categoria = ?, modalidad = ?, organizador = ?, precio = ?, sedes = ?, updated_at = NOW()
                WHERE id = ?
            """.trimIndent()

            jdbcTemplate.update(
                sql,
                torneo.nombre,
                torneo.fecha_Inicio,
                torneo.fecha_Fin,
                torneo.categoria,
                torneo.modalidad,
                torneo.organizador,
                torneo.precio,
                torneo.sedes,
                torneo.id
            )
        } catch (e: Exception) {
            throw Exception("Error al actualizar torneo: ${e.message}")
        }
    }

    // DELETE - Eliminar un torneo
    fun eliminarTorneo(id: Int) {
        try {
            val sql = "DELETE FROM torneos WHERE id = ?"
            jdbcTemplate.update(sql, id)
        } catch (e: Exception) {
            throw Exception("Error al eliminar torneo: ${e.message}")
        }
    }
}