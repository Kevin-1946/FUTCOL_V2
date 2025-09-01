package com.example.TrabandoEnClase

import org.springframework.beans.factory.annotation.Autowired
import org.springframework.jdbc.core.JdbcTemplate
import org.springframework.jdbc.core.RowMapper
import org.springframework.jdbc.support.GeneratedKeyHolder
import org.springframework.stereotype.Service
import java.sql.ResultSet
import java.sql.Statement
import java.time.LocalDate
import java.time.LocalTime
import java.time.format.DateTimeFormatter

@Service
open class EncuentrosService @Autowired constructor(
    private val jdbcTemplate: JdbcTemplate
) {

    // RowMapper para mapear los resultados de la consulta a objetos Encuentro
    private val rowMapper = RowMapper<Encuentro> { rs: ResultSet, _: Int ->
        Encuentro(
            id = rs.getLong("id"),
            torneoId = rs.getObject("torneo_id", java.lang.Long::class.java)?.toLong(),
            equipoLocalId = rs.getObject("equipo_local_id", java.lang.Long::class.java)?.toLong(),
            equipoVisitanteId = rs.getObject("equipo_visitante_id", java.lang.Long::class.java)?.toLong(),
            juezId = rs.getObject("juez_id", java.lang.Long::class.java)?.toLong(),
            sedeId = rs.getObject("sede_id", java.lang.Long::class.java)?.toLong(),
            // Convertir fecha y hora a tipos LocalDate y LocalTime
            fecha = rs.getString("fecha")?.let { LocalDate.parse(it, DateTimeFormatter.ISO_DATE) },
            hora = rs.getString("hora")?.let { LocalTime.parse(it, DateTimeFormatter.ISO_TIME) },
            golesLocal = rs.getObject("goles_local", java.lang.Integer::class.java)?.toInt(),
            golesVisitante = rs.getObject("goles_visitante", java.lang.Integer::class.java)?.toInt(),
            estado = rs.getString("estado")
        )
    }

    // Obtener todos los encuentros
    open fun findAll(): List<Encuentro> =
        jdbcTemplate.query(
            """SELECT id, torneo_id, equipo_local_id, equipo_visitante_id, juez_id, sede_id,
                      fecha, hora, goles_local, goles_visitante, estado
               FROM encuentros ORDER BY id DESC""".trimIndent(), rowMapper
        )

    // Obtener un encuentro por ID
    open fun findById(id: Long): Encuentro? =
        jdbcTemplate.query(
            """SELECT id, torneo_id, equipo_local_id, equipo_visitante_id, juez_id, sede_id,
                      fecha, hora, goles_local, goles_visitante, estado
               FROM encuentros WHERE id = ?""".trimIndent(), rowMapper, id
        ).firstOrNull()

    // Crear un nuevo encuentro
    open fun create(e: Encuentro): Long {
        val sql = """
            INSERT INTO encuentros
              (torneo_id, equipo_local_id, equipo_visitante_id, juez_id, sede_id,
               fecha, hora, goles_local, goles_visitante, estado)
            VALUES (?,?,?,?,?,?,?,?,?,?)
        """.trimIndent()

        val kh = GeneratedKeyHolder()
        jdbcTemplate.update({ con ->
            val ps = con.prepareStatement(sql, Statement.RETURN_GENERATED_KEYS)
            ps.setObject(1, e.torneoId)
            ps.setObject(2, e.equipoLocalId)
            ps.setObject(3, e.equipoVisitanteId)
            ps.setObject(4, e.juezId)
            ps.setObject(5, e.sedeId)
            ps.setString(6, e.fecha.toString())  // Convertir LocalDate a String
            ps.setString(7, e.hora.toString())   // Convertir LocalTime a String
            ps.setObject(8, e.golesLocal)
            ps.setObject(9, e.golesVisitante)
            ps.setString(10, e.estado)
            ps
        }, kh)

        return kh.key!!.toLong()
    }

    // Actualizar un encuentro existente
    open fun update(id: Long, e: Encuentro): Boolean {
        val rows = jdbcTemplate.update(
            """
            UPDATE encuentros SET
              torneo_id = ?, equipo_local_id = ?, equipo_visitante_id = ?,
              juez_id = ?, sede_id = ?, fecha = ?, hora = ?,
              goles_local = ?, goles_visitante = ?, estado = ?
            WHERE id = ?
            """.trimIndent(),
            e.torneoId, e.equipoLocalId, e.equipoVisitanteId,
            e.juezId, e.sedeId, e.fecha.toString(), e.hora.toString(),
            e.golesLocal, e.golesVisitante, e.estado, id
        )
        return rows > 0
    }

    // Eliminar un encuentro
    open fun delete(id: Long): Boolean =
        jdbcTemplate.update("DELETE FROM encuentros WHERE id = ?", id) > 0
}
