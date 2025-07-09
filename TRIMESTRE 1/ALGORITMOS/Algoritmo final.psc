Algoritmo FINAL
	Escribir "**********Servicio Nacional de Aprendizaje (SENA)**********";
	Escribir "************Análisis y Desarrollo de Software**************";
	Escribir "**************Javier Felipe Pulido Herrera*****************";
	Escribir "***********************2823506-G1**************************";
	Escribir "";
	menuprincipal;
	Definir opcmenuprincipal como entero;
	Escribir "Ingresa una opción por favor" Sin Saltar;
	Leer opcmenuprincipal;
		Segun opcmenuprincipal Hacer
			1:
				Limpiar Pantalla
				Definir a, b, opcoperaciones como entero;
				Definir continuaroperaciones Como Caracter
				Escribir "Ingresa el primer número por favor"
				Leer a
				Escribir "Ingresa el segundo número por favor"
				Leer b
				Repetir
					Limpiar Pantalla
					menudeoperaciones;
					Leer opcoperaciones
					Segun opcoperaciones Hacer
						1:suma(a,b)
						2:resta(a,b)
						3:multiplicación(a,b)
						4:división(a,b)
					FinSegun
				Hasta Que opcoperaciones=5
				Limpiar Pantalla
				Escribir "¡NOS VEMOS!";
			2:
				Limpiar Pantalla
				Escribir "**********Empresa de Acueducto y Alcantarillado de Bogotá**********";
				Escribir "****************Bienvenido a atención al cliente*******************"; Escribir "";
				Escribir "Para brindarte una solución necesitamos la siguiente información:"; Escribir "";
				Definir nombrecompleto,direccion como cadena;
				Escribir "Nombre completo" Sin Saltar;
				Leer nombrecompleto; 
				Escribir "Dirección del predio (casa, apartamento, empresa, establecimiento, etc)" Sin Saltar;
				Leer direccion; 
				Definir estrato Como Entero
				Escribir "Estrato" Sin Saltar;
				Leer estrato; 
				Definir pmc Como Entero;
				Definir existe Como logico;
				existe=Verdadero
					Segun estrato Hacer
						1: pmc=1000
						2: pmc=1500
						3: pmc=2500
						4: pmc=5000
						5: pmc=8000
						6: pmc=10000
					De Otro Modo:
						existe=Falso;
					FinSegun
				Limpiar Pantalla
				Si (existe) entonces
					Escribir "Estimad@ ", nombrecompleto;
					Escribir "Debido a que la ", direccion, " se ubica en un estrato ", estrato, ", el valor por metro cúbico facturado es de " pmc;
				SiNo
					Escribir "Estimad@ ", nombrecompleto, ", el estrato no existe. Es imposible continuar con el proceso.";
				FinSi
				Escribir "";
				Escribir "Para generar su factura ingrese los siguientes datos:";
				Definir lecturaanterior, lecturaactual, consumo Como Entero
				Escribir "Lectura anterior del contador" Sin Saltar;
				Leer lecturaanterior;
				Escribir "Lectura actual del contador" Sin Saltar;
				Leer lecturaactual;
				consumo=lecturaactual-lecturaanterior;
				Definir pmce, consumoexcedido, preciobasico, precioexceso como entero;
				Definir continuaracueducto como cadena;
					Segun estrato Hacer
						1: pmce=1500
						2: pmce=2000
						3: pmce=3000
						4: pmce=7000
						5: pmce=10000
						6: pmce=15000
					FinSegun
					Si (consumo>20) Entonces
					preciobasico=20*pmc;
					consumoexcedido=consumo-20;
					precioexceso=(consumoexcedido)*pmce;
					SiNo
					preciobasico=consumo*pmc;
					precioexceso=0;
					consumoexcedido=0;
					FinSi
				netopagar=preciobasico+precioexceso;
				Limpiar Pantalla;
				factura(nombrecompleto, lecturaanterior, lecturaactual, consumo, consumoexcedido, precioexceso, netopagar);
			3:
				Limpiar Pantalla
				Definir opcreloj Como Entero;
				Definir horario, minutero, segundero Como Entero;
				menureloj;
				Escribir "Selecciona una opción por favor" Sin Saltar;
				Leer opcreloj;
				Segun opcreloj Hacer
					1:
						Limpiar Pantalla
						Escribir "Ingrese la hora" Sin Saltar;
						Leer horario;
						Escribir "Ingrese el minuto" Sin Saltar;
						Leer minutero;
						rh=horario*30
						rm=minutero*6
						Si rm>rh Entonces
							angulo1=rm-rh
							Escribir "El ángulo entre las ", horario " y ", minutero " es ", angulo1, " grados.";
						SiNo
							angulo11=360-(rh-rm)
							Escribir "El ángulo entre las ", horario " y ", minutero " es ", angulo11, " grados.";
						FinSi
					2:
						Limpiar Pantalla
						Escribir "Ingrese el minuto" Sin Saltar;
						Leer minutero;
						Escribir "Ingrese el segundo" Sin Saltar;
						Leer segundero;
						rm=minutero*6
						rs=segundero*6
						Si rs>rm Entonces
							angulo2=rs-rm
							Escribir "El ángulo entre el minuto ", minutero " y el segundo ", segundero " es ", angulo2, " grados.";
						SiNo
							angulo22=360-(rm-rs)
							Escribir "El ángulo entre el minuto ", minutero " y el segundo ", segundero " es ", angulo22, " grados.";
						FinSi
						
					3:
						Limpiar Pantalla
						Escribir "Ingrese la hora" Sin Saltar;
						Leer horario;
						Escribir "Ingrese el segundo" Sin Saltar;
						Leer segundero;
						rh=horario*30
						rs=segundero*6
						Si rs>rh Entonces
							angulo3=rs-rh
							Escribir "El ángulo entre las ", horario " con ", segundero " segundos es ", angulo3, " grados.";
						SiNo
							angulo33=360-(rh-rs)
							Escribir "El ángulo entre las ", horario " con ", segundero " segundos es ", angulo33, " grados.";
						FinSi
					4:
						Limpiar Pantalla
						Escribir "Ingrese el minuto" Sin Saltar;
						Leer minutero;
						Escribir "Ingrese la hora" Sin Saltar;
						Leer horario;
						rm=minutero*6
						rh=horario*30
						Si rh>rm Entonces
							angulo4=rh-rm
							Escribir "El ángulo entre el minuto ", minutero " y la hora ", horario " es de ", angulo4, " grados.";
						SiNo
							angulo44=360-(rm-rh)
							Escribir "El ángulo entre el minuto ", minutero " y la hora ", horario " es de ", angulo44, " grados.";
						FinSi
					5: 
						Limpiar Pantalla
						Escribir "¡NOS VEMOS!";
	           FinSegun
			4: 
				Limpiar Pantalla
				Escribir "¡NOS VEMOS!"
		FinSegun
		
