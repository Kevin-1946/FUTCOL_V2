SubProceso suma(n1,n2)
	Escribir "La suma de ", n1, " + ", n2, " es ", n1+n2;
	Escribir "Enter para hacer usar otra opci�n";
	Leer continuar;
FinSubProceso
SubProceso resta(n1,n2)
	Escribir "La resta de ", n1, " - ", n2, " es ", n1-n2;
	Escribir "Enter para hacer usar otra opci�n";
	Leer continuar;
FinSubProceso
SubProceso multiplicaci�n(n1,n2)
	Escribir "La multiplicaci�n de ", n1, " * ", n2, " es ", n1*n2;
	Escribir "Enter para hacer usar otra opci�n";
	Leer continuar;
FinSubProceso
SubProceso divisi�n(n1,n2)
	Si n2<=0 Entonces
		Escribir "ERROR"
		Escribir "Enter para hacer usar otra opci�n";
		Leer continuar;
	SiNo
		Escribir "La suma de ", n1, " / ", n2, " es ", n1/n2;
		Escribir "Enter para hacer usar otra opci�n";
		Leer continuar;
	FinSi
	FinSubProceso

Algoritmo men�deopciones
	Definir n1, n2, opc Como Entero
	Definir continuar Como Caracter
	Escribir "*****Bienvenido*****"
	Escribir "Ingrese el primer n�mero"
	Leer n1
	Escribir "Ingrese el segundo n�mero"
	Leer n2
	Repetir
	   Limpiar Pantalla
	   Escribir "MEN� DE OPCIONES"; Escribir "";
	   Escribir "1. Suma"
	   Escribir "2. Resta"
	   Escribir "3. Multiplicaci�n"
	   Escribir "4. Divisi�n"; Escribir "";
	   Escribir "5. Salir"; Escribir "";
	   Leer opc
	   Segun opc Hacer
		1:suma(n1,n2)
		2:resta(n1,n2)
		3:multiplicaci�n(n1,n2)
		4:divisi�n(n1,n2)
	FinSegun
Hasta Que opc=5
Limpiar Pantalla
Escribir "�Nos vemos!"
FinAlgoritmo
