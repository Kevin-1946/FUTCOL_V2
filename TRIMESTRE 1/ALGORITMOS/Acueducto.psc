Algoritmo Acueducto
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
FinAlgoritmo

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
	Escribir "El precio de su consumo excedido es de $", precioexceso;
	Escribir "El precio neto a pagar es de $", netopagar; 
FinSubProceso