FinAlgoritmo
SubProceso menuprincipal
	Escribir "¨**********************Bienvenido**************************";
	Escribir "*********************Menú principal************************";
	Escribir "";
	Escribir "1. Operaciones aritméticas.";
	Escribir "2. Empresa de Acueducto y Alcantarillado de Bogotá.";
	Escribir "3. Reloj.";
	Escribir "4. Salir";
	Escribir "";
FinSubProceso

SubProceso menudeoperaciones
	Escribir "Menú de operaciones"; 
	Escribir "";
	Escribir "1. Suma"
	Escribir "2. Resta"
	Escribir "3. Multiplicación"
	Escribir "4. División"; 
	Escribir "5. Salir"; Escribir "";
FinSubProceso

SubProceso suma(a,b)
			Escribir "La suma de ", a, " + ", b, " es ", a+b;
			Escribir "Pulsa Enter para hacer usar otra opción";
			Leer continuaroperaciones;
FinSubProceso
SubProceso resta(a,b)
	Escribir "La resta de ", a, " - ", b, " es ", a-b;
	Escribir "Pulsa Enter para hacer usar otra opción";
	Leer continuaroperaciones;
FinSubProceso
SubProceso multiplicación(a,b)
	Escribir "La multiplicación de ", a, " * ", b, " es ", a*b;
	Escribir "Pulsa Enter para hacer usar otra opción";
	Leer continuaroperaciones;
FinSubProceso
SubProceso división(a,b)
	Si b<=0 Entonces
		Escribir "¡ERROR!"
		Escribir "Pulsa Enter para hacer usar otra opción";
		Leer continuaroperaciones;
	SiNo
		Escribir "La división entre ", a, " y ", b, " es ", a/b;
		Escribir "Pulsa Enter para hacer usar otra opción";
		Leer continuaroperaciones;
	FinSi
FinSubProceso

SubProceso factura(nombrecompleto, lecturaanterior, lecturaactual, consumo, consumoexcedido, precioexceso, netopagar)
	Escribir "*******************ACUEDUCTO********************"
	Escribir "EMPRESA DE ACUEDUCTO DE ALCANTARILLADO DE BOGOTÁ"
	ESCRIBIR "**************FACTURA ELECTRONICA***************"
	Escribir ""
	Escribir "Estimad@ ", nombrecompleto;
	Escribir "";
	Escribir "La lectura actual de su contador es: ", lecturaactual;
	Escribir "Lectura anterior de su contador fue: ", lecturaanterior;
	Escribir "Su consumo de agua fue de ", consumo, " metros cúbicos.";
	Escribir "Su consumo excedido de agua fue de ", consumoexcedido, " metros cúbicos.";
	Escribir "El precio de su consumo excedido de agua es de $", precioexceso;
	Escribir "El precio de su consumo total a pagar es de $", netopagar; 
FinSubProceso

SubProceso menureloj
	Escribir "¿Qué quieres hacer?";
	Escribir "";
	Escribir"1. Horario - Minutero";
	Escribir"2. Minutero - Segundero";
	Escribir"3. Horario - Segundero";
	Escribir"4. Minutero - Horario";
	Escribir"5. Salir";
	Escribir ""; 
FinSubProceso


