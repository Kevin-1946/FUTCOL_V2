package com.example.TrabandoEnClase

import org.springframework.beans.factory.annotation.Autowired
import org.springframework.jdbc.core.JdbcTemplate
import org.springframework.jdbc.core.RowMapper
import org.springframework.stereotype.Service

@Service
class JugadoresService {

    @Autowired
    lateinit var jdbcTemplate: JdbcTemplate

    private val jugadorRowMapper = RowMapper { rs, _ ->
        Jugador(
            id = rs.getInt("id"),
            nombre = rs.getString("nombre"),
            n_documento = rs.getString("n_documento"),
            fecha_nacimiento = rs.getString("fecha_nacimiento"),
            email = rs.getString("email"),
            password = rs.getString("password"),
            genero = rs.getString("genero"),
            edad = rs.getInt("edad"),
            user_id = rs.getInt("user_id"),
            equipo_id = rs.getInt("equipo_id")
        )
    }

    fun obtenerJugadores(): List<Jugador> {
        val sql = "SELECT * FROM jugadores"
        return jdbcTemplate.query(sql, jugadorRowMapper)
    }

    fun agregarJugador(
        nombre: String,
        n_documento: String,
        fecha_nacimiento: String,
        email: String,
        password: String,
        genero: String,
        edad: Int,
        user_id: Int,
        equipo_id: Int
    ) {
        val sql = """
            INSERT INTO jugadores 
            (nombre, n_documento, fecha_nacimiento, email, password, genero, edad, user_id, created_at, updated_at, equipo_id)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW(), ?)
        """
        jdbcTemplate.update(sql,
            nombre,
            n_documento,
            fecha_nacimiento,
            email,
            password,
            genero,
            edad,
            user_id,
            equipo_id
        )
    }

    fun actualizarJugador(
        id: Int,
        nombre: String,
        n_documento: String,
        fecha_nacimiento: String,
        email: String,
        password: String,
        genero: String,
        edad: Int,
        user_id: Int,
        equipo_id: Int
    ) {
        val sql = """
            UPDATE jugadores 
            SET nombre = ?, n_documento = ?, fecha_nacimiento = ?, email = ?, password = ?, genero = ?, edad = ?, user_id = ?, updated_at = NOW(), equipo_id = ?
            WHERE id = ?
        """
        jdbcTemplate.update(sql,
            nombre,
            n_documento,
            fecha_nacimiento,
            email,
            password,
            genero,
            edad,
            user_id,
            equipo_id,
            id
        )
    }

    fun eliminarJugador(id: Int) {
        val sql = "DELETE FROM jugadores WHERE id = ?"
        jdbcTemplate.update(sql, id)
    }
}