SubProceso suma(n1,n2)
	Escribir "La suma de ", n1, " + ", n2, " es ", n1+n2;
	Escribir "Enter para hacer usar otra opción";
	Leer continuar;
FinSubProceso
SubProceso resta(n1,n2)
	Escribir "La resta de ", n1, " - ", n2, " es ", n1-n2;
	Escribir "Enter para hacer usar otra opción";
	Leer continuar;
FinSubProceso
SubProceso multiplicación(n1,n2)
	Escribir "La multiplicación de ", n1, " * ", n2, " es ", n1*n2;
	Escribir "Enter para hacer usar otra opción";
	Leer continuar;
FinSubProceso
SubProceso división(n1,n2)
	Si n2<=0 Entonces
		Escribir "ERROR"
		Escribir "Enter para hacer usar otra opción";
		Leer continuar;
	SiNo
		Escribir "La suma de ", n1, " / ", n2, " es ", n1/n2;
		Escribir "Enter para hacer usar otra opción";
		Leer continuar;
	FinSi
	FinSubProceso

Algoritmo menúdeopciones
	Definir n1, n2, opc Como Entero
	Definir continuar Como Caracter
	Escribir "*****Bienvenido*****"
	Escribir "Ingrese el primer número"
	Leer n1
	Escribir "Ingrese el segundo número"
	Leer n2
	Repetir
	   Limpiar Pantalla
	   Escribir "MENÚ DE OPCIONES"; Escribir "";
	   Escribir "1. Suma"
	   Escribir "2. Resta"
	   Escribir "3. Multiplicación"
	   Escribir "4. División"; Escribir "";
	   Escribir "5. Salir"; Escribir "";
	   Leer opc
	   Segun opc Hacer
		1:suma(n1,n2)
		2:resta(n1,n2)
		3:multiplicación(n1,n2)
		4:división(n1,n2)
	FinSegun
Hasta Que opc=5
Limpiar Pantalla
Escribir "¡Nos vemos!"
FinAlgoritmo
