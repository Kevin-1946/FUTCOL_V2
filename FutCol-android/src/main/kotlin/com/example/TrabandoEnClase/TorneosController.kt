package com.example.TrabandoEnClase

import org.springframework.beans.factory.annotation.Autowired
import org.springframework.web.bind.annotation.*

@RestController
@RequestMapping("/torneos")
class TorneosController {

    @Autowired
    lateinit var torneoService: TorneosService

    // GET - Obtener todos los torneos
    @GetMapping
    fun obtenerTorneos(): List<Torneo> {
        return torneoService.obtenerTorneos()
    }

    // POST - Agregar torneo
    @PostMapping
    fun agregarTorneo(@RequestBody torneo: Torneo): String {
        return try {
            torneoService.agregarTorneo(torneo)
            "Torneo agregado correctamente"
        } catch (e: Exception) {
            "Error al agregar el torneo: ${e.message}"
        }
    }

    // PUT - Actualizar torneo
    @PutMapping("/{id}")
    fun actualizarTorneo(@PathVariable id: Int, @RequestBody torneo: Torneo): String {
        return try {
            torneoService.actualizarTorneo(torneo)
            "Torneo actualizado correctamente"
        } catch (e: Exception) {
            "Error al actualizar el torneo: ${e.message}"
        }
    }

    // DELETE - Eliminar torneo
    @DeleteMapping("/{id}")
    fun eliminarTorneo(@PathVariable id: Int): String {
        return try {
            torneoService.eliminarTorneo(id)
            "Torneo eliminado correctamente"
        } catch (e: Exception) {
            "Error al eliminar el torneo: ${e.message}"
        }
    }
}